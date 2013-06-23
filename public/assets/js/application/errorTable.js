
var error_filters = {};

function tagsFormatter(cellvalue, options, rowObject){
    return cellvalue[0].id;
}
$(document).ready(function () {
    $("#list").jqGrid({
        url: "api/error",
        height:'auto',
        width:"100%",
        datatype: "json",
        mtype: "GET",
        colNames: ["Identifier", "Resource", "Property", "test set", "query", "tags"],
        colModel: [
            { name: "id" },
            { name: "violationRoot.0.label" },
            { name: "inaccurateProperty.0.label", align: "right" },
            { name: "test.0.id",  align: "right" },
            { name: "query",  align: "right" },
            { name: "subject", sortable: false,formatter:tagsFormatter  }
        ],
        jsonReader : {
            root:"errors",
            page: "page",
            total: "total",
            records: "records",
            cell: "",
            id: "0"
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
    jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false});
    //fillData();
});

