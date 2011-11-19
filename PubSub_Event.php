<?php

class PubSub_Event {
  
  public $name,
         $callback,
         $mode,
         $is_original = true,
         
         // Used when unsubscribing
         $position_in_stack = 1;
          
  function __construct($name, $callback, $mode) {
    $this->name = $name;
    $this->callback = $callback;
    $this->mode = $mode;
  }
  
  public function is_standard() {
    return $this->mode == 'standard';
  }
  
  public function is_extendable() {
    return $this->mode == 'extendable';
  }
  
  public function is_locked() {
    return $this->mode == 'locked';
  }
  
  public function is_original() {
    return $this->is_original;
  }
  
  public function call($params) {
    call_user_func_array($this->callback, $params);
  }
  
}