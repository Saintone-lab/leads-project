$(function(){
    var invoiceRepeater = $('.form-invoice-repeater')
    if (invoiceRepeater.length) {
        var row = 2;
        var col = 1;
        invoiceRepeater.on("submit", function (e) {
            e.preventDefault();
        });
        invoiceRepeater.repeater({
            show: function () {
                var price = $(this).find(".invoice-item-price");
                var priceLabel = $(this).find(".invoice-item-price-label");
                var qty = $(this).find(".invoice-item-qty");
                var disc = $(this).find(".invoice-item-disc");
                var info = $(this).find(".invoice-item-info");
                var amount = $(this).find(".invoice-item-amount");
                var amountLabel = $(this).find(".amount-label");
                var fromControl = $(this).find(".form-control, .form-select");
                var formLabel = $(this).find(".form-label");
    
                fromControl.each(function (i) {
                    var id = "form-repeater-" + row + "-" + col;
                    var idPrice = "price-" + row;
                    var idPriceLabel = "price-label-" + row;
                    var idQty = "qty-" + row;
                    var idDisc = "disc-" + row;
                    var idInfo = "info-qty-" + row;
                    var idAmount = "amount-" + row;
                    var idAmountLabel = "amount-label-" + row;
                    $(price[i]).attr("id", idPrice);
                    $(priceLabel[i]).attr("id", idPriceLabel);
                    $(priceLabel[i]).attr("data-id", row);
                    $(qty[i]).attr("id", idQty);
                    $(qty[i]).attr("data-id", row);
                    $(info[i]).attr("id", idInfo);
                    $(info[i]).attr("data-id", row);
                    $(disc[i]).attr("id", idDisc).val(0);
                    $(disc[i]).attr("data-id", row).val(0);
                    $(amount[i]).attr("id", idAmount);
                    $(amountLabel[i]).attr("id", idAmountLabel);
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

