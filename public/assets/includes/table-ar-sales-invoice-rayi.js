$(function () {
    var dt_table_sales_invoice_rayi = $(".datatable-sales-invoice-rayi");
    var Url = "/db/sales/invoice/rayi";

    if (dt_table_sales_invoice_rayi.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-sales-invoice-rayi thead tr")
            .clone(true)
            .appendTo(".datatable-sales-invoice-rayi thead");
        $(".datatable-sales-invoice-rayi thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_sales_invoice_rayi.DataTable({
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
                // { data: "" },
                // { data: "id" },
                // { data: "id" },
                { data: "no_invoice" },
                { data: "no_po" },
                { data: "tanggal" },
                { data: "company" },
                { data: "harga_total" },
                { data: "total_payment_level1" },
                { data: "outstanding" },
                { data: "last_payment_type" },
                { data: "name" },
                { data: "bendera" },
            ],
            columnDefs: [
                // {
                //     // For Responsive
                //     className: "control",
                //     orderable: false,
                //     searchable: false,
                //     responsivePriority: 2,
                //     targets: 0,
                //     render: function (data, type, full, meta) {
                //         return "";
                //     },
                // },
                // {
                //     targets: 0,
                //     searchable: true,
                //     visible: false,
                // },
                {
                    responsivePriority: 1,
                    targets: 0,
                },
                {
                    targets: 0,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("payment_detail.invoice", id);
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
                    targets: [4, 5, 6],
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
                    targets: 7,
                    render: function (data, type, full, row) {
                        var type = full["last_payment_type"];
                        var overdue = full["last_overdue"] ?? "No";
                        var paytot = full["total_payment"];
                        var title, label;
                        if (type == "CBD" || type == "COD" || type == "BP") {
                            title = "Full Paid";
                            label = "bg-label-success";
                        } else if (type == "DP") {
                            title = "Partial";
                            label = "bg-label-warning";
                        } else if (type == "Tempo") {
                            title = "Credit " + overdue + " Days";
                            label = "bg-label-primary";
                        } else {
                            title = "Unpaid";
                            label = "bg-label-danger";
                        }
                        return (
                            '<span class="badge rounded-pill ' +
                            label +
                            '">' +
                            title +
                            "</span>"
                        );
                    },
                },
            ],
            order: [],
            // orderCellsTop: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_sales_invoice_rayi.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
