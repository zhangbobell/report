/*
 * LoadField and addField is not used 
 * 
 */
var jsonData;
var filterData;
var shopSalesFlag=true;
var rankFlag=true;
var mgFlag=true;
var halfYearGoodRateFlag=true;


$(document).ready(function(){

    getList();
    // This file demonstrates the different options of the pagination plugin
    // It also demonstrates how to use a JavaScript data structure to
    // generate the paginated content and how to display more than one
    // item per page with items_per_page.

    // When document has loaded, initialize pagination and form
        // Create pagination element with options from form
    

    // Event Handler for for button
    $("#setoptions").click(function(){
        var opt = getOptionsFromForm();
        // Re-create pagination content with new parameters
        $("#Pagination").pagination(filterData.length, opt);
    });

    

$("#db-select").change(function(){
    getList();
});

$("#getList").on("click", function(){
    filter();
});

});

$(document).on("click","#shopSales",function(){

    if(shopSalesFlag)
    {
       filterData =filterData.sort(sortShopSales);
       shopSalesFlag=false;
   }
    else
    {
       filterData.sort(sortShopSales).reverse();
       shopSalesFlag=true;
   }
    
    var opt = getOptionsFromForm();
        // Re-create pagination content with new parameters
    $("#Pagination").pagination(filterData.length, opt);
});

$(document).on("click","#rank",function(){
    
    if(rankFlag)
    {
       filterData =filterData.sort(sortRank);
       rankFlag=false;
    }
    else
    {
       filterData.sort(sortRank).reverse();
       rankFlag=true;
    }
    var opt = getOptionsFromForm();
        // Re-create pagination content with new parameters
    $("#Pagination").pagination(filterData.length, opt);
});

$(document).on("click","#mg",function(){
    
    if(mgFlag)
    {
       filterData =filterData.sort(sortMg);
       mgFlag=false;
    }
    else
    {
       filterData.sort(sortMg).reverse();
       mgFlag=true;
    }
    
    var opt = getOptionsFromForm();
        // Re-create pagination content with new parameters
    $("#Pagination").pagination(filterData.length, opt);
});

$(document).on("click","#halfYearGoodRate",function(){
    
    if(halfYearGoodRateFlag)
    {
       filterData =filterData.sort(sortHalfYearGoodRate);
       halfYearGoodRateFlag=false;
   }
    else
    {
       filterData.sort(sortHalfYearGoodRate).reverse();
       halfYearGoodRateFlag=true;
    }
    var opt = getOptionsFromForm();
        // Re-create pagination content with new parameters
    $("#Pagination").pagination(filterData.length, opt);
});

