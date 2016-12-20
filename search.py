from __future__ import division
from time import gmtime, strftime
import traceback
import urllib
import urllib2
import json
import requests, re
from bs4 import BeautifulSoup
import sys
import time
import os

os.chdir("/var/www/html")

def print_progress(percent,total):
	p = int((percent/total)*100)
	output = "["
	index = 1
	while index < 100:
		if index <= p:
			output += "*"
		else:
			output += " "
		index+=1
	output += "] "
	output += str(p)+"%"
	sys.stdout.write(str(output)+"\r")
	sys.stdout.flush()

def google_fetch(query,pages):
	google = []
	start = 0
	total = pages*10

	print "Getting Google results:"
	print_progress(start,total)

	while pages > 0:
		#url
		url = 'http://www.google.com/search'

		#Parameters in payload
		payload = { 'q' : query, 'start' : str(start) }

		#Setting User-Agent
		my_headers = { 'User-agent' : 'Mozilla/11.0' }

		#Getting the response in an Object r
		r = requests.get( url, params = payload, headers = my_headers )
		if str(r) == "<Response [503]>":
			print "GOOGLE BAN HAMMER."
			sys.exit()

		#Create a Beautiful soup Object of the response r parsed as html
		soup = BeautifulSoup( r.text, 'html.parser' )

		#Getting all h3 tags with class 'r'
		results = soup.find_all( 'div', class_='g' )

		#Finding URL inside each h3 tag using regex.
		#If found : Print, else : Ignore the exception

		for result in results:
			link = str(result.h3.a["href"])[7:].split("&sa=")[0]
			title = u''
			for item in result.h3.a.contents:
				title+=unicode(item)
			title = title.replace("<b>",'').replace("</b>",'')
			try:
				desc_out = u''
				desc = result.find_all('span', class_='st')[0].contents
				for item in desc:
					desc_out+=unicode(item)
				desc_out = desc_out.replace("<b>",'').replace("</b>",'')
			except:
				pass

			google.append(
				{
					"title":title,
					"desc":desc_out,
					"link":link
				}
			)

		pages-=1;
		start+=10;
		print_progress(start,total)

	print ""

	with open("google.json","w+") as f:
		f.write(json.dumps(google,indent=2))

	return google


query = "lixie display"
pages = 20
tags = ["connor","nishijima","lixie","nixie","numeric","acrylic"]

google = google_fetch(query,pages)

with open("google.json","r") as f:
	google = json.loads(f.read())

with open("links.json","r") as f:
	links = json.loads(f.read())

print len(google)
t = len(google)

if len(google) > 0:
	index = 0
	print "Analyzing results:"
	print_progress(0,t)

	for item in google:
		index+=1
		print_progress(index,t)

		try:
			link = urllib.unquote(urllib.unquote(item["link"]))
#			link = "http://connor-n.com"
			response = urllib2.urlopen(link, timeout=5)
			title = item["title"]

			ht = str(response.read()).lower()

			found = 0
			for tag in tags:
				if tag.lower() in ht.lower():
					found+=1
			if found >= len(tags)/2:
				try:
					found = False
					for item in links:
						if link == item["link"]:
							found = True
					if found == False:
						links.append({
							"link":link,
							"matches":found,
							"title":title,
							"date":strftime("%Y-%m-%d %H:%M:%S")
						})
				except:
#					traceback.print_exc()
					pass
		except:
#			traceback.print_exc()
			pass

try:
	print "Links found:"
	for item in links:
		print "\t"+item["link"]
except:
	print "No links found."

with open("links.json","w+") as f:
	f.write(json.dumps(links, indent=2))

#End
