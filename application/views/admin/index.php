<div class="cat-title">
    <input type="checkbox" name="" id="zhuican-all" checked="checked" data-on-label="追灿" data-off-label="全部" class="switch switch-small" />
    <select id="db-select">
        <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
        <?php endforeach;?>
    </select>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">销售额</div>
    <div class="chart-b" id="c_sales">销售额作图区域</div>
    <div class="num-b">
        <div class="l-num-title">昨天销售额</div>
        <div class="l-num-title">前天销售额</div>
        <div class="l-num curr" id="n_sales_current"></div>
        <div class="l-num last" id="n_sales_last"></div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">累计分销商数量</div>
    <div class="chart-b" id="c_seller_num">累计分销商数量图表</div>
    <div class="num-b">
        <div class="l-num-title">昨天分销商数量</div>
        <div class="l-num-title">前天分销商数量</div>
        <div class="l-num curr" id="n_seller_num_current"></div>
        <div class="l-num last" id="n_seller_num_last"></div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">整体上架率</div>
    <div class="chart-b" id="c_up_rate">上架率图表</div>
    <div class="num-b">
        <div class="l-num-title">昨天上架率</div>
        <div class="l-num-title">前天上架率</div>
        <div class="l-num curr" id="n_up_rate_current"></div>
        <div class="l-num last" id="n_up_rate_last"></div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>
