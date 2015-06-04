omniconv-php
============
VERY simple api wrapper for omniconv.com

API Reference
-------------
http://omniconv.com/api

Example
-------
```php
$omniconv = new Omniconv\Client($apiKey);

$omniconv->conv('pdf', '/path/to/infile.odt', '/path/to/outfile.pdf');
```