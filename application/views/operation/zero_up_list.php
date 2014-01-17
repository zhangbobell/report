<div class="cat-title-op">
    <a href="operation/list_query/" class="op-list unactivated">乱价分销商名单</a><a href="operation/zero_up_list/" class="op-list activated">0上架分销商名单</a><div class="fix"></div>
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
              &nbsp;&nbsp;日期：
                <input type="text" id="date"  class="datepicker ml5" size="10" />
                <div class="fix"></div>
            </div>
            <div class="fix"></div>
        </div>
            <div class="pct100 mt20" id="rank">
                
            </div>
        </div>
    </div>
    <div class="fix"></div>