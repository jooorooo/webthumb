Webthumb
==========

This is a library for PHP to take a screen shot using PhantomJS.

It is easy specification of only have Exec the phantomjs command in the middle.

Webthumb Class for Laravel 5

## Requirements

* Laravel 5.X
* php 5.4+
* php exec enabled

## Installation

##### Step 1: Install package using [Composer](https://getcomposer.org)

Add simexis/webthumb to "require" section of your composer.json

```javascript
"require": {
    "simexis/webthumb": ">=1.0"
},
```

Add repository to "repositories" section of your composer.json

```javascript
"repositories": [ {
	"type": "vcs",
	"url": "https://github.com/jooorooo/webthumb.git"
}],
```

Then install dependencies using following command:
```bash    
php composer.phar install
```

or

```bash    
php composer.phar update
```

##### Step 2: Laravel Setup
Add following line to 'providers' section of app/config/app.php file:
```php
'Simexis\Webthumb\WebthumbServiceProvider',
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