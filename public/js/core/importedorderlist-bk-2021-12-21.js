function submitImportOrder(orders_id)
{
    // document.location = site_url+"imported-order-list.html";
    if (typeof orders_id  !== "undefined" && orders_id != '') { 
        if(confirm("Are you sure, You want to Delete?"))
        {   
            $.ajax({
                type: 'DELETE',
                url: site_url+'delete-imported-order-list.html',
                data: { 'orders_id' : orders_id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() 
                {
                },
                success: (function(data) {
                    if(data.status == 200) {
                        $("#delete-success-message").show();
                        $("#tr_"+orders_id).remove();
                    }
               })
            });
        }
    } else {
        alert("Something went wrong");
        return false;
    }
}