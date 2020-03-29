<html>
<head>
    <meta charset="utf-8">
    <title><?=$row["title"]?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />
	<meta name="csrf-token" content="<?=csrf_token()?>" />

    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css?t=<?=time()?>">
	<style>
		.watermark{  background:url(<?=U("fontImg",array('name'=>urlencode(session("name"))))?>);}
	</style>
</head>
<body>
<main id="app">
    <div class="focus-news-detail watermark">
        <div class="container-fluid news">
			<div class="title">
				<h2><?=$row["title"]?></h2>
				<span><?=$row["pubdate"]?></span>
			</div>
			
			<div class="content">
				<?=$row["content"]?>
			</div>
			
			<div class="likes current" @click="doLike">
				<div>阅读数： <?=$row["reads"]?></div>
				<div class="text-right"> 赞 <span>{{likes}}</span> <i class="icon-yz" v-if="liked == true"></i> <i class="icon-dz" v-else></i> </div>
			</div>
        </div>
		
		<div class="newsComments">
			<div class="mui-content">
				<div class="title">
					精选留言（{{dataList.length}}）
				</div>
				<ul class="mui-table-view">
					<li class="mui-table-view-cell mui-media" v-for="(row,index) in dataList">
						<a href="javascript:;">
							<img class="mui-media-object mui-pull-left" src="__PUBLIC__/images/img-user2.png">
							<div class="mui-media-body">
								{{row.name}}
								<p>{{row.content}}</p>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>
		
		<a href="<?=autolink(array(get('class'),'comment','newsid'=>$row["newsid"]))?>" class="write-message" style="z-index: 9999;">写留言</a>
		
    </div>
</main>
<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/weiui/lib/fastclick.js"></script>
<script src="__PUBLIC__/weiui/js/jquery-weui.js"></script>
<script src="__PUBLIC__/artTemplate/template.js"></script>
<script src="__PUBLIC__/js/vue.min.js"></script>
<script src="__PUBLIC__/js/common.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
wx.config(<?=json_encode($config)?>);
wx.ready(function(){
	<?php if($row["safe"] == 1) {?>
	wx.hideOptionMenu();
	<?php }?>
	
	wx.onMenuShareTimeline({
		title: '<?=$row["title"]?>', // 分享标题
		link: location.href, // 分享链接；在微信上分享时，该链接的域名必须与企业某个应用的可信域名一致
		imgUrl: '<?=getHost() . $row["picture"]?>', // 分享图标
		success: function () {
			// 用户确认分享后执行的回调函数
		},
		cancel: function () {
			// 用户取消分享后执行的回调函数
		}
	});
	
	wx.onMenuShareAppMessage({
		title: '<?=$row["title"]?>', // 分享标题
		desc: '<?=$row["description"]?>',
		link: location.href, // 分享链接；在微信上分享时，该链接的域名必须与企业某个应用的可信域名一致
		imgUrl: '<?=getHost() . $row["picture"]?>', // 分享图标
		success: function () {
			// 用户确认分享后执行的回调函数
		},
		cancel: function () {
			// 用户取消分享后执行的回调函数
		}
	});
	
	var imgs = [];
	var host = location.href.split('/index.php')[0];
	$(".content img").each(function(){
		if($(this).width() > 50 && $(this).height() > 50){
			imgs.push(host + $(this).attr("src").split('?')[0]);
		}
	})

	$(".content img").click(function(){
		if($(this).width() > 50 && $(this).height() > 50){
			wx.previewImage({
				current: host + $(this).attr("src").split('?')[0], // 当前显示图片的http链接
				urls: imgs // 需要预览的图片http链接列表
			});
		}
	})
	

	
});

var userVue = new Vue({
	el:"#app",
	data:{
		form:{
			page:1,
			userToken:'<?=$getCommentsToken?>'
		},
		commentCount:0,
		likes:<?=$row["likes"]?>,
		liked:<?=$row["liked"]?>,
		isEndPage:false,
		loading:false,
		dataList:[]
	},
	methods:{
		loadData:function(){
			var that = this;
			if(this.isEndPage == true) return;
			$.ajax({
				url:"<?=autolink(array(get('class'),'getComments','newsid'=>$row["newsid"]))?>",
				type:"POST",
				data:that.form,
				dataType:'json',
				success:function(res){
					if(res.errcode == 0){
						if(res.data){
							that.dataList = that.dataList.concat(res.data);
							if(res.data.length < 100){
								that.isEndPage = true;
							}
						}else{
							that.isEndPage = true;
						}
					}
				}
			})
		},
		doLike(){
			var that = this;
			$.ajax({
				url:"<?=autolink(array(get('class'),'doLike','newsid'=>$row["newsid"]))?>",
				type:"POST",
				data:{userToken:'<?=$userToken?>'},
				dataType:"json",
				success:function(res){
					that.likes = res.total;
					that.liked = res.liked;
				}
			})
		}
	}
});

userVue.loadData();


$(function(){
	
	FastClick.attach(document.body);
	$(document.body).infinite(100).on("infinite", function() {
		var that = this;
		if (userVue.isEndPage == false) {
			if(userVue.loading) return;
			if(userVue.isEndPage) return;
			userVue.loading = true;
			setTimeout(function() {
				userVue.form.page++;
				userVue.loadData();
				userVue.loading = false;
			}, 600);
		}
	});
})
</script>
</body>
</html>