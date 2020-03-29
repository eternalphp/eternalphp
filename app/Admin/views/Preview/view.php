<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>图片查看器</title>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.mousewheel.min.js" ></script>
<script type="text/javascript" src="<?=TEMP_PATH?>/js/jquery.iviewer.js" ></script>
<script type="text/javascript">
$(document).ready(function(){
  var iviewer = {};
  $("#viewer").iviewer({
	  src: "<?=$filename?>",
	  initCallback: function(){
		iviewer = this;
	  }
  });
});
</script>
<link rel="stylesheet" href="<?=TEMP_PATH?>/css/jquery.iviewer.css" />
<style>
.viewer{
	width: 100%;
	height: 600px;
	border: 1px solid #ffffff;
	position: relative;
	text-align:center;
}

.wrapper{
   text-align:center;
	overflow: hidden;
}
</style>
</head>
<body>

<div class="wrapper">
  <div id="viewer" class="viewer"></div>
</div>
</body>
</html>
<script>
$(function(){
   setTimeout(function(){
	  var width = ($(document).width() - $(".viewer img").attr("width"))/2;
	  $(".viewer img").css("left",width);
   },50);
})
</script>