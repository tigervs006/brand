<?php
use think\facade\Route;

Route::group(function () {
    Route::rule('case/$', 'cases/index')->name('caseList');                     // 案例列表
    Route::rule('tags/:id', 'tags/index')->name('tagsDetail');                  // Tag列表
    Route::rule('case/:id', 'cases/detail')->name('caseDetail');                // 案例详情
    Route::rule('industry/$', 'industry/index')->name('industry');              // 文章列表
    Route::rule('industry/:id', 'industry/detail')->name('article');            // 文章内容
    Route::rule('product/:name', 'product/index')->name('productList');         // 商品列表
    Route::rule('product/:id', 'product/detail')->name('productDetail');        // 商品详情
    Route::rule('support/:name', 'support/:name')->name('supportDetail');       // 视频教程
})->option(['method' => 'get', 'https' => true])->pattern(['id' => '\d+', 'name' => '\w+']);