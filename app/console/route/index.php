<?php
use think\facade\Route;

/** 无授权接口 */
Route::group(function () {
    Route::group('public', function () {
        Route::post('login', 'login');        // 登录接口
        Route::post('logout', 'logout');      // 登出接口
        Route::post('submit', 'submitForm');  // 表单留言
    })->prefix('publicController/');
})->option(['https' => true])->pattern(['id' => '\d+', 'name' => '\w+']);

/** 需授权接口 */
Route::group(function () {
    // Tags部分
    Route::group('tags', function () {
        Route::get('<id?>$', 'index');          // Tag内容
        Route::get('list', 'list');             // Tag列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除Tag
    })->prefix('tags.tagsController/');
    // 文章部分
    Route::group('article', function () {
        Route::get('<id?>$', 'index');          // 文章内容
        Route::get('list', 'list');             // 文章列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除文章
        Route::get('author', 'getAuthor');      // 文章作者
        Route::post('status', 'setStatus');     // 文章状态
        Route::get('channel', 'getChannel');    // 新闻栏目
    })->prefix('article.articleController/');
    // 商品部分
    Route::group('product', function () {
        Route::get('<id?>$', 'index');          // 商品内容
        Route::get('cate', 'getCate');          // 商品内容
        Route::get('list', 'list');             // 商品列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('status', 'setStatus');     // 商品状态
        Route::post('del', 'delete');           // 删除商品
    })->prefix('product.productController/');
    // 用户部分
    Route::group('user', function () {
        Route::get('<id?>$', 'index');          // 用户信息
        Route::get('list', 'list');             // 用户列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除用户
        Route::post('status', 'setStatus');     // 用户状态
    })->prefix('user.userController/');
    // 客户管理
    Route::group('client', function () {
        Route::get('list', 'list');             // 客户列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除客户
    })->prefix('user.clientController/');
    // 栏目部分
    Route::group('channel', function () {
        Route::get('<id?>$', 'index');          // 栏目信息
        Route::get('list', 'list');             // 栏目列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除栏目
        Route::post('status', 'setStatus');     // 栏目状态
    })->prefix('channel.channelController/');
    // 友链部分
    Route::group('link', function () {
        Route::get('<id?>$', 'index');          // 友链信息
        Route::get('list', 'list');             // 友链列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除友链
        Route::post('status', 'setStatus');     // 友链状态
    })->prefix('link.linkController/');
    // 用户权限菜单
    Route::group('auth', function () {
        Route::get('list', 'list');             // 菜单列表
        Route::post('del', 'delete');           // 菜单列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('status', 'setStatus');     // 菜单状态
    })->prefix('auth.authController/');
    // 用户组权限列表
    Route::group('group', function () {
        Route::get('list', 'list');             // 用户组列表
        Route::post('save', 'save');            // 编辑用户组
        Route::post('del', 'delete');           // 删除用户组
        Route::post('status', 'setStatus');     // 用户组状态
    })->prefix('auth.groupController/');
    // 公共接口
    Route::group('public', function () {
        Route::post('upload', 'upload');        // 文件上传接口
        Route::post('remove', 'removeFile');    // 文件删除接口
        Route::post('refresh_cache', 'refreshCache'); // 刷新缓存
    })->prefix('publicController/');
    // 行政区域
    Route::group('region', function () {
        Route::get('list', 'list');             // 行政区域列表
        Route::get('list', 'index');            // 懒加载行政区
        Route::post('del', 'delete');           // 删除行政区域
        Route::post('status', 'setStatus');     // 设置区域状态
        Route::post('save', 'save');            // 编辑/新增区域
    })->prefix('system.regionController/');
    // 个人中心
    Route::group('account', function () {
        Route::get('center', 'index');
        Route::get('fakelist', 'fakeList');
        Route::get('settings', 'settings');
        Route::get('province', 'province');
        Route::get('city<code?>$', 'city');
    })->prefix('user.accountController/');
    // 系统配置
    Route::group('system', function () {
        Route::get('list', 'list');
        Route::post('save', 'save');
    })->prefix('system.configController/');
    // 数据看板
    Route::group('dashboard', function () {
        Route::get('monitor', 'monitorController/index');
        Route::get('analysis', 'analysisController/index');
        Route::get('notice', 'workplaceController/notice');
        Route::get('workplace', 'workplaceController/index');
        Route::get('activities', 'workplaceController/activities');
    })->prefix('dashboard.');
})->option(['https' => true])->pattern(['id' => '\d+', 'name' => '\w+'])
    ->middleware(think\middleware\AllowCrossDomain::class)
    ->middleware(app\http\middleware\AuthTokenMiddleware::class);
