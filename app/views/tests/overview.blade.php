@extends('layouts.test')
@section('content')
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/backbone_extensions.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/jquery_extensions.js') }}}"></script>


<script src="{{{ asset('assets/js/visualsearch/visualsearch.js') }}}" type="text/javascript"></script>

<!--[if (!IE)|(gte IE 8)]><!-->
<link href="{{{ asset('assets/css/visualsearch/visualsearch-datauri.css') }}}" media="screen" rel="stylesheet"
      type="text/css"/>

<!--<![endif]-->
<!--[if lte IE 7]><!-->
<link href="{{{ asset('assets/css/visualsearch/visualsearch.css') }}}" media="screen" rel="stylesheet" type="text/css"/>
<!--<![endif]-->
<div class="container-fluid">
    <div class="row-fluid">
        <h1 class="page-header">{{{$title}}}</h1>

        <h3 id="test_name"></h3>
    </div>
    <div class="row-fluid">
        <h2>Details</h2>
        <div class="row">
            Date
        </div>
        <div class="row">
            Errors
        </div>
    </div>

    <div class="row-fluid">
        <h2>Search Errors</h2>
        <div id="search_box_container"></div>
        <div id="search_query">&nbsp;</div>
    </div>


    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {

            jQuery.getJSON("ajax/test.json", function( data ) {
                    var items = [];
                    $.each( data, function( key, val ) {
                        items.push( "<li id='" + key + "'>" + val + "</li>" );
                    });

                    $( "<ul/>", {
                        "class": "my-new-list",
                        html: items.join( "" )
                    }).appendTo( "body" );
                }

            );


            window.visualSearch = VS.init({
                container: $('#search_box_container'),
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

                        
                        jQuery.getJSON(appRoot+"api/tests/errors", {test:test_item, resource:searchCollection.Resource,type:searchCollection.Type,class:searchCollection.Classification,source:searchCollection.Source,query:searchCollection.Query}, function( data ) {
                                callback(data);
                            }

                        );


                    },
                    valueMatches: function (category, searchTerm, callback) {
                        switch (category) {
                            case 'Resource':

                                jQuery.getJSON(appRoot+"api/tests/resources", {test:test_item, search:searchTerm}, function( data ) {
                                        callback(data);
                                    }

                                );


                                break;
                            case 'Classification':
                                jQuery.getJSON(appRoot+"api/tests/classifications", {test:test_item, search:searchTerm}, function( data ) {
                                        callback(data);
                                    }

                                );
                                break;
                            case 'Source':
                                jQuery.getJSON(appRoot+"api/tests/sources", {test:test_item, search:searchTerm}, function( data ) {
                                        callback(data);
                                    }

                                );
                                break;
                            case 'Type':
                                jQuery.getJSON(appRoot+"api/tests/types", {test:test_item, search:searchTerm}, function( data ) {
                                        callback(data);
                                    }

                                );
                                break;
                            case 'Query':
                                jQuery.getJSON(appRoot+"api/tests/queries", {test:test_item, search:searchTerm}, function( data ) {
                                        callback(data);
                                    }

                                );
                                break;

                        }
                    },
                    facetMatches: function (callback) {
                        callback([


                            { label: 'Resource', category: 'resource' },
                            { label: 'Query', category: 'Error' },
                            { label: 'Classification', category: 'Error' },
                            { label: 'Source', category: 'Error' },
                            { label: 'Type', category: 'Error' }
                        ]);
                    }
                }
            });
        });
    </script>


</div>
@stop