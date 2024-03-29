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
</div>
<div class="mt10">
    <div class="cat-title bb10 b">商品上架质量</div>
    <div class="num-b">
        <div class="l-num-title">总体上架率</div>
        <div class="l-num-title">乱价率</div>
        <div class="l-num curr" id="up_seller_rate"></div>
        <div class="l-num curr" id="arbitrary_price_rate"></div>
        <div class="fix"></div>
    </div>
    <div class="num-b">
        <div class="l-num-title">动销率</div>
        <div class="l-num-title">订单关闭比率</div>
        <div class="l-num curr" id="dynamic_sales_rate"></div>
        <div class="l-num curr" id="order_failed_rate"></div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">商品和分销商</div>
    <div class="num-b">
        <div class="l-num-title">平均上架商品数</div>
        <div class="l-num-title">流失分销商数量</div>
        <div class="l-num curr" id="up_item_num"></div>
        <div class="l-num curr" id="seller_num_lost"></div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>