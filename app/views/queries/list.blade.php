@extends('layouts.default')
@section('content')

<div class="container-fluid">
    <div class="row-fluid">
        <h1 class="page-header">{{{$title}}}</h1>
        <div id="list"></div>
    </div>

</div>




<script id="resultsTmpl" type="text/x-jsrender">

                @%for results%@
                <div class="row-fluid result">
                    <h4> @%:id%@ </h4>
                    <div> @%:sparql%@ </div>

                </div>
                @%/for%@
                </script>


<script type="text/javascript" charset="utf-8">
    $.views.settings.delimiters("@%", "%@");
    function setResults(data){
        var results = [];
        $.each(data, function () {
            results.push(this);

        });
        var app = {
            results: results
        };
        var resultsTemplate = $.templates("#resultsTmpl");

        resultsTemplate.link("#list", app);


    }

    $(document).ready(function (){
        jQuery.getJSON( appRoot+ "api/queries/list", function( data ) {
          setResults(data.children);
        });
    });


    </script>
@stop