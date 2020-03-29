<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>
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
<form id="form1" action="<?=autolink(array('account','change'))?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered table-hover m10">
    <tbody>
      <tr>
        <td width="10%" class="tableleft">原密码：</td>
        <td><input name="pwd" type="password" class="input-xlarge" data-required="{required:true}"/>
        </td>
      </tr>
      <tr>
        <td width="10%" class="tableleft">新密码：</td>
        <td><input name="newpwd" id="newpwd" type="password" class="input-xlarge" data-required="{required:true}"/>
        </td>
      </tr>
      <tr>
        <td width="10%" class="tableleft">确认密码：</td>
        <td><input name="newpwd2" type="password" class="input-xlarge" data-required="{required:true,equalTo:'#newpwd'}"/>
        </td>
      </tr>
      <tr>
        <td class="tableleft"></td>
        <td><button type="button" name="Submit" class="btn btn-primary"> 保存 </button></td>
      </tr>
    </tbody>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function () {
	Control.onSubmit();
})
</script>
