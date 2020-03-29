<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/vue.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script src="__PUBLIC__/plugins/layer/layer.js"></script>
<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" media="all">
<script src="__PUBLIC__/plugins/layui/layui.js"></script>
<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
        .user-bold{ font-weight: bold;}
        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
		
		.answerForm{ display:none;}
		
		.rowLine{ margin:10px 0px;}
		.buttons{ margin-top:10px;}
		.answerType{ margin:10px 0px;}
		.answer{ margin:10px 0px;}

    </style>
</head>
<body>

  <table width="100%" class="table table-bordered definewidth m10">
    <tr>
      <td class="tableleft">标题</td>
      <td><?=$row["title"]?></td>
    </tr>
    <tr>
      <td height="120" valign="top" class="tableleft">内容介绍</td>
      <td valign="top"><div class="content"><?=$row["content"]?></div></td>
    </tr>
	
	<?php if($row["picture"] != '') {?>
    <tr>
      <td class="tableleft">图片</td>
      <td>
			<?php if($row["picture"]!='') {?>  <img src="<?=$row["picture"]?>" width="200" class="thumbnail" /> <?php }?>
	  </td>
    </tr>
	<?php }?>
	
    <tr>
      <td class="tableleft">发送部门</td>
      <td><?=$row["party_name"]?></td>
    </tr>
	
    <tr>
      <td class="tableleft">下发人员</td>
      <td><?=isset($row["toUser"])?$row["toUser"]:'未发送'?></td>
    </tr>
	
</table>

<?php if(isset($row["readUser"]) && $row["readUser"]) {?>
  <table width="100%" class="table table-bordered table-hover definewidth m10">
    <tr>
      <th align="left">已阅人员：</th>
    </tr>
    <tr>
      <td><?=$row["readUser"]?></td>
    </tr>
  </table>
  <?php }?>
  
  <?php if(isset($row["unReadUser"]) && $row["unReadUser"]) {?>
  <table width="100%" class="table table-bordered table-hover definewidth m10">
    <tr>
      <th align="left">未阅人员：</th>
    </tr>
    <tr>
      <td><?=$row["unReadUser"]?></td>
    </tr>
  </table>
  <?php }?>
  
<div id="app">
<template v-if="commentList.length > 0">
<br>
<table width="100%" class="table table-bordered definewidth m10">
    <tr>
        <th height="32">评论</th>
    </tr>

    <tr v-for="(row,index) in commentList">
        <td><div class="rowLine"> 
			<span class="rowCell">
				{{ row.name }} ({{ row.party }})
            </span>
			<span class="rowCell">
				{{ row.createtime }}
            </span>

				<template  v-if="row.status == 1">
					<span class="rowCell">
					<button class="btn btn-small btn-danger handleClose" @click="handleClose(row)" type="button"> 屏蔽 </button>
					</span>
				</template>
				<template v-else>
					<span class="rowCell">(已屏蔽)</span>
					<span class="rowCell">
					<button class="btn btn-small btn-primary handleShow" @click="handleShow(row)" type="button"> 显示 </button>
					</span>
				</template>
            </div>
            <div class="rowLine">
				{{ row.content }}
            </div>
		</td>
    </tr>
</table>
<div class="definewidth">
	<div class="pagination" style="float:right;">
		  <ul>
				<li><a href="javascript:void(0)" style="width:120px; text-align:center;" @click="handlePageNext"> <i class="icon-refresh"></i> 加载更多>>> </a></li>
		  </ul>
	</div>
</div>
</template>
</div>
  
</body>
</html>
<script>
$(function(){
	var vue = new Vue({
		el:"#app",
		data:{
			page:1,
			total:0,
			isPageEnd:false,
			answerForm:{
				commenid:'',
				display:false
			},
			commentList:[],
			saveForm:{
				commentid:'',
				newsid:'',
				userid:'',
				answerType:'',
				answer:''
			}
		},
		methods:{
			loadList:function(){
				var that = this;
				$.ajax({
					url:"<?=autolink(array(get('class'),'commentList','newsid'=>$row["newsid"]))?>",
					type:"GET",
					data:{page:this.page},
					dataType:"json",
					success:function(res){
						if(res.errcode == 0){
							if(that.commentList.length == 0){
								that.commentList = res.list;
								that.total = res.total;
							}else{
								res.list.forEach(function(row){
									that.commentList.push(row);
								})
							}
						}else{
							that.isPageEnd = true;
						}
					}
				})
			},
			handleShow(row){
				var that = this;
				var index = layer.confirm("确定要显示此条评论？",function(){
					$.ajax({
						url: "<?=autolink(array(get('class'),'handleShow'))?>",
						type:"GET",
						data:{id:row.id},
						dataType:"json",
						success:function(res){
							if(res.errcode == 0){
								row.status = 1;
								layer.close(index);
							}
						}
					})
				});
			},
			handleClose(row){
				var that = this;
				var index = layer.confirm("确定要屏蔽此条评论？",function(){
					$.ajax({
						url: "<?=autolink(array(get('class'),'handleClose'))?>",
						type:"GET",
						data:{id:row.id},
						dataType:"json",
						success:function(res){
							if(res.errcode == 0){
								row.status = 0;
								layer.close(index);
							}
						}
					})
				});
			},
			handleAnswer(row){
				this.answerForm.display = true;
				this.answerForm.commenid = row.id;
				this.saveForm.newsid = row.newsid;
				this.saveForm.userid = row.userid;
				this.saveForm.commentid = row.id;
				this.saveForm.answerType = row.status;
				this.saveForm.answer = '';
			},
			handlePageNext(){
				if(this.page < this.total){
					this.page++;
					this.loadList();
				}else{
					layer.alert("已经是最后一页");
				}
			}
		}
	})

	vue.loadList();
})
</script>