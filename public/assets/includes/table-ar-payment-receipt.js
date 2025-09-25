$(function () {
    var dt_table_payment_receipt_ar = $(".datatable-payment-receipt-ar");
    var Url = "/db/payment/receipt/ar";

    if (dt_table_payment_receipt_ar.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-payment-receipt-ar thead tr")
            .clone(true)
            .appendTo(".datatable-payment-receipt-ar thead");
        $(".datatable-payment-receipt-ar thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_payment_receipt_ar.DataTable({
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
                    targets: 0,
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
                    targets: [4, 5],
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
            order: [[0, "desc"]],
            // orderCellsTop: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_payment_receipt_ar.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
