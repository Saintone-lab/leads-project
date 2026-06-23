$(function () {
    var dt_table_sales_completed_search_project = $(
        ".datatable-sales-completed-search-project"
    );
    var Url = "/db/pending/sales-completed-project";

    if (dt_table_sales_completed_search_project.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-sales-completed-search-project thead tr")
            .clone(true)
            .appendTo(".datatable-sales-completed-search-project thead");
        $(".datatable-sales-completed-search-project thead tr:eq(1) th").each(function (
            i
        ) {
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
        });

        var dt_filter = dt_table_sales_completed_search_project.DataTable({
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
                { data: "po_date" },
                {
                    data: "no_po",
                    render: function (data, type, row) {
                        return data ? data : "belum ada invoice";
                    },
                },
                { data: "company" },
                { data: "title" },
                { data: "status" },
                { data: "level" },
                { data: "delivery" },
            ],
            columnDefs: [
                // {
                //     targets: 1,
                //     render: function (data, type, full, row) {
                //         if (type === "display") {
                //             var id = full["id"];
                //             return (
                //                 '<a class="text-black cursor-pointer" data-bs-toggle="modal" data-bs-target="#detailPending-' +
                //                 id +
                //                 '">' +
                //                 data +
                //                 "</a>"
                //             );
                //         }
                //         return data;
                //     },
                // },
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $status_number = full["status"];
                        var $status = {
                            null: {
                                title: "New PO",
                                class: "bg-label-info",
                            },
                            0: {
                                title: "New PO",
                                class: "bg-label-info",
                            },
                            1: {
                                title: "On Check",
                                class: " bg-label-info",
                            },
                            2: {
                                title: "Ready Stock",
                                class: " bg-label-whatsapp",
                            },
                            3: {
                                title: "Kurang",
                                class: " bg-label-reddit",
                            },
                            4: {
                                title: "Pre-Order",
                                class: " bg-label-primary",
                            },
                            5: {
                                title: "Delivery Process",
                                class: " bg-label-linkedin",
                            },
                            6: {
                                title: "Completed",
                                class: "bg-label-success",
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
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
                        var bayar = full["paytype"];
                        var info, warna;
                        if (data == 0) {
                            info = "UNPAID";
                            warna = "bg-label-danger";
                        } else {
                            if (bayar == "DP") {
                                info = "Half Paid";
                                warna = "bg-label-warning";
                            } else if (bayar == "BP") {
                                info = "PAID";
                                warna = "bg-label-success";
                            } else if (bayar == "Tempo") {
                                info = "Kredit";
                                warna = "bg-label-success";
                            } else {
                                info = "full Paid";
                                warna = "bg-label-success";
                            }
                        }
                        return (
                            '<span class="badge rounded-pill ' +
                            warna +
                            '">' +
                            info +
                            "</span>"
                        );
                    },
                },
                {
                    targets: 6,
                    render: function (data, type, full, meta) {
                        var delivery = full["delivery"];
                        switch (delivery) {
                            case 1:
                                delivery = "Kurir";
                                break;
                            case 2:
                                delivery = "Teknisi";
                                break;
                            case 3:
                                delivery = "Direct";
                                break;
                            case 4:
                                delivery = "Other";
                                break;

                            default:
                                break;
                        }
                        return delivery;
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("pending-po.show", id);
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
            ],
            order: [],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_sales_completed_search_project.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
