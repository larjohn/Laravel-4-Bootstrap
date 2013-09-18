@extends('layouts.default')

@section('left')
<!-- .user-media -->
<script src="http://d3js.org/d3.v3.js" charset="utf-8"></script>


<div class="media user-media hidden-phone">
    <a  class="user-link">
        <div id="donut"></div>
        <span id="total" class="label user-label">0</span>
    </a>

    <div class="media-body hidden-tablet">
        <a href="{{{URL::to('tests/item/'.$test)}}}"><h5 class="media-heading">{{$test}}</h5><a/>
        <ul class="unstyled user-info">
            <li><a href="">Full Test</a></li>
            <li>Execution Date: <br/>
                <small><i class="icon-calendar"></i> 16 Mar 16:32</small>
            </li>
        </ul>
    </div>
</div>
<!-- /.user-media -->

<!-- BEGIN MAIN NAVIGATION -->
<ul id="menu" class="unstyled accordion collapse in">

    <li class="accordion-group active">
        <a data-parent="#menu" >
            <i class="icon-bug icon-large"></i> Browse Errors
        </a>
        <ul class="collapse in" id="dashboard-nav">
            <li><a href="{{{URL::to('tests/item/'.$test.'/all')}}}"><i class="icon-angle-right"></i> all </a></li>
            <li><a href="{{{URL::to('tests/item/'.$test.'/type')}}}"><i class="icon-angle-right"></i> by type </a></li>
            <li><a href="{{{URL::to('tests/item/'.$test.'/category')}}}"><i class="icon-angle-right"></i> by category </a></li>
            <li><a href="{{{URL::to('tests/item/'.$test.'/source')}}}"><i class="icon-angle-right"></i> by source </a></li>
            <li><a href="{{{URL::to('tests/item/'.$test.'/query')}}}"><i class="icon-angle-right"></i> by query </a></li>

        </ul>
    </li>
</ul>
<!-- END MAIN NAVIGATION -->




<script>

    function beautifySciExp(numb, elements){
        s = numb.toExponential(elements).toString();
        s2 = s.split("e");
        return s2[0]+ " ✕ 10"+ "<sup>" + s2[1].substring(1) + "</sup>";

    }


             test_item = unprefix("{{$test}}");

            var width = 128,
                height = 128,
                radius = Math.min(width, height) / 2;

            var color = d3.scale.category10();


            var arc = d3.svg.arc()
                .outerRadius(radius - 30)
                .innerRadius(radius - 10);

            var pie = d3.layout.pie()
                .sort(null)
                .value(function(d) {
                    return d.count; });

            var donut_svg = d3.select("#donut").append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

            d3.json(appRoot+"api/tests/classification?test=" + test_item, function(error, data) {


                $("#total").html(beautifySciExp(parseInt(data.total)));

                var g = donut_svg.selectAll(".arc")
                    .data(pie(data.cats))
                    .enter().append("g")
                    .attr("class", "arc");

                g.append("path")
                    .attr("d", arc)
                    .style("fill", function(d) {

                        return color(d.data.class);

                    });

                g.append("text")
                    .attr("transform", function(d) {
                        return "translate(" + arc.centroid(d) + ")";
                    })
                    .attr("dy", ".15em")
                    .style("text-anchor", "middle")
                    .text(function(d) {
                        return d.data.count;
                    });

            });



</script>
@stop





