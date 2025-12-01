$(function () {
    var dt_table_payable_data = $(".datatable-payable-data");
    var Url = "db/payable/data";

    if (dt_table_payable_data.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-payable-data thead tr")
            .clone(true)
            .appendTo(".datatable-payable-data thead");
        $(".datatable-payable-data thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_payable_data.DataTable({
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
                { data: "date" },
                { data: "memo" },
                { data: "no_voucher" },
                { data: "no_cheque" },
                { data: "amount" },
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
                    targets: 1,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var $dataId = full["id"];
                            var detailRoute = route("payable.show", $dataId);
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
                {
                    targets: 4,
                    render: $.fn.dataTable.render.number(".", "", 0, "Rp."),
                },
            ],
            order: [[2, "desc"]],
            // orderCellsTop: true,
            dom:
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"table-responsive"t>' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Payable</span>',
                    className: "btn btn-primary btn-new",
                    action: function (e, dt, node, config) {
                        window.location = route("payable.create");
                    },
                },
                // {
                //     text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Payable</span>',
                //     className: "btn btn-primary",
                //     attr: {
                //         href: "{{ route('payable.create') }}",
                //     },
                // },
            ],
        });
    }
    dt_table_payable_data.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
