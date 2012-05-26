<?php
require __DIR__ . "/../src/Racer/X.php";

$x = new Racer\X();

$x->get("/",function(){
    return "Hello";
});

$x->listen(8888);
