$(document).ready(function(){
    
    //默认加载昨天的
    //$('#t1').css('background-color','#ccc');
    get_data();
    
    $(".switch").bootstrapSwitch();
        
    //选择项目
    $('#db-select').change(function(){
        get_data();
    });
    
     //是否追灿
    $('#zhuican-all').on('switch-change', function () {
        get_data();
});
  });
  
  function get_data(){
      
            //显示正在加载 
            $('#c_sales').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#n_sales_current').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#n_sales_last').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#c_seller_num').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#n_seller_num_current').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#n_seller_num_last').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#c_up_rate').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#n_up_rate_current').html('<img src="public/images/loading.gif" alt="loading..." />');
            $('#n_up_rate_last').html('<img src="public/images/loading.gif" alt="loading..." />');
            
            
            $.ajax({
	    url:'admin/index_data',
            type:'POST',
            data:'is_zc='+$('#zhuican-all').bootstrapSwitch('state')+'&db='+$('#db-select').val(),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    //在反转之前获取当前和昨天的值
                    //销售额
                    $('#n_sales_current').text(json['sales_1'][0])
                    $('#n_sales_last').text(json['sales_1'][1])
                    //分销商数量
                    $('#n_seller_num_current').text(json['seller_num_2'][0])
                    $('#n_seller_num_last').text(json['seller_num_2'][1])
                    //上架率
                    $('#n_up_rate_current').text((json['up_rate_3'][0]).toFixed(2)+"%")
                    $('#n_up_rate_last').text((json['up_rate_3'][1]).toFixed(2)+"%")
                    
                    json['sales_1'].reverse();
                    json['seller_num_2'].reverse();
                    json['updatetime_1'].reverse();
                    json['up_rate_3'].reverse();
                    json['updatetime_3'].reverse();
                    
              //======================================== 第一部分：销售额 ======================================
                    $('#c_sales').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '销售额'
                    },
                    subtitle: {
                        text: '来源：追灿数据库'
                    },
                    credits: { 
                        enabled: false   //不显示LOGO 
                    },
                    xAxis: {
                        categories: json['updatetime_1']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '销售额 （元）'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} 元</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: '销售额',
                        data: json['sales_1']

                    }]
                });
                
                //======================================== 第二部分：分销商数量 ======================================
                $('#c_seller_num').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '分销商数量'
                    },
                    subtitle: {
                        text: '来源：追灿数据库'
                    },
                    credits: { 
                        enabled: false   //不显示LOGO 
                    },
                    xAxis: {
                        categories: json['updatetime_1']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '分销商数量'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: '分销商数量',
                        data: json['seller_num_2']

                    }]
                });
                
                //======================================== 第三部分：上架率 ======================================
                $('#c_up_rate').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '上架率'
                    },
                    subtitle: {
                        text: '来源：追灿数据库'
                    },
                    credits: { 
                        enabled: false   //不显示LOGO 
                    },
                    xAxis: {
                        categories: json['updatetime_3']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: '上架率（%）'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.2f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: '上架率',
                        data: json['up_rate_3']

                    }]
                });
            }
            else
                    $('#rank').html("<h2>&nbsp;&nbsp;&nbsp;&nbsp;没有找到符合条件的数据</h2>");
            }
             
	  });//ajax结束
   
         
  }
