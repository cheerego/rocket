<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/19
 * Time: 下午3:39
 */

namespace App\Http\Controllers;


use App\Rocket\Context;
use Illuminate\Http\Request;

class UserController
{
    public function index(Request $request)
    {


        go(function () use ($request) {
            \co::sleep(10);
            var_dump(\request()->all());
        });
        Context::coroutine(function () {
            \co::sleep(10);
            var_dump(\request()->all());
        });
        return app(Request::class)->input('a');
    }
}
