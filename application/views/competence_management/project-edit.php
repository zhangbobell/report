<?php

/* 
 * File : project-edit.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-7
 */
?>
<div>您现在的位置：项目管理 >> <?php echo $title ?></div>
<br />
<form action="../project_edit_data/<?php echo $id; ?>" method="post" target="_self">
    <label for="project-name">项目名称 : </label><input name="project-name" id="project-name" type="text" value="<?php echo $project['projectname'] ?>" />
&nbsp;&nbsp;<input id="gen-cn-pid" type="button" value="生成数据库名和编号"/>
<br />
<label for="project-db">项目数据库名 : db_</label><input name="project-db" id="project-db" type="text" value="<?php echo substr($project['dbname'], 3) ?>" />
<br />
<label for="pid">项目编号 : </label><input name="pid" id="pid" type="text" size="12" value="<?php echo $project['pid'] ?>" />
<br />
<label for="padmin">项目管理员：</label>
<br />
<select multiple="multiple" size="5" name="padmin[]">
<?php foreach ($userAll as $userLine): ?>
    <option id="puser" type="checkbox" value="<?php echo $userLine['userid'] ?>" 
        <?php if(in_array($userLine['userid'], $user))
               {
                   echo 'selected="selected"';  
               }
        ?> 
            ><?php echo $userLine['username'] ?></option>
<?php endforeach; ?>
</select>
<br />
<label for="is_valid">
    <label><input name="is_valid" type="radio" value="-1" 
        <?php 
            if($project['is_valid'] === '-1') 
            {
                echo 'checked="checked"';
            }
        ?> />过期 </label> 
<label><input name="is_valid" type="radio" value="0" 
        <?php 
            if($project['is_valid'] === '0') 
            {
                echo 'checked="checked"';
            }
        ?> />未知 </label> 
<label><input name="is_valid" type="radio" value="1" 
        <?php 
            if($project['is_valid'] === '1') 
            {
                echo 'checked="checked"';
            }
        ?> />进行中 </label> 
<br />
<input name="submit" type="submit" value="保存修改" />
</form>


