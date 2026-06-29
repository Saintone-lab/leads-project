$(function () {
    var dt_table_reports_fajar = $(".datatable-reports-fajar");
    var Url = "/db/service-reports/fp";

    if (dt_table_reports_fajar.length) {
        // Clone header row lalu replace isinya dengan input search
        dt_table_reports_fajar.find("thead tr")
            .clone(true)
            .appendTo(dt_table_reports_fajar.find("thead"));

        dt_table_reports_fajar.find("thead tr:eq(1) th").each(function (i) {
            var title = $(this).text();
            $(this).html(
                '<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />'
            );
            $("input", this).on("keyup change", function () {
                if (dt_reports_fajar.column(i).search() !== this.value) {
                    dt_reports_fajar.column(i).search(this.value).draw();
                }
            });
        });

        var dt_reports_fajar = dt_table_reports_fajar.DataTable({
            ajax: {
                type: "GET",
                url: Url,
                headers: { "Content-Type": "application/json" },
            },
            columns: [
                { data: "no_service" },
                { data: "brand_type" },
                { data: "tag" },
                { data: "location" },
                { data: "jobdesc" },
                {
                    data: "date",
                    render: function (data, type) {
                        if (type === "display") {
                            return data ? moment(data).format("DD-MM-YYYY") : "-";
                        }
                        return data;
                    },
                },
                { data: "name" },
            ],
            columnDefs: [
                {
                    responsivePriority: 1,
                    targets: 0,
                    className: "text-nowrap",
                    render: function (data, type, full) {
                        if (type === "display") {
                            var detailRoute = route("service-reports.show", full["id"]);
                            return '<a class="fw-bold text-primary" href="' + detailRoute + '">' + data + "</a>";
                        }
                        return data;
                    },
                },
                { targets: 2, className: "text-center" },
                { targets: 5, className: "text-center" },
                { targets: 6, className: "text-center" },
            ],
            orderCellsTop: true,
            order: [[0, "desc"]],
            dom: '<"card-header flex-column flex-md-row"<"head-label hl-sr text-center">><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [7, 10, 25, 50, 75, 100],
            displayLength: 10,
        });

        $("div.hl-sr").html('<h5 class="card-title mb-0">Table Service Reports</h5>');
    }
});
