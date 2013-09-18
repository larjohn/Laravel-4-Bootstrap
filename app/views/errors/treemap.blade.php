@extends('layouts.test')
@section('content')
<div id="loading"><i class="icon-spinner icon-spin icon-large"></i> Loading content...</div>
<style type="text/css">






    #chart {
        width: 960px;
        height: 500px;
        background: #bbb;
    }

    text {
        pointer-events: none;
    }

    .grandparent text {
        font-weight: bold;
    }

    rect {
        fill: none;
        stroke: #fff;
    }

    rect.parent,
    .grandparent rect {
        stroke-width: 2px;
    }

    .grandparent rect {
        fill: orange;
    }

    .grandparent:hover rect {
        fill: #ee9700;
    }

    .children rect.parent,
    .grandparent rect {
        cursor: pointer;
    }

    rect.parent {
        pointer-events: all;
    }

    .children:hover rect.child {
        fill: #aaa;
    }


</style>


<div class="row-fluid">
    <div class="span6" id="treemap"></div>
    <div class="span6">

        <table class="table table-bordered table-striped">
            <thead>
                <th>Query</th>
                <th>Errors</th>
            </thead>
            <tbody>
                <tr>
                    <td>Wrong Height</td>
                    <td>43</td>
                </tr>
            </tbody>
        </table>

    </div>

</div>




