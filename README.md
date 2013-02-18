## PubSub for PHP
This is an implementation of PubSub for PHP that's designed to have a simple api.

## Usage

```php
<?php

include 'pub_sub.php';

// called when the admin area is loaded
PubSub::subscribe('/admin/load', function() {
  AdminArea::add_nav_link('Log out', '/logout');
});

// used to add js files to the page
PubSub::subscribe('/enqueue_js', function($additional_js = array()) {
  AssetManager::add_js(array_merge(array(
    'http://code.jquery.com/jquery.min.js',
    '/js/jquery.anything-slider.js'
  ), $additional_js));
});
?>

  <!-- product page -->
  <?php PubSub::publish('/enqueue_js', array('/js/product-gallery.js')); ?>
</body>
</html>
```
