# RacerX - Ultimate PHP event driven framework -

whh,whattttt an amazing speeeeeeeeeeeed!

# Examples

````php
<?php
require __DIR__ . '/../vendor/autoload.php';
require "debug.php";

use Racer\X\Request;
use Racer\X\Response;

$x = new Racer\X(function($app){
    $count = 0;

    $app->get("/",function(Request $req) use (&$count){
        $count++;
        switch ($count) {
            case 1:
                $suffix = "st";
                break;
            case 2:
                $suffix = "nd";
                break;
            case 3:
                $suffix = "rd";
                break;
            default:
                $suffix = "th";
        }
        return new Response("Hello. you are the {$count}{$suffix} visitor.");
    });
});
$x->sacrifice(8888);
````

# Dependencies

* php-uv

https://github.com/chobie/php-uv

* php-httpparser

https://github.com/chobie/php-httpparser

# License

MIT License