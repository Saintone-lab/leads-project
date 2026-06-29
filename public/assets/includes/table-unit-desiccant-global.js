$(function () {
    var dt_table_desiccant = $(".datatable-unit-desiccant");
    var Url = "/db/unit/global/desiccant";

    if (dt_table_desiccant.length) {
        dt_table_desiccant.find("thead tr")
            .clone(true)
            .appendTo(dt_table_desiccant.find("thead"));

        dt_table_desiccant.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_desiccant.column(i).search() !== this.value) {
                    dt_desiccant.column(i).search(this.value).draw();
                }
            });
        });

        var dt_desiccant = dt_table_desiccant.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "sku" },
                { data: "brand" },
                { data: "air_cap" },
                { data: "refrigerant_type" },
                { data: "pdp" },
                { data: "voltage" },
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
                    targets: 2,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? data + " m³/min" : "-";
                    },
                },
                {
                    targets: [1, 3, 4, 5, 6],
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

        $("#btn-tab-desiccant").on("shown.bs.tab", function () {
            dt_desiccant.columns.adjust();
        });
    }
});
