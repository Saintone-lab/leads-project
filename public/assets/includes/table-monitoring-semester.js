$(function () {
    var dt_table_semester_monitoring = $(".datatable-monitoring-semester");
    var Url = "/db/monitoring/semester";

    if (dt_table_semester_monitoring.length) {
        $('[data-toggle="tooltip"]').tooltip();
        var dt_semester_monitoring = dt_table_semester_monitoring.DataTable({
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
                { data: "semester" },
                { data: "year" },
                { data: "" },
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
                    responsivePriority: 1,
                    targets: 2,
                },
                {
                    // Actions
                    targets: 4,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full, meta) {
                        var year = full["year"];
                        var semester = full["semesterNum"];
                        var month = {
                            1: {
                                month1: "Januari",
                                noMonth1: "1",
                                month2: "Februari",
                                noMonth2: "2",
                                month3: "Maret",
                                noMonth3: "3",
                                month4: "April",
                                noMonth4: "4",
                                month5: "Mei",
                                noMonth5: "5",
                                month6: "Juni",
                                noMonth6: "6",
                            },
                            2: {
                                month1: "Juli",
                                noMonth1: "1",
                                month2: "Agustus",
                                noMonth2: "2",
                                month3: "September",
                                noMonth3: "3",
                                month4: "Oktober",
                                noMonth4: "4",
                                month5: "November",
                                noMonth5: "5",
                                month6: "Desemmber",
                                noMonth6: "6",
                            },
                        };
                        var $detailUrl1 = route(
                            "monitoring.fajarPaper-reportsMonthly",
                            [year, month[semester].noMonth1]
                        );
                        var $detailUrl2 = route(
                            "monitoring.fajarPaper-reportsMonthly",
                            [year, month[semester].noMonth2]
                        );
                        var $detailUrl3 = route(
                            "monitoring.fajarPaper-reportsMonthly",
                            [year, month[semester].noMonth3]
                        );
                        var $detailUrl4 = route(
                            "monitoring.fajarPaper-reportsMonthly",
                            [year, month[semester].noMonth4]
                        );
                        var $detailUrl5 = route(
                            "monitoring.fajarPaper-reportsMonthly",
                            [year, month[semester].noMonth5]
                        );
                        var $detailUrl6 = route(
                            "monitoring.fajarPaper-reportsMonthly",
                            [year, month[semester].noMonth6]
                        );
                        return (
                            '<div class="demo-inline-spacing">' +
                            '<div class="btn-group" id="dropdown-icon-demo">' +
                            '<button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" ' +
                            'data-bs-toggle="dropdown" aria-expanded="false">' +
                            '<i class="mdi mdi-menu me-1"></i> Month' +
                            "</button>" +
                            '<ul class="dropdown-menu" style="">' +
                            "<li>" +
                            '<a href="' +
                            $detailUrl1 +
                            '" class="dropdown-item">' +
                            month[semester].month1 +
                            "</a>" +
                            '<a href="' +
                            $detailUrl2 +
                            '"class="dropdown-item">' +
                            month[semester].month2 +
                            "</a>" +
                            '<a href="' +
                            $detailUrl3 +
                            '"class="dropdown-item">' +
                            month[semester].month3 +
                            "</a>" +
                            '<a href="' +
                            $detailUrl4 +
                            '"class="dropdown-item">' +
                            month[semester].month4 +
                            "</a>" +
                            '<a href="' +
                            $detailUrl5 +
                            '"class="dropdown-item">' +
                            month[semester].month5 +
                            "</a>" +
                            '<a href="' +
                            $detailUrl6 +
                            '"class="dropdown-item">' +
                            month[semester].month6 +
                            "</a>" +
                            "</li>" +
                            "</ul>" +
                            "</div>" +
                            "</div>"
                        );
                    },
                },
            ],
            // order: [[2, "asc"]],
            // dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            // displayLength: 7,
            // lengthMenu: [7, 10, 25, 50, 75, 100],
            // buttons: [
            //     {
            //         extend: "collection",
            //         className: "btn btn-label-primary dropdown-toggle me-2",
            //         text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
            //         buttons: [
            //             {
            //                 extend: "print",
            //                 text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
            //                 className: "dropdown-item",
            //                 exportOptions: {
            //                     columns: [3, 4, 5, 6, 7, 8, 9],
            //                     // prevent avatar to be display
            //                     format: {
            //                         body: function (inner, coldex, rowdex) {
            //                             if (inner.length <= 0) return inner;
            //                             var el = $.parseHTML(inner);
            //                             var result = "";
            //                             $.each(el, function (index, item) {
            //                                 if (
            //                                     item.classList !== undefined &&
            //                                     item.classList.contains(
            //                                         "user-name"
            //                                     )
            //                                 ) {
            //                                     result =
            //                                         result +
            //                                         item.lastChild.firstChild
            //                                             .textContent;
            //                                 } else if (
            //                                     item.innerText === undefined
            //                                 ) {
            //                                     result =
            //                                         result + item.textContent;
            //                                 } else
            //                                     result =
            //                                         result + item.innerText;
            //                             });
            //                             return result;
            //                         },
            //                     },
            //                 },
            //                 customize: function (win) {
            //                     //customize print view for dark
            //                     $(win.document.body)
            //                         .css("color", config.colors.headingColor)
            //                         .css(
            //                             "border-color",
            //                             config.colors.borderColor
            //                         )
            //                         .css(
            //                             "background-color",
            //                             config.colors.bodyBg
            //                         );
            //                     $(win.document.body)
            //                         .find("table")
            //                         .addClass("compact")
            //                         .css("color", "inherit")
            //                         .css("border-color", "inherit")
            //                         .css("background-color", "inherit");
            //                 },
            //             },
            //             {
            //                 extend: "csv",
            //                 text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
            //                 className: "dropdown-item",
            //                 exportOptions: {
            //                     columns: [3, 4, 5, 6, 7, 8, 9],
            //                     // prevent avatar to be display
            //                     format: {
            //                         body: function (inner, coldex, rowdex) {
            //                             if (inner.length <= 0) return inner;
            //                             var el = $.parseHTML(inner);
            //                             var result = "";
            //                             $.each(el, function (index, item) {
            //                                 if (
            //                                     item.classList !== undefined &&
            //                                     item.classList.contains(
            //                                         "user-name"
            //                                     )
            //                                 ) {
            //                                     result =
            //                                         result +
            //                                         item.lastChild.firstChild
            //                                             .textContent;
            //                                 } else if (
            //                                     item.innerText === undefined
            //                                 ) {
            //                                     result =
            //                                         result + item.textContent;
            //                                 } else
            //                                     result =
            //                                         result + item.innerText;
            //                             });
            //                             return result;
            //                         },
            //                     },
            //                 },
            //             },
            //             {
            //                 extend: "excel",
            //                 text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
            //                 className: "dropdown-item",
            //                 exportOptions: {
            //                     columns: [3, 4, 5, 6, 7, 8, 9],
            //                     // prevent avatar to be display
            //                     format: {
            //                         body: function (inner, coldex, rowdex) {
            //                             if (inner.length <= 0) return inner;
            //                             var el = $.parseHTML(inner);
            //                             var result = "";
            //                             $.each(el, function (index, item) {
            //                                 if (
            //                                     item.classList !== undefined &&
            //                                     item.classList.contains(
            //                                         "user-name"
            //                                     )
            //                                 ) {
            //                                     result =
            //                                         result +
            //                                         item.lastChild.firstChild
            //                                             .textContent;
            //                                 } else if (
            //                                     item.innerText === undefined
            //                                 ) {
            //                                     result =
            //                                         result + item.textContent;
            //                                 } else
            //                                     result =
            //                                         result + item.innerText;
            //                             });
            //                             return result;
            //                         },
            //                     },
            //                 },
            //             },
            //             {
            //                 extend: "pdf",
            //                 text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
            //                 className: "dropdown-item",
            //                 exportOptions: {
            //                     columns: [3, 4, 5, 6, 7, 8, 9],
            //                     // prevent avatar to be display
            //                     format: {
            //                         body: function (inner, coldex, rowdex) {
            //                             if (inner.length <= 0) return inner;
            //                             var el = $.parseHTML(inner);
            //                             var result = "";
            //                             $.each(el, function (index, item) {
            //                                 if (
            //                                     item.classList !== undefined &&
            //                                     item.classList.contains(
            //                                         "user-name"
            //                                     )
            //                                 ) {
            //                                     result =
            //                                         result +
            //                                         item.lastChild.firstChild
            //                                             .textContent;
            //                                 } else if (
            //                                     item.innerText === undefined
            //                                 ) {
            //                                     result =
            //                                         result + item.textContent;
            //                                 } else
            //                                     result =
            //                                         result + item.innerText;
            //                             });
            //                             return result;
            //                         },
            //                     },
            //                 },
            //             },
            //             {
            //                 extend: "copy",
            //                 text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
            //                 className: "dropdown-item",
            //                 exportOptions: {
            //                     columns: [3, 4, 5, 6, 7, 8, 9],
            //                     // prevent avatar to be display
            //                     format: {
            //                         body: function (inner, coldex, rowdex) {
            //                             if (inner.length <= 0) return inner;
            //                             var el = $.parseHTML(inner);
            //                             var result = "";
            //                             $.each(el, function (index, item) {
            //                                 if (
            //                                     item.classList !== undefined &&
            //                                     item.classList.contains(
            //                                         "user-name"
            //                                     )
            //                                 ) {
            //                                     result =
            //                                         result +
            //                                         item.lastChild.firstChild
            //                                             .textContent;
            //                                 } else if (
            //                                     item.innerText === undefined
            //                                 ) {
            //                                     result =
            //                                         result + item.textContent;
            //                                 } else
            //                                     result =
            //                                         result + item.innerText;
            //                             });
            //                             return result;
            //                         },
            //                     },
            //                 },
            //             },
            //         ],
            //     },
            //     {
            //         text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Year/Semester</span>',
            //         className: "btn btn-primary",
            //         attr: {
            //             "data-bs-target": "#createReports",
            //             "data-bs-toggle": "modal",
            //         },
            //     },
            // ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
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
    dt_table_semester_monitoring.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
