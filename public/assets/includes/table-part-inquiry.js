$(function () {
    var dt_table = $(".datatable-part-inquiry");
    var Url = "/db/part-inquiry";

    if (dt_table.length) {
        $('[data-toggle="tooltip"]').tooltip();

        $(".datatable-part-inquiry thead tr")
            .clone(true)
            .appendTo(".datatable-part-inquiry thead");
        $(".datatable-part-inquiry thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control" placeholder="Search ' + title + '" />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_filter.column(i).search() !== this.value) {
                    dt_filter.column(i).search(this.value).draw();
                }
            });
        });

        var dt_filter = dt_table.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "sku" },
                { data: "brand" },
                { data: "pn" },
                { data: "selling_price" },
                { data: "cheapest_vendor" },
                { data: "min_price_idr" },
                { data: "last_inquiry" },
            ],
            columnDefs: [
                {
                    responsivePriority: 1,
                    targets: 0,
                    render: function (data, type, full) {
                        if (type === "display") {
                            return '<a href="/part-inquiry/' + full.serial_id + '">' + data + '</a>';
                        }
                        return data;
                    },
                },
                {
                    targets: 2,
                    render: function (data, type) {
                        if (type !== 'display') return data;
                        if (!data || data === '-') {
                            return '<span class="badge bg-label-warning">PN Pending</span>';
                        }
                        return data;
                    },
                },
                {
                    targets: 3,
                    searchable: false,
                    render: function (data, type, row) {
                        if (type === "display" || type === "filter") {
                            return '<div class="text-end">Rp ' + new Intl.NumberFormat("id-ID").format(data) + '</div>';
                        }
                        return data;
                    },
                },
                {
                    targets: 4,
                    searchable: false,
                    render: function (data) {
                        return data ? data : '<span class="text-muted">-</span>';
                    },
                },
                {
                    targets: 5,
                    searchable: false,
                    render: function (data, type, full) {
                        if (!full.vendor_count || full.vendor_count == 0) return '<span class="text-muted">-</span>';
                        var price = new Intl.NumberFormat("id-ID").format(data || 0);
                        return 'Rp ' + price + ' <span class="badge bg-label-primary ms-1">' + full.vendor_count + ' vendor</span>';
                    },
                },
                {
                    targets: 6,
                    searchable: false,
                    render: function (data) {
                        if (!data) return '-';
                        var parts = data.split('-');
                        return parts[2] + '-' + parts[1] + '-' + parts[0];
                    },
                },
            ],
            order: [[6, "desc"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }

    dt_table.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
