@extends('layouts.test')
@section('content')

<div id="loading"><i class="icon-spinner icon-spin icon-large"></i> Loading content...</div>
<div class="row-fluid">
    <h1 class="page-header">{{{$title}}}</h1>
    <div class="span12" id="bubble"></div>


</div>

<script>

    var filterName = "{{$filterName}}";
    var apiPath = "{{$apiPath}}";

    var diameter = 960,
        format = d3.format(",d"),
        bubble_color = d3.scale.category20c();

    var bubble = d3.layout.pack()
        .sort(null)
        .size([diameter, diameter])
        .padding(1.5);

    var svg = d3.select("#bubble").append("svg")
        .attr("width", diameter)
        .attr("height", diameter)
        .attr("class", "bubble")
        .attr("preserveAspectRatio","xMinYMin meet")
        .attr("viewBox","0 0 "+diameter +" "+diameter );

    d3.json(appRoot + "api/tests/"+apiPath+"?test=" + test_item, function(error, data) {
        $("#loading").hide();
        var root = data[filterName];
        var node = svg.selectAll(".node")
            .data(bubble.nodes(classes(root))
                .filter(function(d) { return !d.children; }))
            .enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

        node.append("title")
            .text(function(d) { return d.className + ": " + format(d.value); });

        node.append("circle")
            .attr("r", function(d) { return d.r; })
            .style("fill", function(d) { return color(d.value); });

        node.append("a")
            .attr("xlink:href",function(d){
                var filters={};
                filters[filterName] = {name:filterName, operator:"=", value: d.className};
                return appRoot+"tests/item/"+prefix(test_item)+"/all?"+ $.param({filters:filters});
            })
            .append("text")
            .attr("dy", ".3em")
            .style("text-anchor", "middle")
            .text(function(d) { return prefix(d.className); });
    });

    // Returns a flattened hierarchy containing all leaf nodes under the root.
    function classes(root) {
        var classes = [];

        function recurse(name, node) {
            if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
            else classes.push({packageName: name, className: node.name, value: node.size});
        }

        recurse(null, root);
        return {children: classes};
    }

    d3.select(self.frameElement).style("height", diameter + "px");

</script>


@stop