$(function () {

    var BASE_URL = '/db/catalog-unit';

    function fmtRupiah(n) {
        var s = String(parseInt(n) || 0);
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    var COMMON_DEFS = {
        skuCol: function (full) {
            return '<a href="/catalog-unit/' + full.id + '" class="text-primary fw-bold">' + (full.sku || '-') + '</a>';
        },
        idrCol: function (data) {
            return '<div class="d-flex justify-content-between px-1"><span>Rp.</span><span>' + fmtRupiah(data) + '</span></div>';
        },
    };

    // ── Factory: init one DataTable ────────────────────────────────────────
    function makeTable(selector, urlParams, columns, columnDefs, order) {
        var $t = $(selector);
        if (!$t.length) return null;

        $t.find('thead tr').clone(true).appendTo($t.find('thead'));
        $t.find('thead tr:eq(1) th').each(function (i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="' + title + '...">');
        });

        var url = BASE_URL + (urlParams ? '?' + $.param(urlParams) : '');

        var dt = $t.DataTable({
            ajax: { type: 'GET', url: url, headers: { 'Content-Type': 'application/json' } },
            columns: columns,
            columnDefs: columnDefs,
            orderCellsTop: true,
            order: order || [[0, 'asc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [10, 25, 50],
            pageLength: 25,
            language: { emptyTable: 'No units in catalog.' },
        });

        $t.find('thead tr:eq(1) th').each(function (i) {
            $('input', this).on('keyup change', function () {
                if (dt.column(i).search() !== this.value) {
                    dt.column(i).search(this.value).draw();
                }
            });
        });

        return dt;
    }

    // ── Column definitions ─────────────────────────────────────────────────

    var OIL_INJECTED_COLS = [
        { data: 'sku' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'generation' },
        { data: 'price_idr' },
        { data: 'air_cap' },
        { data: 'bar' },
        { data: 'power' },
    ];

    var OIL_INJECTED_DEFS = [
        { targets: 0, render: function (d, t, f) { return t !== 'display' ? d : COMMON_DEFS.skuCol(f); } },
        { targets: 1, render: function (d) { return d || '-'; } },
        { targets: 2, render: function (d) { return d || '-'; } },
        {
            targets: 3, className: 'text-center',
            render: function (d, t) {
                if (t !== 'display') return d || '';
                if (d === 'old') return '<span class="badge bg-label-primary">Old Model</span>';
                if (d === 'new') return '<span class="badge bg-label-warning">New Model</span>';
                return '<span class="text-muted">-</span>';
            },
        },
        { targets: 4, render: function (d, t) { return t !== 'display' ? d : COMMON_DEFS.idrCol(d); } },
        { targets: 5, className: 'text-center', render: function (d) { return d ? d + ' m³/min' : '-'; } },
        { targets: 6, className: 'text-center', render: function (d) { return d ? d + ' Bar' : '-'; } },
        { targets: 7, className: 'text-center', render: function (d) { return d || '-'; } },
    ];

    var OIL_FREE_COLS = [
        { data: 'sku' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'price_idr' },
        { data: 'air_cap' },
        { data: 'bar' },
        { data: 'power' },
    ];

    var OIL_FREE_DEFS = [
        { targets: 0, render: function (d, t, f) { return t !== 'display' ? d : COMMON_DEFS.skuCol(f); } },
        { targets: 1, render: function (d) { return d || '-'; } },
        { targets: 2, render: function (d) { return d || '-'; } },
        { targets: 3, render: function (d, t) { return t !== 'display' ? d : COMMON_DEFS.idrCol(d); } },
        { targets: 4, className: 'text-center', render: function (d) { return d ? d + ' m³/min' : '-'; } },
        { targets: 5, className: 'text-center', render: function (d) { return d ? d + ' Bar' : '-'; } },
        { targets: 6, className: 'text-center', render: function (d) { return d || '-'; } },
    ];

    var REF_DRYER_COLS = [
        { data: 'sku' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'price_idr' },
        { data: 'air_cap' },
        { data: 'voltage' },
        { data: 'exhaust' },
    ];

    var REF_DRYER_DEFS = [
        { targets: 0, render: function (d, t, f) { return t !== 'display' ? d : COMMON_DEFS.skuCol(f); } },
        { targets: 1, render: function (d) { return d || '-'; } },
        { targets: 2, render: function (d) { return d || '-'; } },
        { targets: 3, render: function (d, t) { return t !== 'display' ? d : COMMON_DEFS.idrCol(d); } },
        { targets: 4, className: 'text-center', render: function (d) { return d ? d + ' m³/min' : '-'; } },
        { targets: 5, className: 'text-center', render: function (d) { return d || '-'; } },
        { targets: 6, className: 'text-center', render: function (d) { return d || '-'; } },
    ];

    var DESICCANT_COLS = [
        { data: 'sku' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'price_idr' },
        { data: 'air_cap' },
        { data: 'voltage' },
    ];

    var DESICCANT_DEFS = [
        { targets: 0, render: function (d, t, f) { return t !== 'display' ? d : COMMON_DEFS.skuCol(f); } },
        { targets: 1, render: function (d) { return d || '-'; } },
        { targets: 2, render: function (d) { return d || '-'; } },
        { targets: 3, render: function (d, t) { return t !== 'display' ? d : COMMON_DEFS.idrCol(d); } },
        { targets: 4, className: 'text-center', render: function (d) { return d ? d + ' m³/min' : '-'; } },
        { targets: 5, className: 'text-center', render: function (d) { return d || '-'; } },
    ];

    var FILTRATION_COLS = [
        { data: 'sku' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'price_idr' },
        { data: 'exhaust' },
        { data: 'filtration' },
        { data: 'oil_content' },
        { data: 'grade' },
    ];

    var FILTRATION_DEFS = [
        { targets: 0, render: function (d, t, f) { return t !== 'display' ? d : COMMON_DEFS.skuCol(f); } },
        { targets: 1, render: function (d) { return d || '-'; } },
        { targets: 2, render: function (d) { return d || '-'; } },
        { targets: 3, render: function (d, t) { return t !== 'display' ? d : COMMON_DEFS.idrCol(d); } },
        { targets: [4, 5, 6, 7], className: 'text-center', render: function (d) { return d || '-'; } },
    ];

    var TANK_COLS = [
        { data: 'sku' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'price_idr' },
        { data: 'capacity' },
        { data: 'bar' },
        { data: 'type_unit' },
    ];

    var TANK_DEFS = [
        { targets: 0, render: function (d, t, f) { return t !== 'display' ? d : COMMON_DEFS.skuCol(f); } },
        { targets: 1, render: function (d) { return d || '-'; } },
        { targets: 2, render: function (d) { return d || '-'; } },
        { targets: 3, render: function (d, t) { return t !== 'display' ? d : COMMON_DEFS.idrCol(d); } },
        { targets: 4, className: 'text-center', render: function (d) { return d ? d + ' Liter' : '-'; } },
        { targets: 5, className: 'text-center', render: function (d) { return d ? d + ' Bar' : '-'; } },
        { targets: 6, className: 'text-center', render: function (d) { return d || '-'; } },
    ];

    // ── Init Oil-Injected (default tab — loads on page ready) ─────────────
    var dtOilInjected = makeTable(
        '#table-oil-injected',
        { category: 'AIR COMPRESSOR SCREW', type_unit: 'Oil-injected' },
        OIL_INJECTED_COLS, OIL_INJECTED_DEFS, [[1, 'asc']]
    );

    // Generation filter buttons
    $(document).on('click', '.btn-gen-filter', function () {
        if (!dtOilInjected) return;
        $('.btn-gen-filter').removeClass('active');
        $(this).addClass('active');
        var gen = $(this).data('gen');
        var params = { category: 'AIR COMPRESSOR SCREW', type_unit: 'Oil-injected' };
        if (gen) params.generation = gen;
        dtOilInjected.ajax.url(BASE_URL + '?' + $.param(params)).load();
    });

    // ── Lazy init per tab ─────────────────────────────────────────────────
    var dtOilFree = null;
    $('button[data-bs-target="#tab-oil-free"]').on('shown.bs.tab', function () {
        if (dtOilFree) return;
        dtOilFree = makeTable(
            '#table-oil-free',
            { category: 'AIR COMPRESSOR SCREW', type_unit: 'Oil-free Compressor' },
            OIL_FREE_COLS, OIL_FREE_DEFS, [[1, 'asc']]
        );
    });

    // Dryer tab: init Refrigerant sub-tab on first open
    var dtRefDryer = null;
    $('button[data-bs-target="#tab-main-dryer"]').on('shown.bs.tab', function () {
        if (dtRefDryer) return;
        dtRefDryer = makeTable(
            '#table-ref-dryer',
            { category: 'REFRIGERANT AIR DRYER' },
            REF_DRYER_COLS, REF_DRYER_DEFS, [[1, 'asc']]
        );
    });

    // Desiccant sub-tab: lazy init on first open
    var dtDesiccant = null;
    $('button[data-bs-target="#tab-desiccant"]').on('shown.bs.tab', function () {
        if (dtDesiccant) return;
        dtDesiccant = makeTable(
            '#table-desiccant',
            { category: 'DESICANT DRYER' },
            DESICCANT_COLS, DESICCANT_DEFS, [[1, 'asc']]
        );
    });

    var dtFiltration = null;
    $('button[data-bs-target="#tab-main-filtration"]').on('shown.bs.tab', function () {
        if (dtFiltration) return;
        dtFiltration = makeTable(
            '#table-filtration',
            { category: 'FILTRATION SYSTEM' },
            FILTRATION_COLS, FILTRATION_DEFS, [[1, 'asc']]
        );
    });

    var dtTank = null;
    $('button[data-bs-target="#tab-main-tank"]').on('shown.bs.tab', function () {
        if (dtTank) return;
        dtTank = makeTable(
            '#table-tank',
            { category: 'AIR RECEIVER TANK' },
            TANK_COLS, TANK_DEFS, [[1, 'asc']]
        );
    });

});
