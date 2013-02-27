<?php

namespace PubSub;

class PubSub {
  
  private static $events = array();
  
  /**
   * Get all events that have been subscribed to
   *
   * @return array
   * @author Baylor Rae'
   */
  public static function events() {
    return self::$events;
  }

  /**
   * Add a new event subscription
   * 
   * throws InvalidArgumentException if callback isn't callable
   *
   * @param string $event 
   * @param callback $callback 
   * @return void
   * @author Baylor Rae'
   */
  public static function subscribe($event, $callback) {
    if( !is_callable($callback) ) {
      throw new \InvalidArgumentException();
    }
    
    if( empty(self::$events[$event]) ) {
      self::$events[$event] = array();
    }
    
    array_push(self::$events[$event], $callback);
  }
  
  /**
   * Call all subscriptions within an event
   *
   * @param string $event 
   * @param mixed *$params 
   * @return void
   * @author Baylor Rae'
   */
  public static function publish($event, $params = '') {
    $events = self::events();
    $params = func_get_args();
    array_shift($params);
    
    foreach( $events[$event] as $event ) {
      call_user_func_array($event, $params);
    }
  }
  
  public static function unsubscribe($event) {
    self::$events[$event] = array();
  }
  
  public static function unsubscribe_all() {
    self::$events = array();
  }

}

