$(function () {

    var BASE_URL = 'db/purchase-request';

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function prLinkCol(data, type, full) {
        if (type !== 'display') return data;
        var detailRoute = route('purchase-request.show', full.id);
        var text = data ? escapeHtml(data) : '-';
        return '<a class="fw-semibold text-primary" href="' + detailRoute + '">' + text + '</a>';
    }

    function dateCol(data, type) {
        if (type !== 'display') return data;
        if (!data) return '-';
        var m = moment(data);
        return m.isValid() ? m.format('DD-MM-YYYY') : data;
    }

    function noteAvatarCol(data, type, full) {
        if (type !== 'display') return data || '';
        var img = full.user_image ? '/' + escapeHtml(full.user_image) : '/assets/img/avatars/1.png';
        var name = full.user_name ? escapeHtml(full.user_name) : 'Unknown';
        return (
            '<img src="' + img + '" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" ' +
            'data-bs-toggle="tooltip" data-bs-placement="top" title="' + name + '">'
        );
    }

    // ── Factory: init one DataTable ────────────────────────────────────────
    function makeTable(selector, endpoint, columns, columnDefs, order, noSearchCols) {
        noSearchCols = noSearchCols || [];
        var $t = $(selector);
        if (!$t.length) return null;

        $t.find('thead tr').clone(true).appendTo($t.find('thead'));
        $t.find('thead tr:eq(1) th').each(function (i) {
            if (noSearchCols.indexOf(i) !== -1) {
                $(this).html('');
                return;
            }
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Search ' +
                    title +
                    '..." />'
            );
        });

        var dt = $t.DataTable({
            ajax: { type: 'GET', url: BASE_URL + '/' + endpoint, headers: { 'Content-Type': 'application/json' } },
            columns: columns,
            columnDefs: columnDefs,
            orderCellsTop: true,
            order: order || [[2, 'desc']],
            dom:
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>' +
                '<"table-responsive"t>' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [10, 25, 50],
            pageLength: 10,
            language: { emptyTable: 'Tidak ada data.' },
        });

        $t.find('thead tr:eq(1) th').each(function (i) {
            if (noSearchCols.indexOf(i) !== -1) return;
            $('input', this).on('keyup change', function () {
                if (dt.column(i).search() !== this.value) {
                    dt.column(i).search(this.value).draw();
                }
            });
        });

        dt.on('draw', function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        return dt;
    }

    // ── Column definitions ─────────────────────────────────────────────────
    // Order: No PR, No PO, No SO, Customer, Item, Qty, Date, Note[, ...extra]
    var BASE_COLS = [
        { data: 'no_pr' },
        { data: 'no_po' },
        { data: 'no_pending' },
        { data: 'company' },
        { data: 'item' },
        { data: 'qty_full' },
        { data: 'date' },
        { data: 'note' },
    ];

    var BASE_DEFS = [
        { targets: 0, render: prLinkCol },
        { targets: 6, render: dateCol },
        { targets: 7, render: noteAvatarCol, className: 'text-center' },
    ];

    var DELIVERY_COLS = [
        { data: 'no_pr' },
        { data: 'no_po' },
        { data: 'no_pending' },
        { data: 'company' },
        { data: 'item' },
        { data: 'qty_full' },
        { data: 'date' },
        { data: 'note' },
        { data: 'purchase_type' },
        { data: 'cargo' },
        { data: 'no_resi' },
        { data: 'purchase_date' },
    ];

    // Column index of the avatar "Sign" column — same position in all 4 tables
    var SIGN_COL_INDEX = 7;

    // ── Tab → table config map ──────────────────────────────────────────────
    var TAB_CONFIG = {
        'navs-pills-top-new': {
            selector: '.datatable-purchase-request-new',
            endpoint: 'new',
            columns: BASE_COLS,
            defs: BASE_DEFS,
        },
        'navs-pills-top-acc': {
            selector: '.datatable-purchase-request-acc',
            endpoint: 'acc',
            columns: BASE_COLS,
            defs: BASE_DEFS,
        },
        'navs-pills-top-delivery': {
            selector: '.datatable-purchase-request-delivery',
            endpoint: 'delivery',
            columns: DELIVERY_COLS,
            defs: BASE_DEFS,
        },
        'navs-pills-top-done': {
            selector: '.datatable-purchase-request-done',
            endpoint: 'done',
            columns: BASE_COLS,
            defs: BASE_DEFS,
        },
    };

    var initialized = {};

    function initTab(tabId) {
        if (initialized[tabId]) return;
        var cfg = TAB_CONFIG[tabId];
        if (!cfg) return;
        var noSearchCols = cfg.noSearchCols || [SIGN_COL_INDEX];
        var dt = makeTable(cfg.selector, cfg.endpoint, cfg.columns, cfg.defs, null, noSearchCols);
        initialized[tabId] = dt || true;
    }

    // Init whichever tab-pane is active on first load (role-dependent default tab)
    $('.tab-content > .tab-pane.active').each(function () {
        initTab(this.id);
    });

    // Lazy-init the rest only when their tab is actually opened
    $('#purchaseRequestTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var targetId = $(e.target).attr('data-bs-target').replace('#', '');
        initTab(targetId);
    });

});
