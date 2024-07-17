$(document).ready(function(){

      $("#tableContainer").on("change","#orderstatus",function(e){
         var title = $(this).val();
        var oid = $(this).attr("data-id");

        $('#inquirymodal').show();
        $('#inquirymodal').css('opacity', '1');
        $('#inquirymodal').css('top', '10%');
        $('#inquirymodal').css('padding', '3rem 1rem 1rem');
        $('#inquirymodal .close').click(function(){
            $('#inquirymodal').hide();
        });
        var status = "Delivered Date";

        $('#inquirymodal .modal-title').html(status);
         $('#statusValue').val(title);
        $('#orderid').val(oid);

    });


    $("form#addForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            //alert("working");
            $.ajax({
                url: 'actions/order/updateDate.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    //alert(data);
                    result = $.parseJSON(data);
                     if(result.response == 'y'){
                          alert(result.message);
                          location.reload();
                     }
                     else{

                         alert(result.message);
                     }
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });

});
