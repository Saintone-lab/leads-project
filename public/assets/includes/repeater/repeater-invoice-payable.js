$(function () {
    var invoiceRepeater = $(".form-invoice-repeater");
    if (invoiceRepeater.length) {
        var row = 2;
        var col = 1;
        invoiceRepeater.on("submit", function (e) {
            e.preventDefault();
        });
        invoiceRepeater.repeater({
            show: function () {
                var account = $(this).find(".invoice-item-account");
                var memo = $(this).find(".invoice-item-memo");
                var memoLabel = $(this).find(".invoice-item-memo-label");
                var amount = $(this).find(".invoice-item-amount");
                var amountLabel = $(this).find(".invoice-item-amount-label");
                var fromControl = $(this).find(".form-control, .form-select");
                var formLabel = $(this).find(".form-label");

                fromControl.each(function (i) {
                    var id = "form-repeater-" + row + "-" + col;
                    var idaccount = "account-" + row;
                    var idmemo = "memo-" + row;
                    var idmemoLabel = "memo-label-" + row;
                    var idAmount = "amount-" + row;
                    var idAmountLabel = "amount-label-" + row;
                    $(account[i]).attr("data-id", row);
                    $(account[i]).attr("id", idaccount);
                    $(memo[i]).attr("id", idmemo);;
                    $(memoLabel[i]).attr("id", idmemoLabel);;
                    $(amount[i]).attr("id", idAmount);
                    $(amountLabel[i]).attr("id", idAmountLabel);
                    $(amountLabel[i]).attr("data-id", row);
                    $(amount[i]).attr("data-id", row);
                    col++;
                });

                row++;

                $(this).slideDown();
            },
            remove: function (e) {
                confirm("Are you sure you want to delete this element?") &&
                    $(this).remove(e);
            },
        });
    }
});
