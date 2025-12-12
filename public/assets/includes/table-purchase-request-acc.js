$(function () {
    var dt_table_purchase_request_acc = $(".datatable-purchase-request-acc");
    var Url = "db/purchase-request/acc";

    if (dt_table_purchase_request_acc.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-purchase-request-acc thead tr")
            .clone(true)
            .appendTo(".datatable-purchase-request-acc thead");
        $(".datatable-purchase-request-acc thead tr:eq(1) th").each(function (
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

        var dt_filter = dt_table_purchase_request_acc.DataTable({
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
                { data: "no_po" },
                { data: "no_pending" },
                { data: "item" },
                { data: "company" },
                { data: "qty_full" },
                { data: "note" },
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
                            var detailRoute = route(
                                "purchase-request.show",
                                $dataId
                            );
                            var text = data ? data : "Belum ada invoice";

                            return (
                                '<a class="text-dark" href="' +
                                detailRoute +
                                '">' +
                                text +
                                "</a>"
                            );
                        }
                        return data;
                    },
                },
            ],
            order: [[2, "desc"]],
            // orderCellsTop: true,
            dom:
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"table-responsive"t>' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                // {
                //     text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add acc Payable</span>',
                //     className: "btn btn-primary",
                //     attr: {
                //         href: "{{ route('payable.create') }}",
                //     },
                // },
            ],
        });
    }
    dt_table_purchase_request_acc.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
