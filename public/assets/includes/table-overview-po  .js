$(function () {
    var dt_table_overview_po = $(".datatable-overview-po");
    var Url = "/db/overview/po/";
    var path = window.location.pathname;
    var segments = path.split("/");

    var sales = segments[segments.length - 2]; // Mendapatkan segment kedua dari belakang
    var dateRep = segments[segments.length - 1]; // Mendapatkan segment terakhir

    if (dt_table_overview_po.length) {
        var dt_po = dt_table_overview_po.DataTable({
            ajax: {
                type: "GET",
                url: Url + sales + "/" + dateRep,
                headers: {
                    "Content-Type": "application/json",
                },
                dataSrc: function(response) {
                    // Mengambil total_nett dari response dan mengubah po-head-label
                    var totalNett = response.total_nett || 0;
                    $("div.po-head-label").html(
                        '<h5 class="card-title mb-0">Table PO - Total PO: Rp ' + totalNett.toLocaleString() + '</h5>'
                    );

                    // Return data untuk datatable
                    return response.data;
                },
                error: function (error) {
                    console.error("Error:", error);
                },
            },
            columns: [
                { data: "" },
                { data: "id" },
                { data: "id" },
                { data: "no_quote" },
                { data: "company" },
                { data: "title" },
                { data: "estimated_date" },
                { data: "nett" },
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
                    // For Checkboxes
                    targets: 1,
                    orderable: false,
                    searchable: false,
                    responsivePriority: 3,
                    checkboxes: true,
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                    checkboxes: {
                        selectAllRender:
                            '<input type="checkbox" class="form-check-input">',
                    },
                },
                {
                    targets: 2,
                    searchable: true,
                    visible: false,
                },
                {
                    responsivePriority: 1,
                    targets: 3,
                },
                {
                    targets: 3,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var $dataId = full["id"];
                            var detailRoute = route("quotation.show", $dataId);
                            return (
                                '<a class="text-dark" href="' +
                                detailRoute +
                                '">' +
                                data +
                                "</a>"
                            );
                        }
                        return data;
                    },
                },
                {
                    targets: 7,
                    render: $.fn.dataTable.render.number(".", "", 0, "Rp."),
                },
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            order: [[2, "desc"]],
            dom: '<"card-header flex-column flex-md-row"<"po-head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            buttons: [
                // Tombol-tombol export di sini
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return "Details of " + data["company"];
                        },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== "" // ? Do not show row in modal popup if title is blank (for check box)
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
    }
});