function getList()
{
   $('#process-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />");
    
   $.ajax({
      url : 'tag/getList',
      type : 'POST',
      data : 'db='+$("#db-select").val(),
      dataType : 'json',
      success:function(json){
                jsonData=json;

                filterData=jsonData.slice(0);
                var optInit = getOptionsFromForm();
                $("#Pagination").pagination(filterData.length, optInit);
                $('#process-info').html( "");
      }
      
   });
   
}

function filter()
{
    $('#process-info').html( "<img src=\"public/images/loading_s.gif\" alt=\"loading...\" />");
    
    var shopSalesNumFrom = parseInt($("#shopSalesNumFrom").val().trim());
    var shopSalesNumTo = parseInt($("#shopSalesNumTo").val().trim());
    var rankFrom = parseInt($("#rankFrom").val().trim());
    var rankTo = parseInt($("#rankTo").val().trim());
    var mgFrom = parseInt($("#mgFrom").val().trim());
    var mgTo = parseInt($("#mgTo").val().trim());
    var halfYearGoodRateFrom = parseInt($("#halfYearGoodRateFrom").val().trim());
    var halfYearGoodRateTo = parseInt($("#halfYearGoodRateTo").val().trim());

    filterData.splice(0,filterData.length); 
    
    for(var i = 0; i < jsonData.length; i++)
    {
        if(shopSalesNumFrom!="" && jsonData[i]['shopSales']<shopSalesNumFrom)
            continue;
        if(shopSalesNumTo!="" && jsonData[i]['shopSales']>shopSalesNumTo)
            continue;
        if(jsonData[i]['rank']==null)
            continue;
        if(rankFrom!="" && jsonData[i]['rank'].toString().substring(12)<rankFrom)
            continue;
        if(rankTo!="" && jsonData[i]['rank'].toString().substring(12)>rankTo)
            continue;
        if(mgFrom!="" && jsonData[i]['mg']<mgFrom)
            continue;
        if(mgTo!="" && jsonData[i]['mg']>mgTo)
            continue;
        if(halfYearGoodRateFrom!="" && jsonData[i]['halfYearGoodRate']<halfYearGoodRateFrom)
            continue;
        if(halfYearGoodRateTo!="" && jsonData[i]['halfYearGoodRate']>halfYearGoodRateTo)
            continue;

        filterData.push(jsonData[i]);
    }

    var optInit = getOptionsFromForm();
    $("#Pagination").pagination(filterData.length, optInit);
    $('#process-info').html( "");
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
    if(a.rank && b.rank)
    {
        return b.rank.toString().substring(12) - a.rank.toString().substring(12);
    }
    else if(a.rank)
        return 0-a.rank.toString().substring(12);
    else if(b.rank)
        return b.rank.toString().substring(12)-0;
    else
        return 0;
}

function sortMg(a, b)
{
    return b.mg - a.mg;
}

function sortHalfYearGoodRate(a, b)
{
    return b.halfYearGoodRate - a.halfYearGoodRate;
}

/**
* Callback function that displays the content.
*
* Gets called every time the user clicks on a pagination link.
*
* @param {int}page_index New Page index
* @param {jQuery} jq the container with the pagination links as a jQuery object
*/
function pageselectCallback(page_index, jq){
   // Get number of elements per pagionation page from form
   var items_per_page = $('#items_per_page').val();
   var max_elem = Math.min((page_index+1) * items_per_page, filterData.length);
   var newcontent = '<table class="project-table">\n\
                <tr>\n\
                <th>旺旺昵称</th>\n\
                <th><a href="javascript:void(0);" id="shopSales" title="点击排序">月销笔数</a></th>\n\
                <th><a href="javascript:void(0);" id="rank" title="点击排序">店铺等级</a></th>\n\
                <th><a href="javascript:void(0);" id="mg" title="点击排序">服务相符高于</a></th>\n\
                <th><a href="javascript:void(0);" id="halfYearGoodRate" title="点击排序">半年好评差评比</a></th>\n\
                <th>标签</th>\n\
                </tr>';

   // Iterate through a selection of the content and build an HTML string
   for(var i=page_index*items_per_page;i<max_elem;i++)
   {
       newcontent += '<tr><td>' + filterData[i]['sellerNick'] + '<a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=' + filterData[i]['sellerNick'] + '&siteid=cntaobao&status=2&charset=utf-8"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=' + filterData[i]['sellerNick'] + '&site=cntaobao&s=2&charset=utf-8" alt="点这里给我发消息" /></a></td>\n\
                <td>' + filterData[i]['shopSales'] + '</td>\n\
                <td>' + filterData[i]['rank'] + '</td>\n\
                <td>' + filterData[i]['mg'] + '</td>\n\
                <td>' + filterData[i]['halfYearGoodRate'] + '</td>\n\
                <td><ul id="seller' + filterData[i]['id'] + '"></ul></td>\n\
                </tr>';
       
   }
   newcontent += '</table>';
   // Replace old content with new content
   $('#Searchresult').html(newcontent);
   for(var i=page_index*items_per_page;i<max_elem;i++)
   {
        var sellerNick = filterData[i]['sellerNick'];
        $("#seller"+ filterData[i]['id']).tagHandler({
        getData: { sellerNick: filterData[i]['sellerNick'],db:$("#db-select").val() },
        getURL: 'tag/getTag',
        updateData: { sellerNick: filterData[i]['sellerNick'],db:$("#db-select").val() },
        updateURL: 'tag/updateTag',
        autocomplete: true,
        autoUpdate: true
        });
   }

   // Prevent click eventpropagation
   return false;
}

function addTag(tag, sellerNick)
{
    $.ajax({
      url : 'tag/updateTag',
      type : 'POST',
      data : 'tag='+tag+'&sellerNick='+sellerNick,
      dataType : 'json',
      success:function(json){
                if(json==='true')
                    console.log('okok');
                else
                    console.log('add failed');
      }
      
   });
}

// The form contains fields for many pagiantion optiosn so you can
// quickly see the resuluts of the different options.
// This function creates an option object for the pagination function.
// This will be be unnecessary in your application where you just set
// the options once.
function getOptionsFromForm(){
   var opt = {callback: pageselectCallback};
   // Collect options from the text fields - the fields are named like their option counterparts
   /*$("input:text").each(function(){
       opt[this.name] = this.className.match(/numeric/) ? parseInt(this.value) : this.value;
   });
   // Avoid html injections in this demo
   var htmlspecialchars ={ "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;"}
   $.each(htmlspecialchars, function(k,v){
       opt.prev_text = opt.prev_text.replace(k,v);
       opt.next_text = opt.next_text.replace(k,v);
   })*/
   opt.items_per_page=$('#items_per_page').val();
   opt.num_display_entries=5;
   opt.num_edge_entries=5;
   opt.prev_text="上一页";
   opt.next_test="下一页"; 
   
   return opt;
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