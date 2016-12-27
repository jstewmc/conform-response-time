<?php
/**
 * The file for the floor-execution-time service
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\FloorExecutionTime;

use InvalidArgumentException;
use RuntimeException;

/**
 * The floor-execution-time service 
 *
 * @since  2.0.0
 */
class FloorExecutionTime
{
    /* !Private properties */
    
    /**
     * @var    int  the floor in milliseconds
     * @since  2.0.0
     */
    private $floor;
    
    
    /* !Magic methods */
    
    /**
     * Called when the service is constructed
     *
     * @param   int  $floor  the floor in milliseconds
     * @throws  RuntimeException          if the server variable, 
     *     $_SERVER['REQUEST_TIME_FLOAT'] does not exist
     * @throws  InvalidArgumentException  if $floor is not a positive integer
     * @since   2.0.0
     */
    public function __construct(int $floor)
    {   
        // if the server variable does not exist, short-circuit
        if ( 
            ! isset($_SERVER) 
            || ! array_key_exists('REQUEST_TIME_FLOAT', $_SERVER)
        ) {
            throw new RuntimeException(
                __METHOD__ . "() expects the server variable, 'REQUEST_TIME_FLOAT', "
                    . "to exist"
            );    
        }
        
        // if $floor is not a positive integer, short-circuit
        if ($floor < 1) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, floor, to be a positive "
                    . "number of milliseconds"
            );
        }
        
        $this->floor = $floor;
    }
    
    /**
     * Called when the service is treated like a function
     *
     * If the difference between the requset's start-time and the current-time is 
     * less than the floor time, I'll sleep for the difference.
     *
     * @return  void
     * @since   2.0.0
     */
    public function __invoke()
    {
        // get the request's start time
        $start = $_SERVER['REQUEST_TIME_FLOAT'];
        
        // get the difference between the now and start time
        $diff = $this->getDiff($start);
        
        // if a wait exists, sleep my sweet prince!
        if (0 < ($wait = $this->getWait($diff))) {
            usleep($wait);
        }
		
        return;
    }
    
    
    /* !Private methods */
    
    /**
     * Returns the difference between now and start-time in microseconds
     *
     * Watch the units! I'll return the difference in microseconds (e.g., ######), 
     * while PHP's microtime() and $start are in seconds *with* microseconds (e.g., 
     * ######.###).
     *
     * @param   float  $start  the start-time *with* microseconds (e.g., ###.###)
     * @return  int
     * @since   2.0.0
     */
    private function getDiff(float $start): int
    {
        return round((microtime(true) - $start) * 1000000);
    }
    
    /**
     * Returns the wait-time in microseconds
     *
     * Watch the units! I'll return the amount of time the application should sleep 
     * as a whole number of microseconds (e.g., #########). However, $floor is in
     * milli-seconds and $diff is in micro-seconds.
     *
     * @param   int  $diff  the difference between now and the start-time *in* 
     *     microseconds (e.g., #########)
     * @return  int
     * @since   2.0.0
     */
    private function getWait(int $diff): int
    {
        return ($this->floor * 1000) - $diff;
    }
}
