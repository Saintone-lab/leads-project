$(function () {
    var dt_table_sales_completed_retur = $(
        ".datatable-sales-completed-retur"
    );
    var Url = "/db/retur";

    if (dt_table_sales_completed_retur.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-sales-completed-retur thead tr")
            .clone(true)
            .appendTo(".datatable-sales-completed-retur thead");
        $(".datatable-sales-completed-retur thead tr:eq(1) th").each(
            function (i) {
                var title = $(this).text();
                $(this).html(
                    '<input type="text" class="form-control" placeholder="Search ' +
                        title +
                        '" />'
                );

                $("input", this).on("keyup change", function () {
                    if (dt_filter.column(i).search() !== this.value) {
                        dt_filter.column(i).search(this.value).draw();
                    }
                });
            }
        );

        var dt_filter = dt_table_sales_completed_retur.DataTable({
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
                { data: "no_return" },
                // { data: "no_po" },
                { data: "company" },
                // { data: "note" },
                { data: "status" },
                { data: "name" },
                { data: "po_date" },
                { data: "date" },
            ],
            columnDefs: [
                // {
                //     targets: 1,
                //     render: function (data, type, row) {
                //         if (type === "sort" || type === "type") {
                //             return row.po_date_raw; // pakai versi raw untuk sorting
                //         }
                //         return data; // tampilan tetap yang dd-mm-yyyy
                //     },
                // },
                // {
                //     targets: 1,
                //     render: function (data, type, full, row) {
                //         if (type === "display") {
                //             var id = full["id"];
                //             detailRoute = route("pending-po.show", id);
                //             return (
                //                 '<a class="text-black" href="' +
                //                 detailRoute +
                //                 '">' +
                //                 data +
                //                 "</a>"
                //             );
                //         }
                //         return data;
                //     },
                // },
                {
                    targets: 0,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("return.show", id);
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
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $status_number = full["status"];
                        var $status = {
                            null: {
                                title: "Belum Di ACC",
                                class: "bg-label-info",
                            },
                            0: {
                                title: "Belum Di ACC",
                                class: "bg-label-info",
                            },
                            1: {
                                title: "Done",
                                class: " bg-label-success",
                            },
                        };
                        if (typeof $status[$status_number] === "undefined") {
                            return data;
                        }
                        return (
                            '<span class="badge rounded-pill ' +
                            $status[$status_number].class +
                            '">' +
                            $status[$status_number].title +
                            "</span>"
                        );
                    },
                },
            ],
            order: [],
            // deliveryCellsTop: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_sales_completed_retur.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
