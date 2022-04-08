<?php
use think\facade\Route;

// 未授权接口
Route::group(function () {
    // 登录接口
    Route::post('login', 'login/index');
})->option(['ext' => 'html', 'https' => true]);

// 需授权接口
Route::group(function () {
    //文章类
    Route::group('article', function () {
        Route::get(':id$', 'index');    // 文章内容
        Route::get('list', 'lists');    // 文章列表
        Route::post('save', 'save');    // 新增编辑
        Route::delete('del', 'delete'); // 删除文章
    })->prefix('article/');
})->option(['https' => true])->pattern(['id' => '\d+', 'name' => '\w+'])->middleware(think\middleware\AllowCrossDomain::class);