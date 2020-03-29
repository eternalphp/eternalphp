<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="no-store" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/Request.js"></script>

<link rel="stylesheet" type="text/css" href="__PUBLIC__/plugins/validator/validator.css" />
<script type="text/javascript" src="__PUBLIC__/plugins/validator/jquery.validator.js"></script>

<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" media="all">
<script src="__PUBLIC__/plugins/layui/layui.js"></script>


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
		
		.viewList{ height:400px; width:800px; overflow:scroll;}
		.viewList ul{ padding:0px; margin:0px; width:800%;}
		.viewList ul li{ height:30px; line-height:30px; list-style:none; font-size:14px;}
		.viewList ul li span{width:300px;padding:0px 10px;}
    </style>
</head>
<body>
<form id="form1" action="<?=ACTION?>" method="post" class="definewidth m20" target="hidden_frame">
  <table width="100%" class="table table-bordered m10">
  
    <tr>
      <td style="width:200px;" class="tableleft"><?=L("base.select.file")?></td>
      <td> 
			<button type="button" name="button" id="upload" class="btn btn-primary"> <i class="layui-icon">&#xe67c;</i> <?=L("file.upload")?>  </button>
	  </td>
    </tr>
	
    <tr>
      <td class="tableleft">  </td>
      <td>
	  	<div style="overflow:hidden; width:100%;">
			<div class="progress progress-striped active" style="display:none;">
				<div class="bar" style="width: 0%;"></div>
			</div>
			 <div class="viewList" id="make_result">
				<ul>
					
				</ul>
			 </div>
		 </div>
	  </td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td> 
	  <button type="button" name="Submit" class="btn btn-primary">  <?=L("button.submit")?>  </button>    
	  &nbsp;&nbsp;
	  <button type="button" name="button" style="min-width:120px;" class="btn btn-primary downTemplate">  <?=L("base.download")?>  </button>
	  </td>
    </tr>
  </table>
</form>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
</body>
</html>
<script>
$(function(){
	
	var dataList = [];

	var page = 1;
	
	layui.use('upload', function(){
	   var upload = layui.upload;
	   
	   //执行实例
	   var uploadInst = upload.render({
		 elem: '#upload',
		 url: '<?=autolink(array('upload','upfile'))?>',
		 accept:'file',
		 exts:'xls|xlsx',
		 field:'Filedata',
		 done: function(res){
			if(res.filename != ''){
				Request.get("<?=autolink(array(get('class'),'importData'))?>",{filename:res.filename},function(data){
					var html = '';
					dataList = [];
					//dataUserids = [];
					page = 1;
					
					
					html+='<table class="table table-bordered" style="width:auto;">';
					for(var id in data){
						if(id > 0){
							dataList.push(data[id]);
							html+='<tr>';
							for(var k in data[id]){
								html+='<td style="min-width:160px;">'+data[id][k]+'</td>';
							}
							html+='</tr>';
						}else{
							html+='<tr>';
							for(var k in data[id]){
								html+='<th style="min-width:160px;">'+data[id][k]+'</th>';
							}
							html+='</tr>';
						}
					}
					html+='</table>';
					$(".viewList ul").html(html);
					$(":input[name='Submit']").attr("disabled",false);
				})
			}
		 },
		 error: function(){
		 
		 }
	  });
	});
	
	
	$(":input[name='Submit']").click(function(){
		if(dataList.length>0){
			$(".viewList ul").html('');
			runTask();
		}else{
		 	if($(".FilePannel li").length>0){
				Control.message("<?=L("msg.check_excel")?>");
			}else{
				Control.message("<?=L("select.fileUpload")?>");
			}
			return false;
		}
	})
	
	function runTask(){
		
		var pagesize = 10;
		if(dataList.length>500){
			pagesize = 50;
		}
		
		var rows = getData(page++,pagesize);
		if(rows.length>0){
			Request.post("<?=autolink(array(get('class'),'saveData'))?>",{data:rows},function(res){
				if(res.logs){
					var html = '';
					$(res.logs).each(function(index,row){
						html+='<li>'+row+'</li>';
					})
					$(".viewList ul").append(html);
				}

				updateProgress((page-1)*pagesize+rows.length);
				runTask();
			})
		}else{
			$(".viewList ul").append('<li><?=L("import.complete")?></li>');
			$(".progress").hide();
			$(":input[name='Submit']").attr("disabled",true);
		}
		
	}
	
	function getData(page,num){
		var rows = [];
		var start = (page-1)*num;
		for(var index in dataList){
			if(index>=start){
				rows.push(dataList[index]);
			}
			if(rows.length==num){
				break;
			}
		}
		return rows;
	}
	
	function updateProgress(num){
		var value = parseInt((num/dataList.length)*100);
		if(value>0){
			$(".progress").show();
			$(".progress .bar").css("width",value+"%");
			$("#make_result").scrollTop(update_scrollHeight());
		}
	}
	
	function update_scrollHeight(){
	 	 return document.getElementById("make_result").scrollHeight;
	}
	
	$(".downTemplate").click(function(){
		location.href = "<?=(session("roleid")>2)?"/Common/data/employee2.xlsx":"/Common/data/employee.xlsx";?>";
	})

});

</script>
