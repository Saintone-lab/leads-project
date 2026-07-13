$(function () {
    var dt_table_tool_master = $(".datatable-tool-master");
    var Url = "/db/tool-master";

    if (dt_table_tool_master.length) {
        var dt_tool_master = dt_table_tool_master.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: {
                    "Content-Type": "application/json",
                },
            },
            columns: [
                { data: "" },
                { data: "id" },
                {
                    data: "foto_referensi",
                    render: function (data, type, row) {
                        if (type !== "display") return data;
                        if (!data) return '-';
                        return '<img src="/' + data + '" alt="foto" style="width:45px;height:45px;object-fit:cover;border-radius:6px;">';
                    },
                },
                { data: "nama_tools" },
                {
                    data: "kategori",
                    render: function (data) {
                        return data ? data : '-';
                    },
                },
                {
                    data: "spesifikasi",
                    render: function (data) {
                        return data ? data : '-';
                    },
                },
                {
                    data: "harga_referensi",
                    render: function (data, type, row) {
                        if (type !== "display") return data;
                        if (!data) return '-';
                        return "Rp " + Number(data).toLocaleString("id-ID");
                    },
                },
                {
                    data: "status_aktif",
                    render: function (data, type, row) {
                        if (type !== "display") return data;
                        return data == 1
                            ? '<span class="badge bg-label-success">Aktif</span>'
                            : '<span class="badge bg-label-secondary">Nonaktif</span>';
                    },
                },
                { data: "" },
            ],
            columnDefs: [
                {
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () {
                        return "";
                    },
                },
                {
                    targets: 1,
                    searchable: true,
                    visible: false,
                },
                {
                    responsivePriority: 1,
                    targets: 3,
                },
                {
                    targets: -1,
                    title: "Aksi",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full, meta) {
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-end m-0">' +
                            '<li><a href="javascript:;" class="dropdown-item edit-tool-master" data-tool=\'' + JSON.stringify(full).replace(/'/g, "&#39;") + '\'>Edit</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" class="dropdown-item text-danger delete-tool-master" data-id="' + full.id + '">Delete</a></li>' +
                            "</ul>" +
                            "</div>"
                        );
                    },
                },
            ],
            order: [[3, "asc"]],
            dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 15,
            lengthMenu: [15, 25, 50, 75, 100],
            buttons: [
                {
                    text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Tools</span>',
                    className: "btn btn-primary",
                    action: function () {
                        resetToolMasterForm();
                        var modal = new bootstrap.Modal(document.getElementById('toolMasterModal'));
                        modal.show();
                    },
                },
            ],
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            },
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return "Details of " + data["nama_tools"];
                        },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== ""
                                ? '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                      "<td>" + col.title + ":</td> " +
                                      "<td>" + col.data + "</td>" +
                                      "</tr>"
                                : "";
                        }).join("");

                        return data
                            ? $('<table class="table"/><tbody />').append(data)
                            : false;
                    },
                },
            },
        });
        $("div.head-label").html('<h5 class="card-title mb-0">Table Master Tools</h5>');
    }

    function resetToolMasterForm() {
        var form = document.getElementById('formToolMaster');
        form.reset();
        form.action = route('tool-master.store');
        document.getElementById('toolMasterMethod').value = 'post';
        document.getElementById('toolMasterModalLabel').innerText = 'Create New Master Tools';
        document.getElementById('fotoReferensiPreviewWrapper').style.display = 'none';
    }

    $(document).on('click', '.edit-tool-master', function () {
        var tool = $(this).data('tool');
        var form = document.getElementById('formToolMaster');
        form.reset();
        form.action = route('tool-master.update', tool.id);
        document.getElementById('toolMasterMethod').value = 'patch';
        document.getElementById('toolMasterModalLabel').innerText = 'Update Master Tools';
        $('#nama_tools').val(tool.nama_tools);
        $('#kategori').val(tool.kategori);
        $('#spesifikasi').val(tool.spesifikasi);
        $('#link_pembelian').val(tool.link_pembelian);
        $('#harga_referensi').val(tool.harga_referensi);
        $('#status_aktif').val(tool.status_aktif == 1 ? '1' : '0');
        if (tool.foto_referensi) {
            $('#fotoReferensiPreview').attr('src', '/' + tool.foto_referensi);
            $('#fotoReferensiPreviewWrapper').show();
        } else {
            $('#fotoReferensiPreviewWrapper').hide();
        }
        var modal = new bootstrap.Modal(document.getElementById('toolMasterModal'));
        modal.show();
    });

    $(document).on('click', '.delete-tool-master', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Master Tools ini?',
            text: "Data yang sudah dihapus tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-label-secondary',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: route('tool-master.destroy', id),
                    type: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        dt_table_tool_master.DataTable().ajax.reload();
                        Swal.fire('Terhapus!', 'Master Tools telah dihapus.', 'success');
                    },
                });
            }
        });
    });
});
