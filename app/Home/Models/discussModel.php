<?php

namespace App\Home\Models;
use App\Librarys\Emoji;
use App\Librarys\SLSLog;

class discussModel extends BaseModel {
	
	
	const CANCEL_LIKED = true; //是否可取消点赞
	
	public function __construct(SLSLog $SLSLog) {
		parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('红锚论坛');
		$this->onQuery();
	}
	
	public function index(){
		
		$where = array();

		if(request("keyword") != ''){
			$keyword = request("keyword");
			$where[] = "(t1.title like '%".$keyword."%' or t2.name like '%".$keyword."%')";
		}
		
		$where[] = "t1.isdelete=0";
		$where = implode(" and ",$where);
		$list = $this->model->table("discuss as t1")
		->join("user as t2","t1.userid=t2.userid")
		->field("t1.*,t2.name")
		->where($where)
		->offset(C("offset"))
		->order("t1.stick desc,t1.createtime desc")
		->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		if($list){
			foreach($list as $k=>$val){
				$res = $this->getUserName($val["userid"]);
				if($res){
					$list[$k]["name"] = sprintf("%s(%s)",$res["name"],$res["partyName"]);
				}
				$url = autolink(array(get('class'),'detail','newsid'=>$val["newsid"]));
				$list[$k]["url"] = shortUrl($url);
				$list[$k]["createtime"] = date("Y-m-d H:i",strtotime($val["createtime"]));
				
				$list[$k]["time"] = time2Units($val["createtime"]);
				
				$list[$k]["images"] = $this->model->table("discuss_picture")->field()->where("newsid",$val["newsid"])->select();
			}
		}
		
		return $list;
	}
	
	public function getRow(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			
			$row = $this->table("discuss")->field()->where("newsid",$newsid)->find();
			if($row){
				$row["time"] = time2Units($row["createtime"]);
				$res = $this->getUserName($row["userid"]);
				if($res){
					$row["name"] = sprintf("%s(%s)",$res["name"],$res["partyName"]);
				}

				$row["images"] = $this->model->table("discuss_picture")->where("newsid=$newsid")->select();
				$row["videos"] = $this->model->table("discuss_files")->where("newsid=$newsid")->where("type=1")->select();
				$row["files"] = $this->model->table("discuss_files")->where("newsid=$newsid")->where("type=2")->select();
				
				$row["liked"] = 0;
				$res = $this->table("discuss_liked")->where("newsid=$newsid")->where("userid",session("userid"))->find();
				if($res){
					$row["liked"] = true;
				}
				
				//判断是否已读
				$query = $this->table("discuss_user")->where("newsid=$newsid")->where("userid",session("userid"));
				$res = $query->find();
				if($res){
					if($res["read"] == 0){
						$query->update(array(
							'read'=>1,
							'readtime'=>date("Y-m-d H:i:s")
						));
						$this->model->table("discuss")->where("newsid=$newsid")->update("views=views+1");
					}
				}else{
					$this->model->table("discuss_user")->insert(array(
						'newsid'=>$newsid,
						'userid'=>session("userid"),
						'read'=>1,
						'readtime'=>date("Y-m-d H:i:s")
					));
					$this->model->table("discuss")->where("newsid=$newsid")->update("views=views+1");
				}
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
			return $row;
		}
	}
	
