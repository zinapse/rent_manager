# zinapse/rent_manager
## Rent Manager API package
----------------------------------

This package aims to help developers communicate with the Rent Manager API. All you need to do is define data in an array and pass that to the constructor.

## Example Usage
```php
<?php

use zinapse\RentManager;

$data = [
  'id' => null,         // (optional, string|int)     ID of object to get.
  'entity' => 'Units',  // (required, string)         The entity type to get (Units, Amenities, etc)
  'embeds' => [],       // (optional, array[string])  Any data you want to embed
  'fields' => []        // (optional, array[string])  Any fields you want to include
];

// Create the object
$rent_manager = new RentManager($data);

// $return will now be an array created from the JSON response
$return = $rent_manager->run();
```
