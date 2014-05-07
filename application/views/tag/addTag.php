<p>增加标签<span id="process-info"></span></p>
<select id="db-select" class="mt10">
    <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
    <?php endforeach;?>
</select>
<div id="filter" class="mt10">
    <div class="mb10 pl20">旺旺名称<input id="wwName" class="filterInput"></div>
    <div class="subFilter">月销/笔<input id="shopSalesNumFrom" class="filterInput">-<input id="shopSalesNumTo" class="filterInput"></div>
    <div class="subFilter">等级<input id="rankFrom" class="filterInput">-<input id="rankTo" class="filterInput"></div>
    <div class="subFilter">描述相符高于<input id="mgFrom" class="filterInput">%-<input id="mgTo" class="filterInput">%</div>
    <div class="subFilter">半年好评差评比<input id="halfYearGoodRateFrom" class="filterInput">%-<input id="halfYearGoodRateTo" class="filterInput">%</div>
    <div class="l" >每页显示<select id="items_per_page">
        <option value="10">10</option>
        <option value="20" selected="selected">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
        </select>条</div>&nbsp;&nbsp;&nbsp;&nbsp;
    <input id="getList" type="button" value="筛选" class="l filterInput" />
    <div class="fix"></div>
</div>
<div id="Searchresult"></div>
<br />
<div id="Pagination" class="pagination">
</div>
<br /><br /><br /><br />
<ul id="addtag"></ul>
