<div class="cat-title">
项目<select id="project">
        <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
        <?php endforeach;?>
    </select>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">全网商家销售额（30天）</div>
    <div class="chart-a" id="order_fee">
        全网商家销售额
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">全网商家数量趋势（30天）</div>
    <div class="chart-a" id="seller_num_full">
        全网商家数量趋势
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">授权分销商销售额占比（30天）</div>
    <div class="chart-a" id="page_sales_fee_30_auth_srate">
        授权分销商销售额占比
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">全网商家乱价率（30天）</div>
    <div class="chart-a" id="price_change_rate">
        全网商家乱价率
    </div>
    <div class="fix"></div>
</div>