<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>发表评论</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />
	<meta name="csrf-token" content="<?=csrf_token()?>" />
	
    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css?t=<?=time()?>">
</head>
<body>
<main id="app">
    <div class="container-fluid">
        <h3 class="news-title"><?=$row["title"]?></h3>

        <div class="mui-table-view">
            <div class="mui-table-view-cell">
                <textarea  id="textarea" class="textarea" v-model="content" rows="8" placeholder="留言将由管理员筛选后显示,对所有人可见"></textarea>
            </div>
        </div>
		<button type="button" class="btn-submit-message" @click="onSubmit">提交留言</button>
    </div>

    <!--    提交评论弹窗-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <img class="icon-sign-success" src="__PUBLIC__/images/icon-sign-success.png" />
                    <h4 class="modal-title" id="myModalLabel">提交成功</h4>
                </div>
                <div class="modal-body">
                    <p>留言将由管理员筛选后显示,对所有人可见！</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-xy" class="btn btn-default" data-dismiss="modal">确定</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/weiui/lib/fastclick.js"></script>
<script src="__PUBLIC__/weiui/js/jquery-weui.js"></script>
<script src="__PUBLIC__/js/vue.min.js"></script>
<script src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/common.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>

<script>

	var userVue = new Vue({
		el:"#app",
		data:{
			content:''
		},
		methods:{
			onSubmit:function(){
				if(this.content.trim() != ''){
					$.ajax({
						url:"<?=U("news/saveComment",array('newsid'=>$row["newsid"]))?>",
						type:"POST",
						data:{content:this.content,userToken:'<?=$userToken?>'},
						dataType:"json",
						success:function(res){
							if(res.errcode == 0){
								
								$('#myModal').modal('show');
								
							}else{
								alert(res.errmsg);
							}
						}
					})
				}else{
					alert("请输入评论内容！");
				}
			},
			validateData(){
				var result = false;
				$.ajax({
					url:"",
					type:"POST",
					data:{content:this.content},
					dataType:"json",
					success:function(res){
						if(res.errcode > 0){
							$('#myModal').modal('show');
						}
					}
				})
			}
		}
	});

    var text = document.querySelector('textarea');
    var bac = document.querySelector('.btn-submit-message');
    text.onkeyup=function () {
        bac.classList.add('btn-submit-red');
        if(text.value===''){
            bac.classList.remove('btn-submit-red');
            console.log(text.value)
        }
    };

// 弹窗上下居中
    $(function(){
        var $m_btn = $('#btn-xy');
        var $modal = $('#myModal');
        $m_btn.on('click', function(){
            $modal.modal({backdrop: 'static'});
			location.href = "<?=U("news/detail",array('newsid'=>$row["newsid"]))?>";
        });

        // 测试 bootstrap 居中
        $modal.on('show.bs.modal', function(){
            var $this = $(this);
            var $modal_dialog = $this.find('.modal-dialog');
            // 关键代码，如没将modal设置为 block，则$modala_dialog.height() 为零
            $this.css('display', 'block');
            $modal_dialog.css({'margin-top': Math.max(0, ($(window).height() - $modal_dialog.height()) / 2) });
        });

    });
</script>
</body>
</html>