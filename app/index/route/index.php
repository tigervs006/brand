<?php
use think\facade\Route;

Route::group(function () {
    Route::rule('case/$', 'cases/index');
    Route::rule('tags/:id', 'tags/index');
    Route::rule('case/:id', 'cases/detail');
    Route::rule('product/:id', 'product/detail');
    Route::rule('industry/:id', 'industry/detail');
    Route::rule('services/video/:id', 'services/detail');
})->option(['method' => 'get', 'https' => true])->pattern(['id' => '\d+', 'name' => '\w+']);