# Rocket 
## Swoole with laravel component
### now It is like toy

```javascript
php index.php
```
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
每一个协程自己都会有自己的一份容器实例，不会收到父协程退出和全局变量的影响的印象
