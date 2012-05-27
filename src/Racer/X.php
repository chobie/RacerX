<?php
namespace Racer;

class X
{
    /* @var resouce uv_tcp */
    protected $tcp;

    /* @var array controllers */
    public $controllers_get = array();

    /* @var array controllers */
    public $controllers_post = array();

    /**
     * make an instance
     */
    public function __construct()
    {
        $this->tcp = uv_tcp_init();
    }

    /**
     * add GET routing
     *
     * @param $path
     * @param $callback
     */
    public function get($path, $callback)
    {
        $this->controllers_get[$path] = $callback;
    }

    /**
     * add POST routing
     *
     * @param $path
     * @param $callback
     */
    public function post($path, $callback)
    {
        $this->controllers_post[$path] = $callback;
    }

    /**
     * listen and run loop.
     *
     * @param int $port
     */
    public function listen($port = 8080)
    {
        uv_tcp_bind($this->tcp, "0.0.0.0", $port);

        $cb = $this;
        uv_listen($this->tcp,1000, function($server) use ($cb){
            $client = uv_tcp_init();
            uv_accept($server, $client);
            uv_read_start($client, function($buffer, $client) use ($cb){
                $parser = http_parser_init();
                
                $result = array();
                if (http_parser_execute($parser, $buffer, $result)){

                    switch($result['REQUEST_METHOD']) {
                        case "GET":
                            if(isset($cb->controllers_get[$result['path']])) {
                                $r = $cb->controllers_get[$result['path']]();

                                $buffer = "HTTP/1.0 200 OK\n\n$r";
                                uv_write($client, $buffer, function($c) use ($client){
                                    uv_close($client,function(){});
                                });
                            } else {
                                uv_write($client, "HTTP/1.0 404 Not Found\n\n", function($c) use ($client){
                                    uv_close($client,function(){});
                                });
                            }
                            break;
                        case "POST":
                            if(isset($cb->controllers_post[$result['path']])) {
                                $r = $cb->controllers_post[$result['path']]();

                                $buffer = "HTTP/1.0 200 OK\n\n$r";
                                uv_write($client, $buffer, function($c) use ($client){
                                    uv_close($client,function(){});
                                });
                            } else {
                                uv_write($client, "HTTP/1.0 404 Not Found\n\n", function($c) use ($client){
                                    uv_close($client,function(){});
                                });
                            }
                            break;
                        default:
                            uv_write($client, "HTTP/1.0 404 Not Found\n\n", function($c) use ($client){
                                uv_close($client,function(){});
                            });
                    }
                } else {
                    uv_close($client,function(){});
                }
            });
        });

        uv_run();
    }
}

