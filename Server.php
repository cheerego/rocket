<?php

/**
 *  基于swoole扩展的http-server封装的一个接受异步任务请求的http服务器
 */


use App\Http\Transformers\TransformRequest;
use App\Http\Transformers\TransformResponse;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;

class Server
{
//    private $router;
    private $container;
//    private $events;

    public function __construct()
    {
        $this->container = \Illuminate\Container\Container::getInstance();
//        $this->events = new Dispatcher($this->container);
//        $router = new Router($this->events, $this->container);
//        require './routes/web.php';
//        $this->router = $router;
    }

    private function setProcessName($name)
    {
        if (PHP_OS == 'Darwin') {
            return false;
        }
        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else {
            if (function_exists('swoole_set_process_name')) {
                swoole_set_process_name($name);
            } else {
                throw new \Exception(__METHOD__ . "failed,require cli_set_process_title|swoole_set_process_name");
            }
        }
    }


    private $config = [
        'pid_file' => __DIR__ . '/rocket.pid',
        'host' => __DIR__ . '0.0.0.0',
        'port' => 9501,
        'ps_name' => 'rocket',
        'worker_num' => 5,
        'task_worker_num' => 5
    ];


    public function run()
    {
        $http = new \swoole_http_server($this->config['host'], $this->config['port']);
        $call = [
            'start',
            'workerStart',
            'managerStart',
            'request',
            'task',
            'finish',
            'workerStop',
            'shutdown',
        ];
        foreach ($call as $v) {
            $m = 'on' . ucfirst($v);
            if (method_exists($this, $m)) {
                $http->on($v, [$this, $m]);
            }
        }
        $http->start();
    }

    public function onStart($server)
    {
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server master worker start\n";
        $this->setProcessName($this->config['ps_name'] . '-master');
        //记录进程id,脚本实现自动重启
        $pid = "{$server->master_pid}\n{$server->manager_pid}";
        file_put_contents($this->config['pid_file'], $pid);
    }

    public function onManagerStart($server)
    {
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server manager worker start\n";
        $this->setProcessName($this->config['ps_name'] . '-manager');
    }

    public function onShutdown()
    {
        unlink($this->setting['pid_file']);
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server shutdown\n";
    }

    public function onWorkerStart($server, $workerId)
    {
        $this->clearCache();

//        $this->container->singleton(Sandbox::class, function ($app) {
//            return new Sandbox($app);
//        });
//        $this->container->alias(Sandbox::class, 'swoole.sandbox');

        if ($workerId >= $this->config['worker_num']) {
            $this->setProcessName($this->config['ps_name'] . '-task');
        } else {
            $this->setProcessName($this->config['ps_name'] . '-work');
        }
    }

    public function onWorkerStop($server, $workerId)
    {
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server[{$server->setting['ps_name']}] worker:{$workerId} shutdown\n";
    }

    public function onRequest(\Swoole\Http\Request $swooleRequest, \Swoole\Http\Response $swooleResponse)
    {
        $uri = $swooleRequest->server['request_uri'];
        if ($uri == '/favicon.ico') {
            $swooleResponse->status(404);
            $swooleResponse->end();
        }
//        $handleStatic = $this->container['config']->get('swoole_http.handle_static_files', true);
//        $publicPath = $this->container['config']->get('swoole_http.server.public_path', base_path('public'));

        try {
//             handle static file request first
//            if ($handleStatic && TransformRequest::handleStatic($swooleRequest, $swooleResponse, $publicPath)) {
//                return;
//            }

            \App\Rocket\Coroutine::setBaseId();




            \App\Concern\Context::setApp(clone $this->container);
            $routingRequest = TransformRequest::make($swooleRequest)->getIlluminateRequest();
            \App\Concern\Context::getApp()->instance('Illuminate\Http\Request', $routingRequest);
            $router = new Router(new Dispatcher(\App\Concern\Context::getApp()), \App\Concern\Context::getApp());
            require './routes/web.php';
            $routingResponse = $router->dispatch($routingRequest);
            $response = TransformResponse::make($routingResponse, $swooleResponse);
            $response->send();


        } catch (Throwable $e) {

        } finally {
            \App\Rocket\Coroutine::clear(co::getCid());
            \App\Concern\Context::clear();
        }
    }

    /**
     * 任务处理
     *
     * @param $server
     * @param $taskId
     * @param $fromId
     * @param $request
     *
     * @return mixed
     */
    public function onTask($server, $taskId, $fromId, $request)
    {
        echo 'onTask';
    }

    /**
     * 任务结束回调函数
     *
     * @param $server
     * @param $taskId
     * @param $ret
     */
    public function onFinish($server, $taskId, $ret)
    {
        echo 'onFinish';
    }

    protected function clearCache()
    {
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
}
