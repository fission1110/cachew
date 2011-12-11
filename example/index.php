<?php

// Aliasing rules
use Cachew\Cachew as Cachew;

// Let's go ahead an include the entire suite.
require_once '../lib/Cachew/Cachew.php';
require_once '../lib/Cachew/Cachew/Driver.php';
require_once '../lib/Cachew/Cachew/Driver/APC.php';
require_once '../lib/Cachew/Cachew/Driver/File.php';
require_once '../lib/Cachew/Cachew/Driver/Memcache.php';
require_once '../lib/Cachew/Cachew/Driver/Memcached.php';

// Let's set up our configurations!
Cachew::$configuration['default'] = 'file'; // Maybe try a different driver?
Cachew::$configuration['key'] = 'example1_';
Cachew::$configuration['path'] = __DIR__;

// Let's get our default instance
$cache = Cachew::instance();

// Let's set a nice default
$cache->set('hello-world', 'Hello World!<br>'.PHP_EOL);

// Did it save?
echo $cache->get('hello-world');

// Alright, forget that we did that.
$cache->forget('hello-world');

// Now, this time I want to save "hello-world" if it doesn't already exist, and
// I want to use a Closure instead of a string
$cache->remember('hello-world', function()
{
	return 'Well, hello to you world!<br>'.PHP_EOL;
});

// How'd we do?
echo $cache->get('hello-world');

// M'k.. let's do it again. If "hello-world" isn't set, overwrite it with this
// Clorure
$cache->remember('hello-world', function()
{
	return 'This should not show up, because the cache exists<br>'.PHP_EOL;
});

// But wait... the old cache didn't expire yet? That's so awesome!
echo $cache->get('hello-world');

// Alright.. let's clean up the data since we're just doing an example
$cache->forget('hello-world');

/* End of file file.php */