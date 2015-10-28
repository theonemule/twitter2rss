Twitter2RSS
===========

I wrote this simple script with one purpose in mind: to convert twitter searches/feeds into RSS. Twitter used to have an RSS feature, but deprecated it. I used it to read Twitter in my RSS Reader application. It uses pure PHP without the need for any external libraries or scripts.

This script is easy to use:

1. Register a new app with Twitter. It's a wizard. Once the application is registered, go to the "Keys and Access Tokens" tab. 

	```
	https://apps.twitter.com/
	```

2. Open the script in your favorite text editor.

3. Copy and Paste the "API Key" into $APIKey in the script.

4. Copy and Paste the "API Secret" into $APISecret in the script.

5. Edit the $title, $description, and #copyright to your liking.

6. Set $link to the URL of your blog, website, or Twitter page.

7. Edit the search parameters. Twitter's search parameters are documented here. You can search on hashtags , users, and many other criterion.

	```
	https://dev.twitter.com/rest/public/search
	```

8. Set the count to the number of items your want to return.

9. Save the script and upload to your web server that supports PHP. 

10. Point your browser to the page (http://www.example.com/twitter2rss.php) and run it. It should return an RSS XML document with your results.
