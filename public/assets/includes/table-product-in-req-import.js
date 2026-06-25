$(function () {
    var dt_table_product_import = $(".datatable-product-in-req-import");
    var Url = "db/product/in/logistik/import";

    if (dt_table_product_import.length) {
        $('[data-toggle="tooltip"]').tooltip();
        var dt_product = dt_table_product_import.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: {
                    "Content-Type": "application/json",
                },
            },
            columns: [
                { data: "" },
                { data: "id" },
                { data: "no_product_in" },
                { data: "no_do" },
                {
                    data: "date",
                    render: function (data, type, row) {
                        if (!data) return "-";
                        var d = new Date(data);
                        var day = String(d.getDate()).padStart(2, "0");
                        var month = String(d.getMonth() + 1).padStart(2, "0");
                        var year = d.getFullYear();
                        return day + "-" + month + "-" + year;
                    },
                },
                {
                    data: "tax",
                    render: function (data, type, row) {
                        return data == 0 ? "VAT" : "Non VAT";
                    },
                },
                { data: "total_qty" },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return "";
                    },
                },
                {
                    targets: 1,
                    searchable: true,
                    visible: false,
                },
                {
                    // No. Product In sebagai link ke halaman detail
                    responsivePriority: 1,
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $dataId = full["id"];
                        var $detailUrl = route("product-in.show", $dataId);
                        var $label = data ? data : "-";
                        return (
                            '<a href="' + $detailUrl + '">#' +
                            $label +
                            "</a>"
                        );
                    },
                },
            ],
            order: [[1, "desc"]],
            dom: '<"card-header flex-column flex-md-row"<"head-label-delay-import text-center">><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return "Details of " + data["no_product_in"];
                        },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== ""
                                ? '<tr data-dt-row="' +
                                      col.rowIndex +
                                      '" data-dt-column="' +
                                      col.columnIndex +
                                      '">' +
                                      "<td>" +
                                      col.title +
                                      ":" +
                                      "</td> " +
                                      "<td>" +
                                      col.data +
                                      "</td>" +
                                      "</tr>"
                                : "";
                        }).join("");

                        return data
                            ? $('<table class="table"/><tbody />').append(data)
                            : false;
                    },
                },
            },
        });
        $("div.head-label-delay-import").html(
            '<h5 class="card-title mb-0">Table Product In Delay Import</h5>'
        );
    }
    dt_table_product_import.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
