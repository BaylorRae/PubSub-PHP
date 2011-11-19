## PubSub for PHP
This is an implementation of PubSub for PHP. It has three main "modes" of functionality.

1. Standard: This is the traditional form, where every subscription gets pushed to in the stack and called in order.
2. Extendable: This provides an environment where the last subscription added gets called. Its "parent" can then be called with `PubSub::super($params ...)`
3. Locked: Only allows one subscription with its name to be created.

## Standard Example

    <?php

    require 'PubSub.php';

    PubSub::subscribe('sayHello', function() {
      echo "This gets called first";
    });

    PubSub::subscribe('sayHello', function() {
      echo 'This gets called second';
    });
    
    PubSub::publish('sayHello');
    
## Extendable Example

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
    
    PubSub::subscribe('sayHello', function($name) {
      $name .= ', you found my class!';
      PubSub::super($name);
    });
    
    PubSub::publish('sayHello', 'Wonderful World');
    
## Locked Example

    <?php
    
    require 'PubSub.php';
    
    PubSub::subscribe('sayHello', function() {
       echo 'This is the only one called'; 
    }, 'locked');
    
    PubSub::subscribe('sayHello', function() {
       echo 'this doesn't show up :('; 
    });
    
    PubSub::publish('sayHello');