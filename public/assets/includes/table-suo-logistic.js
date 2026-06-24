$(function () {
    var dt = $('.datatable-suo-logistic');
    if (!dt.length) return;

    var statusBadge = {
        submitted: '<span class="badge bg-warning text-dark">Perlu Cek Stok</span>',
        confirmed: '<span class="badge bg-info">Sudah Dicek</span>',
        goods_out: '<span class="badge bg-success">Barang Keluar</span>',
        converted: '<span class="badge bg-primary">Converted</span>',
    };

    dt.DataTable({
        ajax: { type: 'GET', url: route('suo.data.logistic') },
        columns: [
            { data: 'no_suo' },
            { data: 'company' },
            { data: 'pic' },
            { data: 'status' },
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
                    if (!data) return '-';
                    var d = new Date(data);
                    return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + d.getFullYear();
                },
            },
            {
                targets: 5,
                orderable: false,
                searchable: false,
                render: function (data, type, full) {
                    var url = route('suo.show', data);
                    if (full.status === 'submitted') {
                        return '<a href="' + url + '" class="btn btn-sm btn-warning waves-effect"><i class="mdi mdi-clipboard-check-outline me-1"></i>Cek Stok</a>';
                    }
                    return '<a href="' + url + '" class="btn btn-sm btn-outline-info waves-effect"><i class="mdi mdi-eye-outline me-1"></i>Detail</a>';
                },
            },
        ],
        order: [[4, 'desc']],
        dom:
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>' +
            '<"table-responsive"t>' +
            '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    });
});
