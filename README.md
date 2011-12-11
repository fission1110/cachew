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

Also, if you check out the example we've provided, it shows you how you can do
the same thing above, but by using our gateway Cachew class! It's even easier!

    $cache = Cachew::instance('file');

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

## How does Memcache(d) work?

Well, you have a couple of options on that.

1. We've made a library for both Memcache and Memcached that behave identically,
   which you can (and we highly recommend you do) use.
2. You can pass your own Memcache(d) instances to the drivers through the
   constructors.

### Well, tell me how to use your libraries?

They're pretty simple. All you need to do is pass your configurations to the
object like so (feel free to exchange the word Memcached for Memcache, because
they're exactly the same to use)

    Memcached::$configuration = array(
    	array('host' => '127.0.0.1', 'port' => 11211, 'weight' => 50),
    	array('host' => '192.168.2.10', 'port' => 11211, 'weight' => 50),
    );

After you've done that, just call the instancer method, and you'll have yourself
a fully functional Memcached connection to do whatever you want with!

    Memcached::instance();

### Okay, but if it's just returning an instance to PHP's Memcache(d) libraries, why don't I just use those?

You can, but there's two things that happen if you choose to:

1. You have to manage your own singleton instance to it. It's pretty wasteful to
   have multiple variables lying around when you could just this method.
2. You can't use the gateway class we've created. There's no way for us to know
   your Memcached configurations within that class, as well as not create
   duplicate instances without doing this.  So, just keep in mind, you can't do:
       Cachew::instance('memcached');
   without using our classes. Sorry!