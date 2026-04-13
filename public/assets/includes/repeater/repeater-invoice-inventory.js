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
                var commodity = $(this).find(".invoice-item-commodity");
                var equivalent = $(this).find(".invoice-item-equivalent");
                var replacement = $(this).find(".invoice-item-replacement");
                var warehouse = $(this).find(".invoice-item-warehouse");
                var priceLabel = $(this).find(".invoice-item-price-label");
                var price = $(this).find(".invoice-item-price");
                var qtyLabel = $(this).find(".info-max-label");
                var qty = $(this).find(".invoice-item-qty");
                var amountLabel = $(this).find(".amount-label");
                var amount = $(this).find(".invoice-item-amount");
                var formLabel = $(this).find(".form-label");

                fromControl.each(function (i) {
                    var id = "form-repeater-" + row + "-" + col;
                    var idaccount = "account-" + row;
                    var idmemo = "memo-" + row;
                    var idmemoLabel = "memo-label-" + row;
                    var idAmount = "amount-" + row;
                    var idAmountLabel = "amount-label-" + row;
                    var idEquivalent = "equivalent-dropdown-" + row;
                    var idReplacement = "replacement-dropdown-" + row;
                    var idWarehouse = "warehouse-" + row;
                    var idPriceLabel = "price-label-" + row;
                    var idPrice = "price-" + row;
                    var idQtyLabel = "info-max-" + row;
                    var idQty = "qty-" + row;
                    $(account[i]).attr("data-id", row);
                    $(account[i]).attr("id", idaccount);
                    $(memo[i]).attr("id", idmemo);
                    $(memoLabel[i]).attr("id", idmemoLabel);
                    $(amount[i]).attr("id", idAmount);
                    $(amountLabel[i]).attr("id", idAmountLabel);
                    $(amountLabel[i]).attr("data-id", row);
                    $(amount[i]).attr("data-id", row);
                    $(commodity[i]).attr("data-id", row);
                    $(equivalent[i]).attr("id", idEquivalent);
                    $(equivalent[i]).attr("data-id", row);
                    $(replacement[i]).attr("id", idReplacement);
                    $(replacement[i]).attr("data-id", row);
                    $(warehouse[i]).attr("id", idWarehouse);
                    $(priceLabel[i]).attr("id", idPriceLabel);
                    $(priceLabel[i]).attr("data-id", row);
                    $(price[i]).attr("id", idPrice);
                    $(qtyLabel[i]).attr("id", idQtyLabel);
                    $(qty[i]).attr("id", idQty);
                    $(qty[i]).attr("data-id", row);
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
