PayNearMe - PHP Callbacks
-------------------------

This is a starting point for integrating your service with PayNearMe's callback
system. Provided in this project is a framework for handling incoming callbacks
from PayNearMe and constructing the appropriate response. Each callback method
includes an example implementation.

Requirements
============

* PHP 5.3.x or later
* Some way of serving php

Usage
=====

### Standalone

	$ php -S 0.0.0.0:3000 routes.php

### Apache/standalone files

Serve this directory and access authorize.php or confirm.php

