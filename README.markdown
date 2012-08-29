#Nod
========
Notifications in PHP (`notify-send, growl, etc`) like **that**.

##Examples
============

Letting `Nod` figure out the best Adapter to use (**not recommend ATM, only works with some Linux environments**):

```php
#!/usr/bin/env php
<?php  

$notification = new Nod\Notification;
$notification
  ->setTitle('My rad notification')
  ->setMessage('Check it.')
  ->setUrgency('high')
  ->setExpiry(3000)
  ->setIcon('emblem-favorite.png')
  ->send();
```

Explicitly giving **Nod** an Adapter to work with:

```php
#!/usr/bin/env php
<?php  

use Nod\Adapter\Terminal as TerminalAdapter;

$notification = new Nod\Notification(new TerminalAdapter);
$notification
  ->setTitle('Look at ya')
  ->setMessage('Let me see what ya got')
  // you can also specify the expiry directly in the send() method:
  ->send(5000);
```

Creating your own Adapters is also as easy as implementing `Nod\Adapter\AdapterInterface`:

```php
<?php

namespace Nod\Adapter;
interface AdapterInterface
{
    /* bool */ public function canNotify();
    /* bool */ public function process($title, $message, $urgency, $expiry, $icon);
}
```

##Installation
============

**Nod** is available through **[composer](http://getcomposer.org/)** ([packagist page](http://packagist.org/packages/filp/nod)), just drop `filp/nod` into your `composer.json` and you're good to go:

```javascript
{
    "require" : {
        "filp/nod" : "dev-master"
    }
}
```

The Nod library can be loaded directly through the composer autoloader, or with any `PSR-0` compatible autoloader.

##TODOs, wishlist, etc
============

* Platform support (OSX growl, Windows growl, etc)
* Unit tests (soon!)
* More adapters for all sorts of zany stuff.