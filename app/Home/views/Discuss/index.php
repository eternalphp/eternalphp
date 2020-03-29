<html>
<head>
    <meta charset="utf-8">
    <title>红锚论坛</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />
	<meta name="csrf-token" content="<?=csrf_token()?>" />

    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__PUBLIC__/css/global.css">
	
	<link rel="stylesheet" href="__PUBLIC__/weiui/lib/weui.min.css">
	<link rel="stylesheet" href="__PUBLIC__/weiui/css/jquery-weui.css">
	
	<style>
		.watermark{  background:url(<?=U("fontImg",array('name'=>urlencode(session("name"))))?>);}
	</style>
</head>
<body>
<main id="app">
<div class="mui-content" style="margin-bottom:50px;">
	
	<div class="discuss-search">
		<div class="mui-row">
			<div class="mui-col-xs-12 mui-col-sm-12">
				<input name="keyword" v-model="form.keyword" type="text" placeholder="请输入主题关键词/发帖人姓名" @input="doSearch" />
				<i class="icon-search" @click="doSearch"></i>
			</div>
		</div>
	</div>


	<div class="mui-card" v-for="(row,index) in dataList">
		<a :href="row.url">
		<div class="mui-card-header"><div>{{row.title}} <span class="mui-badge mui-badge-warning" v-if="row.stick > 0">置顶</span></div></div>
		<div class="mui-card-header" v-if="row.images.length > 0">
			<div class="mui-row">
				<template v-for="(val,k) in row.images">
				<div class="mui-col-xs-4 mui-col-sm-4">
					<img v-if="k<3" :src="val.picture" class="mui-card-media discussImg" />
				</div>
				</template>
			</div>
		</div>
		<div class="mui-card-content">
			<div class="mui-card-content-inner">
				<div class="mui-row">
					<div class="mui-col-xs-6 mui-col-sm-6">
						<p><span style="color:#8f8c8c">{{row.name}}  {{row.time}}</span>  </p>
					</div>
					<div class="mui-col-xs-6 mui-col-sm-6">
						<div class="mui-row">
							<div class="mui-col-xs-4 mui-col-sm-4 text-right">
								<i class="icon-record"></i> {{row.views}}
							</div>
							<div class="mui-col-xs-4 mui-col-sm-4 text-right">
								<i class="icon-dz"></i> {{row.likes}}
							</div>
							<div class="mui-col-xs-4 mui-col-sm-4 text-right">
								<i class="icon-fx"></i> {{row.reviews}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</a>
	</div>
	<div style="clear:both;"></div>

	<div class="weui-loadmore">
	  <i class="weui-loading"></i>
	  <span class="weui-loadmore__tips"> 正在加载…… </span>
	</div>

</div>

<div class="footer-display">
	<a class="col-xs-12 fb" href="<?=autolink(array('discuss','create'))?>">发布话题</a>
</div>

</main>

<script src="__PUBLIC__/js/jquery-1.11.1.min.js"></script>
<script src="__PUBLIC__/weiui/lib/fastclick.js"></script>
<script src="__PUBLIC__/weiui/js/jquery-weui.js"></script>
<script src="__PUBLIC__/js/vue.min.js"></script>
<script src="__PUBLIC__/js/common.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
wx.config(<?=json_encode($config)?>);
wx.ready(function(){
	wx.hideOptionMenu();
});

var userVue = new Vue({
	el:"#app",
	data:{
		form:{
			page:1,
			keyword:''
		},
		isEndPage:false,
		isLoadData:false,
		loading:false,
		dataList:[]
	},
	methods:{
		loadData(){
			var that = this;
			
			if(this.isEndPage == true) return;
			
			$.ajax({
				url:"<?=autolink(array(get('class'),'getJson'))?>",
				type:"POST",
				data:that.form,
				dataType:'json',
				success:function(res){
					if(res.errcode == 0){
                        
                        if(res.data){
    						that.dataList = that.dataList.concat(res.data);
    						
    						if(res.data.length < 10){
    							that.isEndPage = true;
    						}
                        }else{
                            that.isEndPage = true;
                        }
						
					}else{
						that.isEndPage = true;
					}
					
					that.isLoadData = true;
					that.loading = false;
				}
			})
		},
		doSearch(){
			this.dataList = [];
			this.form.page = 1;
			this.loadData();
		}
	}
});

userVue.loadData();


$(function(){

	FastClick.attach(document.body);
	
	$.showLoading();
	
	setTimeout(function() {
	  $(".main").show();
	  $.hideLoading();
	}, 500);
	
	
	$(".weui-loadmore").hide();

	$(document.body).infinite().on("infinite", function() {
		var that = this;
		if(userVue.isEndPage) return;
		if(userVue.loading) return;
		$("#loading").show();
		userVue.loading = true;
		setTimeout(function() {
			
			if (userVue.isEndPage == false) {
				userVue.form.page++;
				userVue.loadData();
				$("#loading").hide();
			}
			
		}, 600);
	});
	
})

</script>

</body>
</html>