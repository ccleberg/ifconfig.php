# ifconfig.php

**Simple PHP script to show IP address, UserAgent and other info**

It works exactly the same as [ifconfig.me](http://ifconfig.me) and [ifconfig.co](http://ifconfig.co).

Available all basic features such as output in HTML, plain text, XML and JSON.
By default it will be in HTML. Also it is possible to request single values.

Visit some of the links below to view it in action:
* [all in plain text](http://ip.cmc.pub?q=text)
* [all in xml](http://ip.cmc.pub?q=xml)
* [ip address only](http://ip.cmc.pub?q=ip)
* [useragent only](http://ip.cmc.pub?q=ua)

If you're using the Apache web server, you can use the .htaccess rules below to make this site more user friendly:

	RewriteEngine On

	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ifconfig$ ifconfig.php [L]
	RewriteRule ^ifconfig/(.*)$ /ifconfig.php?q=$1 [L]

With this code in the .htaccess file there is no need to type out the full URL, i.e. 'ifconfig.php?q='.
Instead, you can just open 'ifconfig/xml' to get XML output or 'ifconfig/ua' to determine useragent.

Run `curl -L -X GET https://ip.cmc.pub/\?q\=text` in Linux terminal to get some info.

## Development

First, install PHP and Composer. For Fedora, run:

```bash
sudo dnf install php-cli composer
```

If applicable, install or update the maxmind-db dependency:

```bash
composer require maxmind-db/reader:~1.0

# to update, run:
composer update
```

Next, you can launch a dev server to test any of your changes:

```bash
sudo php --server localhost:8080 --docroot  .
```
