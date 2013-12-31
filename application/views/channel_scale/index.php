<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo PUB_DIR;?>/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo PUB_DIR;?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="<?php echo PUB_DIR;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo PUB_DIR;?>/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo PUB_DIR;?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="<?php echo PUB_DIR;?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

</head>
<body>
    <div style="width:200px" class="datetimepicker  date input-group" date-date-format="yyyy-mm-dd">
        <input type="text" class="form-control span2" size="16" />
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
    <script type="text/javascript">
        $(".datetimepicker").datetimepicker();
    </script>
</body>
</html>
