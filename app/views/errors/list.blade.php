@extends('layouts.test')
@section('content')




<script src="{{{ asset('assets/js/jqGrid/i18n/grid.locale-en.js') }}}" type="text/javascript"></script>


<script type="text/javascript" charset="utf-8">
    var mode = "{{$mode}}";
    error_filters = eval({{$filters}});
    facet_sizes = {};
    @if (isset($mode) && $mode == "item" )
    var test_item = "{{$test}}"
    @endif


</script>
<script type="text/javascript" src="{{{ asset('assets/js/application/errorTable.js') }}}"></script>
<link href="{{{ asset('assets/css/jstree/themes/default/style.css') }}}" media="screen" rel="stylesheet" type="text/css"/>
<link href="{{{ asset('assets/css/ui.jqgrid.css') }}}" media="screen" rel="stylesheet" type="text/css"/>

<script id="permalinkTmpl" type="text/x-jsrender">
    <span><i class="icon-link"></i>
    <a data-link="href%:permalink%">permalink</a>

</script>

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
    <div class="row-fluid">
        <div class="span4">
        <dl>
            <dd>Query:</dd>
            <dt>@%:query[0].id%@</dt>
        </dl>
        <dl>
            <dd>Resource:</dd>
            <dt>@%curie:violationRoot[0].id%@</dt>
        </dl>
        <dl>
            <dd>Property:</dd>
            <dt>@%curie:violationPath[0].id%@</dt>
        </dl>
        <dl class="alert alert-error">
            <dd>Value (@%curie:value.offender[0].path%@):</dd>
            <dt>
            <ul>
             @%for value.offender%@
                    <li>@%:value%@</li>
             @%/for%@
            </ul>

        </dl>
        </div>
         <div class="span4">

        @%if value.context %@
        <dl class="alert alert-warning">
            <dd>Context</dd>
            <dt>
                <ul>
                @%for value.context%@

                    <li>@%curie:path%@: @%curie:value%@</li>
                @%/for%@
                </ul>
        @%/if%@

        </dl>

         @%if violationRoot[0].category[0]% %@
        <dl>
            <dd>Categories:</dd>
            <dt>
                <ul>
                @%for violationRoot[0].category%@
                    <li>@%curie:id%@</li>
                @%/for%@
                </ul>

            </dt>
        </dl>
        @%/if%@

    </div>
    </div>


    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

    </div>

</script>


<div class="container-fluid">
    <div class="row-fluid">
        <h1 class="page-header">{{{$title}}}</h1>

        <div class="pull-right" id="permalink"></div>
    </div>
    <div class="row-fluid">

        <div class="span9" id="errors-span">

            <table id="list">

            </table>
            <div id="pager"></div>
            <!--Body content-->
        </div>
        <script id="facetTmpl" type="text/x-jsrender">

                @%for facets%@
                <div class="row-fluid facet @%:state%@" data-facet="@%:title%@">
                    <h4><a class="revert" data-facet="@%:title%@"><i class="icon-undo"></i></a> @%:title%@ </h4>

                    <div class="span12">
                        @%for elements%@
                        <div class="row-fluid facet-item @%:state%@">
                            <a data-facet="@%:#parent.parent.data.title%@" data-facet-value="@%:value%@">@%curie:label%@</a>
                            <span class="badge badge-success">@%:count%@</span>
                        </div>
                        @%/for%@
                        @%if total>requested%@
                        <div>
                            <a class="more-loader label label-info">more...</a>
                        </div>
                        @%/if%@
                    </div>
                </div>
                @%/for%@
            </script>
        <div class="span3 text-right" id="facets">


            <!--Sidebar content-->
        </div>
    </div>
</div>
@stop
