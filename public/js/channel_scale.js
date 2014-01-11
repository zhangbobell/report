$(document).ready(function(){
    $.datepicker.setDefaults(
        $.datepicker.regional["zh-CN"]
    );
    $(".datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true,
        showOn:"both",
        buttonImage:"public/jquery-ui/calendar.gif",
        buttonImageOnly:true
    });
    
    $(".switch").bootstrapSwitch();
    $("#query").on("click",query);
    
    
    
});

function query(){
    project=$("#project").val();
    operator=$("#operator").val();
    startDate=$("#start-date").val();
    endDate=$("#end-date").val();
    if($("#zhuican-all").bootstrapSwitch("state")){
        $.ajax({
            url:"channel_auth/order_sales_num_success_ex",
            type:"post",
            async:false,
            dateType:"json",
            data:{"project":project,"operator":operator,"startDate":startDate,"endDate":endDate}
        }).done(function(data){
               //data=$.parseJSON(data);
               //console.log(data);
               //if(!data){
                 //  $("#order-sales-fee").html(data.avg_seller_num);
              // }
        });
    }else{
        $.ajax({
            url:"order_sales_num_success",
            type:"post",
            async:false,
            dateType:"json",
            data:{"project":project,"operator":operator,"startDate":startDate,"endDate":endDate}
        }).done(function(data){
               //data=$.parseJSON(data);
               //console.log(data);
               //if(!data){
                 //  $("#order-sales-fee").html(data.avg_seller_num);
              // }
        });
    }
    
}
