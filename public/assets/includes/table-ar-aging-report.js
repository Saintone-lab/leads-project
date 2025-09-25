$(function () {
    var dt_table_aging_report_ar = $(".datatable-aging-report-ar");
    var Url = "/db/aging/report/ar";

    if (dt_table_aging_report_ar.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-aging-report-ar thead tr")
            .clone(true)
            .appendTo(".datatable-aging-report-ar thead");
        $(".datatable-aging-report-ar thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_aging_report_ar.DataTable({
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
                { data: "no_invoice" },
                { data: "date" },
                { data: "no_po" },
                { data: "company" },
                { data: "harga_total" },
                { data: "due_date" },
                { data: "tax" },
                { data: "due_status" },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("payment_detail.aging", id);
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
                    targets: 6,
                    render: function (data, type, row) {
                        var vat;
                        if (data == 11) {
                            vat = "VAT";
                        }else{
                            vat = "Non VAT";
                        }
                        return vat;
                    },
                }
            ],
            order: [[1, "desc"]],
            // orderCellsTop: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
    dt_table_aging_report_ar.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
