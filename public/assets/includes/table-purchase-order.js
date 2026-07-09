$(function () {
    var dt_table_purchase_order = $(".datatable-purchase-order");
    var Url = "/db/purchase-order";

    if (dt_table_purchase_order.length) {
        dt_table_purchase_order.find("thead tr")
            .clone(true)
            .appendTo(dt_table_purchase_order.find("thead"));

        dt_table_purchase_order.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_purchase_order.column(i).search() !== this.value) {
                    dt_purchase_order.column(i).search(this.value).draw();
                }
            });
        });

        var dt_purchase_order = dt_table_purchase_order.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "no_po" },
                { data: "company" },
                { data: "attn" },
                { data: "total" },
                { data: "date" },
                { data: "payment" },
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-nowrap",
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var detailRoute = route("purchase.show", full["id"]);
                        return '<a class="fw-semibold text-primary" href="' + detailRoute + '">' + (data || "-") + "</a>";
                    },
                },
                {
                    targets: [3, 4, 5],
                    className: "text-center",
                },
                {
                    targets: 3,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        var formatted = parseInt(data || 0).toLocaleString("id-ID");
                        return '<div class="d-flex justify-content-between px-2"><span>Rp.</span><span>' + formatted + "</span></div>";
                    },
                },
                {
                    targets: 4,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? moment(data).format("DD-MM-YYYY") : "-";
                    },
                },
            ],
            orderCellsTop: true,
            order: [[4, "desc"]],
            dom: '<"row align-items-center mb-2"<"col-auto"l><"col-auto ms-auto dt-btn-po">><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [10, 25, 50, 100],
            displayLength: 10,
            initComplete: function () {
                $(".dt-btn-po").html(
                    '<a href="' + route("purchase.create") + '" class="btn btn-primary btn-sm">' +
                        '<i class="mdi mdi-plus me-1"></i>New Purchase Order</a>'
                );
            },
        });

        dt_table_purchase_order.on("draw.dt", function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    }
});
