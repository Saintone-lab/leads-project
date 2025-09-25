$(function () {
    var dt_table_sales_order_search_admin = $(
        ".datatable-sales-order-search-admin"
    );
    var Url = "db/pending/sales-order/admin";

    if (dt_table_sales_order_search_admin.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-sales-order-search-admin thead tr")
            .clone(true)
            .appendTo(".datatable-sales-order-search-admin thead");
        $(".datatable-sales-order-search-admin thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_sales_order_search_admin.DataTable({
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
                { data: "company" },
                { data: "title" },
                { data: "status" },
                { data: "status_p" },
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
                    targets: 1,
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
                    targets: 3,
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
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $status_number = full["status_p"];
                        var $titleTool = full["note_p"];
                        var $status = {
                            null: {
                                title: "Belum Ada Invoice",
                                class: "bg-label-danger",
                                colorTip: "tooltip-danger",
                                titleTip: $titleTool,
                            },
                            0: {
                                title: "Not Confirmed Yet",
                                class: "bg-label-warning",
                                colorTip: "tooltip-warning",
                                titleTip: $titleTool,
                            },
                            1: {
                                title: "Confirmed Payment",
                                class: " bg-label-success",
                                colorTip: "tooltip-success",
                                titleTip: $titleTool,
                            },
                        };
                        if (typeof $status[$status_number] === "undefined") {
                            return data;
                        }
                        return (
                            '<span data-toggle="tooltip" data-container="body" data-bs-placement="top" data-bs-custom-class="' +
                            $status[$status_number].colorTip +
                            '" title="' +
                            $status[$status_number].titleTip +
                            '" class="badge rounded-pill ' +
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
                    targets: 7,
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
            // orderCellsTop: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_sales_order_search_admin.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
