$(function () {
    var dt_table_recap_week = $(".datatable-recap-week");
    var Url = "/db/weeks/";
    var path = window.location.pathname;
    var segments = path.split("/");

    var month = segments[segments.length - 2]; // Mendapatkan segment kedua dari belakang
    var year = segments[segments.length - 1]; // Mendapatkan segment terakhir

    // console.log("ID:", id); // Output: ID: 2

    if (dt_table_recap_week.length) {
        $('[data-toggle="tooltip"]').tooltip();
        var dt_product = dt_table_recap_week.DataTable({
            ajax: {
                type: "GET",
                url: Url + month + '/' + year,
                headers: {
                    "Content-Type": "application/json",
                },
                // success: function (hasil, url) {
                //     console.log("Url:", url);
                //     console.log(hasil);
                // },
                // error: function (error) {
                //     console.log("Url:", Url);
                //     console.error("Error:", error);
                //     console.log("error disini");
                // },
            },
            columns: [
                { data: "" },
                { data: "id" },
                { data: "id" },
                { data: "week_name" },
                { data: "date" },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 5,
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
                            var $idMachine = full["id"];
                            var $date = full["date"];
                            var $week = full["weeks"];
                            var detailRoute = route("service-manager.recap-monitoring-week", [$week, $date]);
                            return (
                                '<a class="text-dark" href="' + detailRoute + '">' + data + "</a>"
                            );
                        }
                        return data;
                    },
                },
                {
                    targets: 4,
                    render: function (data, type,full, row) {
                        var $idMachine = full["id"];
                        var $date = full["date"];
                            var $week = full["weeks"];
                        var detailRoute = route("service-manager.recap-monitoring-week", [$week, $date]);
                        return (
                            '<a href="' + detailRoute + '"class="btn btn-sm btn-primary m-2">Recap</a>'
                        );
                    },
                },
            ],
            order: [[1, "asc"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
                            return "Details of " + data["pn"];
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
        $("div.head-label").html(
            '<h5 class="card-title mb-0">Table Product</h5>'
        );
    }
    dt_table_recap_week.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
