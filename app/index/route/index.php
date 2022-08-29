<?php
use think\facade\Route;

Route::group(function () {
    Route::rule('/', 'index');
    Route::rule('info', 'index/info');
    Route::rule('area/$', 'area/index');
    Route::rule('region', 'index/region');
    Route::rule('about/$', 'about/index');
    Route::rule('support/$', 'support/index');
    Route::rule('support/video/:id', 'support/detail');
    Route::rule('case/$', 'cases/index')->name('caseList');                     // 案例列表
    Route::rule('tags/:id', 'tags/index')->name('tagsDetail');                  // 标签列表
    Route::rule('case/:id', 'cases/detail')->name('caseDetail');                // 案例详情
    Route::rule('area/<name?>', 'area/:name')->name('areaDeatil');              // 行业应用
    Route::rule('industry/:id', 'industry/detail')->name('article');            // 文章内容
    Route::rule('about/<name?>', 'about/:name')->name('aboutDeatil');           // 关于我们
    Route::rule('industry/<name?>', 'industry/index')->name('industry');        // 文章列表
    Route::rule('product/:id', 'product/detail')->name('productDetail');        // 商品详情
    Route::rule('product/<name?>', 'product/index')->name('productList');       // 商品列表
    Route::rule('support/<name?>', 'support/:name')->name('supportDetail');     // 视频教程
    /* 标签模型伪静态 */
//    Route::group('tags', function () {
//        Route::rule('$', 'tags/index');
//        Route::rule('<tid>$', 'tags/list')->option(['ext' => 'html']);
//    });
    /* 单页模型伪静态 */
//    Route::rule('single/<tid>$', 'single/index')->option(['ext' => 'html']);
    /* 视频模型伪静态 */
//    Route::group('video', function () {
//        Route::rule('<tid>$', 'video/list');
//        Route::rule('<dirname>/<aid>$', 'video/index')->option(['ext' => 'html']);
//    });
    /* 文档模型伪静态 */
//    Route::group('article', function () {
//        Route::rule('<tid>$', 'article/list');
//        Route::rule('<dirname>/<aid>$', 'article/index')->option(['ext' => 'html']);
//    });
    /* 商品模型伪静态 */
//    Route::group('product', function () {
//        Route::rule('<tid>$', 'product/list');
//        Route::rule('<dirname>/<aid>$', 'product/index')->option(['ext' => 'html']);
//    });
    /* 图集模型伪静态 */
//    Route::group('images', function () {
//        Route::rule('<tid>$', 'images/list');
//        Route::rule('<dirname>/<aid>$', 'images/index')->option(['ext' => 'html']);
//    });
    /* 下载模型伪静态 */
//    Route::group('download', function () {
//        Route::rule('<tid>$', 'download/list');
//        Route::rule('<dirname>/<aid>$', 'download/index')->option(['ext' => 'html']);
//    });
    /* 留言模型伪静态 */
//    Route::group('guestbook', function () {
//        Route::rule('<tid>$', 'guestBook/list');
//        Route::rule('<dirname>/<aid>$', 'guestBook/index')->option(['ext' => 'html']);
//    });
    /* miss路由 */
    Route::miss(function() { return '404 Not Found!'; });
})->option(['method' => 'get', 'https' => true])->pattern(['id' => '\d+', 'tid' => '\w+', 'aid' => '\d+', 'dirname' => '\w+']);
