$(function () {
    var dt_table_fixed_asset = $(".datatable-fixed-asset");
    var Url = "/db/fixed-asset";

    if (dt_table_fixed_asset.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-fixed-asset thead tr")
            .clone(true)
            .appendTo(".datatable-fixed-asset thead");
        $(".datatable-fixed-asset thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_fixed_asset.DataTable({
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
                { data: "code" },
                {
                    data: "desc",
                },
                { data: "qty" },
                { data: "total" },
                { data: "umur" },
                { data: "tanggal_beli" },
                { data: "tanggal_pakai" },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var id = full["id"];
                            detailRoute = route("fixed.show", id);
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
                        window.location = route("fixed-asset.create");
                    },
                },
            ],
        });
    }
    dt_table_fixed_asset.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
