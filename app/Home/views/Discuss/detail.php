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
		.menu{
			margin: 10px 0px 0px 10px;
			color: #6d6d72;
			font-size: 15px;
			line-height:40px;
		}
	</style>
</head>
<body>
<main id="app">
    <div class="focus-news-detail watermark">
        <div class="container-fluid news">
			<div style="font-size:18px; margin-top:20px;">
				<?php if($row["stick"] == 1) {?>   <span class="mui-badge mui-badge-warning">置顶</span> <?php }?> <?=$row["title"]?>
			</div>
            
            <div class="department">
                <img src="__PUBLIC__/images/icon-wjx.png" /><small> <?=$row["name"]?> </small>
            </div>
			
			<div class="content">
				<?=$row["content"]?>
			</div>
			
			<?php if($row["images"]) {?>
			<div class="imageList">
			<?php foreach($row["images"] as $k=>$val) {?>
			<img class="img-width" src="<?=$val["picture"]?>" />
			<?php }?>
			</div>
			<?php }?>
			
			<?php if($row["videos"]) {?>
			<div class="imageList">
			<?php foreach($row["videos"] as $k=>$val) {?>
				<video width="100%" height="240" controls>
				  <source src="<?=$val["filename"]?>" type="video/mp4">
				您的浏览器不支持Video标签。
				</video>
			<?php }?>
			</div>
			<?php }?>
			
			<?php if($row["files"]) {?>
			<div class="mui-content">
				<div class="menu">
					附件列表
				</div>
				<ul class="mui-table-view">
					<?php foreach($row["files"] as $k=>$val) {?>
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" href="<?=$val["filename"]?>" download="<?=$val["title"]?>">
							<?=$val["title"]?>
						</a>
					</li>
					<?php }?>
				</ul>
			</div>
			<?php }?>
			
			<div style="height:20px"></div>
			<div class="mui-row">
				<div class="mui-col-xs-8 mui-col-sm-8"> <?=$row["time"]?> </div>
				<div class="mui-col-xs-2 mui-col-sm-2 text-right"> <a href="javascript:void(0)" @click="doLike"><i class="icon-yz" v-if="liked == true"></i><i class="icon-dz" v-else></i><span>{{likes}}</span></a> </div>
				<div class="mui-col-xs-2 mui-col-sm-2 text-right"> <i class="icon-fx"></i><span>{{dataList.length}}</span> </div>
			</div>
			
        </div>
		
		<div class="newsComments">
			<div class="mui-content">
				<div class="title">
					全部评论（{{dataList.length}}）
				</div>
				<ul class="mui-table-view">
					<li class="mui-table-view-cell mui-media" v-for="(row,index) in dataList">
						<a href="javascript:;">
							<img class="mui-media-object mui-pull-left" src="__PUBLIC__/images/img-user2.png">
							<div class="mui-media-body">
								<div class="mui-row">
									<div class="mui-col-xs-6 mui-col-sm-6"> <span v-if="row.parentid == 0" style="color:#005EBB;">{{row.name}}</span> <span v-else><font color="#005EBB">{{row.name}}</font> 回复 <font color="#005EBB">{{row.touser}}</font></span> ：</div>
									<div class="mui-col-xs-6 mui-col-sm-6 text-right"> {{row.time}} </div>
								</div>
								<div class="commnet">
									<p>{{row.content}}</p>
								</div>
								<div class="mui-row">
									<div class="mui-col-xs-8 mui-col-sm-8"> </div>
									<div class="mui-col-xs-2 mui-col-sm-2 text-right" @click="doCommentLike(row)"> <i class="icon-yz" v-if="row.liked == true"></i> <i class="icon-dz" v-else></i> <span>{{row.likes}}</span> </div>
									<div class="mui-col-xs-2 mui-col-sm-2 text-right" @click="doComment(row)"> <i class="icon-fx"></i><span>{{row.comments}}</span> </div>
								</div>

							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="reply-input">
            <div class="mui-row">
				<div class="mui-col-xs-10 text-center">
					<input name="content" type="text" v-model="saveForm.content" :placeholder="toUser" style="width:96%;" />
				</div>
				<div class="mui-col-xs-2 text-center">
					<input name="submit" type="button" @click="onSubmit" value=" 提交 " style="margin:3px 0px; height:35px; width:80%;">
				</div>
            </div>
        </div>
		
    </div>
</main>
<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/weiui/lib/fastclick.js"></script>
<script src="__PUBLIC__/weiui/js/jquery-weui.js"></script>
<script src="__PUBLIC__/artTemplate/template.js"></script>
<script src="__PUBLIC__/js/vue.min.js"></script>
<script src="__PUBLIC__/js/mui.min.js"></script>
<script src="__PUBLIC__/js/common.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
wx.config(<?=json_encode($config)?>);
wx.ready(function(){

	wx.hideOptionMenu();

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
		saveForm:{
			cid:0,
			content:'',
			userToken:'<?=$commentToken?>'
		},
		userid:'<?=session("userid")?>',
		toUser:'@楼主：',
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
		onSubmit(){
			var that = this;
			$.ajax({
				url:"<?=autolink(array(get('class'),'saveComment','newsid'=>$row["newsid"]))?>",
				type:"POST",
				data:that.saveForm,
				dataType:'json',
				success:function(res){
					if(res.errcode == 0){
						that.form.page = 1;
						that.dataList = [];
						that.loadData();
						that.saveForm.content = '';
						that.toUser = '@楼主：';
						that.cid = 0;
					}else{
						mui.alert(res.errmsg);
					}
				}
			})
		},
		doComment(row){
			if(row.userid != this.userid){
				this.saveForm.cid = row.cid;
				this.toUser = '@' + row.name + "：";
			}
		},
		doLike(){
			var that = this;
			$.ajax({
				url:"<?=autolink(array(get('class'),'doLike','newsid'=>$row["newsid"]))?>",
				type:"POST",
				data:{userToken:'<?=$likeToken?>'},
				dataType:"json",
				success:function(res){
					that.likes = res.likes;
					that.liked = res.liked;
				}
			})
		},
		doCommentLike(row){
			var that = this;
			$.ajax({
				url:"<?=autolink(array(get('class'),'doCommentLike'))?>?cid="+row.cid,
				type:"POST",
				data:{userToken:row.userToken},
				dataType:"json",
				success:function(res){
					row.likes = res.likes;
					row.liked = res.liked;
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