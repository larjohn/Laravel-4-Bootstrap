$.views.settings.delimiters("@%", "%@");
var data_page = {};

function setFacets(data){
    var facets = [];
    $.each(data, function () {
        facets.push(this);

    });
    var app = {
        facets: facets
    };
    var facetsTemplate = $.templates("#facetTmpl");

    facetsTemplate.link("#facets", app);


}




function fillData() {
    $.getJSON(appRoot + 'api/error/facets', error_filters, function (data) {
       setFacets(data);
    });

    $("#list").jqGrid({
        url: appRoot + "api/error",
        height: 'auto',
        postData: {
            filters:  error_filters,
            test: unprefix(test_item)
        },
        autowidth: true,
        datatype: "json",
        mtype: "GET",
        colNames: ["Actions", "Resource", "Property", "Query"],
        colModel: [
         //   {name: 'checkbox', index: 'checkbox', formatter: "checkbox", formatoptions: { disabled: false } },

            { name: "view", formatter: function (cellvalue, options, rowObject) {
                return '<a href="#myModal" role="button" class="btn btn-info view-item" data-row="' + options.rowId + '" data-resource="' + rowObject.violationRoot[0].id + '" data-property="' + rowObject.violationPath[0].id + '" data-test="' + test_item + '"  data-query="' + rowObject.query + '"    data-toggle="modal">Details</a>';
            } },
            { name: "violationRoot.0.id", formatter: function (cellvalue, options, rowObject) {
                var decoded = decodeURIComponent(cellvalue);
                var abbr = prefix(decoded);
                return "<span title='" + rowObject.violationRoot[0].id + "'>" + abbr + "</span><a href='" + decoded + "' title='" + rowObject.violationRoot[0].id + "' target='_blank'>   <i class='icon-external-link'></i> </a>"

            } },
            { name: "violationPath.0.id", align: "right",
                formatter: function (cellvalue, options, rowObject) {
                    var decoded = decodeURIComponent(cellvalue);
                    var abbr = prefix(decoded);
                    return "<span title='" + rowObject.violationPath[0].id + "'>" + abbr + "</span><a href='" + decoded + "' title='" + rowObject.violationPath[0].id + "' target='_blank'>   <i class='icon-external-link'></i> </a>"

                }
            },
            // { name: "test.0.id",  align: "right" },
            { name: "query.0.id", align: "right",
                formatter: function (cellvalue, options, rowObject) {
                    if(rowObject.query==undefined)return "";
                    var decoded = decodeURIComponent(cellvalue);
                    var abbr = prefix(decoded );
                    return "<span title='" + rowObject.query[0].id + "'>" + abbr + "</span><a href='" + decoded + "' title='" + rowObject.query[0].id + "' target='_blank'>   <i class='icon-external-link'></i></a>"

                }
            }

        ],
        jsonReader: {
            root: "errors",
            page: "page",
            total: "total",
            records: "records",
            cell: "",
            id: "0"
        },
        loadComplete: function (data) {
            data_page = data;
            setFacets(data.facets)
        },
        pager: "#pager",
        rowNum: 50,
        rowList: [ 25, 50, 100],
        sortname: "id",
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        autoencode: true,
        caption: "DBpedia Errors"
    });
    jQuery("#list").jqGrid('navGrid', '#pager', {edit: false, add: false, del: false});
}


function loadError(resource, property, query, test) {

    var error_id = {
        resource: resource,
        property: property,
        test: test,
        query: query
    };

    $.getJSON(appRoot + 'api/error/item', error_id, function (data) {


        var app = {
            item: data
        };
        var itemTemplate = $.templates("#itemTmpl");
        itemTemplate.link("#facets", app);

    });
}

$(document).ready(function () {

    if (mode == "latest") {
        $.getJSON(appRoot + 'api/tests/latest', function (data) {
            fillData();
            var shortened = VIE.Util.toCurie("<" + data.name + ">", false, namespaces);
            $("#test_name").html("<a href='" + appRoot + "tests/item/"+encodeURIComponent(shortened)+"'>"+data.name+"</a>");

        });
    }
    else if(mode=="item"){

            fillData();
           // var shortened = VIE.Util.toCurie("<" + test_item + ">", false, namespaces);
    }
    else{
        fillData();
    }



    $.views.converters("curie", function (val) {
        var decoded = decodeURIComponent(val);
        try {
            return VIE.Util.toCurie("<" + decoded + ">", false, namespaces);
        }
        catch (err) {
            return val;
        }
    });



    jQuery.ajaxSetup({
        dataFilter: function (data, dataType) {
            return data;
        }
    });

    $(document).on("click", '.view-item', function (event) {

        var rowId = $(event.target).attr("data-row");

        var errorsTemplate = $.templates("#itemTmpl");
        var row = data_page.errors[rowId - 1];
        var params = {resource: row.violationRoot[0].id,
            property: row.violationPath[0].id,
            query: row.query[0].id,
            test: unprefix(test_item)
        };

        $("#errorModal").modal({}).toggleClass('loading', true);
        $.getJSON(appRoot + 'api/error/item', params, function (data) {
            errorsTemplate.link("#item-modal-content", data);
            $("#errorModal").toggleClass('loading', false);


        });


    });


    $(document).on("click", '.revert', function (event) {

        $(event.target).parents(".facet").toggleClass("active", false);
        $(event.target).parents(".facet").find(".facet-item").toggleClass("selected", false);
        delete error_filters[$(event.target).parent(".revert").attr("data-facet")];
        $("#list").setGridParam({postData: null});
        $('#list').setGridParam({ postData: {filters: error_filters, test:unprefix(test_item)}  }).trigger('reloadGrid');
    });

    $(document).on("click", '.facet-item a', function (event) {
        $(event.target).parents(".facet").find(".facet-item").toggleClass("selected", false);
        $(event.target).parent(".facet-item").toggleClass("selected", true);
        $(event.target).parents(".facet").toggleClass("active", true);
        error_filters[$(event.target).attr("data-facet")] = {name: $(event.target).attr("data-facet"), operator: "=", value: $(event.target).attr("data-facet-value")};

        $('#list').setGridParam({ postData: {filters: error_filters, test:unprefix(test_item)} }).trigger('reloadGrid');
    });


    //fillData();
});

