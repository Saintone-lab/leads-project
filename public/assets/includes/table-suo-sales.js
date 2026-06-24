$(function () {
    var dt = $('.datatable-suo-sales');
    if (!dt.length) return;

    var statusBadge = {
        draft:     '<span class="badge bg-secondary">Draft</span>',
        submitted: '<span class="badge bg-warning text-dark">Submitted</span>',
        confirmed: '<span class="badge bg-info">Confirmed</span>',
        goods_out: '<span class="badge bg-success">Goods Out</span>',
        converted: '<span class="badge bg-primary">Converted</span>',
    };

    var table = dt.DataTable({
        ajax: { type: 'GET', url: route('suo.data.sales') },
        columns: [
            { data: 'no_suo' },
            { data: 'company' },
            { data: 'pic' },
            { data: 'status' },
            { data: 'no_invoice_booking' },
            { data: 'created_at' },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full) {
                    var url = route('suo.show', full.id);
                    var badge = full.status === 'goods_out'
                        ? ' <span class="badge bg-danger rounded-pill" style="font-size:9px;">!</span>'
                        : '';
                    return '<a href="' + url + '" class="fw-semibold text-primary">' + (data ?? '-') + '</a>' + badge;
                },
            },
            {
                targets: 3,
                render: function (data) {
                    return statusBadge[data] ?? data;
                },
            },
            {
                targets: 4,
                render: function (data) {
                    return data ? '<span class="text-success fw-semibold">' + data + '</span>' : '<span class="text-muted">-</span>';
                },
            },
            {
                targets: 5,
                render: function (data) {
                    if (!data) return '-';
                    var d = new Date(data);
                    return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + d.getFullYear();
                },
            },
        ],
        order: [[5, 'desc']],
        dom:
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"B>>' +
            '<"table-responsive"t>' +
            '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [
            {
                text: '<i class="mdi mdi-plus me-1"></i> <span class="d-none d-sm-inline-block">Buat SUO</span>',
                className: 'btn btn-primary btn-sm',
                action: function () { window.location = route('suo.create'); },
            },
        ],
    });
});
