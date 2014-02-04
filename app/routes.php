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

    $imagesDir = 'images/themes/';

	$images = glob($imagesDir . '{fans,santorini,athens}.{jpg,jpeg}', GLOB_BRACE);

	$randomImage = $images[array_rand($images)]; // See comments

    return View::make('homepage')->with('devscount',$devs)->with('randomImage', $randomImage);
});

Route::get('/docs/'.'{chapter?}', 'DocumentationController@showDocs');
