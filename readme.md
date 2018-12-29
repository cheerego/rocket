# Rocket 
## Swoole with laravel component
### now It is like a toy

### 目前实现
* laravel routing
* 每一协程都有自己的 Container

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
```
每一个协程自己都会有自己的一份容器实例，不会收到父协程退出和全局变量的影响的印象。
使用协程可以使用swoole的原生方式，这个方式是没有自己独有的容器的，只能通过 use (xxx) 来使用。
如果使用Context::coroutine 来创建协程，每一个协程都具有自己独有的容器

