<html>
<head>
    <meta charset="utf-8">
    <title>意见建议</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />
	<meta name="csrf-token" content="<?=csrf_token()?>" />

    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css">
	<link rel="stylesheet" href="__PUBLIC__/validator/validator.css">
</head>
<body>
<main>
<form id="form1" action="<?=autolink(array(get('class'),'save'))?>" method="post" target="hidden_frame">
    <div class="send-post">
        <div class="container-fluid">
            <div class="mui-table-view">
                <div class="mui-table-view-cell">
                    <textarea name="content" id="content" rows="9" placeholder="请输入投诉/建议/问题" data-required="{required:true}"></textarea>
                </div>

                <div class="mui-table-view-cell">
                    <div class="id-upload">
                        <span>注：可上传多张图片</span>
                            <div id="feedback" class="mui-page feedback">
                                <div class="mui-page-content">
                                    <div id='image-list' class="image-list">

                                    </div>
                                </div>
                                <input type="hidden" name="s" value="ss" />
                            </div>
                    </div>
                </div>
				
				<div class="mui-table-view-cell">
					<div class="mui-input-row mui-checkbox" style="width:130px;">
					  <label>匿名提交</label>
					  <input name="anonymous" value="1" type="checkbox" checked>
					</div>
				</div>
				
            </div>
        </div>

        <div class="footer-display">
            <a class="col-xs-12 fb" id="submit" href="javascript:void(0)">提交</a>
        </div>
    </div>
	<input name="token" type="hidden" value="<?=session("token")?>">	
</form>	
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</main>
<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/js/mui.min.js"></script>
<script src="__PUBLIC__/js/feedback-page.js"></script>
<script src="__PUBLIC__/validator/jquery.validator.js"></script>
<script src="__PUBLIC__/js/common.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
wx.config(<?=json_encode($config)?>);
wx.ready(function(){
	wx.hideOptionMenu();
});

$(function(){
	$("#submit").click(function(){
		if($("#form1").validateData()){
			$("#form1").submit();
		}
	})
})

function callback(res){
	mui.alert(res.errmsg, '操作提示', function() {
		location.href = "<?=autolink(array(get('class')))?>";
	});
}
</script>
</body>
</html>