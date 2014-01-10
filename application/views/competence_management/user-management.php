<?php

/* 
 * File : user-management.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-8
 */
?>
<a class="ico add" href="../user_add">增加用户</a>
<table class="project-table">
    <tr>
        <th>用户编号</th>
        <th>用户名</th>
        <th>组别</th>
        <th>授权项目</th>
        <th>是否已禁用</th>
        <th>操  作</th>
    </tr>
    <?php
        $odd = false;
        foreach ($user as $userLine)
        {
            if( !$odd )
            {
                echo '<tr>';
                $odd = true;
            }
            else 
            {
                echo '<tr class="odd">';
                $odd = false;
            }
            foreach ($userLine as $userData)
            {
                echo '<td>'.$userData.'</td>';
            }
            echo '<td><a href="../user_delete/'. $userLine['userid'] .'" class="ico del">删除</a><a href="../user_edit/'. $userLine['userid'] .'" class="ico edit">编辑</a></td>';
            echo '</tr>';
        }
    ?>
</table>
<br />
<?php 
    echo $partipation;
?>

