$('#frmNewCardReader').validate({
    ignore: ":hidden:not(#keycode)",
    rules:
    {
        user_code: { required: true },
        reader_name: { required: true  },
        //location_name : { required: true  }
    },
    messages:
    {
        user_code:{ required: "Please Enter User Code"},
        reader_name: { required: "Please Enter Reader Name" },
        //location_name: { required: "Please Enter LocationID" },
    },
    onsubmit: false
});

$(document).ready(function(){
    $("#saveReader").click(function(){
        if($("#frmNewCardReader").valid())
        {
            $.ajax({
                type: 'POST',
                url: site_url+'store/connect-reader',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    user_code: $("#user_code").val(),
                    reader_name: $("#reader_name").val(),
                    location_name: $("#location_name").val()
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
        return false;
    });
    $("#btnCancelChangeCardReader").click(function(){
        $('#change_cardreader_div').hide();
        var oldSelReader = $("#selcardreader").val();
        $("#sel_cardreader_id").val(oldSelReader);
    });

    $("#btnChangeCardReader").click(function(){
        var sel_cardreader_id = $('#sel_cardreader_id').find(":selected").val();
        var sel_cardreader_name = $('#sel_cardreader_id').find(":selected").text();
        $.ajax({
            type: 'POST',
            url: site_url+'store/change-connect-reader',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                sel_cardreader_id: sel_cardreader_id,
                sel_cardreader_name: sel_cardreader_name
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $("#change_cardreader_link").click(function(){
        $('#change_cardreader_div').toggle();
    });
})