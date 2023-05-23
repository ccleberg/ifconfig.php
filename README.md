ifconfig.php
============

**Simple PHP script to show IP address, UserAgent and other info**

It works exactly the same as [ifconfig.me](http://ifconfig.me) and [ifconfig.co](http://ifconfig.co).

Available all basic features such as output in HTML, plain text, XML and JSON.
By default it will be in HTML. Also it is possible to request single values.

Visit some of the links below to view it in action:
* [all in plain text](http://ip.cleberg.net?q=text)
* [all in xml](http://ip.cleberg.net?q=xml)
* [ip address only](http://ip.cleberg.net?q=ip)
* [useragent only](http://ip.cleberg.net?q=ua)

If you're using the Apache web server, you can use the .htaccess rules below to make this site more user friendly:

	RewriteEngine On

	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ifconfig$ ifconfig.php [L]
	RewriteRule ^ifconfig/(.*)$ /ifconfig.php?q=$1 [L]

With this code in the .htaccess file there is no need to type out the full URL, i.e. 'ifconfig.php?q='.
Instead, you can just open 'ifconfig/xml' to get XML output or 'ifconfig/ua' to determine useragent.

Run `curl -L -X GET https://ip.cleberg.net/\?q\=text` in Linux terminal to get some info.
