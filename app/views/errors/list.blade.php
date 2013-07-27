@extends('layouts.default')
@section('content')

<script type="text/javascript" src="{{{ asset('assets/js/jsviews/jsviews.js') }}}"></script>
<script src="{{{ asset('assets/js/VIE/vie-2.1.0.debug.js') }}}" type="text/javascript"></script>
<script type="text/javascript" src="{{{ asset('assets/js/application/rdf.js') }}}"></script>
<script type="text/javascript" src="{{{ asset('assets/js/application/errorTable.js') }}}"></script>
<script src="{{{ asset('assets/js/jqGrid/jquery.jqGrid.src.js') }}}" type="text/javascript"></script>
<script src="{{{ asset('assets/js/jqGrid/i18n/grid.locale-en.js') }}}" type="text/javascript"></script>


<script type="text/javascript" charset="utf-8">
    var mode = "{{$mode}}";
    error_filters = {};
    @if (isset($mode) && $mode == "item" )
    var test_item = "{{$test}}"
    @endif

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

            var tree = jQuery.jstree._reference($("#significance-tree"));
            var checked = tree.get_checked(undefined, true);
            error_filters.significance = [];
            for (var i = 0; i < checked.length; i++) {

                error_filters.significance.push($(checked[i]).attr("id"));
            }
        });
    });


</script>
<script src="{{{ asset('assets/js/jstree/jquery.jstree.js') }}}" type="text/javascript"></script>
<link href="{{{ asset('assets/css/jstree/themes/default/style.css') }}}" media="screen" rel="stylesheet" type="text/css"/>
<link href="{{{ asset('assets/css/ui.jqgrid.css') }}}" media="screen" rel="stylesheet" type="text/css"/>



<!-- Modal -->
<div id="errorModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <div id="item-loader">
        <i class="icon-spinner icon-spin icon-large"></i> Loading...

    </div>
    <div id="item-modal-content">

    </div>
</div>

<script id="itemTmpl" type="text/x-jsrender">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Error Details</h3>
    </div>
    <div class="modal-body">
        <dl>
            <dd>Resource:</dd>
            <dt>@%curie:violationRoot[0].id%@</dt>
        </dl>
        <dl>
            <dd>Property:</dd>
            <dt>@%curie:inaccurateProperty[0].id%@</dt>
        </dl>
        <dl class="alert alert-error">
            <dd>Value:</dd>
            <dt>@%:value%@</dt>
        </dl>
        <dl>
            <dd>Test:</dd>
            <dt>@%curie:test[0].id%@</dt>
        </dl>
        <dl>
            <dd>Query:</dd>
            <dt>@%:query[0]%@</dt>
        </dl>
        <dl>
            <dd>Tags:</dd>
            <dt>
                @%for subject%@
                @%curie:id%@
                @%/for%@
            </dt>
        </dl>
<div class="form-actions">
    <button class="btn btn-success">Add subject</button>
</div>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
    </div>

</script>


<div class="container-fluid">
    <div class="row-fluid">
        <h1 class="page-header">{{{$title}}}</h1>
        <h3 id="test_name"></h3>
    </div>
    <div class="row-fluid">

        <div class="span9" id="errors-span">

            <table id="list">

            </table>
            <div id="pager"></div>
            <!--Body content-->
        </div>
        <div class="span3 text-right" id="facets">

            <script id="facetTmpl" type="text/x-jsrender">

                @%for facets%@
                <div class="row-fluid facet">
                    <h4><a class="revert" data-facet="@%:title%@"><i class="icon-undo"></i></a> @%:title%@ </h4>

                    <div class="span12">
                        @%for elements%@
                        <div class="row-fluid facet-item">
                            <a data-facet="@%:#parent.parent.data.title%@" data-facet-value="@%:value%@">@%:label%@</a>
                            <span class="badge badge-info">@%:count%@</span>
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
