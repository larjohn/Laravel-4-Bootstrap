$.views.settings.delimiters("@%", "%@");
var data_page = {};


function tagsFormatter(cellvalue, options, rowObject) {
    return cellvalue[0].id;
}


function fillData() {
    $.getJSON(appRoot + 'api/error/facets', error_filters, function (data) {
        var facets = [];
        $.each(data, function () {
            facets.push(this);
        });
        var app = {
            facets: facets
        };
        var facetsTemplate = $.templates("#facetTmpl");
        facetsTemplate.link("#facets", app);
    });

    $("#list").jqGrid({
        url: appRoot + "api/error",
        height: 'auto',
        postData: {
            filters:  error_filters

        },
        autowidth: true,
        datatype: "json",
        mtype: "GET",
        colNames: ["Select", "Actions", "Resource", "Property", "query"],
        colModel: [
            {name: 'checkbox', index: 'checkbox', formatter: "checkbox", formatoptions: { disabled: false } },

            { name: "view", formatter: function (cellvalue, options, rowObject) {
                return '<a href="#myModal" role="button" class="btn btn-info view-item" data-row="' + options.rowId + '" data-resource="' + rowObject.violationRoot[0].id + '" data-property="' + rowObject.inaccurateProperty[0].id + '" data-test="' + rowObject.test[0].id + '"  data-query="' + rowObject.query + '"    data-toggle="modal">Details</a>';
            } },
            { name: "violationRoot.0.id", formatter: function (cellvalue, options, rowObject) {
                var decoded = decodeURIComponent(cellvalue);
                var abbr = VIE.Util.toCurie("<" + decoded + ">", false, namespaces);
                return "<span title='" + rowObject.violationRoot[0].label + "'>" + abbr + "</span><a href='" + decoded + "' title='" + rowObject.violationRoot[0].label + "' target='_blank'>   <i class='icon-external-link'></i> </a>"

            } },
            { name: "inaccurateProperty.0.label", align: "right" },
            // { name: "test.0.id",  align: "right" },
            { name: "query", align: "right" }

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
        },
        pager: "#pager",
        rowNum: 10,
        rowList: [10, 20, 30],
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


            error_filters["test"] = {value: data.name, name: "test", "operator": "="};
            fillData();
            var shortened = VIE.Util.toCurie("<" + data.name + ">", false, namespaces);
            $("#test_name").html("<a href='item/"+encodeURIComponent(shortened)+"'>"+data.name+"</a>")

        });
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
            property: row.inaccurateProperty[0].id,
            query: row.query[0],
            test: row.test[0].id
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
        $('#list').setGridParam({ postData: {filters: error_filters} }).trigger('reloadGrid');
    });

    $(document).on("click", '.facet-item a', function (event) {
        $(event.target).parents(".facet").find(".facet-item").toggleClass("selected", false);
        $(event.target).parent(".facet-item").toggleClass("selected", true);
        $(event.target).parents(".facet").toggleClass("active", true);
        error_filters[$(event.target).attr("data-facet")] = {name: $(event.target).attr("data-facet"), operator: "=", value: $(event.target).attr("data-facet-value")};

        $('#list').setGridParam({ postData: {filters: error_filters} }).trigger('reloadGrid');
    });


    //fillData();
});

