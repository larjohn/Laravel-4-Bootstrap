@extends('layouts.default')
@section('content')
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/backbone_extensions.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/jquery_extensions.js') }}}"></script>


<script src="{{{ asset('assets/js/visualsearch/visualsearch.js') }}}" type="text/javascript"></script>

<!--[if (!IE)|(gte IE 8)]><!-->
<link href="{{{ asset('assets/css/visualsearch/visualsearch-datauri.css') }}}" media="screen" rel="stylesheet" type="text/css"/>

<!--<![endif]-->
<!--[if lte IE 7]><!-->
<link href="{{{ asset('assets/css/visualsearch/visualsearch.css') }}}" media="screen" rel="stylesheet" type="text/css"/>
<!--<![endif]-->
<div class="container-fluid">
    <div id="search_box_container"></div>
    <div id="search_query">&nbsp;</div>

    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {


            window.visualSearch = VS.init({
                container: $('#search_box_container'),
                query: 'severity: "error"',
                showFacets: true,
                unquotable: [
                    'title'

                ],
                callbacks: {
                    search: function (query, searchCollection) {
                        var $query = $('#search_query');
                        $query.stop().animate({opacity: 1}, {duration: 300, queue: false});
                        $query.html('<span class="raquo">&raquo;</span> You searched for: <b>' + searchCollection.serialize() + '</b>');
                        clearTimeout(window.queryHideDelay);
                        window.queryHideDelay = setTimeout(function () {
                            $query.animate({
                                opacity: 0
                            }, {
                                duration: 1000,
                                queue: false
                            });
                        }, 2000);
                    },
                    valueMatches: function (category, searchTerm, callback) {
                        switch (category) {
                            case 'severity':
                                callback([
                                    { value: 'warning', label: 'warning' },
                                    { value: 'error', label: 'error' }
                                ]);
                                break;
                            case 'significance':
                                callback(['important', 'very important', 'urgent']);
                                break;
                            case 'history':
                                callback(['fresh', 'reoccuring', 'unknown']);
                                break;
                            case 'title':
                                callback([
                                    'Pentagon Papers',
                                    'CoffeeScript Manual',
                                    'Laboratory for Object Oriented Thinking',
                                    'A Repository Grows in Brooklyn'
                                ]);
                                break;
                            case 'test-set':
                                callback([
                                    '20130531',
                                    '20130607',
                                    '20130601'

                                ]);
                                break;
                            case 'category':
                                callback([
                                    "people", "places", "history", "science", "business"

                                ]);
                                break;
                            case 'test':
                                callback([
                                    "Underage marriage", "Birth date after death", "Two names", "Very tall"

                                ], {
                                    preserveOrder: true // Otherwise the selected value is brought to the top
                                });
                                break;
                        }
                    },
                    facetMatches: function (callback) {
                        callback([


                            { label: 'Title', category: 'resource' },
                            { label: 'test', category: 'test' },
                            { label: 'test-set', category: 'test' },
                            { label: 'severity', category: 'tags' },
                            { label: 'significance', category: 'tags' },
                            { label: 'history', category: 'tags' },
                            { label: 'category', category: 'tags' }
                        ]);
                    }
                }
            });
        });
    </script>


</div>
@stop