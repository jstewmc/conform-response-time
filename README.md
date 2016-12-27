# floor-execution-time
Floor a script's execution time.

This library will calculate a script's total execution time from `$_SERVER['REQUEST_TIME_FLOAT']` to _now_ in milliseconds. 

If the total execution time is _less than_ the _floor_, it will sleep for the difference:

```php
use Jstewmc\FloorExecutionTime;

// instantiate the service with a 500 millisecond floor
$service = new FloorExecutionTime(500);

// ... something that takes 100 milliseconds

$service();  // sleeps for 400 milliseconds
```

If the execution time is _greater than_ the _floor_, it will not sleep:

```php
use Jstewmc\FloorExecutionTime;

// instantiate the service with a 500 millisecond floor
$service = new FloorExecutionTime(500);

// ... something that takes 1,000 milliseconds

$service();  // sleeps for 0 milliseconds
```

Flooring execution time helps defend against _brute-force_ and _timing_ attacks. Scripts which are likely to be brute-forced should be slow. Scripts which are likely to be timing attacked should have a constant execution time. Some scripts should be both.

## Dependencies

This library depends on the `REQUEST_TIME_FLOAT` [server variable](http://php.net/manual/en/reserved.variables.server.php). There is no guarantee that every web server will provide the `REQUEST_TIME_FLOAT` variable, however, most will. If the `REQUEST_TIME_FLOAT` server variable does not exist, this library will throw a `RuntimeException` on construction.

## License

[MIT](https://github.com/jstewmc/floor-execution-time/blob/master/LICENSE)

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## Version

### 2.0.0, December 27, 2016

* Rename `Floor` to `FloorExecutionTime`.
* Update `__construct()` to throw exception if `REQUEST_TIME_FLOAT` server variable does not exist.
* Remove `$start` argument of `__invoke()` method. It makes more sense to use `REQUEST_START_TIME`.

### 1.0.0, August 16, 2016

* Major release
* Update `composer.json`
* Clean up comments

### 0.1.0, August 3, 2016

* Initial release
