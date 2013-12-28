#!/usr/bin/python

import datetime
import json
import os
import requests
import string
from xml.etree import ElementTree

bookTemplate = string.Template("""
    <div class="book"><p>
        <a href="${link}">${title}</a>
    </p><p>
        <span class="author">${author}</span>
        ${extras}
    </p></div><!-- end .book -->
""")

currentExtrasTemplate = string.Template("""
    <span class="rating">
        <span class="done" style="width: ${done}em"></span><span class="left" style="width: ${left}em"></span>
        (${percent}%)
    </span>
    <span class="finished">last read on ${last_read}</span>
""")
# Do the percent and proportion of 4em calculations here.
def currentExtras(et):
    try:
        percent = float(et.find('.//user_status[1]/percent').text)
    except AttributeError:
        # percentage not provided, so calculate it
        pages_total = float(et.find('.//book/num_pages').text)
        pages_done = float(et.find('.//user_status[1]/page').text)
        percent = pages_total / pages_done * 100
    def cleanDate(string):
        date = string.split('T')[0]
        return datetime.datetime.strptime(date, '%Y-%m-%d').strftime('%d %B %Y')
    last_read = cleanDate(et.find('.//user_status[1]/updated_at').text)
    return currentExtrasTemplate.substitute({
        'last_read': last_read,
        'percent': percent,
        'done': (percent/100) * 4,
        'left': (1 - percent/100) * 4,
    })

recentExtrasTemplate = string.Template("""
    <span class="rating">${stars}</span>
    <span class="finished">finished on ${read_at}</span>
""")
# Do the rating and star text processing here.
def recentExtras(et):
    rating = int(et.find('.//rating').text)
    blackStars = '<i class="fa fa-star"></i>'  *   rating
    whiteStars = '<i class="fa fa-star-o"></i>'* (5-rating)
    def cleanDate(string):
        date = string.split(' ') # Tue Dec 17 00:00:00 -0800 2013
        date = ' '.join([date[2], date[1], date[-1]])
        return datetime.datetime.strptime(date, '%d %b %Y').strftime('%d %B %Y')
    read_at = cleanDate(et.find('.//read_at').text)
    return recentExtrasTemplate.substitute({
        'read_at': read_at,
        'stars': blackStars + whiteStars,
    })

# store API key in memory    
with open(os.path.expanduser('~splintax/.goodreads-api-key'), 'r') as fd:
    API_KEY = fd.read()

def apiCall(method, params):
    url = 'https://www.goodreads.com/' + method
    params.update({'key': API_KEY})
    return requests.get(url, data=params).content

def transformGoodreadsXml(string):
    try:
        et = ElementTree.fromstring(string)
        bookWasRated = not(et.find('.//rating').text == '0')
        if bookWasRated: # recently finished
            extras = recentExtras(et)
        else:            # currently reading
            extras = currentExtras(et)
        return bookTemplate.substitute({
            'title': et.find('.//title').text,
            'author': et.find('.//author/name').text,
            'link': et.find('.//review/link').text,
            'extras': extras,
        })
    except:
        print 'An error occurred when parsing this XML:'
        print string
        raise

def getCurrentBooks():
    print "Listing currently-reading shelf..."
    html = ''
    response = apiCall('review/list', {
        'format':   'json',
        'id':       '6901419', # Scott Young
        'shelf':    'currently-reading',
        # 'sort':     'date_updated',
        # doesn't seem to update when the status changes, only when the
        # review itself is edited.
        'per_page': 1,
    })
    for review in json.loads(response):
        print 'Fetching review {0}...'.format(review['id'])
        reviewXml = apiCall('review/show', {'id': review['id']})
        html += transformGoodreadsXml(reviewXml)
    return html

def getRecentBooks():
    print "Listing read shelf..."
    html =''
    response = apiCall('review/list', {
        'format':   'json',
        'id':       '6901419', # Scott Young
        'shelf':    'read',
        'sort':     'date_read',
        'per_page': 3,
    })
    for review in json.loads(response):
        print 'Fetching review {0}...'.format(review['id'])
        reviewXml = apiCall('review/show', {'id': review['id']})
        html += transformGoodreadsXml(reviewXml)
    return html

mainTemplate = string.Template("""
    <h3>I'm currently reading...</h3>
    ${currentBooks}
    <h3>I recently finished...</h3>
    ${recentBooks}
""")
html = mainTemplate.substitute({
    'currentBooks': getCurrentBooks(),
    'recentBooks': getRecentBooks(),
})
with open('/home/wheel/splintax/public-html/sjy.id.au/home/goodreads.html', 'w') as fd:
    fd.write(html.encode('utf-8'))
    print "goodreads.html updated."
