$(function () {
    var dt_table_purchase_order = $(".datatable-purchase-order");
    var Url = "/db/purchase-order";

    if (dt_table_purchase_order.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-purchase-order thead tr")
            .clone(true)
            .appendTo(".datatable-purchase-order thead");
        $(".datatable-purchase-order thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_purchase_order.DataTable({
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
                { data: "tanggal" },
                {
                    data: "no_po",
                },
                { data: "company" },
                { data: "attn" },
                { data: "total" },
                { data: "payment" },
            ],
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("purchase.show", id);
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
                    targets: 4,
                    render: $.fn.dataTable.render.number(".", "", 0, "Rp "),
                },
            ],
            order: [[1, "desc"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            button: [
                {
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Fixed Asset</span>',
                    className: "btn btn-primary btn-new",
                    action: function (e, dt, node, config) {
                        window.location = route("purchase-order.create");
                    },
                },
            ],
        });
    }
    dt_table_purchase_order.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
