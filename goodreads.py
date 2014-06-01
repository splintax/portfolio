#!/usr/bin/python3
"""
Queries the Goodreads API for my currently-reading and read
bookshelves, and writes formatted HTML output to goodreads.html.

One API call is required per book, and this is quite slow, so
HTTP requests should never be held up to refresh goodreads.html.
Instead, this script should be run periodically by cron.
"""

import datetime
import json
import os
import requests
import string
from xml.etree import ElementTree

mainTemplate = string.Template("""
    <h3>What I've been reading lately</h3>
    ${currentBooks}
    ${completedBooks}
""")

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
        <small>${percent}%</small>
    </span>
    <small>updated on ${last_read}</small>""")

completedExtrasTemplate = string.Template("""
    <span class="rating">${stars}</span>
    <small>finished on ${read_at}</small>""")

with open(os.path.expanduser('~splintax/.goodreads-api-key'), 'r') as fd:
    API_KEY = fd.read()

def cleanDate(string):
    if ' ' not in string:
        # 2014-04-29T02:10:47+00:00
        string = string.split('T')[0]
        date = datetime.datetime.strptime(string, '%Y-%m-%d')
    else:
        # Tue Dec 17 00:00:00 -0800 2013
        date = datetime.datetime.strptime(string, '%a %b %d %H:%M:%S %z %Y')
    return date.strftime('%d %B %Y').lstrip('0')

# Current books have a progress indicator.
def currentExtras(et):
    try:
        percent = float(et.find('.//user_status[1]/percent').text)
    except AttributeError:
        # percentage not provided, so calculate it
        pages_total = float(et.find('.//num_pages').text)
        pages_done = float(et.find('.//user_status[1]/page').text)
        percent = pages_total / pages_done * 100
    last_read = cleanDate(et.find('.//user_status[1]/updated_at').text)
    return currentExtrasTemplate.substitute({
        'last_read': last_read,
        'percent': int(percent),
        'done': (percent/100) * 4,
        'left': (1 - percent/100) * 4,
    })

# Completed books have a rating.
def completedExtras(et):
    rating = int(et.find('.//rating').text)
    blackStars = '<i class="fa fa-star"></i>'  *   rating
    whiteStars = '<i class="fa fa-star-o"></i>'* (5-rating)
    read_at = cleanDate(et.find('.//read_at').text)
    return completedExtrasTemplate.substitute({
        'read_at': read_at,
        'stars': blackStars + whiteStars,
    })

def apiCall(method, params):
    url = 'https://www.goodreads.com/' + method
    params.update({'key':  API_KEY})
    response = requests.get(url, data=params).content
    return response

def parseXml(string):
    try:
        return ElementTree.fromstring(string)
    except:
        print('An error occurred when parsing this XML:\n{}'.format(string))
        raise

def parseBook(string, shelf):
    et = parseXml(string)
    title = et.find('.//title').text
    print("Parsing '{}'.".format(title))
    if shelf   == 'currently-reading': extras = currentExtras(et)
    elif shelf == 'read':              extras = completedExtras(et)
    else: raise Exception(shelf)
    return bookTemplate.substitute({
        'title': title,
        'author': et.find('.//author/name').text,
        'link': et.find('.//review/link').text,
        'extras': extras,
    })

def getBooks(shelf, count):
    print("Listing shelf '{}'.".format(shelf))
    response = apiCall('review/list', {
        'v':        2,
        'id':       6901419, # Scott Young
        'shelf':    shelf,
        'sort':     'date_updated',
        'per_page': count,
    })
    et = parseXml(response)
    review_ids = [el.text for el in et.findall('.//review/id')]
    html = ''
    for i in review_ids:
        response = apiCall('review/show.xml', {'id': i})
        html += parseBook(response, shelf)
    return html

html = mainTemplate.substitute({
    'currentBooks': getBooks('currently-reading', 2),
    'completedBooks': getBooks('read', 3),
})

with open('/home/wheel/splintax/public-html/sjy.id.au/home/goodreads.html', 'w') as fd:
    fd.write(html)
    print("goodreads.html updated.")
