<?php

require 'PubSub.php';

PubSub::subscribe('sayHello', function($name) {
  echo "Hello $name";
});


PubSub::publish('sayHello', 'World');