<script type="text/javascript">
    var margin = {top: 20, right: 0, bottom: 0, left: 0},
        width = 800,
        height = 500 - margin.top - margin.bottom,
        formatNumber = d3.format(",d"),
        transitioning;

    var x = d3.scale.linear()
        .domain([0, width])
        .range([0, width]);

    var y = d3.scale.linear()
        .domain([0, height])
        .range([0, height]);


    var color = d3.scale.category10();


    var treemap = d3.layout.treemap()
        .children(function (d, depth) {
            return depth ? null : d.children;
        })
        .sort(function (a, b) {
            return a.value - b.value;
        })
        .ratio(height / width * 0.5 * (1 + Math.sqrt(5)))
        .sticky(false)
        .round(false);

    var svg = d3.select("#treemap").append("svg")
        .attr("width", "100%")
        .attr("height", height + margin.bottom + margin.top)
        .style("margin-left", -margin.left + "px")
        .style("margin.right", -margin.right + "px")
        .attr("preserveAspectRatio","xMinYMin meet")
        .attr("viewBox","0 0 "+width +" "+height )
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
        .style("shape-rendering", "crispEdges");

    var grandparent = svg.append("g")
        .attr("class", "grandparent");

    grandparent.append("rect")
        .attr("y", -margin.top)
        .attr("width", width)
        .attr("height", margin.top);

    grandparent.append("text")
        .attr("x", 6)
        .attr("y", 6 - margin.top)
        .attr("dy", ".75em");


    loadTreemap(appRoot + "api/tests/categories?test=" + test_item);

    function loadTreemap(url) {


        d3.json(url, function(root) {

            initialize(root);
            accumulate(root);
            layout(root);
            display(root);
            $("#loading").hide();
            function initialize(root) {
                root.x = root.y = 0;
                root.dx = width;
                root.dy = height;
                root.depth = 0;
            }

            // Aggregate the values for internal nodes. This is normally done by the
            // treemap layout, but not here because of our custom implementation.
            function accumulate(d) {
                return d.children
                    ? d.value = d.children.reduce(function(p, v) { return p + accumulate(v); }, 0)
                    : d.value;
            }

            // Compute the treemap layout recursively such that each group of siblings
            // uses the same size (1×1) rather than the dimensions of the parent cell.
            // This optimizes the layout for the current zoom state. Note that a wrapper
            // object is created for the parent node for each group of siblings so that
            // the parent’s dimensions are not discarded as we recurse. Since each group
            // of sibling was laid out in 1×1, we must rescale to fit using absolute
            // coordinates. This lets us use a viewport to zoom.
            function layout(d) {
                if (d.children) {
                    treemap.nodes({children: d.children});
                    d.children.forEach(function(c) {
                        c.x = d.x + c.x * d.dx;
                        c.y = d.y + c.y * d.dy;
                        c.dx *= d.dx;
                        c.dy *= d.dy;
                        c.parent = d;
                        layout(c);
                    });
                }
            }

            function display(d) {
                grandparent
                    .datum(d.parent)
                    .on("click", transition)
                    .select("text")
                    .text(name(d));

                var g1 = svg.insert("g", ".grandparent")
                    .datum(d)
                    .attr("class", "depth");

                var g = g1.selectAll("g")
                    .data(d.children)
                    .enter().append("g");

                g.filter(function(d) { return d.children; })
                    .classed("children", true)
                    .on("click", transition);

                g.selectAll(".child")
                    .data(function(d) { return d.children || [d]; })
                    .enter().append("rect")
                    .attr("class", "child")
                    .classed("background", true)
                    .style("fill", function (d) {
                        return color(d.parent.name);
                    })
                    .call(rect);

                g.append("rect")
                    .attr("class", "parent")
                    .call(rect)
                    .append("title")
                    .text(function(d) { return formatNumber(d.value); })
                    .style("fill", function (d) {
                        return idealTextColor(color(d.parent.name));
                    });

                g.append("text")
                    .attr("dy", ".75em")
                    .text(function(d) { return d.name; })
                    .call(text);

                function transition(d2) {
                    if (transitioning || !d2) return;
                    $("#loading").show();
                    var uri ="";
                    if(d2.id=="0"){

                         uri = appRoot + "api/tests/categories?test=" + test_item;
                    }
                    else{
                        uri = appRoot + "api/tests/categories?test="+test_item+"&category="+d2.id;//  d.id.split("~")[1];
                    }
                    d3.json(uri, function(error, d)
                    {
                        initialize(d);
                        accumulate(d);
                        layout(d);
                        $("#loading").hide();
                        transitioning = true;
                        d.parent= d2.parent;
                        var g2 = display(d),
                            t1 = g1.transition().duration(0),
                           t2 = g2.transition().duration(0);

                        // Update the domain only after entering new elements.
                        x.domain([d.x, d.x + d.dx]);
                        y.domain([d.y, d.y + d.dy]);

                        // Enable anti-aliasing during the transition.
                        svg.style("shape-rendering", null);

                        // Draw child nodes on top of parent nodes.
                        svg.selectAll(".depth").sort(function(a, b) { return a.depth - b.depth; });

                        // Fade-in entering text.
                        g2.selectAll("text").style("fill-opacity", 0);

                        // Transition to the new view.
                        t1.selectAll("text").call(text).style("fill-opacity", 0);
                        t2.selectAll("text").call(text).style("fill-opacity", 1);
                        t1.selectAll("rect").call(rect);
                        t2.selectAll("rect").call(rect);

                        // Remove the old node when the transition is finished.
                        t1.remove().each("end", function() {
                            svg.style("shape-rendering", "crispEdges");
                            transitioning = false;
                        });
                    });


                }

                return g;
            }

            function text(text) {
                text.attr("x", function(d) { return x(d.x) + 6; })
                    .attr("y", function(d) { return y(d.y) + 6; });
            }

            function rect(rect) {
                rect.attr("x", function(d) { return x(d.x); })
                    .attr("y", function(d) { return y(d.y); })
                    .attr("width", function(d) { return x(d.x + d.dx) - x(d.x); })
                    .attr("height", function(d) { return y(d.y + d.dy) - y(d.y); });
            }


            function getRGBComponents(color) {
                var r = color.substring(1, 3);
                var g = color.substring(3, 5);
                var b = color.substring(5, 7);
                return {
                    R: parseInt(r, 16),
                    G: parseInt(g, 16),
                    B: parseInt(b, 16)
                };
            }


            function idealTextColor(bgColor) {
                var nThreshold = 105;
                var components = getRGBComponents(bgColor);
                var bgDelta = (components.R * 0.299) + (components.G * 0.587) + (components.B * 0.114);
                return ((255 - bgDelta) < nThreshold) ? "#000000" : "#ffffff";
            }

            function name(d) {

                return d.name;
                return d.parent
                    ? name(d.parent) + "." + d.name
                    : d.name;
            }
        });


    }
</script>


@stop