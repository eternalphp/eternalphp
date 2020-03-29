
<html>
<head>
    <meta charset="utf-8">
    <title>学习园地</title>
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
		.main{ display:none;}
	</style>
</head>
<body>
<main id="app">
<div class="mui-content">

	<div class="tab-control">
		<div class="mui-row">
			<div class="mui-col-xs-5 mui-col-sm-5 text-center tab-column"><a href="javascript:void(0)" @click="handle(2)" :class="(form.typeid == 2)?'active':''">港航新闻</a></div>
			<div class="mui-col-xs-2 mui-col-sm-2">
				<div class="split"></div>
			</div>
			<div class="mui-col-xs-5 mui-col-sm-5 text-center tab-column"><a href="javascript:void(0)" @click="handle(1)" :class="(form.typeid == 1)?'active':''">学习资料</a></div>
		</div>
	</div>

		
	<div class="mui-card" v-for="(row,index) in dataList">
		<a :href="row.url">
		<div class="mui-card-header">{{row.title}}</div>
		<div class="mui-card-header mui-card-media thumbnail" :style="(row.picture != '')?'background-image:url('+row.picture+')':''">
		</div>
		<div class="mui-card-content">
			<div class="mui-card-content-inner">
				<p style="color: #333;max-height:45px;height:25px; text-overflow: ellipsis;overflow: hidden;">{{row.description}}...</p>
				<p><span style="color:#8f8c8c">时间：{{row.createtime}}  &#8226;</span> <span style="color:#8f8c8c" v-if="row.read == 1">已读</span> <span class="text-danger" v-else>未读</span> </p>
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
			typeid:2
		},
		isEndPage:false,
		isLoadData:false,
		loading:false,
		dataList:[]
	},
	methods:{
		loadData(){
			var that = this;
			$.ajax({
				url:"<?=autolink(array(get('class'),'getJson'))?>",
				type:"POST",
				data:that.form,
				dataType:'json',
				success:function(res){
					if(res.errcode == 0){

						that.dataList = that.dataList.concat(res.data);
						
						if(res.data.length < 10){
							that.isEndPage = true;
						}
						
					}else{
						that.isEndPage = true;
					}
					
					console.log(that.dataList);
					
					that.isLoadData = true;
					that.loading = false;
				}
			})
		},
		handle(typeid){
			this.form.typeid = typeid;
			this.form.page = 1;
			this.dataList = [];
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