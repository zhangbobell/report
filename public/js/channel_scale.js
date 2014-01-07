$(document).ready(function(){
    $.datepicker.setDefaults(
        $.datepicker.regional["zh-CN"]
    );
    $(".datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true,
        showOn:"both",
        buttonImage:"../public/jquery-ui/calendar.gif",
        buttonImageOnly:true
    });

    alert($("#project").val());
    
});
function p(){
        $.ajax({
            url:"channel_scale/get_data",
            type:"post",
            dateType:"json",
            data:{"startDate":$("#start-date").val(),"endDate":$("#end-date").val(),"dbname":dbname}
        }).done(function(data){
            data=$.parseJSON(data);
            //if(!data){
              //  $("#order-sales-fee").html(data.avg_seller_num);
           // }
        });
    }