	function getComments(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$list = $this->model->table("discuss_comment as t1")
			->join("user as t2","t1.userid=t2.userid")
			->field("t1.*,t2.name,t2.avatar_url")
			->where("t1.newsid=$newsid")
			->where("t1.status<2")
			->where("t2.isdelete=0")
			->offset(C("offset"))
			->select();
			
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
			
			if($list){
				foreach($list as $k=>$val){
					$list[$k]["touser"] = '';
					if($val["parentid"] > 0){
						$res = $this->table("discuss_comment")->where("cid",$val["parentid"])->find();
						if($res){
							$user = $this->getUserName($res["userid"]);
							$list[$k]["touser"] = $user["name"];
						}
					}
					$list[$k]["time"] = time2Units($val["createtime"]);
					$list[$k]["liked"] = 0;
					$res = $this->model->table("discuss_comment_liked")->field()->where("cid",$val["cid"])->where("userid",$this->session("userid"))->find();
					if($res){
						$list[$k]["liked"] = true;
					}
					
					$list[$k]["comments"] = $this->table("discuss_comment")->where("parentid",$val["cid"])->count('userid')->getVal('total');
				}
			}
			return $list;
		}
	}
	
	//对正文点赞
	function doLike(){
		
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$likes = 0;
			$res = $this->model->table("discuss")->field("likes")->where("newsid=$newsid")->find();
			if($res){
				$likes = $res["likes"];
			}
			$row = $this->model->table("discuss_liked")
			->field("id")
			->where("newsid=$newsid")
			->where("userid",$this->session("userid"))
			->find();
			if($row){
				
				$liked = true;
				if(self::CANCEL_LIKED == true){
					$this->model->table("discuss_liked")->where("id",$row["id"])->delete();
					$this->model->table("discuss")->where("newsid=$newsid")->update("likes=likes-1");
					$likes--;
					$liked = false;
				}
				
				echo success(array('errmsg'=>'ok','likes'=>$likes,'liked'=>$liked));
			}else{
				$this->model->table("discuss_liked")->insert(array(
					'newsid'=>$newsid,
					'userid'=>$this->session("userid"),
					'createtime'=>date("Y-m-d H:i:s")
				));
				
				$this->model->table("discuss")->where("newsid=$newsid")->update("likes=likes+1");
				
				$likes++;

				echo success(array('errmsg'=>'ok','likes'=>$likes,'liked'=>true));
			}
		}
		
		$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
	}	
	
	//对评论点赞
	function doCommentLike(){
		
		if(requestInt("cid") > 0){
			$cid = requestInt("cid");
			
			$likes = 0;
			$res = $this->model->table("discuss_comment")->field("likes")->where("cid=$cid")->find();
			if($res){
				$likes = $res["likes"];
			}
			$row = $this->model->table("discuss_comment_liked")
			->field("id")
			->where("cid=$cid")
			->where("userid",$this->session("userid"))
			->find();
			if($row){
				
				$liked = true;
				if(self::CANCEL_LIKED == true){
					$this->model->table("discuss_comment_liked")->where("id",$row["id"])->delete();
					$this->model->table("discuss_comment")->where("cid=$cid")->update("likes=likes-1");
					$likes--;
					$liked = false;
				}
				
				echo success(array('errmsg'=>'ok','likes'=>$likes,'liked'=>$liked));
			}else{
				$this->model->table("discuss_comment_liked")->insert(array(
					'cid'=>$cid,
					'userid'=>$this->session("userid"),
					'createtime'=>date("Y-m-d H:i:s")
				));
				
				$this->model->table("discuss_comment")->where("cid=$cid")->update("likes=likes+1");
				
				$likes++;
				
				echo success(array('errmsg'=>'ok','likes'=>$likes,'liked'=>true));
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
		}
	}
	
	//提交评论
	function saveComment(){

		if(empty($_POST["content"])){
			echo fail(array('errmsg'=>'回复内容不能为空'));
			return false;
		}
		
		$Emoji = new Emoji();
		$_POST["content"] = $Emoji->emoji($_POST["content"]);
		$newsid = requestInt("newsid");
		$cid = requestInt("cid",0);
		
		$_POST["content"] = format_text($_POST["content"]);
		if(empty($_POST["content"])){
			echo fail(array('errmsg'=>'请输入内容'));
			exit;
		}
		
		$_POST["status"] = 1;
		$res = $this->model->table("discuss_comment")->insert(array(
			'parentid'=>$cid,
			'newsid'=>$newsid,
			'userid'=>session("userid"),
			'content'=>request("content"),
			'createtime'=>date("Y-m-d H:i:s")
		));
		
		$this->SLSLog->handle(SLSLog::OPT_ADD);
		
		if($res){
			$this->model->table("discuss")->where("newsid=$newsid")->update("reviews=reviews+1");
			echo success(array('errmsg'=>'提交成功'));
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
		}else{
			echo fail(array('errmsg'=>'提交失败'));
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->error(SLSLog::ERR_USER_INPUT)->write();
		}
	}
	
	function getDetails(){
		if(request("newsid")>0){
			$newsid = request("newsid");
			$row = $this->model->table("discuss")->field("likes,reviews,views")->where("newsid=$newsid")->find();
			echo success(array('errmsg'=>'ok','row'=>$row));
		}
	}
	
	function save(){
		
		$token = request("token");
		if(empty($token) || $token != session("token")){
			echo fail('非法提交');
			exit;
		}
		
		$_POST["userid"] = $this->session("userid");
		$_POST["createtime"] = date("Y-m-d H:i:s");
		
		$this->SLSLog->handle(SLSLog::OPT_ADD);
		
		$_POST["title"] = format_text($_POST["title"]);
		$_POST["content"] = format_text($_POST["content"]);
		if(empty($_POST["title"]) || empty($_POST["content"])){
			echo fail('请输入内容');
			exit;
		}
		
		$newsid = $this->model->table("discuss")->insert($_POST);
		if($newsid > 0){
			
			if(isset($_POST["file"])){
				$data = array();
				$picture = null;
				foreach($_POST["file"] as $base64_image_content){
					$filename = $this->uploadBase64File($base64_image_content);
					if($filename){
						$data[] = array(
							'newsid'=>$newsid,
							'picture'=>$filename
						);
						if($picture == null) $picture = $filename;
					}
				}
				
				if($data) $this->model->table("discuss_picture")->insert($data,true);
				if($picture != null){
					$this->model->table("discuss")->where("newsid=$newsid")->update("picture='$picture'");
				}
			}
			
			if(isset($_POST["files"])){

				foreach($_POST["files"] as $k=>$val){
					$name = basename($val["filename"]);
					$names = explode(".",$name);
					$type = (strtolower($names[1]) == 'mp4') ? 1 : 2;
					$_POST["files"][$k]["type"] = $type;
					$_POST["files"][$k]["newsid"] = $newsid;
				}
				$this->model->table("discuss_files")->insert($_POST["files"],true);
			}

			$this->session("token",null);
			echo success('提交成功');
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
		}else{
			echo fail('提交失败');
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->error(SLSLog::ERR_USER_INPUT)->write();
		}
	}
	
	function getDiscussCount(){
		return $this->model->table("discuss_user as t1")
		->join("discuss as t2","t1.newsid=t2.newsid")
		->where("t1.userid",session("userid"))
		->where("t1.read=0")
		->where("t2.isdelete=0")
		->count("t1.id")
		->getVal('total');
	}
}

?>