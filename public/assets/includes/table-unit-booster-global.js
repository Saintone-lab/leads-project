$(function () {
    var dt_table_booster = $(".datatable-unit-booster");
    var Url = "/db/unit/global/booster";

    if (dt_table_booster.length) {
        dt_table_booster.find("thead tr")
            .clone(true)
            .appendTo(dt_table_booster.find("thead"));

        dt_table_booster.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_booster.column(i).search() !== this.value) {
                    dt_booster.column(i).search(this.value).draw();
                }
            });
        });

        var dt_booster = dt_table_booster.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "sku" },
                { data: "brand" },
                { data: "model" },
                { data: "inlet_pressure" },
                { data: "outlet_pressure" },
                { data: "inlet_cap" },
                { data: "outlet_cap" },
                { data: "power" },
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
                    targets: 3,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? data + " Bar" : "-";
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
                    targets: 6,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? data + " m³/min" : "-";
                    },
                },
                {
                    targets: [1, 2, 7],
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

        $("#btn-tab-booster").on("shown.bs.tab", function () {
            dt_booster.columns.adjust();
        });
    }
});
