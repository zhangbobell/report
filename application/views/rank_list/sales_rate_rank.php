<div class="cat-title-op">
    <a href="rank_list/sales_rate_rank/" class="op-list activated">分销商销量增长率排行榜</a>
    <a href="rank_list/sales_rank/" class="op-list unactivated">分销商销量排行榜</a>
    <a href="rank_list/product_sales_rate_rank/" class="op-list unactivated">产品销量增长率排行榜</a>
    <a href="rank_list/product_sales_rank/" class="op-list unactivated">产品销量排行榜</a>
    <div class="fix"></div>
</div>
<div class="mt10">
    <div class="pct100 l bgwh">
        <div class="mt20 ml20 mr20">
            <div class="pct100">
                <select id="db-select">
                    <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                    <?php endforeach;?>
                </select>
             <input type="checkbox" name="" id="zhuican-all" checked="checked" data-on-label="追灿" data-off-label="全部" class="switch switch-small" />
            <div class="r" >
                <div class="time-span" id="t1">昨天</div>
                <div class="time-span" id="t3">最近3天</div>
                <div class="time-span" id="t7">最近7天</div>
                <div class="time-span" id="t30">最近30天</div>
                <div class="time-span" id="t90">最近90天</div>
                <div class="time-span" id="t180">最近180天</div>
                <div class="fix"></div>
            </div>
            <div class="fix"></div>
        </div>
            <div class="pct100 mt20" id="rank">
                
            </div>
        </div>
    </div>
    <div class="fix"></div>
</div>
