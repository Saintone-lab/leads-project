$(function () {
    var dt_table = $(".datatable-unit-dryer-sales");
    var Url = "/db/sales/unit/global/dryer";

    if (dt_table.length) {
        dt_table.find("thead tr").clone(true).appendTo(dt_table.find("thead"));
        dt_table.find("thead tr:eq(1) th").each(function (i) {
            if (i < 3) { $(this).html(""); return; }
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />');
            $("input", this).on("keyup change", function () {
                if (dt_dryer_sales.column(i).search() !== this.value) {
                    dt_dryer_sales.column(i).search(this.value).draw();
                }
            });
        });

        var dt_dryer_sales = dt_table.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "" },
                { data: "id" },
                { data: "id" },
                { data: "sku" },
                { data: "brand" },
                { data: "model" },
                { data: "air_cap" },
                { data: "refrigerant_type" },
                { data: "pdp" },
                { data: "voltage" },
            ],
            columnDefs: [
                {
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () { return ""; },
                },
                {
                    targets: 1,
                    orderable: false,
                    searchable: false,
                    responsivePriority: 3,
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                    checkboxes: {
                        selectAllRender: '<input type="checkbox" class="form-check-input">',
                    },
                },
                { targets: 2, searchable: false, visible: false },
                {
                    responsivePriority: 1,
                    targets: 3,
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var url = route("unit-global.show", full["id_p"]);
                        return '<a class="fw-semibold text-dark" href="' + url + '">' + (data || "-") + "</a>";
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
                    targets: [4, 5, 7, 8, 9],
                    render: function (data) { return data || "-"; },
                },
            ],
            order: [[2, "desc"]],
            dom: '<"row px-3 pt-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>t<"row px-3 pb-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 15,
            lengthMenu: [15, 25, 50, 100],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) { return "Detail: " + row.data()["sku"]; },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col) {
                            return col.title !== ""
                                ? "<tr><td>" + col.title + ":</td><td>" + col.data + "</td></tr>"
                                : "";
                        }).join("");
                        return data ? $('<table class="table"/>').append("<tbody>" + data + "</tbody>") : false;
                    },
                },
            },
        });

        $('button[data-bs-target="#tab-dryer"]').on("shown.bs.tab", function () {
            dt_dryer_sales.columns.adjust().responsive.recalc();
        });
    }
});
