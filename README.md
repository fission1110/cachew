# Cachew - Powerfully Simple PHP Caching

__Cachew__ is an open source object-oriented library built in PHP 5.3. Using
best practices and thoughtful design, Cachew provides you decoupled and
unit-testable code.

## Requirements

The Cachew library has the following requirements:

* PHP 5.3 or greater

And, depending on which driver you plan to use, at least one of the following:

* A writable directory on your system
* [APC](http://php.net/apc)
* [Memcache](http://php.net/memcache) or [Memcached](http://php.net/memcached)

## How it works

### Step 1: Pick your driver

Cachew implements multiple drivers, allowing you to either pick your favorite,
or use which driver makes the most sense for the task at hand.  Each driver
has constructor-injection requirements to allow you to utilize it with the rest
of your system. If the example below doesn't make sense, just check out their
docblocks!

    $cache = new Cachew\Driver\APC('application-key');
    $cache = new Cachew\Driver\File('application-key', $cacheDirectory);
    $cache = new Cachew\Driver\Memcache('application-key', $memcache);
    $cache = new Cachew\Driver\Memcached('application-key', $memcached);

### Step 2: Set up your cached information

The methods you need to consider while working with Cachew are as follows:
`set`, `get`, `exists`, `remember`, and `forget`. Each of the methods do
something a bit difference, and most of them are self explanatory, but here are
their definitions:

##### set(`key`, `value`, `minutes` = 60)

Write an item to the cache for a given number of minutes

##### get(`key`, `default` = null)

Get an item from the cache. If the item doesn't exist in the cache, return a
default value.

##### exists(`key`)

Determine if an item exists in the cache

##### remember(`key`, `default` = null, `minutes` = 60)

Get an item from the cache. If the item doesn't exist in the cache, store the
default value in the cache.

##### forget(`key`)

Delete an item from the cache

### Step 3: Profit

Everything implemented here is meant to provide you all the power you need from
a cache, with as little headache as possible. Go ahead, try it out! Let us know
what you think!