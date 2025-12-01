$(function () {
    var dt_table_account_data = $(".datatable-account-data");
    var Url = "db/account/data";

    if (dt_table_account_data.length) {
        $('[data-toggle="tooltip"]').tooltip();
        // Setup - add a text input to each footer cell
        $(".datatable-account-data thead tr")
            .clone(true)
            .appendTo(".datatable-account-data thead");
        $(".datatable-account-data thead tr:eq(1) th").each(function (i) {
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

        var dt_filter = dt_table_account_data.DataTable({
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
                { data: "name" },
                { data: "category" },
                { data: "id" },
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
                    targets: -1,
                    render: function (data, type, full, row) {
                        var id = full["id"];
                        return (
                            '<a href="#" data-id="' +
                            id +
                            '" class="btn btn-sm btn-label-danger delete-account m-2"><i class="menu-icon tf-icons mdi mdi-14px mdi-delete-outline m-0"></i></a>'
                            // '<a type="button" href="#" data-bs-toggle="modal" data-bs-target="#updatePic-' +
                            // id +
                            // '" data-id="' +
                            // id +
                            // '" class="btn btn-sm btn-label-primary"><i class="menu-icon tf-icons mdi mdi-14px mdi-note-edit-outline m-0"></i></a>'
                        );
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
                {
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Account</span>',
                    className: "btn btn-primary",
                    attr: {
                        "data-bs-target": "#createAccount",
                        "data-bs-toggle": "modal",
                    },
                },
            ],
        });
    }
    dt_table_account_data.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
