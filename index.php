<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/19
 * Time: 下午2:01
 */


require 'vendor/autoload.php';




Swoole\Runtime::enableCoroutine();
$serv = new Server();
$serv->run();
