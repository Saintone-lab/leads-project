$(function () {
    var dt_table_prospect = $(".datatable-prospect-admin");
    var Url = "db/prospect/admin";

    if (dt_table_prospect.length) {
        $('[data-toggle="tooltip"]').tooltip();
        var dt_prospect = dt_table_prospect.DataTable({
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
                { data: "id" },
                { data: "id" },
                { data: "company" },
                { data: "name_pic" },
                { data: "kebutuhan" },
                { data: "date" },
                { data: "nett" },
                { data: "status" },
                { data: "image" },
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
                    targets: 4,
                },
                {
                    targets: 3,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var $dataId = full["id"];
                            var detailRoute = route("prospect.show", $dataId);
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
                // {
                //     targets: [7, 8, 9],
                //     render: function (data, type, row) {
                //         if (data === null || data === undefined) {
                //             return "-";
                //         } else {
                //             return type === "display" ? data : "-";
                //         }
                //     },
                // },
                {
                    // Label Status Name
                    targets: 8,
                    render: function (data, type, full, meta) {
                        var $status_number = full["status"];
                        var $status = {
                            20: {
                                title: "Send WA / Email",
                                class: "bg-label-secondary",
                            },
                            30: {
                                title: "Inquiry Accepted",
                                class: " bg-label-dark",
                            },
                            40: {
                                title: "Progress Follow Up",
                                class: " bg-label-info",
                            },
                            60: {
                                title: "Negotiation / Revisi",
                                class: " bg-label-primary",
                            },
                            80: {
                                title: "Hot Prospect",
                                class: " bg-label-warning",
                            },
                            100: {
                                title: "Done PO",
                                class: " bg-label-success",
                            },
                            0: {
                                title: "Loss",
                                class: " bg-label-danger",
                            },
                        };
                        if (
                            $status[$status_number] === null ||
                            $status[$status_number] === undefined
                        ) {
                            return "-";
                        } else {
                            return (
                                '<span class="badge rounded-pill ' +
                                $status[$status_number].class +
                                '">' +
                                $status[$status_number].title +
                                "</span>"
                            );
                        }
                    },
                },
                {
                    targets: 7,
                    render: function (data, type, full, meta) {
                        if (data === null || data === undefined) {
                            return "-";
                        } else {
                            // Pastikan data dikirimkan ke fungsi render.number untuk diformat
                            return type === "display"
                                ? $.fn.dataTable.render
                                      .number(".", "", 0, "Rp.")
                                      .display(data)
                                : data;
                        }
                    },
                },
                {
                    // Label Status Name
                    targets: 9,
                    render: function (data, type, full, meta) {
                        var name = full["sales"];
                        const domain = window.location.origin;
                            return (
                                '<span data-toggle="tooltip" data-container="body" data-bs-placement="top" data-bs-custom-class="tooltip-dark" title="' +
                                name +
                                '">' +
                                '<img src="'+ domain +'/' + data + '" class="w-px-40 h-auto rounded-circle" alt="Profile Image">' +
                                "</span>"
                            );
                        // }
                    },
                },
            ],
            drawCallback: function (settings) {
                console.log("drawCallback");
                $('[data-toggle="tooltip"]').tooltip();
            },
            order: [[2, "desc"]],
            dom: '<"card-header flex-column flex-md-row"<"head-label hl-1 text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
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
                //     text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Prospect</span>',
                //     className: "btn btn-primary",
                //     attr: {
                //         "data-bs-target": "#createProspect",
                //         "data-bs-toggle": "modal",
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
        $("div.hl-1").html('<h5 class="card-title mb-0">prospects</h5>');
    }
    dt_table_prospect.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
