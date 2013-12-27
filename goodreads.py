#!/usr/bin/python

import json
import os
import requests
import sys
import time
from xml.etree import ElementTree

def debug(string):
    pass
    # print string

def apiCall(method, params):
    url = 'https://www.goodreads.com/' + method
    debug('Firing API method ' + method + '...')
    with open(os.path.expanduser('~splintax/.goodreads-api-key'), 'r') as fd:
        params['key'] = fd.read()
    return requests.get(url, data=params).content

def parseXml(string):
    try:
        et = ElementTree.fromstring(string)
        result = {
            # book details
            'title': et.find('.//title').text,
            'author': et.find('.//author/name').text,
            'image': et.find('.//book//image_url').text,
            'link': et.find('.//book//link').text,
        }
        if et.find('.//rating').text != "0":
            # if book finished
            result.update({
                'read_at': et.find('.//read_at').text,
                'rating': et.find('.//rating').text,
                # 'body': et.find('.//review/body').text,
            })
        else:
            # if book in progress
                result.update({
                    'pages_total': et.find('.//book/num_pages').text,
                    'last_updated': et.find('.//user_status[1]/updated_at').text,
                    'pages_done': et.find('.//user_status[1]/page').text,
                    'percent': et.find('.//user_status[1]/percent').text,
                })
        return result
    except:
        debug('An error occurred when parsing this XML:')
        debug(string)
        raise

# when cache out of date
def update():
    results = {
        'recent': [],
        'current': None,
    }
        
    # obtain 1 most recent currently-readinng
    j = json.loads(apiCall('review/list', {
        'format':   'json',
        'id':       '6901419',
        'shelf':    'currently_reading',
        # 'sort':     'date_updated',
        'page':     1,
        'per_page': 3,
    }))
    results['current'] = parseXml(apiCall('review/show', {'id': j[0]['id']}))

    # obtain 5 most recent reviews
    j = json.loads(apiCall('review/list', {
        'format':   'json',
        'id':       '6901419',
        'shelf':    'read',
        'sort':     'date_read',
        'page':     1,
        'per_page': 5,
    }))
    results['recent'] = [parseXml(apiCall('review/show', {'id': review['id']})) for review in j]

    with open('goodreads.json', 'w') as fd:
        json.dump(results, fd)
        debug('Wrote to goodreads.json.')

if __name__ == '__main__':
    age = time.time() - os.path.getmtime('goodreads.json') # seconds
    stale = age / 60 / 60 / 24 > 1 # days

    if (len(sys.argv) == 2 and sys.argv[1] == '--force'):
        stale = True
        def debug(s): print s

    debug('Cached goodreads.json is {0} minutes old.'.format(age / 60))
    if stale:
        debug('Refreshing cache...')
        update()
    else:
        debug('Reading from cache...')
        with open('goodreads.json', 'r') as fd:
            print fd.read()
