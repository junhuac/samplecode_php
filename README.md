PayNearMe - PHP Callbacks
-------------------------

This is a starting point for integrating your service with PayNearMe's callback
system. Provided in this project is a framework for handling incoming callbacks
from PayNearMe and constructing the appropriate response. Each callback method
includes an example implementation.

Requirements
============

* PHP 5.3.x or later
* Some way of serving php (Apache + mod_php, nginx + fastcgi, etc)

Usage
=====

### Standalone (**not for production!**)

	$ php -S 0.0.0.0:3000 routes.php

You can access the endpoints through http://localhost:3000/authorize and
/confirm.

### Apache/standalone files

Serve this directory and access authorize.php or confirm.php.  Preferably the
lib/ directory would be somewhere not accessible to the web, and added to the
`include_path`.  Do not serve `routes.php` if you serve the `authorize.php`
and `confirm.php` scripts.

Configuration
=============

We recommend setting your php max_execution_time and webserver timeouts high
enough to accomodate long-running requests. Set $site_identifier and $secret in
a configuration file (example uses `config.php`) and verify the site_identifier.
