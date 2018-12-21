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

        Context::coroutine(function () {
            \co::sleep(5);
            var_dump(\request()->all());
        });
        return app(Request::class)->input('a');
    }
}
```
每一个协程都有容器的一份clone自己独有，不会导致下一次的request 影响上一次的
