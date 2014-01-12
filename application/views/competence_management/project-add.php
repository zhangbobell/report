<?php

/* 
 * File : project-add.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */
?>
<div>您现在的位置：项目管理 >> <?php echo $title ?></div>
<br />
<form action="competence_management/project_add_data" method="post" target="_self">
<label for="project-name">项目名称 : </label><input name="project-name" id="project-name" type="text"/>
&nbsp;&nbsp;<input id="gen-cn-pid" type="button" value="生成数据库名和编号"/>
<br />
<label for="project-db">项目数据库名 : db_</label><input name="project-db" id="project-db" type="text"/>
<br />
<label for="pid">项目编号 : </label><input name="pid" id="pid" type="text" size="12"/>
<br />
<label for="padmin">项目管理员：</label>
<br />
<select name="padmin[]" multiple="multiple" size="5">
<?php foreach ($user as $userLine): ?>
<option id="padmin" value="<?php echo $userLine['userid'] ?>"><?php echo $userLine['username'] ?></option>
<?php endforeach; ?>
</select>
<br />
<label for="is_valid">项目状态：</label>
<label><input name="is_valid" type="radio" value="-1" />过期 </label> 
<label><input name="is_valid" type="radio" value="0" checked="checked" />未知 </label> 
<label><input name="is_valid" type="radio" value="1" />进行中 </label> 
<br />
<input name="submit" type="submit" value="增加" />
</form>

