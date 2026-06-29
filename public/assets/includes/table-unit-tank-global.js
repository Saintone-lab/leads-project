$(function () {
    var dt_table_tank = $(".datatable-unit-tank");
    var Url = "/db/unit/global/tank";

    if (dt_table_tank.length) {
        dt_table_tank.find("thead tr")
            .clone(true)
            .appendTo(dt_table_tank.find("thead"));

        dt_table_tank.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_tank.column(i).search() !== this.value) {
                    dt_tank.column(i).search(this.value).draw();
                }
            });
        });

        var dt_tank = dt_table_tank.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "sku" },
                { data: "capacity" },
                { data: "material" },
                { data: "dimension" },
                { data: "bar" },
                { data: "test_pressure" },
                { data: "type_unit" },
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
                    targets: 1,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? data + " Liter" : "-";
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
                        return data ? data + " Bar" : "-";
                    },
                },
                {
                    targets: [2, 3, 6],
                    render: function (data) {
                        return data || "-";
                    },
                },
            ],
            orderCellsTop: true,
            order: [],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [10, 25, 50, 100],
            displayLength: 10,
        });

        $("#btn-tab-tank").on("shown.bs.tab", function () {
            dt_tank.columns.adjust();
        });
    }
});
