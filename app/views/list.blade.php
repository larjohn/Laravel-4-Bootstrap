@extends('layouts.default')

@section('content')
<script type="text/javascript" src="{{{ asset('assets/js/jsviews/jsviews.js') }}}"></script>
<script>
    $.views.settings.delimiters("@%", "%@");
    var error_filters = {};
    function fillData() {
        $.getJSON('api/error', error_filters, function (data) {

            var errors = [];
            $.each(data, function () {
                errors.push(this);
            });
            var app = {
                errors: errors
            };
            var errorTmpl = $.templates("#errorTmpl");
            errorTmpl.link("#errorsList", app);

        });

    }

    $(document).ready(function () {
        fillData();
    });

</script>

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

            fillData();

        });


    });


</script>
<script src="assets/js/jstree/jquery.jstree.js" type="text/javascript"></script>
<link href="assets/css/jstree/themes/default/style.css" media="screen" rel="stylesheet" type="text/css"/>


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
        <h1 class="page-header">Validation Errors</h1>

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

        <div class="span10">
            <script id="errorTmpl" type="text/x-jsrender">

                @%for errors%@
                <tr>
                    <td><a href="http://validation.dbpedia.org/test-set/TS_20130815">TS_20130815</a></td>
                    <td><a href="http://dbpedia.org/resource/A_Hollywood_Star">@%:title%@</a></td>
                    <td><a href="http://validation.dbpedia.org/test/underageMarriage">Underage Marriage</a></td>
                    <td><a href="http://dbpedia.org/ontolohy/age">Age</a>&lt;15 and Marital Status = Married</td>
                    <td>
                        <a class="label label-info" href="http://validation.dbpedia.org/test-types/family">Family</a>
                        <a class="label label-info" href="http://validation.dbpedia.org/test-types/family">Age</a>
                    </td>

                    @%/for%@
                </tr>

            </script>

            <div>
                <table class="table table-bordered table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Test Set</th>
                        <th>Resource</th>
                        <th>Test</th>
                        <th>Erroneous Facts</th>
                        <th>Tags</th>
                    </tr>
                    </thead>


                    <tbody id="errorsList">
                    </tbody>
                </table>
            </div>
            <!--Body content-->
        </div>
        <div class="span2">
            <div id="significance-tree">
                <ul>
                    <li>
                        <a href="#">Significance</a>
                        <!-- UL node only needed for children - omit if there are no children -->
                        <ul>
                            <li id="important"><a href="important">Important (3)</a></li>
                            <li id="very_important"><a href="very_important">Very Important (11)</a></li>
                            <li id="urgent"><a href="urgent">Urgent (1)</a></li>
                            <!-- Children LI nodes here -->
                        </ul>
                    </li>
                </ul>
            </div>

            <!--Sidebar content-->
        </div>
    </div>

</div>
@stop
