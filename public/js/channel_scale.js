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
    }).datepicker("setDate","0");
   
    $(".switch").bootstrapSwitch();
    $("#query").on("click",query);
    
    query();
    
});

function query(){
    project=$("#project").val();
    operator=$("#operator").val();
    startDate=$("#start-date").val();
    endDate=$("#end-date").val();
    zhuicanAll=$("#zhuican-all").bootstrapSwitch("state")?"zhuican":"all";
    
    $.ajax({
        url:"channel_auth/get_data",
        type:"post",
        async:false,
        dateType:"json",
        data:{"project":project,"operator":operator,"startDate":startDate,"endDate":endDate,"zhuicanAll":zhuicanAll}
    }).done(function(data){
           data=$.parseJSON(data);
           for(key in data){
               data[key]=Number(data[key]);
           }
           $("#order_sales_fee_success").html(data.order_sales_fee_success);
           $("#order_sales_num_success").html(data.order_sales_num_success);
           $("#seller_num").html(data.seller_num);
           $("#up_seller_num").html(data.up_seller_num);
    });
}
