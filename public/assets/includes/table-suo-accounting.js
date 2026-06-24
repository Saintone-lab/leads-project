$(function () {
    var dt = $('.datatable-suo-accounting');
    if (!dt.length) return;

    var statusBadge = {
        confirmed: '<span class="badge bg-info">Perlu Approve</span>',
        goods_out: '<span class="badge bg-success">Barang Keluar</span>',
        converted: '<span class="badge bg-primary">Converted</span>',
    };

    dt.DataTable({
        ajax: { type: 'GET', url: route('suo.data.accounting') },
        columns: [
            { data: 'no_suo' },
            { data: 'company' },
            { data: 'pic' },
            { data: 'status' },
            { data: 'no_invoice_booking' },
            { data: 'created_at' },
            { data: 'id' },
        ],
        columnDefs: [
            {
                targets: 3,
                render: function (data) { return statusBadge[data] ?? data; },
            },
            {
                targets: 4,
                render: function (data) {
                    return data
                        ? '<span class="text-success fw-semibold">' + data + '</span>'
                        : '<span class="badge bg-label-warning">Belum diapprove</span>';
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
            {
                targets: 6,
                orderable: false,
                searchable: false,
                render: function (data) {
                    var url = route('suo.show', data);
                    return '<a href="' + url + '" class="btn btn-sm btn-icon btn-outline-info waves-effect" title="Detail"><i class="mdi mdi-eye-outline"></i></a>';
                },
            },
        ],
        order: [[5, 'desc']],
        dom:
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>' +
            '<"table-responsive"t>' +
            '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    });
});
