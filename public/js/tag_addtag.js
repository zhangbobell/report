/*
 * LoadField and addField is not used 
 * 
 */
var jsonData;
var shopSalesFlag=true;
var rankFlag=true;
var mgFlag=true;
var halfYearGoodRateFlag=true;


$(function(){
    
    //loadField();
    getList();

$("#db-select").change(function(){
    //loadField();
});

$("#getList").on("click", function(){
    getList();
});
    
$("#addtag").tagHandler({
                assignedTags: [ 'C', 'Perl', 'PHP' ],
                availableTags: [ 'C', 'C++', 'C#', 'Java', 'Perl', 'PHP', 'Python' ],
                autocomplete: true
            });

});

$(document).on("click","#shopSales",function(){

    if(shopSalesFlag)
       jsonData =jsonData.sort(sortShopSales);
    else
       jsonData.reverse();
    shopSalesFlag=false;
    output(jsonData);
});

$(document).on("click","#rank",function(){
    
    if(rankFlag)
       jsonData =jsonData.sort(sortRank);
    else
       jsonData.reverse();
    rankeFlag=false;
    output(jsonData);
});

function getList()
{
   $('#process-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />");
   
   /*var salesNumFrom = $("#salesNumFrom").val().toString().trim();
   var salesNumTo = ($("#salesNumTo").val().toString()).trim();
   var rankFrom = ($("#rankFrom").val().toString()).trim();
   var rankTo = ($("rankTo").val().toString()).trim();
   var descFrom = ($("descFrom").val().toString()).trim();
   var descTo = ($("descTo").val().toString()).trim();*/
   
   $.ajax({
      url : 'tag/getList',
      type : 'POST',
      data : 'db='+$("#db-select").val(),
      dataType : 'json',
      success:function(json){
          jsonData=json;
          output(jsonData);
          $('#process-info').html( "");
      }
      
   });
   
}



function output(jsonRank)
{
    var html='<table class="project-table">\n\
                <tr>\n\
                <th>旺旺昵称</th>\n\
                <th><a href="javascript:void(0);" id="shopSales" title="点击排序">月销笔数</a></th>\n\
                <th><a href="javascript:void(0);" id="rank" title="点击排序">店铺等级</a></th>\n\
                <th><a href="javascript:void(0);" id="mg" title="点击排序">服务相符高于</a></th>\n\
                <th><a href="javascript:void(0);" id="halfYearGoodRate" title="点击排序">半年好评差评比</a></th>\n\
                </tr>';
    for(var i = 0; i < jsonRank.length; i++)
    {
        html += '<tr><td>' + jsonRank[i]['sellerNick'] + '<a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=' + jsonRank[i]['sellerNick'] + '&siteid=cntaobao&status=2&charset=utf-8"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=' + jsonRank[i]['sellerNick'] + '&site=cntaobao&s=2&charset=utf-8" alt="点这里给我发消息" /></a></td>\n\
                <td>' + jsonRank[i]['shopSales'] + '</td>\n\
                <td>' + jsonRank[i]['rank'] + '</td>\n\
                <td>' + jsonRank[i]['mg'] + '</td>\n\
                <td>' + jsonRank[i]['halfYearGoodRate'] + '</td>\n\
                </tr>' ;
    }
    html += '</table>';
    $('#tagResult').html(html);
}

String.prototype.trim= function(){  
    // 用正则表达式将前后空格  
    // 用空字符串替代。  
    return this.replace(/(^\s*)|(\s*$)/g, "");  
}

function sortShopSales(a, b)
{
    return b.shopSales - a.shopSales;
}
function sortRank(a, b)
{
    //if(b.rank)
      //  console.log(b.rank.substring(12));
    
    if(a.rank && b.rank)
    {
            return b.rank.toString().substring(12) - a.rank.toString().substring(12);
    }
    else if(a.rank)
        return a.rank.toString().substring(12)-0;
    else if(b.rank)
        return b.rank.toString().substring(12)-0;
}

function loadField()
{
    $('#process-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />");
    $.ajax({
	    url:'tag/loadField',
            type:'POST',
            data:'db='+$('#db-select').val(),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    var html='';
                    for(var i = 0; i < eval(json['comment']).length; i++)
                    {
                        html+= '<option title="' + json['comment'][i] + '" value="' + json['field'][i] + '">' + json['field'][i] + '</option>' ;
                    }
                    $('#process-info').html( "");
                    $('#field').html(html);
            }
           }
	  });//ajax函数结束
}

