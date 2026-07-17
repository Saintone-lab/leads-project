$(function () {
    window.dtAdminUnitQuotation = $('.datatable-unit-quotation-admin').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: '/db/unit-quotation/admin',
            type: 'GET',
            data: function (d) {
                d.sales_id = window.adminSalesFilter || '';
                d.year = window.adminQuotationYearFilter || 'all';
                return d;
            },
        },
        columns: [
            { data: 'no_quote',     className: 'text-center text-nowrap' },
            { data: 'client',       className: '' },
            { data: 'title',        className: '' },
            { data: 'date',         className: 'text-center text-nowrap' },
            { data: 'total',        className: 'text-center text-nowrap' },
            { data: 'status',       className: 'text-center' },
            { data: 'sales_image',  className: 'text-center' },
        ],
        columnDefs: [
            { targets: 0, render: function (d, t, row) {
                return '<a href="/unit-quotation/' + row.id + '">' + d + '</a>';
            }},
            { targets: 4, className: 'text-center', render: function (d, t) {
                if (t !== 'display') return d;
                return '<div class="d-flex justify-content-between px-2"><span>Rp.</span><span>' + parseInt(d).toLocaleString('id-ID') + '</span></div>';
            }},
            { targets: 5, render: function (d, t, row) {
                var map = {
                    draft:        'bg-secondary',
                    sent:         'bg-info',
                    negotiation:  'bg-warning',
                    revision:     'bg-primary',
                    hot_prospect: 'bg-danger',
                    po_received:  'bg-success',
                    loss:         'bg-dark',
                };
                var label = {
                    draft:        'Draft',
                    sent:         'Sent',
                    negotiation:  'Negotiation',
                    revision:     'Revisi',
                    hot_prospect: 'Hot Prospect',
                    po_received:  'PO Received',
                    loss:         'Loss',
                };
                var tip = row.last_note_date
                    ? (row.last_note_date + ' | ' + (row.last_note || 'Belum di update'))
                    : 'Belum di update';
                return '<span class="badge ' + (map[d] || 'bg-label-secondary') + ' cursor-pointer"' +
                    ' data-bs-toggle="tooltip" data-bs-placement="top" title="' + tip + '">' +
                    (label[d] || d) + '</span>';
            }},
            {
                targets: 6,
                width: '48px',
                orderable: false,
                searchable: false,
                render: function (data, type, full) {
                    if (type !== 'display') return full.sales_name || '';
                    var name = full.sales_name || '-';
                    var initials = name.split(' ').map(function (w) { return w.charAt(0); }).slice(0, 2).join('').toUpperCase();
                    var colors = ['bg-label-primary','bg-label-success','bg-label-warning','bg-label-danger','bg-label-info','bg-label-secondary'];
                    var colorClass = colors[name.charCodeAt(0) % colors.length];
                    var av = data
                        ? '<img src="/' + data + '" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" alt="' + name + '">'
                        : '<div class="avatar-initial rounded-circle ' + colorClass + '" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:11px;font-weight:700;">' + initials + '</div>';
                    return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + name + '">' + av + '</span>';
                },
            },
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        drawCallback: function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
            var count = this.api().page.info().recordsTotal;
            var badge = $('.datatable-unit-quotation-admin').data('badge');
            if (badge) $('#' + badge).text(count);
        },
    });
});
