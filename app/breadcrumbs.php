<?php
//Breadcrumbs::setView('_partials/breadcrumbs');

Breadcrumbs::register('latest', function($breadcrumbs) {
    $breadcrumbs->push('Latest', URL::route('latest'));
});

Breadcrumbs::register('tests', function($breadcrumbs) {
    $breadcrumbs->push('Tests', URL::route('tests'));
});

Breadcrumbs::register('tests.item', function($breadcrumbs, $label) {
    $breadcrumbs->parent('tests');
    $breadcrumbs->push($label, URL::route('tests.item','{test}'));
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