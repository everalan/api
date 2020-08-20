A RESTful API package for the Laravel frameworks, Like dingo/api but more simple

## 与 `Dingo\Api` 的关系
借鉴了 `Dingo\Api`，但是放弃了对系统的侵入式修改，例如
- 只能使用一个配置文件，无法实现多套接口并存的情况
- 自定义的认证
- 劫持了系统路由，导致 `Response` 被多次渲染，但是只输出了最后一次
 
`Everalan\Api` 通过使用 `Helpers`、`Middleware` 来显示的定制，并不对系统进行修改，但是又实现了大部分功能。  
完全参照 `Dingo\Api` 的语法格式，可以轻松切换过来。 

## 环境要求

1. PHP >= 7.0
2. Laravel >= 5.6

## 安装

```shell
$ composer require everalan/api
```
## 用法

### 响应 Response

控制器通过添加 `Helpers` Trait，获取 `$this->response()` 能力，可以响应以下类型内容：

#### 响应单个模型
```php
use Everalan\Api\Helpers;

class UserController extends Controller
{
    use Helpers;
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return $this->response()->item($user, new UserTransformer());
    }
}
```
#### 响应模型集合
```php
$users = User::all();
return $this->response()->collection($users, new UserTransformer());
```
#### 响应模型分页
```php
$users = User::paginate();
return $this->response()->paginator($users, new UserTransformer());
```
#### 响应数组
```php
$out = [1, 2, 3, 4];
return $this->response()->array($out);
```

#### 设置 `Transformer` 的 `include`
不同于 `Dingo\Api` 只能通过 `QueryString` 的 `include` 参数来设置 `Transformer` 的 `include`，你可以手动制定需要 `include` 的内容。
```
$this->response()->include('user,log');
```
以上响应均通过 [Fractal](https://fractal.thephpleague.com/)  来实现，`include` 参数格式请参考 [Transformers](https://fractal.thephpleague.com/transformers/)
#### 其他响应类型 
```php
return $this->response()->success();
return $this->response()->accepted();
return $this->response()->created();
return $this->response()->noContent();
return $this->response()->error(403, '没有权限访问');

```
### 中间件 Middleware
#### UseGuard
`Laravel` 默认的 `Authenticate` 中间件仅在需要登录的接口里提供服务，提供 `Auth::user()`、设置 `guard` 等功能。    
使用 `Everalan\Api\Http\Middleware\UseGuard` 中间件可在你需要的任意页面实现设置 `guard`。    
```php
Route::group([
        'middleware' => ['guard:api'],
    ], function($api) {
    //不需要登录的接口
    $api->get('/login', 'UserController@login');

    $api->group([
        'middleware'    =>  ['auth.api'],
    ], function ($api) {
        //需要登录的接口
    });
});
```
## License

MIT
