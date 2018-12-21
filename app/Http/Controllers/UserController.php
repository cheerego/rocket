<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/19
 * Time: 下午3:39
 */

namespace App\Http\Controllers;


use App\Concern\Context;
use App\Rocket\Coroutine;
use Illuminate\Http\Request;

class UserController
{
    public function index()
    {
        $app = Context::getApp();
        Coroutine::create(function () use($app){
            \co::sleep(1);
            var_dump(\request()->all());
        });
        return app(Request::class)->input('a');
    }
}
