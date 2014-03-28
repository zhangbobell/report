<p>增加标签<span id="process-info"></span></p>
<br />
<br />
<div id="filter">
<select id="db-select" class="subFilter">
    <?php foreach ($this->session->userdata('authDB') as $k => $v ): ?>
    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
    <?php endforeach;?>
</select>
    <div class="subFilter">月销/笔<input id="salesNumFrom" class="filterInput">-<input id="salesNumTo" class="filterInput"></div>
    <div class="subFilter">等级<input id="rankFrom" class="filterInput">-<input id="rankTo" class="filterInput"></div>
    <div class="subFilter">描述相符高于<input id="descFrom" class="filterInput">%-<input id="descTo" class="filterInput">%</div>
    <input id="getList" type="button" value="获取" class="l" />
    <div class="fix"></div>
</div>
<br />
<div id="tagResult"></div>

<ul id="addtag"></ul>
