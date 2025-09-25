$(function () {
    var dt_table_sales_list_search_admin = $(
        ".datatable-sales-list-search-admin"
    );
    var Url = "/db/pending/sales-list/admin";

    if (dt_table_sales_list_search_admin.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-sales-list-search-admin thead tr")
            .clone(true)
            .appendTo(".datatable-sales-list-search-admin thead");
        $(".datatable-sales-list-search-admin thead tr:eq(1) th").each(
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

        var dt_filter = dt_table_sales_list_search_admin.DataTable({
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
                { data: "no_pending" },
                { data: "po_date" },
                { data: "type" },
                { data: "company" },
                { data: "title" },
                { data: "status" },
                { data: "level" },
                { data: "area" },
                { data: "delivery" },
                {
                    data: "name",
                },
                {
                    data: "team",
                },
            ],
            columnDefs: [
                {
                    targets: 0,
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
                {
                    targets: 1,
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return row.po_date_raw; // pakai versi raw untuk sorting
                        }
                        return data; // tampilan tetap yang dd-mm-yyyy
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var warna;
                        if (data === "Project") {
                            warna = "bg-label-primary";
                        } else {
                            warna = "bg-label-success";
                        }
                        return (
                            '<span class="badge rounded-pill ' +
                            warna +
                            '">' +
                            data +
                            "</span>"
                        );
                    },
                },
                {
                    targets: 5,
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
                                title: "Pre-delivery",
                                class: " bg-label-primary",
                            },
                            5: {
                                title: "Delivery Process",
                                class: " bg-label-linkedin",
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
                    targets: 6,
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
                    targets: 8,
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
                    targets: 10,
                    render: function (data, type, full, row) {
                        var id = full["team"];
                        var team;
                        if (id == 1 || id == 16 || id == 23) {
                            team = "ONLINE";
                        } else {
                            team = "OFFLINE";
                        }
                        return team;
                    },
                },
            ],
            // order: [[1, "desc"]],
            // orderCellsTop: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_sales_list_search_admin.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
