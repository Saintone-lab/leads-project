$(function () {
    var baseAjaxUrl = "/db/crm/status";
    
    function initDataTable(selector, statusId) {
        var $el = $(selector);
        if (!$el.length) return null;
        
        // Clone header row lalu replace isinya dengan input search (seperti di halaman invoice)
        $el.find("thead tr")
            .clone(true)
            .appendTo($el.find("thead"));

        $el.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
        });

        var dt = $el.DataTable({
            ajax: {
                type: "GET",
                url: baseAjaxUrl + "?status=" + statusId,
                data: function (d) {
                    var salesId = $('#admin-sales-filter').val();
                    if (salesId) {
                        d.sales_id = salesId;
                    }
                    var ruType = $('#ru-type-filter').val();
                    if (ruType) {
                        d.ru_type = ruType;
                    }
                },
                headers: {
                    "Content-Type": "application/json",
                },
            },
            columns: [
                { data: "company" },
                { data: "status" },
                { data: "area" },
                { data: "date" },
                { data: "follow_up" },
                { data: "info" }, // Flag
            ],
            columnDefs: [
                {
                    targets: [1, 3, 4, 5],
                    className: "text-center",
                },
                {
                    targets: [0, 2],
                    className: "text-nowrap",
                },
                {
                    responsivePriority: 1,
                    targets: 0,
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var $dataId = full["id"];
                        var detailRoute = route("existing.show", $dataId);
                        var companyName = data || "-";
                        
                        // Limit company name if too long
                        if (companyName.length > 25) {
                            companyName = companyName.substring(0, 25) + "...";
                        }
                        
                        return '<a class="fw-bold text-primary" href="' + detailRoute + '" data-bs-toggle="tooltip" data-bs-placement="top" title="' + (data || "") + '">' + companyName + '</a>';
                    },
                },
                {
                    targets: 1,
                    render: function (data, type, full, meta) {
                        var dropdown =
                            '<select class="form-select status-dropdown" data-id="' +
                            full.id +
                            '">';
                        dropdown +=
                            '<option value="1" ' +
                            (data === "1" ? "selected" : "") +
                            ">Bangkrupt</option>";
                        dropdown +=
                            '<option value="2" ' +
                            (data === "2" ? "selected" : "") +
                            ">Aktif</option>";
                        dropdown +=
                            '<option value="3" ' +
                            (data === "3" ? "selected" : "") +
                            ">Non Aktif</option>";
                        dropdown += "</select>";
                        return dropdown;
                    },
                },
                {
                    targets: [3, 4],
                    render: function (data, type, row) {
                        if (data === null || data === undefined) {
                            return "-";
                        } else {
                            return type === "display" ? data : "-";
                        }
                    },
                },
                {
                    targets: 5,
                    render: function (data, type, full, row) {
                        if (type === "display") {
                            var flag = full["info"];
                            if (!flag) return "-";
                            var note = full["note"] || "No notes available";
                            var $info = {
                                Reftech: {
                                    class: "bg-label-primary",
                                },
                                Kojisha: {
                                    class: " bg-label-warning",
                                },
                            };
                            return (
                                '<span class="badge ' +
                                ($info[flag] ? $info[flag].class : "bg-label-secondary") +
                                '" data-bs-toggle="tooltip" data-bs-placement="top" title="' + note + '">' +
                                data +
                                "</span> "
                            );
                        }
                        return data;
                    },
                },
            ],
            order: [[0, "asc"]],
            dom:
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"table-responsive"t>' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [],
        });

        // Set up search event listeners after DataTable is initialized
        $el.find("thead tr:eq(1) th input").each(function (i) {
            $(this).on("keyup change", function () {
                if (dt.column(i).search() !== this.value) {
                    dt.column(i).search(this.value).draw();
                }
            });
        });

        return dt;
    }

    window.dtCustomerActive = initDataTable(".datatable-customers-active", 2);
    window.dtCustomerNonActive = initDataTable(".datatable-customers-non-active", 3);
    window.dtCustomerBangkrupt = initDataTable(".datatable-customers-bangkrupt", 1);

    $('#crm-tab-nav button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
    });

    $(document).on('draw.dt', function (e) {
        var $tbl    = $(e.target);
        
        // Re-initialize tooltips (e.g. for company hover and flag note tooltip)
        $tbl.find('[data-bs-toggle="tooltip"]').tooltip();
        
        var badgeId = $tbl.data('badge');
        if (!badgeId) return;
        var api   = $tbl.DataTable();
        var count = api.page.info().recordsTotal;
        $('#' + badgeId).text(count);
    });

    $('#admin-sales-filter').on('change', function () {
        if (window.dtCustomerActive) window.dtCustomerActive.ajax.reload();
        if (window.dtCustomerNonActive) window.dtCustomerNonActive.ajax.reload();
        if (window.dtCustomerBangkrupt) window.dtCustomerBangkrupt.ajax.reload();
    });

    $('#ru-type-filter').on('change', function () {
        if (window.dtCustomerActive) window.dtCustomerActive.ajax.reload();
        if (window.dtCustomerNonActive) window.dtCustomerNonActive.ajax.reload();
        if (window.dtCustomerBangkrupt) window.dtCustomerBangkrupt.ajax.reload();
    });
});
