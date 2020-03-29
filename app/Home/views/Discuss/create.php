<html>
<head>
    <meta charset="utf-8">
    <title>发贴</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />
	<meta name="csrf-token" content="<?=csrf_token()?>" />
	
    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css">
	<link rel="stylesheet" href="__PUBLIC__/validator/validator.css">
	<link rel="stylesheet" href="__PUBLIC__/plugins/easyUploader/main.css">
	<style>
	.btn-select-file{
		width: 65px;
		height: 65px;
		background-image: url(__PUBLIC__/images/icon-add.png);
		background-size: 100% 100%;
		display: inline-block;
		position: relative;
		margin-right: 10px;
		margin-bottom: 10px;
		border:none;
	}
	.easy-uploader .btn{border:none;}
	.webuploader-pick{ width:auto;height:100%;}
	.btn-box{ margin-top:10px;}
	</style>
</head>
<body>
<main>
<form id="form1" action="<?=autolink(array(get('class'),'save'))?>" method="post" target="hidden_frame">
    <div class="send-post">
        <div class="container-fluid">
            <div class="mui-table-view">
                <div class="mui-table-view-cell">
                    <input name="title" type="text" placeholder="请输入标题"  data-required="{required:true}" />
                </div>
                <div class="mui-table-view-cell">
                    <textarea name="content" id="content" rows="9" placeholder="请输入内容" data-required="{required:true}"></textarea>
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
					<span>注：可上传视频(mp4)，附件(ppt,pdf,docx,doc)</span>
					
					<div id="uploader">
						<div class="easy-uploader">
							<div class="btn-box">
								<div id="filePicker" class="btn-select-file btn"></div>
							</div>
							<ul class="list">
							
							</ul>
						</div>
					</div>
					
                </div>
            </div>
        </div>

        <div class="footer-display">
            <a class="col-xs-6 fb" id="submit" href="javascript:void(0)">发表</a>
            <a class="col-xs-6 qx" id="cancal" href="javascript:void(0)">取消</a>
        </div>
    </div>
	<input name="token" type="hidden" value="<?=session("token")?>">
	<div class="files" style="display:none;">
		
	</div>
</form>	
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</main>
<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/js/mui.min.js"></script>
<script src="__PUBLIC__/js/feedback-page.js"></script>
<script src="__PUBLIC__/validator/jquery.validator.js"></script>

<!--引入JS-->
<script type="text/javascript" src="__PUBLIC__/plugins/webuploader/webuploader.js"></script>

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
	
	$("#cancal").click(function(){
		location.href = "<?=autolink(array(get('class'),'index'))?>";
	})
	
	var filesList = [];
	
	function updateFiles(){	
		var html = '';
		filesList.forEach(function(row,index){
			html += '<input name="files['+index+'][title]" type="hidden" value="'+row.title+'">';
			html += '<input name="files['+index+'][filename]" type="hidden" value="'+row.filename+'">';
		});
		$(".files").html(html);
	}
	
	var uploader = WebUploader.create({
        pick: {
            id: '#filePicker',
            label: '   '
        },
        accept: {
            title: 'Images',
            extensions: 'ppt,pdf,doc,docx,mp4',
        },
		swf: 'webuploader-0.1.5/Uploader.swf',
		server: '/uploadFile',
		formData:{md5:'<?=md5(time())?>',token:'<?=session("token")?>'},
		resize: false,
		auto:true,
		chunked:true,
		chunkSize:1024*500
	});

	uploader.on( 'fileQueued', function( file ) {
		var $list = $("#uploader .list");
		$list.append( '<li id="'+file.id+'" class="list-item"><div class="preview"><img class="preview-img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAMAAABG8BK2AAAAFVBMVEWZmZmZmZmZmZmZmZmZmZmZmZmZmZk9goCmAAAAB3RSTlMC/rYs1mCR8/Hv3QAAAYBJREFUWMPtmMsSwyAIRRWE///kGmMTH6CYtN0012knC+cU4Uq0zj169CkhqEI7hb2uYOZQnA2cx/GQHjeOFRP0qcHOQe9phLFyCAeBckwc3C1hxDgYchBJEZaYSIgcHpVHE1bRpAzxulUKDuzrCUoVcPOVLCo9t0fjUOGw7k4qaxxyduPPShzQkx8xcHDCOykyZ4zBwysHJnFwEXN4pZhHAmeGeXuFi6UI8Uwx2SuYNn9W6LbXHJM5rUtpFZM9hxCi/P7V7i4LpvYc1oVrMBDOQTVGyKmGwX7dJ6bnqNHwKaCuGbYcFSM3Topb1G0frjkrmLTQzSNpVMVZwaQSjbqYERNBZ0dlE0bpwYKNFgt+BeNKDF/HlF3YXcdYzgn/iklNSRZd9I3YL23RsKp8eHwKfhdDqhh/X/APYVDX11JMC0cBCYOcu7t3dzDUv2tuYGDh0CbmJr2Llw60Ixd/DWO9+3FbnfYobg1mNDFdRufyfrSmzLFodoFGYoPo+a9jVS+3Nw2jSs30KQAAAABJRU5ErkJggg=="></div><div class="info"><p class="filename">'+file.name+'</p><div class="progress-wrapper"><div class="progressbar" style="width:0%;"></div><div class="progress-text">0%</div></div><i class="btn-check-item select-icon cursor-select checkbox checked"></i></div><label class="btn-delete-item delete-label cursor-select"><i class="delete-icon">X</i></label></li>' );
	});

	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
		var obj = $("#uploader .list .list-item[id='"+file.id+"']");
		obj.find(".progressbar").css( 'width', percentage * 100 + '%' );
		obj.find(".progress-text").text(Math.ceil(percentage * 100) + '%');
	});
	
	uploader.on( 'uploadAccept', function( obj,res ) {
		if(res.errcode > 0){
			uploader.removeFile( obj.file );
			var dom = $("#uploader .list .list-item[id='"+obj.file.id+"']");
			dom.find(".progress-text").text('失败');
			dom.find(".progressbar").css( 'background-color', '#f56c6c' );
		}
	});

	uploader.on( 'uploadSuccess', function( file,res ) {
		if(res.errcode == 0){
			filesList.push({
				title:res.title,
				filename:res.filename
			});
			updateFiles();
		}
	});
	
	uploader.on( 'fileDequeued', function( file ) {
		var files = uploader.getFiles();
		files.forEach(function(row,index){
			if(row.id == file.id){
				filesList.splice(index, 1);
				updateFiles();
			}
		})
	});

	uploader.on( 'uploadError', function( file,code ) {
		console.log('uploadError',file);
		console.log('uploadError',code);
	});

	uploader.on( 'uploadComplete', function( file) {

	});
	
	$(document).on('click','#uploader .btn-delete-item',function(){
		var index = $("#uploader .list-item").index($(this));
		$("#uploader .list-item").eq(index).remove();
		filesList.splice(index, 1);
		updateFiles();
	})
	
})

function callback(res){
	mui.alert(res.errmsg, '操作提示', function() {
		location.href = "<?=autolink(array(get('class'),'index'))?>";
	});
}
</script>
</body>
</html>