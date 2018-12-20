# Rocket
## Swoole with laravel component

```php

<?php

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

```


```php
<?php

class UserController
{
    public function index()
    {
        go(function () {
            \co::sleep(10);
            echo app(Request::class)->input('a');
        });
        return app(Request::class)->input('a');
    }
}


```


协程可以使用，但是使用容器时候像上面例子，快速的访问 `http://localhost:9501/a?a=1``http://localhost:9501/a?a=2`，结果都是两次2



