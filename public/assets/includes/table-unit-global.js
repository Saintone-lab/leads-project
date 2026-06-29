$(function () {
    var dt_table_compressor = $(".datatable-unit-compressor");
    var Url = "/db/unit/global";

    if (dt_table_compressor.length) {
        dt_table_compressor.find("thead tr")
            .clone(true)
            .appendTo(dt_table_compressor.find("thead"));

        dt_table_compressor.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_compressor.column(i).search() !== this.value) {
                    dt_compressor.column(i).search(this.value).draw();
                }
            });
        });

        var dt_compressor = dt_table_compressor.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "sku" },
                { data: "brand" },
                { data: "type_unit" },
                { data: "power" },
                { data: "bar" },
                { data: "air_cap" },
                { data: "exhaust" },
            ],
            columnDefs: [
                {
                    targets: "_all",
                    className: "text-center",
                },
                {
                    responsivePriority: 1,
                    targets: 0,
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var url = route("unit-global.show", full["id_p"]);
                        return '<a class="fw-bold text-primary" href="' + url + '">' + (data || "-") + "</a>";
                    },
                },
                {
                    targets: 4,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? data + " Bar" : "-";
                    },
                },
                {
                    targets: 5,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? data + " m³/min" : "-";
                    },
                },
                {
                    targets: [1, 2, 3, 6],
                    render: function (data) {
                        return data || "-";
                    },
                },
                {
                    targets: 1,
                    className: "text-center text-nowrap",
                },
            ],
            orderCellsTop: true,
            order: [],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [10, 25, 50, 100],
            displayLength: 10,
        });

    }
});
