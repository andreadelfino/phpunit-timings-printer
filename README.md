## PHPUnit ResultPrinter with Tests Timings

This printer just collect tests execution timings and report the slowest 10 exceeding a defined threshold in normal mode and all the timings in verbose mode.


## Requirements

 * PHP 5.3.3 or later
 * PHPUnit 3.7 or later

## Installation

To install it via composer [composer](http://getcomposer.org) just add in `composer.json`:

~~~json
{
    "require-dev": {
        "andreadelfino/phpunit-timings-printer": "~0.1"
    }
}
~~~

Once installed, add the following attributes to the `<phpunit>` element in your `phpunit.xml` file:

~~~xml
    printerFile="vendor/andreadelfino/phpunit-timings-printer/lib/Timings/ResultPrinter.php"
    printerClass="Dolphin\PHPUnit\Timings\ResultPrinter"
~~~

and the following tag to tune `Threshold` and `Verbose` options:

~~~xml
    <listeners>
        <listener file="vendor/andreadelfino/phpunit-timings-printer/lib/Timings/TestListener.php"
            class="Dolphin\PHPUnit\Timings\TestListener">
            <arguments>
                <double>1.0</double>/* Threshold (default: 1.0) */
                <boolean>false</boolean>/* Verbose (default: false) */
            </arguments>
        </listener>
    </listeners>
~~~

## Tests

To run the test suite you need [composer](http://getcomposer.org).

    $ php composer.phar install
    $ vendor/bin/phpunit

## License

Licensed under the [MIT license](LICENSE).
