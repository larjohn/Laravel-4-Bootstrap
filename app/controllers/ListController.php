<?php
/**
 * User: larjohns
 * Date: 14/6/2013
 * Time: 11:24 μμ
 * To change this template use File | Settings | File Templates.
 */

class ListController extends BaseController {



    public function showIndex()
    {


        // var_dump($error->label);

        return View::make('list');
    }


}