function addField()
{
    var field =$("#field").val();
    var html="";
   
    $('#process-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />");
    /*
     *  input的css类型分为: ipt_number , ipt_date, ipt_char
     * 
     */
    
    switch(field)
    {
        case 'id':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="id_min" /> < id < <input maxlength="5"  class="ipt-number" size="4" id="id_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'createtime':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="10" size="10" class="ipt-date" id="createtime_min" /> < 创建时间 < <input maxlength="10" class="ipt-date" size="10" id="createtime_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'updatetime':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="10" size="10" class="ipt-date" id="updatetime_min" /> < 更新时间 < <input maxlength="10" class="ipt-date" size="10" id="updatetime_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'shopid':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="20" size="10" class="ipt-number" id="shopid_min" /> < 店铺ID < <input maxlength="10" class="ipt-number" size="10" id="shopid_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'uid':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="20" size="10" class="ipt-number" id="uid_min" /> < 用户ID < <input maxlength="10" class="ipt-number" size="10" id="uid_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'sellernick':
            {
                html+='<div class="filter-sel" id="'+ field +'">卖家旺旺 = <input maxlength="12" class="ipt-char" size="10" id="sellernick_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'distributor_nick':
            {
                html+='<div class="filter-sel" id="'+ field +'">平台注册账户 = <input maxlength="12" class="ipt-char" size="10" id="distributor_nick_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'maincat':
            {
                html+='<div class="filter-sel" id="'+ field +'">maincat = <input maxlength="12" class="ipt-char" size="10" id="maincat_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'mainsell':
            {
                html+='<div class="filter-sel" id="'+ field +'">maincat = <input maxlength="50" class="ipt-char" size="15" id="mainsell_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'itemnew':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="itemnew_min" /> < itemnew < <input maxlength="5"  class="ipt-number" size="4" id="itemnew_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'itempromo':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="itempromo_min" /> < itempromo < <input maxlength="5"  class="ipt-number" size="4" id="itempromo_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'shopname':
            {
                html+='<div class="filter-sel" id="'+ field +'">店铺名称 = <input maxlength="12" class="ipt-char" size="10" id="shopname_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'shoptype':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="shoptype_min" /> < 店铺类型 < <input maxlength="5"  class="ipt-number" size="4" id="shoptype_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'startdate':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="10" size="10" class="ipt-date" id="startdate_min" /> < 合作起始时间 < <input maxlength="10" class="ipt-date" size="10" id="startdate_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'lastdate':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="10" size="10" class="ipt-date" id="lastdate_min" /> < 合作最后更新时间 < <input maxlength="10" class="ipt-date" size="10" id="lastdate_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;   
            }
        case 'status':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="status_min" /> < 合作状态 < <input maxlength="5"  class="ipt-number" size="4" id="status_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'is_zhuican':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="is_zhuican_min" /> < 是否追灿招募 < <input maxlength="5"  class="ipt-number" size="4" id="is_zhuican_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'rank':
            {
                html+='<div class="filter-sel" id="'+ field +'">店铺名称 = <input maxlength="12" class="ipt-char" size="10" id="rank_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'goodcomment':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="goodcomment_min" /> < 好评率 < <input maxlength="5"  class="ipt-number" size="4" id="goodcomment_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'ratenumber':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="ratenumber_min" /> < 好评数 < <input maxlength="5"  class="ipt-number" size="4" id="ratenumber_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'rate':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="rate_min" /> < 主营类目好评占比 < <input maxlength="5"  class="ipt-number" size="4" id="rate_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'dsr_desc':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="dsr_desc_min" /> < 描述相符 < <input maxlength="5"  class="ipt-number" size="4" id="dsr_desc_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'dsr_srv':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="dsr_srv_min" /> < 服务态度 < <input maxlength="5"  class="ipt-number" size="4" id="dsr_srv_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'dsr_ship':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="dsr_ship_min" /> < 发货速度 < <input maxlength="5"  class="ipt-number" size="4" id="dsr_ship_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'address':
            {
                html+='<div class="filter-sel" id="'+ field +'">地区 = <input maxlength="12" class="ipt-char" size="10" id="address_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'number':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="number_min" /> < 在售商品数 < <input maxlength="5"  class="ipt-number" size="4" id="number_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'sales':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="5" size="4" class="ipt-number" id="sales_min" /> < 月销/笔 < <input maxlength="5"  class="ipt-number" size="4" id="sales_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'account_date':
            {
                html+='<div class="filter-sel" id="'+ field +'"><input maxlength="10" size="10" class="ipt-date" id="account_date_min" /> < 分配运营人员时间 < <input maxlength="10" class="ipt-date" size="10" id="account_date_max" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'account':
            {
                html+='<div class="filter-sel" id="'+ field +'">分配帐户名称 = <input maxlength="12" class="ipt-char" size="10" id="account_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        case 'mark':
            {
                html+='<div class="filter-sel" id="'+ field +'">备注 = <input maxlength="12" class="ipt-char" size="10" id="mark_min" />&nbsp;&nbsp;<a onclick="deleteFilter(\''+field+'\')" class="ico del">删除</a></div>';
                break;
            }
        default:
            {
                html+='';
                break;
            }    
    }
    $('#process-info').html( "");
    $('#filter').append(html);
}

function deleteFilter(field)
{
    $('#'+field).remove();
}

/*function getList()
{
    var dataPost="";
    var filterName=new Array();
    if(typeof($('#id_min').val())!='undefined' && trim($('#id_min').val())!='')
    {
        filterName['id_min']=$('#id_min').val();
    }
    /*$('#process-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />");
    
    $.ajax({
	    url:'tag/getList',
            type:'POST',
            data:'db='+$('#db-select').val(),
            dataType:'json',
            success:function(json){
                if(json !== null)
                {
                    var html='';
                    for(var i = 0; i < eval(json['comment']).length; i++)
                    {
                        html+= '<option title="' + json['comment'][i] + '" value="' + json['field'][i] + '">' + json['field'][i] + '</option>' ;
                    }
                    $('#process-info').html( "");
                    $('#field').html(html);
            }
           }
	  });//ajax函数结束
}*/