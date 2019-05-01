Webthumb
==========

This is a library for PHP to take a screen shot using PhantomJS.

It is easy specification of only have Exec the phantomjs command in the middle.

Webthumb Class for Laravel 5.3

## Requirements

* Laravel 5.3
* php 5.6+

## Installation

##### Step 1: Install package using [Composer](https://getcomposer.org)

```javascript
composer require simexis/webthumb
```

### Step 2: Laravel 5.5+

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Simexis\Webthumb\WebthumbServiceProvider::class,
```

## Usage

Default configuration is:

```php
$cfg = [
	'local_cache_dir'	=>	public_path() . "/thumbs", //relative cache directory must exists in install directory and rwx permissions to all (777)
	'phantom_js_root'	=> __DIR__ . '/../lib/phantomjs', //Path to the root directory phantom_js

	'encoding'			=>		"png", // jpg or png
	'bwidth'			=>		"1280", // browser width
	'bheight'			=>		"1024" // browser height only for mode=screen
];
```

#### Basic example

```php
echo \Webthumb::setUrl('http://google.com')->save($save_path);
```

```php
echo (new \Webthumb)
		->setURL('http://google.com')
        ->setScreenWidth('1024')
        ->setScreenHeight('768')
		->save($save_path);
```