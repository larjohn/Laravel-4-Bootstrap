@extends('layouts.test')
@section('content')
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/backbone_extensions.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/jquery_extensions.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/jsviews/jsviews.js') }}}"></script>


<script src="{{{ asset('assets/js/jqGrid/jquery.jqGrid.src.js') }}}" type="text/javascript"></script>

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
    <div id="details" class="row-fluid">

    </div>

    <div class="row-fluid">
        <h2>Search Errors</h2>
        <div id="search_box_container"></div>
        <div id="search_query">&nbsp;</div>
        <div id="results"></div>
    </div>



    <script id="itemTmpl" type="text/x-jsrender">


                <div class="row-fluid result">
                    <h4>Test identifier: @%curie:id%@ </h4>
                    <div>Execution Start Date: @%:start%@ </div>
                    <div>Execution End Date: @%:end%@ </div>
                    <div>Errors found: @%:errorsCount%@ </div>

                </div>

                </script>


    <script id="resultsTmpl" type="text/x-jsrender">

                @%for results%@
                <div class="row-fluid result">
                    <h4> @%:resource%@ </h4>
                    <div> @%:query%@ </div>
                    <div> @%:type%@ </div>
                    <div> @%:classification%@ </div>
                    <div> @%:source%@ </div>

                </div>
                @%/for%@
            </script>
    <script type="text/javascript" charset="utf-8">
        $.views.settings.delimiters("@%", "%@");
        function setSearchResults(data){
            var results = [];
            $.each(data, function () {
                results.push(this);

            });
            var app = {
                results: results
            };
            var resultsTemplate = $.templates("#resultsTmpl");

            resultsTemplate.link("#results", app);


        }


        function setDetails(data){


            var itemTemplate = $.templates("#itemTmpl");

            itemTemplate.link("#details", data);


        }

        $(document).ready(function () {


            $(document).ready(function (){
                jQuery.getJSON( appRoot+ "api/tests/item?test=" +test_item, function( data ) {
                    setDetails(data);
                });
            });

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

                        var search = {};
                        var ft = searchCollection.facets();
                        ft.forEach(function(element){
                            search = jQuery.extend(search, element);
                        });

                        jQuery.getJSON(appRoot+"api/tests/errors", {test:test_item, resource:search.Resource,type:search.Type,class:search.Classification,source:search.Source,query:search.Query}, function( data ) {

                                setSearchResults(data);
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