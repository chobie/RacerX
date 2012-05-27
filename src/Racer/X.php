<?php
namespace Racer;

class X
{
    protected $tcp;
    public $controllers = array();

    public function __construct()
    {
        $this->tcp = uv_tcp_init();
    }
    
    public function get($path, $callback)
    {
        $this->controllers[$path] = $callback;
    }
    
    public function listen($port)
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
                    if(isset($cb->controllers[$result['path']])) {
                        $r = $cb->controllers[$result['path']]();
                        
                        $buffer = "HTTP 1.0 200 OK\n\n$r";
                        uv_write($client, $buffer, function($c) use ($client){
                            uv_close($client,function(){});
                        });
                    } else {
                        uv_write($client, "HTTP 1.0 404 Not Found\n\n", function($c) use ($client){
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

