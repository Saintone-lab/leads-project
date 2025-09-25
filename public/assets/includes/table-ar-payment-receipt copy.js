$(function () {
    var dt_table_payment_receipt_ar = $(".datatable-payment-receipt-ar");
    var Url = "/db/payment/receipt/ar";

    if (dt_table_payment_receipt_ar.length) {
        $('[data-toggle="tooltip"]').tooltip();
        var dt_invoice = dt_table_payment_receipt_ar.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: {
                    "Content-Type": "application/json",
                },
                // success: function (hasil, Url) {
                //     console.log("Url:", Url);
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
                // { data: "id" },
                { data: "id" },
                { data: "no_receipt" },
                { data: "date" },
                { data: "no_invoice" },
                { data: "company" },
                { data: "amount" },
                { data: "total_payment" },
                { data: "method" },
                { data: "title" },
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
                // {
                //     // For Checkboxes
                //     targets: 1,
                //     orderable: false,
                //     searchable: false,
                //     responsivePriority: 3,
                //     checkboxes: true,
                //     render: function () {
                //         return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                //     },
                //     checkboxes: {
                //         selectAllRender:
                //             '<input type="checkbox" class="form-check-input">',
                //     },
                // },
                {
                    targets: 1,
                    searchable: true,
                    visible: false,
                },
                {
                    responsivePriority: 2,
                    targets: 4,
                },
                {
                    targets: 2,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("payment_detail.payment", id);
                            return (
                                '<a class="text-black" href="' +
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
                    targets: [6, 7],
                    render: function (data, type, row) {
                        if (type === "display" || type === "filter") {
                            return (
                                "Rp " +
                                new Intl.NumberFormat("id-ID").format(data)
                            );
                        }
                        return data;
                    },
                },
                {
                    targets: -1,
                    render: function (data, type, full, row) {
                        var judul = data;
                        var label;
                        if (judul == "Partial") {
                            label = "bg-label-warning";
                        } else{
                            label = "bg-label-success";
                        }
                        return (
                            '<span class="badge rounded-pill ' +
                            label +
                            '">' +
                            data +
                            "</span>"
                        );
                    },
                },
            ],
            drawCallback: function (settings) {
                console.log("drawCallback");
                $('[data-toggle="tooltip"]').tooltip();
            },
            dom: '<"card-header flex-column flex-md-row"<"head-label hl-1 text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-label-primary dropdown-toggle me-2",
                    text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = "";
                                        $.each(el, function (index, item) {
                                            if (
                                                item.classList !== undefined &&
                                                item.classList.contains(
                                                    "user-name"
                                                )
                                            ) {
                                                result =
                                                    result +
                                                    item.lastChild.firstChild
                                                        .textContent;
                                            } else if (
                                                item.innerText === undefined
                                            ) {
                                                result =
                                                    result + item.textContent;
                                            } else
                                                result =
                                                    result + item.innerText;
                                        });
                                        return result;
                                    },
                                },
                            },
                            customize: function (win) {
                                //customize print view for dark
                                $(win.document.body)
                                    .css("color", config.colors.headingColor)
                                    .css(
                                        "border-color",
                                        config.colors.borderColor
                                    )
                                    .css(
                                        "background-color",
                                        config.colors.bodyBg
                                    );
                                $(win.document.body)
                                    .find("table")
                                    .addClass("compact")
                                    .css("color", "inherit")
                                    .css("border-color", "inherit")
                                    .css("background-color", "inherit");
                            },
                        },
                        {
                            extend: "csv",
                            text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = "";
                                        $.each(el, function (index, item) {
                                            if (
                                                item.classList !== undefined &&
                                                item.classList.contains(
                                                    "user-name"
                                                )
                                            ) {
                                                result =
                                                    result +
                                                    item.lastChild.firstChild
                                                        .textContent;
                                            } else if (
                                                item.innerText === undefined
                                            ) {
                                                result =
                                                    result + item.textContent;
                                            } else
                                                result =
                                                    result + item.innerText;
                                        });
                                        return result;
                                    },
                                },
                            },
                        },
                        {
                            extend: "excel",
                            text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = "";
                                        $.each(el, function (index, item) {
                                            if (
                                                item.classList !== undefined &&
                                                item.classList.contains(
                                                    "user-name"
                                                )
                                            ) {
                                                result =
                                                    result +
                                                    item.lastChild.firstChild
                                                        .textContent;
                                            } else if (
                                                item.innerText === undefined
                                            ) {
                                                result =
                                                    result + item.textContent;
                                            } else
                                                result =
                                                    result + item.innerText;
                                        });
                                        return result;
                                    },
                                },
                            },
                        },
                        {
                            extend: "pdf",
                            text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = "";
                                        $.each(el, function (index, item) {
                                            if (
                                                item.classList !== undefined &&
                                                item.classList.contains(
                                                    "user-name"
                                                )
                                            ) {
                                                result =
                                                    result +
                                                    item.lastChild.firstChild
                                                        .textContent;
                                            } else if (
                                                item.innerText === undefined
                                            ) {
                                                result =
                                                    result + item.textContent;
                                            } else
                                                result =
                                                    result + item.innerText;
                                        });
                                        return result;
                                    },
                                },
                            },
                        },
                        {
                            extend: "copy",
                            text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [3, 4, 5, 6, 7],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = "";
                                        $.each(el, function (index, item) {
                                            if (
                                                item.classList !== undefined &&
                                                item.classList.contains(
                                                    "user-name"
                                                )
                                            ) {
                                                result =
                                                    result +
                                                    item.lastChild.firstChild
                                                        .textContent;
                                            } else if (
                                                item.innerText === undefined
                                            ) {
                                                result =
                                                    result + item.textContent;
                                            } else
                                                result =
                                                    result + item.innerText;
                                        });
                                        return result;
                                    },
                                },
                            },
                        },
                    ],
                },
                // {
                //     text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New pending</span>',
                //     className: "btn btn-primary btn-new",
                //     action: function (e, dt, node, config) {
                //         window.location = route("create.pending");
                //     },
                // },
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return "Details of " + data["full_name"];
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
        $("div.hl-1").html('<h5 class="card-title mb-0">Payment Receipt</h5>');
    }
    dt_table_payment_receipt_ar.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
