<?php
use think\facade\Route;

// 未授权接口
Route::group(function () {
    // 登录接口
    Route::post('login', 'login/index');        // 登录接口
    Route::post('logout', 'login/logout');      // 登出接口
})->option(['ext' => 'html', 'https' => true]);

// 需授权接口
Route::group(function () {
    // Tags部分
    Route::group('tags', function () {
        Route::get(':id', 'index');             // Tag内容
        Route::get('list', 'lists');            // Tag列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除Tag
    })->prefix('tags.tagsController/');
    // 文章部分
    Route::group('article', function () {
        Route::get(':id', 'index');             // 文章内容
        Route::get('list', 'lists');            // 文章列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除文章
        Route::get('author', 'getAuthor');      // 文章作者
        Route::post('status', 'setStatus');     // 文章状态
        Route::get('channel', 'getChannel');    // 新闻栏目
    })->prefix('article.articleController/');
    // 商品部分
    Route::group('product', function () {
        Route::get(':id', 'index');             // 商品内容
        Route::get('list', 'lists');            // 商品列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除商品
    })->prefix('product.productController/');
    // 用户部分
    Route::group('user', function () {
        Route::get(':id', 'index');             // 用户信息
        Route::get('list', 'lists');            // 用户列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除用户
    })->prefix('user.userController/');
    // 栏目部分
    Route::group('channel', function () {
        Route::get(':id', 'index');             // 栏目信息
        Route::get('list', 'lists');            // 栏目列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除栏目
    })->prefix('channel.channelController/');
    // 友链部分
    Route::group('links', function () {
        Route::get(':id', 'index');             // 友链信息
        Route::get('list', 'lists');            // 友链列表
        Route::post('save', 'save');            // 新增编辑
        Route::post('del', 'delete');           // 删除友链
    })->prefix('links.linksController/');
    // 用户权限
    Route::group('group', function () {
        Route::get(':id', 'index');             // 用户组信息
        Route::get('list', 'lists');            // 用户组列表
        Route::post('save', 'save');            // 编辑用户组
        Route::post('del', 'delete');           // 删除用户组
    })->prefix('group.groupController/');
    // 公共接口
    Route::group('public', function () {
        Route::post('upload', 'upload');        // 文件上传接口
    })->prefix('publicController/');
})->option(['https' => true])->pattern(['id' => '\d+', 'name' => '\w+'])->middleware(think\middleware\AllowCrossDomain::class);
