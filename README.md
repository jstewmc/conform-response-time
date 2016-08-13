# floor-execution-time
Floor a script's execution time.

This library will calculate a script's total execution time to now. If the execution time is less than the _floor_, it will sleep for the difference:

```php
use Jstewmc\FloorExecutionTime;

// get the script's start time in seconds *with* microseconds
$start = microtime(true);

// ... a fast PHP script that takes 100 milliseconds

// floor the execution time at 500 milliseconds
(new Floor(500))($start);  // sleeps for 400 milliseconds
```

```php
use Jstewmc\FloorExecutionTime;

// get the script's start time in seconds *with* microseconds
$start = microtime(true);

// ... a slow PHP process that takes 1,000 milliseconds

// floor the execution time at 500 milliseconds
(new Floor(500))($start);  // sleeps for 0 milliseconds
```

A floor execution time helps defend against _brute-force_ and _timing_ attacks. Scripts which are likely to be brute-forced should be slow. Scripts which are likely to be timing attacked should have a constant execution time. Some scripts should be both.

## Start time

If you're working with HTTP requests, don't forget about the `REQUEST_TIME_FLOAT` [server variable](http://php.net/manual/en/reserved.variables.server.php).

## License

[MIT](https://github.com/jstewmc/floor-execution-time/blob/master/LICENSE)

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## Version

### 1.0.0, August 16, 2016

* Major release
* Update `composer.json`
* Clean up comments

### 0.1.0, August 3, 2016

* Initial release
