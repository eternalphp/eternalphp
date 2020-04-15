<?php

use framework\Router\Router;

Router::namespace("Home")->group(function(){
	
	Router::get('test',function(){
	    
	});
	
	Router::get('message',"messageAction@send");
	
	Router::get('login',"loginAction@index");
	
	Router::get('/',"indexAction@index");
	Router::get('/discuss',"discussAction@index");
	Router::get('/discuss/detail',"discussAction@detail");
	Router::post('/discuss/getJson',"discussAction@getJson");
	Router::get('/discuss/create',"discussAction@create");
	Router::post('/discuss/save',"discussAction@save");
	Router::post('/discuss/getComments',"discussAction@getComments");
	Router::post('/discuss/doLike',"discussAction@doLike");
	Router::post('/discuss/doCommentLike',"discussAction@doCommentLike");
	Router::post('/discuss/saveComment',"discussAction@saveComment");
	
	Router::get('/news',"newsAction@index");
	Router::post('/news/getJson',"newsAction@getJson");
	Router::get('/news/detail',"newsAction@detail");
	Router::get('/news/preview',"newsAction@preview");
	Router::get('/news/comment',"newsAction@comment");
	Router::post('/news/getComments',"newsAction@getComments");
	Router::post('/news/doLike',"newsAction@doLike");
	Router::post('/news/saveComment',"newsAction@saveComment");
	
	Router::get('/work',"workAction@detail");
	
	Router::get('/suggest',"suggestAction@index");
	Router::post('/suggest/save',"suggestAction@save");
	
	Router::get('/fontImg',"fontImgAction@index");
	
	Router::get('/login/sos',"loginAction@sosLogin");
	Router::post('/login/verifyLogin',"loginAction@verifyLogin");
	
	Router::get('/weixin/login',"weixinAction@login");
	Router::get('/weixin/getUser',"weixinAction@getUser");
	
	Router::post('/uploadFile',"uploadAction@upload");
	
});

Router::namespace("Admin")->prefix('admin')->group(function(){
	

	
	Router::get('/',"adminAction@index");
	Router::get('/login',"loginAction@index");
	Router::post('/login/chklogin',"loginAction@chklogin");
	Router::get('/login/loginOut',"loginAction@loginOut");
	
	Router::any('/news',"newsAction@index");
	Router::get('/news/add',"newsAction@add");
	Router::get('/news/edit',"newsAction@edit");
	Router::get('/news/detail',"newsAction@detail");
	Router::any('/news/publish',"newsAction@publish");
	Router::get('/news/statistics',"newsAction@statistics");
	Router::post('/news/save',"newsAction@save");
	Router::get('/news/remove',"newsAction@remove");
	Router::get('/news/setTop',"newsAction@setTop");
	Router::get('/news/cancelTop',"newsAction@cancelTop");
	Router::get('/news/uploadImg',"newsAction@uploadImg");
	Router::get('/news/commentList',"newsAction@commentList");
	Router::get('/news/handleClose',"newsAction@handleClose");
	Router::get('/news/handleShow',"newsAction@handleShow");
	
	Router::any('/work',"workAction@index");
	Router::get('/work/add',"workAction@add");
	Router::get('/work/edit',"workAction@edit");
	Router::get('/work/detail',"workAction@detail");
	Router::post('/work/save',"workAction@save");
	Router::get('/work/remove',"workAction@remove");
	
	Router::any('/suggest',"suggestAction@index");
	Router::get('/suggest/detail',"suggestAction@detail");
	Router::post('/suggest/save',"suggestAction@save");
	Router::get('/suggest/remove',"suggestAction@remove");
	Router::post('/suggest/reply',"suggestAction@reply");
	
	Router::any('/discuss',"discussAction@index");
	Router::get('/discuss/add',"discussAction@add");
	Router::get('/discuss/edit',"discussAction@edit");
	Router::get('/discuss/detail',"discussAction@detail");
	Router::post('/discuss/save',"discussAction@save");
	Router::get('/discuss/remove',"discussAction@remove");
	Router::get('/discuss/setTop',"discussAction@setTop");
	Router::get('/discuss/cancelTop',"discussAction@cancelTop");
	Router::get('/discuss/shield',"discussAction@shield");
	Router::get('/discuss/commentList',"discussAction@commentList");
	Router::get('/discuss/handleClose',"discussAction@handleClose");
	Router::get('/discuss/handleShow',"discussAction@handleShow");
	
	Router::get('/party',"partyAction@index");
	Router::get('/party/add',"partyAction@add");
	Router::get('/party/edit',"partyAction@edit");
	Router::post('/party/save',"partyAction@save");
	Router::get('/party/remove',"partyAction@remove");
	
	Router::any('/user',"userAction@index");
	Router::get('/user/add',"userAction@add");
	Router::get('/user/edit',"userAction@edit");
	Router::post('/user/save',"userAction@save");
	Router::get('/user/remove',"userAction@remove");
	Router::get('/user/modifyParty',"userAction@modifyParty");
	Router::get('/user/handleSort',"userAction@handleSort");
	Router::get('/user/memberFee',"userAction@memberFee");
	Router::get('/user/importFile',"userAction@importFile");
	Router::get('/user/exportFile',"userAction@exportFile");
	Router::get('/user/importData',"userAction@importData");
	Router::post('/user/saveData',"userAction@saveData");
	Router::get('/user/selectParty',"userAction@selectParty");
	
	Router::any('/role',"roleAction@index");
	Router::get('/role/add',"roleAction@add");
	Router::get('/role/edit',"roleAction@edit");
	Router::post('/role/save',"roleAction@save");
	Router::get('/role/remove',"roleAction@remove");
	
	Router::any('/account',"accountAction@index");
	Router::get('/account/add',"accountAction@add");
	Router::get('/account/edit',"accountAction@edit");
	Router::post('/account/save',"accountAction@save");
	Router::get('/account/remove',"accountAction@remove");
	Router::any('/account/change',"accountAction@change");
	
	Router::any('/tags',"tagsAction@index");
	Router::get('/tags/add',"tagsAction@add");
	Router::get('/tags/edit',"tagsAction@edit");
	Router::post('/tags/save',"tagsAction@save");
	Router::get('/tags/remove',"tagsAction@remove");
	
	Router::any('/menu',"menuAction@index");
	Router::get('/menu/add',"menuAction@add");
	Router::get('/menu/edit',"menuAction@edit");
	Router::post('/menu/save',"menuAction@save");
	Router::get('/menu/remove',"menuAction@remove");
	Router::get('/menu/detail',"menuAction@detail");
	Router::get('/menu/page',"menuAction@page");
	
	Router::any('/manual',"manualAction@index");
	Router::get('/manual/add',"manualAction@add");
	Router::get('/manual/edit',"manualAction@edit");
	Router::post('/manual/save',"manualAction@save");
	Router::get('/manual/remove',"manualAction@remove");
	
	Router::get('/score',"scoreAction@index");
	Router::get('/score/seeting',"scoreAction@seeting");
	Router::get('/score/party',"scoreAction@party");
	Router::get('/score/member',"scoreAction@member");
	Router::get('/score/remove',"scoreAction@remove");
	
	Router::get('/qrcode',"qrcodeAction@index");
	
	Router::post('/upload/upload',"uploadAction@upload");
	Router::post('/upload/cropper',"uploadAction@cropper");
	Router::post('/upload/upfile',"uploadAction@upfile");
});
