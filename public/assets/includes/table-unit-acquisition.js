$(function () {
    var dt_table_unit_acquisition = $(".datatable-unit-acquisition");
    var Url = "/db/unit-acquisition";

    if (dt_table_unit_acquisition.length) {
        var dt_filter = dt_table_unit_acquisition.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: {
                    "Content-Type": "application/json",
                },
            },
            columns: [
                { data: "tanggal_beli" },
                { data: "code" },
                { data: "unit_brand" },
                { data: "kondisi" },
                { data: "supplier_name" },
                { data: "total" },
                { data: "qc_status" },
                { data: "id" },
            ],
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, full, row) {
                        return '<a href="/unit-acquisition/' + full["id"] + '">' + full["code"] + "</a>";
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, row) {
                        var brand = full["unit_brand"] || "-";
                        var model = full["unit_model"] || "";
                        return brand + (model ? " " + model : "");
                    },
                },
                {
                    targets: 3,
                    render: function (data, type, full, row) {
                        return full["kondisi"] === "Baru" ? "Unit Baru" : "Unit Second";
                    },
                },
                {
                    targets: 4,
                    render: function (data, type, full, row) {
                        return full["supplier_name"] || "-";
                    },
                },
                {
                    targets: 5,
                    render: $.fn.dataTable.render.number(".", "", 0, "Rp."),
                },
                {
                    targets: 6,
                    render: function (data, type, full, row) {
                        if (full["qc_status"] === "ok") {
                            return '<span class="badge bg-label-success">OK</span>';
                        }
                        if (full["qc_status"] === "reject") {
                            return '<span class="badge bg-label-danger">Reject</span>';
                        }
                        return '<span class="badge bg-label-warning">Dalam Pengecekan</span>';
                    },
                },
                {
                    targets: -1,
                    render: function (data, type, full, row) {
                        return '<a href="/unit-acquisition/' + full["id"] + '" class="btn btn-sm btn-outline-primary">Detail</a>';
                    },
                },
            ],
            order: [[0, "desc"]],
            dom:
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"table-responsive"t>' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        });
    }
});
