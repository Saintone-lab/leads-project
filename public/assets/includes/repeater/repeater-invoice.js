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
                var qty = $(this).find(".invoice-item-qty");
                var disc = $(this).find(".invoice-item-disc");
                var amount = $(this).find(".invoice-item-amount");
                var amountLabel = $(this).find(".amount-label");
                var fromControl = $(this).find(".form-control, .form-select");
                var formLabel = $(this).find(".form-label");
    
                fromControl.each(function (i) {
                    var id = "form-repeater-" + row + "-" + col;
                    var idPrice = "price-" + row;
                    var idQty = "qty-" + row;
                    var idDisc = "disc-" + row;
                    var idAmount = "amount-" + row;
                    var idAmountLabel = "amount-label-" + row;
                    $(price[i]).attr("id", idPrice);
                    $(price[i]).attr("data-id", row);
                    $(qty[i]).attr("id", idQty);
                    $(qty[i]).attr("data-id", row);
                    $(disc[i]).attr("id", idDisc);
                    $(disc[i]).attr("data-id", row);
                    $(amount[i]).attr("id", idAmount);
                    $(amountLabel[i]).attr("id", idAmountLabel);
                    $(amount[i]).attr("data-id", row);
                    col++;
                });
    
                row++;
    
                $(this).slideDown();
            },
            hide: function (e) {
                confirm("Are you sure you want to delete this element?") &&
                    $(this).slideUp(e);
            },
        });
    }

});

