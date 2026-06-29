$(function () {
    var dt_table = $(".datatable-loss-quote-admin-tab");
    var Url = "/db/loss/admin/tab";

    if (dt_table.length) {
        dt_table.find("thead tr")
            .clone(true)
            .appendTo(dt_table.find("thead"));

        dt_table.find("thead tr:eq(1) th").each(function (i) {
            if (i === 6) { $(this).html(""); return; }
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_loss_admin_tab.column(i).search() !== this.value) {
                    dt_loss_admin_tab.column(i).search(this.value).draw();
                }
            });
        });

        var dt_loss_admin_tab = dt_table.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
                data: function (d) { d.sales_id = window.adminSalesFilter || ''; return d; },
            },
            columns: [
                { data: "no_quote" },
                { data: "company" },
                { data: "harga_total" },
                { data: "title" },
                { data: "estimated_date" },
                { data: "status" },
                { data: "sales_image" },
            ],
            columnDefs: [
                { targets: [2, 3, 4, 5, 6], className: "text-center" },
                {
                    targets: 0,
                    className: "text-center text-nowrap",
                    responsivePriority: 1,
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var url = route("quotation.show", full["id"]);
                        return '<a class="fw-bold text-primary" href="' + url + '">' + (data || "-") + "</a>";
                    },
                },
                {
                    targets: 1,
                    className: "text-start",
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var ru  = full["ru"];
                        var map = {
                            User:     '<span class="badge rounded-pill bg-label-info me-1">U</span>',
                            Reseller: '<span class="badge rounded-pill bg-label-dark me-1">R</span>',
                        };
                        return (map[ru] || "") + (data || "-");
                    },
                },
                {
                    targets: 2,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        var formatted = parseInt(data).toLocaleString("id-ID");
                        return '<div class="d-flex justify-content-between px-2"><span>Rp.</span><span>' + formatted + "</span></div>";
                    },
                },
                { targets: 3, render: function (data) { return data || "-"; } },
                {
                    targets: 4,
                    render: function (data, type) {
                        if (type !== "display") return data;
                        return data ? moment(data).format("DD-MM-YYYY") : "-";
                    },
                },
                {
                    targets: 5,
                    render: function (data, type, full) {
                        if (type !== "display") return data;
                        var tip = full["note"] || "";
                        var $status = {
                            20:  { title: "Send WA / Email",     pct: "20%",  class: "bg-label-secondary", colorTip: "tooltip-secondary" },
                            30:  { title: "Inquiry Accepted",     pct: "30%",  class: "bg-label-dark",      colorTip: "tooltip-dark" },
                            40:  { title: "Progress Follow Up",   pct: "40%",  class: "bg-label-info",      colorTip: "tooltip-info" },
                            60:  { title: "Negotiation / Revisi", pct: "60%",  class: "bg-label-primary",   colorTip: "tooltip-primary" },
                            80:  { title: "Hot Prospect",         pct: "80%",  class: "bg-label-warning",   colorTip: "tooltip-warning" },
                            100: { title: "Done PO",              pct: "100%", class: "bg-label-success",   colorTip: "tooltip-success" },
                            0:   { title: "Loss",                 pct: "0%",   class: "bg-label-danger",    colorTip: "tooltip-danger" },
                        };
                        var s = $status[data];
                        if (!s) return data;
                        return '<span class="badge rounded-pill ' + s.class + ' cursor-pointer"' +
                            ' data-bs-toggle="tooltip" data-bs-placement="top"' +
                            ' data-bs-custom-class="' + s.colorTip + '" title="' + tip + '">' +
                            s.title + " · " + s.pct + "</span>";
                    },
                },
                {
                    targets: 6,
                    className: "text-center",
                    width: "48px",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full) {
                        if (type !== "display") return full.sales_name || "";
                        var name = full.sales_name || "-";
                        var initials = name.split(" ").map(function (w) { return w.charAt(0); }).slice(0, 2).join("").toUpperCase();
                        var colors = ["bg-label-primary","bg-label-success","bg-label-warning","bg-label-danger","bg-label-info","bg-label-secondary"];
                        var colorClass = colors[name.charCodeAt(0) % colors.length];
                        var av = data
                            ? '<img src="/' + data + '" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" alt="' + name + '">'
                            : '<div class="avatar-initial rounded-circle ' + colorClass + '" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:11px;font-weight:700;">' + initials + '</div>';
                        return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + name + '">' + av + '</span>';
                    },
                },
            ],
            orderCellsTop: true,
            order: [],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [10, 25, 50, 100],
            displayLength: 10,
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) { return "Detail: " + row.data()["no_quote"]; },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col) {
                            return col.title !== ""
                                ? "<tr><td>" + col.title + ":</td><td>" + col.data + "</td></tr>"
                                : "";
                        }).join("");
                        return data ? $('<table class="table"/>').append("<tbody>" + data + "</tbody>") : false;
                    },
                },
            },
            initComplete: function () {
                var count = this.api().data().count();
                var badge = dt_table.data("badge");
                if (badge) $("#" + badge).text(count);
            },
        });

        window.dtAdminLoss = dt_loss_admin_tab;

        dt_table.on("draw.dt", function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    }
});
