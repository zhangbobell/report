<?php

/* 
 * File : project-add.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */
?>
<div>您现在的位置：项目管理 >> <?php echo $title ?></div>
<br />
<form action="project-add-data" method="post" target="_self">
<label for="project-name">项目名称 : </label><input name="project-name" id="project-name" type="text"/>
&nbsp;&nbsp;<input id="gen-cn-pid" type="button" value="生成数据库名和编号"/>
<br />
<label for="project-db">项目数据库名 : db_</label><input name="project-db" id="project-db" type="text"/>
<br />
<label for="pid">项目编号 : </label><input name="pid" id="pid" type="text" size="12"/>
<br />
<label for="pAdmin">项目管理员：</label>
<br />
<select multiple="multiple" size="5">
<?php foreach ($user as $userLine): ?>
<option id="pAdmin"  name="pAdmin[]" type="checkbox" value="<?php echo $userLine['userid'] ?>"><?php echo $userLine['username'] ?></option>
<?php endforeach; ?>
</select>
<br />
<input name="submit" type="submit" value="增加" />
</form>

