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
<button id="query_channel_auth_trend_analysis">查询</button>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">分销商销售趋势（30天）</div>
    <div class="chart-a" id="order_sales">
        分销商销售额，销量，平均上架商品数
    </div>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">分销商数量</div>
    <div class="chart-a">
        授权分销商数量，在架分销商数量，流失分销商数量
    </div>
    
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="cat-title bb10 b">分销商质量</div>
    <div class="chart-a">
        总体上架率, 乱价率, 动销率，订单关闭比率
    </div>
    <div class="fix"></div>
</div>