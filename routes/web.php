<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/19
 * Time: 下午4:03
 */


use Illuminate\Routing\Router;

/** @var $router Router */
$router->get('/', function (\Illuminate\Http\Request $request) {
    var_dump(app(\Illuminate\Http\Request::class)->all());
    return ['123' => 'asd'];
});

$router->get('/123', function (\Illuminate\Http\Request $request) {
    var_dump(app(\Illuminate\Http\Request::class)->all());
    return ['123' => 'asdadsdasd'];
});


$router->get('/a', 'App\Http\Controllers\UserController@index');
