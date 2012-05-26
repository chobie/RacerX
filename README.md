# RacerX - Ultimate PHP event driven framework -

whh,whattttt an amazing speeeeeeeeeeeed!

# Examples

```
<?php
require __DIR__ . "/../src/Racer/X.php";

$x = new Racer\X();

$x->get("/",function(){
    return "Hello";
});

$x->listen(8888);
````

# Dependencies

* php-uv
* php-httpparser

# License

MIT License