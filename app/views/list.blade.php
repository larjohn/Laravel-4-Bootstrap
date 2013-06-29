@extends('layouts.default')

@section('content')
<script type="text/javascript" src="{{{ asset('assets/js/jsviews/jsviews.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/application/errorTable.js') }}}"></script>
<script src="{{{ asset('assets/js/jqGrid/jquery.jqGrid.src.js') }}}" type="text/javascript"></script>
<script src="{{{ asset('assets/js/jqGrid/i18n/grid.locale-en.js') }}}" type="text/javascript"></script>


<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {

        // TO CREATE AN INSTANCE
        // select the tree container using jQuery
        $("#significance-tree")
            // call `.jstree` with the options object
            .jstree({
                // the `plugins` array allows you to configure the active plugins on this instance
                "plugins": ["themes", "html_data", "ui", 'checkbox', "crrm"]

                // it makes sense to configure a plugin only if overriding the defaults
            })
            // EVENTS
            // each instance triggers its own events - to process those listen on the container
            // all events are in the `.jstree` namespace
            // so listen for `function_name`.`jstree` - you can function names from the docs
            .bind("loaded.jstree", function (event, data) {
                // you get two params - event & data - check the core docs for a detailed description
            });

        $("#significance-tree").bind("change_state.jstree", function (event, data) {

            var tree = jQuery.jstree._reference ($("#significance-tree"));
            var checked  = tree.get_checked(undefined,true);
            error_filters.significance = [];
            for(var i=0; i<checked.length; i++){

                error_filters.significance.push($(checked[i]).attr("id"));
            }



        });


    });


</script>
<script src="assets/js/jstree/jquery.jstree.js" type="text/javascript"></script>
<link href="assets/css/jstree/themes/default/style.css" media="screen" rel="stylesheet" type="text/css"/>
<link href="assets/css/ui.jqgrid.css" media="screen" rel="stylesheet" type="text/css"/>
<link href="assets/css/jquery-ui.css" media="screen" rel="stylesheet" type="text/css"/>


<script type="text/javascript" src="{{{ asset('assets/js/underscore-1.4.3.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/backbone-0.9.10.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.core.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.widget.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.autocomplete.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.position.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.menu.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/backbone_extensions.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/visualsearch/utils/jquery_extensions.js') }}}"></script>



<script src="assets/js/visualsearch/visualsearch.js" type="text/javascript"></script>

<!--[if (!IE)|(gte IE 8)]><!-->
<link href="assets/css/visualsearch/visualsearch-datauri.css" media="screen" rel="stylesheet" type="text/css"/>

<!--<![endif]-->
<!--[if lte IE 7]><!-->
<link href="assets/css/visualsearch/visualsearch.css" media="screen" rel="stylesheet" type="text/css"/>
<!--<![endif]-->


<div class="container-fluid">
    <div class="row-fluid">
        <h1 class="page-header">DBpedia Validation Errors</h1>

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
    <div class="row-fluid">

        <div class="span9">


            <table id="list"><tr><td></td></tr></table>
            <div id="pager"></div>
            <!--Body content-->
        </div>
        <div class="span3 text-right" id="facets">

            <script id="facetTmpl" type="text/x-jsrender">

                @%for facets%@
                <div class="row-fluid">
                    <h4>@%:title%@</h4>
                    <div class="span12">
                        @%for elements%@
                        <div class="row-fluid">
                        <a class="facet-item" href="#" data-facet="@%:#parent.parent.data.title%@" data-facet-value="@%:value%@">@%:label%@</a> <span class="badge badge-info">@%:count%@</span>
                            </div>
                        @%/for%@
                    </div>
                </div>
                @%/for%@


            </script>
            <!--Sidebar content-->
        </div>
    </div>

</div>
@stop
