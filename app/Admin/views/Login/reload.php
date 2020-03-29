<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?=TEMP_PATH?>/css/style.css" />
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.validator.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/artDialog/plugins/iframeTools.source.js"></script>
<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }


    </style>
</head>
<body>
</body>
</html>
<script>
 parent.artDialog.message("您还没有登录，请重新登录",function(){
	parent.window.location.href="<?=autolink(array("login"))?>";
 });
</script>
