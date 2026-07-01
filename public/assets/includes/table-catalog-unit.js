$(function () {
    var dt_table = $(".datatable-catalog-unit");
    var Url = "/db/catalog-unit";

    if (!dt_table.length) return;

    dt_table.find("thead tr").clone(true).appendTo(dt_table.find("thead"));

    dt_table.find("thead tr:eq(1) th").each(function (i) {
        var title = $(this).text();
        $(this).html(
            '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
        );
        $("input", this).on("keyup change", function () {
            if (dt_catalog.column(i).search() !== this.value) {
                dt_catalog.column(i).search(this.value).draw();
            }
        });
    });

    var dt_catalog = dt_table.DataTable({
        ajax: {
            type: "GET",
            url: Url,
            headers: { "Content-Type": "application/json" },
        },
        columns: [
            { data: "sku" },
            { data: "brand" },
            { data: "model" },
            { data: "price_idr" },
            { data: "air_cap" },
            { data: "bar" },
            { data: "power" },
            { data: "is_active" },
        ],
        columnDefs: [
            {
                targets: 0,
                className: "text-nowrap fw-semibold",
                render: function (data, type, full) {
                    if (type !== "display") return data;
                    return '<a href="/catalog-unit/' + full.id + '" class="text-primary fw-bold">' + (data || "-") + "</a>";
                },
            },
            { targets: 1, render: function (data) { return data || "-"; } },
            { targets: 2, render: function (data) { return data || "-"; } },
            {
                targets: 3,
                className: "text-end",
                render: function (data, type) {
                    if (type !== "display") return data;
                    var n = parseInt(data);
                    var fmt = String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return '<div class="d-flex justify-content-between px-2"><span>Rp.</span><span>' + fmt + "</span></div>";
                },
            },
            {
                targets: 4,
                className: "text-center",
                render: function (data) { return data ? data + " m³/min" : "-"; },
            },
            {
                targets: 5,
                className: "text-center",
                render: function (data) { return data ? data + " Bar" : "-"; },
            },
            {
                targets: 6,
                className: "text-center",
                render: function (data) { return data || "-"; },
            },
            {
                targets: 7,
                className: "text-center",
                orderable: false,
                render: function (data) {
                    return parseInt(data) === 1
                        ? '<span class="badge bg-label-success">Active</span>'
                        : '<span class="badge bg-label-secondary">Inactive</span>';
                },
            },
        ],
        orderCellsTop: true,
        order: [[1, "asc"]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        lengthMenu: [10, 25, 50, 100],
        displayLength: 25,
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
});
