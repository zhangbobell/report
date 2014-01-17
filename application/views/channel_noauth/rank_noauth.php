<div class="cat-title">
<select id="db-select">
    <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
    <?php endforeach;?>
</select>
&nbsp;&nbsp;从
 <input type="text" id="start-date"  class="datepicker ml5" size="10" />
 到
 <input type="text" id="end-date"  class="datepicker ml5" size="10" /> 
 &nbsp;&nbsp;
 按&nbsp;<input type="checkbox" name="" id="sales-price" checked="checked" data-on-label="销量" data-off-label="乱价幅度" class="switch switch-small" />&nbsp;排序
</div>
<div class="mt10">
    <div class="pct100 l bgwh">
            <div class="pct100 mt20" id="rank">
                
            </div>
        </div>
    </div>
<div class="fix"></div>