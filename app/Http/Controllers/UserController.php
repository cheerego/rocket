<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/19
 * Time: 下午3:39
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UserController
{
    public function index()
    {
        echo \co::getuid();
        go(function () {
            echo \co::getuid();
            \co::sleep(10);
            echo app(Request::class)->input('a');
        });
        return app(Request::class)->input('a');
    }
}
