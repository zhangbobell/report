<div class="cat-title">
项目
<select id="project">
    <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
    <?php endforeach;?>
</select>
<input type="checkbox" name="" id="zhuican-all" checked="checked" data-on-label="追灿" data-off-label="全部" class="switch switch-small" />
运营人员<?php echo $operator;?>
日期<input type="text" id="start-date" class="datepicker" size="10" />
到<input type="text" id="end-date"  class="datepicker" size="10" />
<button id="query">查询</button>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">产品和分销商情况</div>
    <div class="num-b">
        <div class="l-num-title">授权分销商销售额</div>
        <div class="l-num-title">授权分销商销量</div>
        <div class="l-num curr" id="order_sales_fee_success">12345</div>
        <div class="l-num curr" id="order_sales_num_success">7780</div>
        <div class="fix"></div>
    </div>
    <div class="num-b">
        <div class="l-num-title">授权分销商数量</div>
        <div class="l-num-title">在架授权分销商数量</div>
        <div class="l-num curr" id="seller_num">12345</div>
        <div class="l-num curr" id="up_seller_num">7780</div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>