<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

use \laravelgr\picturefill\PictureFill;

Route::get('/', function()
{
    $twitter = new \laravelgr\twitterfans\TwitterFans;
    $devs = $twitter->sumDevs();

    return View::make('homepage')->with('devscount',$devs);
});

Route::get('/docs/'.'{chapter?}', 'DocumentationController@showDocs');

Route::get('/test',function(){
    return "test!!";
});