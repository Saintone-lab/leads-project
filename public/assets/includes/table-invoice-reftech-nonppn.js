$(function () {
    var dt_table_invoice_nonppn = $(".datatable-invoice-nonppn");
    var Url = "/db/invoice/nonppn/reftech";

    if (dt_table_invoice_nonppn.length) {
        // Clone header row lalu replace isinya dengan input search
        dt_table_invoice_nonppn.find("thead tr")
            .clone(true)
            .appendTo(dt_table_invoice_nonppn.find("thead"));

        dt_table_invoice_nonppn.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_invoice_nonppn.column(i).search() !== this.value) {
                    dt_invoice_nonppn.column(i).search(this.value).draw();
                }
            });
        });

        var dt_invoice_nonppn = dt_table_invoice_nonppn.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
                data: function (d) {
                    d.year = $("#invoice-year-filter").val();
                },
            },
            columns: [
                { data: "no_invoice" },
                { data: "no_po" },
                { data: "company" },
                {
                    data: "type",
                    render: function (data, type) {
                        if (type !== "display") return data;
                        var map = {
                            "DP": '<span class="badge bg-label-warning">DP</span>',
                            "BP": '<span class="badge bg-label-info">BP</span>',
                            "CT": '<span class="badge bg-label-success">Full Payment</span>',
                        };
                        return map[data] || '<span class="badge bg-label-secondary">' + data + '</span>';
                    },
                },
                { data: "harga_total" },
                {
                    data: "po_date",
                    render: function (data, type) {
                        if (type === "display") {
                            return data ? moment(data).format("DD-MM-YYYY") : "-";
                        }
                        return data;
                    },
                },
                { data: "name" },
            ],
            columnDefs: [
                {
                    responsivePriority: 1,
                    targets: 0,
                    className: "text-nowrap",
                },
                {
                    targets: 0,
                    render: function (data, type, full) {
                        if (type === "display") {
                            var detailRoute = full["source"] === "unit"
                                ? route("invoice.show_unit", full["id"])
                                : route("invoice.show", full["id"]);
                            return '<a class="fw-bold text-primary" href="' + detailRoute + '">' + data + "</a>";
                        }
                        return data;
                    },
                },
                {
                    targets: 3,
                    className: "text-center",
                },
                {
                    targets: 4,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        var formatted = parseInt(data).toLocaleString("id-ID");
                        return '<div class="d-flex justify-content-between"><span>Rp.</span><span>' + formatted + '</span></div>';
                    },
                },
                {
                    targets: 5,
                    className: "text-center",
                },
                {
                    targets: 6,
                    className: "text-center",
                },
            ],
            orderCellsTop: true,
            order: [[0, "desc"]],
            dom: '<"card-header flex-column flex-md-row"<"head-label hl-2 head-invoice text-center">><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [7, 10, 25, 50, 75, 100],
            displayLength: 10,
        });

        $("div.hl-2.head-invoice").html('<h5 class="card-title mb-0">Table Invoice Reftech Non PPN</h5>');

        // Reload saat year filter di tabel PPN berubah
        $(document).on("change", "#invoice-year-filter", function () {
            dt_invoice_nonppn.ajax.reload();
        });
    }
});
