function openpaymethod(type){
    if(type=='paybypaypal'){
        $("#paybypaypal").slideDown();
        $("#paybycc").slideUp();
        $("#AmazonPayFundButton").hide();

        $(".ccslide").html("+");
        $(".paypalslide").html("-");
        $(".amazonslide").html("+");

    }else if(type=='paybycc'){
        $("#paybycc").slideDown();
        $("#paybypaypal").slideUp();
        $("#AmazonPayFundButton").hide();

        $(".ccslide").html("-");
        $(".paypalslide").html("+");
        $(".amazonslide").html("+");

    }else if(type=='AmazonPayButton'){
        $("#AmazonPayFundButton").show();
        $("#paybycc").slideUp();
        $("#paybypaypal").slideUp();

        $(".amazonslide").html("-");
        $(".ccslide").html("+");
        $(".paypalslide").html("+");
    }
}