<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showIndex()
	{
        $error =  RDFError::find("ArchitecturalStructure");

       // var_dump($error->label);
        //var_dump($error->listing("domain"));
        $error->testQ();
		return View::make('home');
	}

}
