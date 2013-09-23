<?php
//Breadcrumbs::setView('_partials/breadcrumbs');

Breadcrumbs::register('latest', function($breadcrumbs) {
    $breadcrumbs->push('Latest', URL::route('latest'));
});

Breadcrumbs::register('tests', function($breadcrumbs) {
    $breadcrumbs->push('Tests', URL::route('tests'));
});

Breadcrumbs::register('queries', function($breadcrumbs) {
    $breadcrumbs->push('Queries', URL::route('queries'));
});

Breadcrumbs::register('tests.item', function($breadcrumbs, $params) {
    $breadcrumbs->parent('tests'); //var_dump($params);die;
    $breadcrumbs->push($params["test"], URL::route('tests.item',$params["label"]));
});

Breadcrumbs::register('tests.item.categories', function($breadcrumbs, $params) {

    $breadcrumbs->parent('tests.item',$params);
    $breadcrumbs->push($params["label"], URL::route('tests.item.categories',$params["test"]));
});

Breadcrumbs::register('tests.item.types', function($breadcrumbs, $params) {

    $breadcrumbs->parent('tests.item',$params);
    $breadcrumbs->push($params["label"], URL::route('tests.item.types',$params["test"]));
});

Breadcrumbs::register('tests.item.queries', function($breadcrumbs, $params) {

    $breadcrumbs->parent('tests.item',$params);
    $breadcrumbs->push($params["label"], URL::route('tests.item.queries',$params["test"]));
});

Breadcrumbs::register('tests.item.sources', function($breadcrumbs, $params) {

    $breadcrumbs->parent('tests.item',$params);
    $breadcrumbs->push($params["label"], URL::route('tests.item.sources',$params["test"]));
});

Breadcrumbs::register('tests.item.all', function($breadcrumbs, $params) {


    $breadcrumbs->parent('tests.item',$params);
    $breadcrumbs->push($params["label"], URL::route('tests.item.all',$params["test"]));
});
Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', URL::route('home'));
});

Breadcrumbs::register('blog', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});

Breadcrumbs::register('category', function($breadcrumbs, $category) {
    $breadcrumbs->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $breadcrumbs->push($ancestor->title, route('category', $ancestor->id));
    }

    $breadcrumbs->push($category->title, route('category', $category->id));
});

Breadcrumbs::register('page', function($breadcrumbs, $page) {
    $breadcrumbs->parent('category', $page->category);
    $breadcrumbs->push($page->title, route('page', $page->id));
});