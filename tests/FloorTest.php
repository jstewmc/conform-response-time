<?php
/**
 * The file for the floor-execution-time tests
 *
 * @author     Jack Clayton <jack@jahuty.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\FloorExecutionTime;

use Jstewmc\TestCase\TestCase;

/**
 * Tests for the floor-execution-time class
 */
class FloorTest extends TestCase
{
    /* !__construct() */
    
    /**
     * __construct() should throw exception if $floor is not positive
     */
    public function testConstructThrowsExceptionIfFloorIsNotPositive()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new Floor(-1);
        
        return;
    }
    
    /**
     * __construct() should set properties if $floor is positive
     */
    public function testConstructIfFloorIsPositive()
    {
        $floor = 1;
        
        $floorer = new Floor($floor);
        
        $this->assertEquals($floor, $this->getProperty('floor', $floorer));
        
        return;
    }
    
    
    /* !__invoke() */
    
    /**
     * __invoke() should throw exception if $start is not positive
     */
    public function testInvokeThrowsExceptionIfStartIsNotPositive()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Floor(1))(-1.0);
        
        return;
    }
    
    /**
     * __invoke() should throw exception if $start is after "now"
     */
    public function testInvokeThrowsExceptionIfStartIsAfterNow()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Floor(1))(microtime(true) + 1000);
        
        return;
    }
    
    /**
     * __invoke() should sleep if wait exists
     */
    public function testInvokeSleepsIfWaitDoesExist()
    {
        $floor = 100;  
        $start = microtime(true);    
        
        // floor the execution time
        (new Floor($floor))($start);
        
        // get the time that's elapsed in milliseconds
        $diff = (microtime(true) - $start) * 1000;
        
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
        $start = microtime(true);
    
        // sleep for more than the floor
        usleep($floor * 1000 + 1);
        
        // get the current time
        $break = microtime(true);
        
        // floor the execution time
        (new Floor($floor))($start);
        
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
