<?php

class PubSub {
  
  private static $events = array();
  
  /**
   * Create a "cache" of the function/callback to be called later
   *
   * @param string $event_name The name of the event that can be called with PubSub::publish
   * @param mixed $callback Anything that can be called with call_user_func()
   * @return void
   * @author Baylor Rae'
   */
  public static function subscribe($event_name, $callback) {
    array_push(self::$events, array(
      $event_name => $callback
    ));
  }
    
  public static function publish($event_name, $params = '') {
    
    $params = func_get_args();
    array_shift($params);
    
    foreach( self::$events as $i => $event ) {
      
      if( isset($event[$event_name]) ) {
        call_user_func_array($event[$event_name], $params);
      }
      
    }
  }
  
  public static function unsubscribe($event_name) {
    foreach( self::$events as $i => $event ) {
      unset(self::$events[$i][$event_name]);
    }
  }
  
}