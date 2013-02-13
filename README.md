# Postmaster

Developer Friendly Shipping

Postmaster takes the pain out of sending shipments via UPS, Fedex, and USPS.

Save money before you ship, while you ship, and after you ship.

https://www.postmaster.io/

## Requirements

- [PHP](http://www.php.net) >= 5.3 **with** [cURL](http://www.php.net/manual/en/curl.installation.php)
    
## Issues

Please use appropriately tagged github [issues](https://github.com/postmaster/postmaster-api/issues) to request features or report bugs.

## Installation

You can install using [composer](#composer) or from [source](#source). 

### Composer

If you don't have Composer [install](http://getcomposer.org/doc/00-intro.md#installation) it:

    $ curl -s https://getcomposer.org/installer | php

Add this to your `composer.json`: 

    {
        "require": {
            "postmaster/postmaster-php": "*"
        }
    }
    
Refresh your dependencies:

    $ php composer.phar update
    

Then make sure to `require` the autoloader:
    
    <?php
    require(__DIR__ . '/vendor/autoload.php');
    ...

### Source

Download the postmaster-php source:

    $ git clone https://github.com/postmaster/postmaster-php

And then `require` all bootstrap files:

    <?php
    require_once("/path/to/postmaster-php/lib/Postmaster.php");
    ...

## Quickstart

    curl -s http://getcomposer.org/installer | php

    echo '{
        "require": {
            "postmaster/postmaster-php": "*"
         }
    }' > composer.json

    php composer.phar install

    curl https://raw.github.com/postmaster/postmaster-php/master/Example.php > Example.php

    php Example.php
 
## Usage

See https://www.postmaster.io/docs for tutorials and documentation.

Some examples also in [Example.php](Example.php) file.

## Testing
    
    $ cd tests
    $ PM_API_KEY=your-api-key phpunit
    

