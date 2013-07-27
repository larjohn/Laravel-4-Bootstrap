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


Route::controller('account','AccountController' );
Route::get('/', array("as"=>"home","uses"=> 'HomeController@showIndex'));




Route::group(array('prefix' => 'tests'), function()
{
    Route::get('/', array("as"=>"tests","uses"=>'ErrorController@showTestIndex'));
    Route::get('/latest', array("as"=>"latest", "uses"=>'ErrorController@showLatestTestIndex'));

    Route::group(array('prefix'=>'item/{test}'),function(){
        Route::get('/',array("as"=>"tests.item", "uses"=>'TestController@showTestOverview'));
        Route::get('/all',array("as"=>"tests.item.all", "uses"=>'ErrorController@showTestIndex'));
        Route::get('/type',array("as"=>"tests.item.type", "uses"=>'ErrorController@showTestIndex'));
        Route::get('/query',array("as"=>"tests.item.queries", "uses"=>'ErrorController@showQueryIndex'));

    });




});

Route::group(array('prefix' => 'api'), function()
{
    Route::controller('error', 'RDFErrorController');
    Route::controller('tests', 'RDFErrorController');
   /* Route::group(array('prefix' => 'tests'), function(){
        Route::controller('latest', 'RDFErrorController');


    });
*/
});