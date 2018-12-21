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
    public function index()
    {

        Context::coroutine(function () {
            \co::sleep(5);
            var_dump(\request()->all());
        });
        return app(Request::class)->input('a');
    }
}
