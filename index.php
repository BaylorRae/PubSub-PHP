<?php

require 'PubSub.php';

// This is the original subscription
// All others should eventually need to call this one, but it's not necessary
// because it's __extendable__
PubSub::subscribe('sayHello', function($name) {
  echo "<p>Hello $name</p>";
}, 'extendable');

PubSub::subscribe('sayHello', function($name) {
  $name = strtolower(str_replace(' ', '-', $name));
  PubSub::super($name);
});

PubSub::subscribe('lol', function() {
  echo 'lol';
}, 'extendable'); // try changing "extendable" to 'standard' or 'locked'

PubSub::subscribe('lol', function() {
  echo 'I said ';
  PubSub::super();
});

PubSub::subscribe('lol', function() {
  echo 'Dad and ';
  PubSub::super();
});

// PubSub::unsubscribe('sayHello');

PubSub::publish('sayHello', "Baylor Rae'");
PubSub::publish('lol');