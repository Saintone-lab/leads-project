$(function () {
    var Url = "/db/fixed-asset";

    function codeColumn() {
        return {
            targets: 0,
            render: function (data, type, full) {
                if (type === "display") {
                    var detailRoute = route("fixed.show", full["id"]);
                    var label = data ? data : "#" + full["id"];
                    return '<a class="text-black" href="' + detailRoute + '">' + label + "</a>";
                }
                return data;
            },
        };
    }

    function addPerColumnSearch($table, dt) {
        $table
            .find("thead tr")
            .clone(true)
            .appendTo($table.find("thead"));
        $table
            .find("thead tr:eq(1) th")
            .each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                $("input", this).on("keyup change", function () {
                    if (dt.column(i).search() !== this.value) {
                        dt.column(i).search(this.value).draw();
                    }
                });
            });
    }

    var domLayout =
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>';

    // Tanah / Bangunan / Peralatan Kantor — kolom generik, difilter dari atribut data-type per tabel
    $(".datatable-fixed-generic").each(function () {
        var $table = $(this);
        var assetType = $table.data("type");

        var dt = $table.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                data: { type: assetType },
            },
            columns: [
                { data: "code" },
                { data: "desc" },
                { data: "qty" },
                { data: "total" },
                { data: "tanggal_beli" },
                { data: "tanggal_pakai" },
            ],
            columnDefs: [
                codeColumn(),
                { targets: 3, render: $.fn.dataTable.render.number(".", "", 0, "Rp ") },
            ],
            order: [[0, "desc"]],
            dom: domLayout,
        });
        addPerColumnSearch($table, dt);
    });

    // Kendaraan
    var $tableKendaraan = $(".datatable-fixed-kendaraan");
    if ($tableKendaraan.length) {
        var dtKendaraan = $tableKendaraan.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                data: { type: "Kendaraan" },
            },
            columns: [
                { data: "code" },
                { data: "jenis_kendaraan", render: function (d) { return d || "-"; } },
                { data: "merk_model", render: function (d) { return d || "-"; } },
                { data: "plat_nomor", render: function (d) { return d || "-"; } },
                { data: "atas_nama", render: function (d) { return d || "-"; } },
                { data: "tanggal_beli" },
                { data: "total" },
            ],
            columnDefs: [
                codeColumn(),
                { targets: 6, render: $.fn.dataTable.render.number(".", "", 0, "Rp ") },
            ],
            order: [[0, "desc"]],
            dom: domLayout,
        });
        addPerColumnSearch($tableKendaraan, dtKendaraan);
    }

    // Mesin
    var $tableMesin = $(".datatable-fixed-mesin");
    if ($tableMesin.length) {
        var dtMesin = $tableMesin.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                data: { type: "Mesin" },
            },
            columns: [
                { data: "code" },
                { data: "serial_number", render: function (d) { return d || "-"; } },
                { data: "kondisi", render: function (d) { return d || "-"; } },
                { data: "status_unit", render: function (d) { return d || "-"; } },
                { data: "tanggal_beli" },
                { data: "total" },
            ],
            columnDefs: [
                codeColumn(),
                { targets: 5, render: $.fn.dataTable.render.number(".", "", 0, "Rp ") },
            ],
            order: [[0, "desc"]],
            dom: domLayout,
        });
        addPerColumnSearch($tableMesin, dtMesin);
    }

    // Tools
    var $tableTools = $(".datatable-fixed-tools");
    if ($tableTools.length) {
        var dtTools = $tableTools.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                data: { type: "Tools" },
            },
            columns: [
                { data: "code" },
                { data: "nama_tools", render: function (d) { return d || "-"; } },
                { data: "teknisi_name", render: function (d) { return d || "-"; } },
                { data: "qty" },
                { data: "tanggal_serah_terima_fmt", render: function (d) { return d || "-"; } },
                {
                    data: "status_finance",
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data === "Lengkap"
                            ? '<span class="badge bg-label-success">Lengkap</span>'
                            : '<span class="badge bg-label-danger">Belum Lengkap</span>';
                    },
                },
                { data: "total" },
            ],
            columnDefs: [
                codeColumn(),
                { targets: 6, render: $.fn.dataTable.render.number(".", "", 0, "Rp ") },
            ],
            order: [[0, "desc"]],
            dom: domLayout,
        });
        addPerColumnSearch($tableTools, dtTools);
    }

    // Kolom lebar 0 di tab non-aktif waktu load — re-adjust pas tab-nya dibuka
    $('#fixed-asset-tab-nav button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        var target = $(e.target).data("bs-target");
        $(target)
            .find("table")
            .each(function () {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().columns.adjust();
                }
            });
    });

    // Badge jumlah item per tab
    $(document).on("draw.dt", function (e) {
        var $tbl = $(e.target);
        var badgeId = $tbl.data("badge");
        if (!badgeId) return;
        var api = $tbl.DataTable();
        $("#" + badgeId).text(api.page.info().recordsTotal);
    });
});
