<?php

require_once 'PubSub_Event.php';

class PubSub {
  
  private static $events = array();
  
  /*
    array('event_name', (integer))
  */
  private static $current_item = array();
  
  
  /*
    standard: events with the same name get called in the order created
    extendable: the last event is the one called, but can call the previous one with `PubSub::super()`
    locked: prevents additional events created with the same name
  */
  private static $modes = array('standard', 'extendable', 'locked');
  
  /**
   * Create a "cache" of the function/callback to be called later
   *
   * @param string $event_name The name of the event that can be called with PubSub::publish
   * @param mixed $callback Anything that can be called with call_user_func()
   * @return void
   * @author Baylor Rae'
   */
  public static function subscribe($event_name, $callback, $mode = 'standard') {
    // array_push(self::$events, array(
    //   $event_name => array(
    //     'callback' => $callback,
    //     'mode' => $mode
    //   )
    // ));
        
    if( $events = self::find_events($event_name) ) {
      
      if( $events[0]->is_locked() )
        return;
      
      $event = new PubSub_Event($event_name, $callback, $mode);
      $event->is_original = false;
      
      if( $events[0]->is_extendable() )
        $event->mode = 'extendable';
    }else {
      $event = new PubSub_Event($event_name, $callback, $mode);
    }
    
    array_push(self::$events, $event);
  }
    
  public static function publish($event_name, $params = '') {
    
    $params = func_get_args();
    array_shift($params);
    
    if( $events = self::find_events($event_name) ) {
      // echo '<pre>',  print_r($events, true), '</pre>';
      
      if( $events[0]->is_extendable() ) {
        $e = array_pop($events);
        self::current_item($event_name, $e->position_in_stack);
        $e->call($params);
      }else {
        foreach( $events as $e ) {
          self::current_item($event_name, $e->position_in_stack);
          $e->call($params);
        }
      }
    }
  }
  
  public static function unsubscribe($event_name) {
    
    if( $events = self::find_events($event_name) ) {
      foreach( $events as $event ) {
        if( !$event->is_original() && !$event->is_locked() ){
          unset(self::$events[$event->position_in_stack]);
        }
      }
    }
  }
  
  public static function super($params = '') {
    $current_item = self::current_item();
    
    if( $current_item['position'] > 0 ) {
      if( $event = self::find_event_by_name_and_position($current_item['event_name'], --$current_item['position']) ) {
        
        if( $event->is_extendable() ) {
          self::current_item($current_item['event_name'], $current_item['position']);
          $event->call(func_get_args());
        }
      }
    }
  }
  
  private static function find_events($event_name) {
    $events = array();

    foreach( self::$events as $i => $event ) {
      if( $event->name == $event_name ) {
        $event->position_in_stack = $i;
        array_push($events, $event);
      }
    }
    
    return empty($events) ? false : $events;
  }
  
  private static function find_event_by_name_and_position($event_name, $position) {
    foreach( self::$events as $i => $event ) {
      if( $event->name == $event_name && $i == $position )
        return $event;
    }
    return false;
  }
  
  private static function current_item($event_name = null, $new_value = null) {
    if( empty($new_value) )
      return self::$current_item;
    else
      self::$current_item = array('event_name' => $event_name, 'position' => $new_value);
  }
  
}