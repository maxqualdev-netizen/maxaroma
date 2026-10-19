<div id="afterpay-widget-container"></div>
<input type="hidden" name="pschecksum" id="pschecksum" value="" aria-hidden="true">
<script>
// Ensure this function is defined before loading afterpay.js
function createAfterpayWidget() {
    window.afterpayWidget = new AfterPay.Widgets.PaymentSchedule({
    token: '{{Session::get("ShoppingCart.AfterPay.Checkout_Token")}}',
    target: '#afterpay-widget-container',
    locale: 'en-US',
    onReady: function(event) {
        var paymentScheduleChecksum;
        paymentScheduleChecksum = event.data.paymentScheduleChecksum;
        console.log(paymentScheduleChecksum);
        afterpayWidget.update({
        amount: {
            amount: "{{$NetTotal}}",
            currency: "USD"
        },
        })
        $("#pschecksum").val(paymentScheduleChecksum);
        if ($("#ap_psChecksum").length > 0) {
        $("#ap_psChecksum").val(paymentScheduleChecksum);
        }
    },
    onChange: function(event) {
        // Fires after each update and on any other state changes.
        // See "Getting the widget's state" for more details.
        // console.log(event);
        var paymentScheduleChecksum;
        if (event.data.isValid == true) {
        $("#alert_order").html("");
        $("#alert_ap").hide();
        $("#checkout_widget_ap").show();

        paymentScheduleChecksum = event.data.paymentScheduleChecksum;
        $("#pschecksum").val(paymentScheduleChecksum);
        if ($("#ap_psChecksum").length > 0) {
            $("#ap_psChecksum").val(paymentScheduleChecksum);
        }
        } else {
        //issue
        // console.log(event);
        $("#alert_order").html("AfterPay is disabled because of amount range limit reached.");
        $("#alert_ap").show();
        $("#checkout_widget_ap").hide();
        }
    },
    onError: function(event) {
        // See "Handling widget errors" for more details.
        // console.log(event);
        $("#alert_order").html("AfterPay is disabled because of amount range limit reached.");
        $("#alert_ap").show();
        $("#checkout_widget_ap").hide();

        // if($("#net_total_amt").data("amt") >= $("#net_total_amt").data("minap") && $("#net_total_amt").data("amt") < $("#net_total_amt").data("maxap")){
        //code here
        // }
        // $("#ap_checkoutwidget").hide();
    },
    })
}
</script>
<script src="{{$token_js_url}}" async onload="createAfterpayWidget()"></script>