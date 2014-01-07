<?php

/* 
 * File : project-management.php
 * Author : ibm   Email: zhangbobell@163.com
 * createTime : 2014-1-6
 */
?>
<a class="ico add" href="project_add">增加项目</a>
<table class="project-table">
    <tr>
        <th>序  号</th>
        <th>更新时间</th>
        <th>项目编号</th>
        <th>项目名称</th>
        <th>项目数据库</th>
        <th>项目状态</th> 
        <th>操  作</th>
    </tr>
    <?php
        $odd = false;
        foreach ($project as $projectLine)
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
            foreach ($projectLine as $projectData)
            {
                echo '<td>'.$projectData.'</td>';
            }
            echo '<td><a href="#" class="ico del">删除</a><a href="#" class="ico edit">编辑</a></td>';
            echo '</tr>';
        }
    ?>
</table>