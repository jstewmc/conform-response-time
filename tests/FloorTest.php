<?php
/**
 * The file for the floor-execution-time service tests
 *
 * @author     Jack Clayton <jack@jahuty.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\FloorExecutionTime;

use Jstewmc\TestCase\TestCase;

/**
 * Tests for the floor-execution-time service
 */
class FloorTest extends TestCase
{	
	/**
	 * __construct() should throw exception if server variable does not exist
	 */
	public function testConstructThrowsExceptionIfServerVariableDoesNotExist()
	{
		$this->setExpectedException('RuntimeException');
		
		unset($_SERVER['REQUEST_TIME_FLOAT']);
		
		new FloorExecutionTime(1);
		
		return;
	}
    
    /**
     * __construct() should throw exception if $floor is not positive
     */
    public function testConstructThrowsExceptionIfFloorIsNotPositive()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new FloorExecutionTime(-1);
        
        return;
    }
    
    /**
     * __construct() should set properties if $floor is positive
     */
    public function testConstructIfFloorIsPositive()
    {
        $floor = 1;
        
        $service = new FloorExecutionTime($floor);
        
        $this->assertEquals($floor, $this->getProperty('floor', $service));
        
        return;
    }
    
    /**
     * __invoke() should sleep if wait exists
     */
    public function testInvokeSleepsIfWaitDoesExist()
    {
        $floor = 100;  
        
        // instantiate the service
        $service = new FloorExecutionTime($floor);
        
        // set the server variable
        $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);    
        
        // floor the execution time
        $service();
        
        // get the time that's elapsed in milliseconds
        $diff = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
        
        // the diff should be greater than or equal to the interval
        $this->assertGreaterThanOrEqual($floor, $diff);
        
        return;
    }
    
    /**
     * sleep() should not sleep if time does not exist
     */
    public function testInvokeDoesNotSleepIfWaitDoesNotExist()
    {
        $floor = 100; 
        
        // set the server variable
        $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
    
        // sleep for more than the floor
        usleep($floor * 1000 + 1);
        
        // get the current time
        $break = microtime(true);
        
        // floor the execution time
        $service = new FloorExecutionTime($floor);
        
        // floor the execution time
        $service();
        
        // get the time that's elapsed from the break to now in milliseconds
        $diff = (microtime(true) - $break) * 1000;
        
        // the diff should be less than the interval...
        // keep in mind, it should be very close to zero, essentially the time it
        //     took for PHP to load the class and execute the function; however, 
        //     we can't test for equality
        //
        $this->assertLessThan($floor, $diff);
        
        return;
    }
}
