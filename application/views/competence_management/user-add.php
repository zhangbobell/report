<?php

/* 
 * File : user-add.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-8
 */
?>
<div>您现在的位置：用户管理 >> <?php echo $title ?></div>
<br />
<form action="competence_management/user_add_data" method="post" target="_self">
<label for="username">用户名 : </label><input name="username" id="username" type="text"/>
<br />
<label for="password">密码 : </label><input name="password" id="password" type="text"/>
<br />
<label for="re-password">再次输入密码 : </label><input name="re-password" id="re-password" type="text"/>
<br />
<label for="group">组别 : </label>
<select name="group" size="1">
<?php foreach ($group as $groupLine): ?>
<option id="group" value="<?php echo $groupLine['groupid'] ?>"><?php echo $groupLine['group'] ?></option>
<?php endforeach; ?>
</select>
<br />
<label for="auth-project">授权项目：</label>
<br />
<select name="auth-project[]" multiple="multiple" size="5">
<?php foreach ($project as $projectLine): ?>
<option id="auth-project" value="<?php echo $projectLine['pid'] ?>"><?php echo $projectLine['projectname'] ?></option>
<?php endforeach; ?>
</select>
<br />
<label for="is_valid">是否禁用：</label>
<label><input name="is_valid" type="radio" value="0" />禁用 </label> 
<label><input name="is_valid" type="radio" value="1" checked="checked" />启用 </label> 
<br />
<input name="submit" type="submit" value="增 加" />
</form>

