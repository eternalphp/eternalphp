<?php

namespace App\Home\Models;
use App\Librarys\SLSLog;

class newsModel extends BaseModel {
	
	public function __construct(SLSLog $SLSLog) {
		parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('学习园地');
	}
	
	public function index(){
		
		$where = array();
		if(requestInt("typeid") > 0){
			$typeid = requestInt("typeid");
			$where[] = "typeid=$typeid";
		}
		$where[] = "status=3";
		$where[] = "isdelete=0";
		$where = implode(" and ",$where);
		$list = $this->model->table("news")
		->field("newsid,title,description,picture,createtime,pubdate")
		->where($where)
		->order("stick desc,createtime desc")
		->offset(10)
		->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		if($list){
			foreach($list as $k=>$val){
				$url = autolink(array(get('class'),'detail','newsid'=>$val["newsid"]));
				$list[$k]["url"] = shortUrl($url);
				$pubdate = ($val["pubdate"] != '')?$val["pubdate"]:$val["createtime"];
				$list[$k]["createtime"] = date("Y-m-d H:i",strtotime($pubdate));
				$list[$k]["read"] = 0;
				$res = $this->table("news_user")
				->where("newsid",$val["newsid"])
				->where("userid",session("userid"))
				->where("`read`=1")
				->find();
				if($res){
					$list[$k]["read"] = 1;
				}
			}
		}

		return $list;
	}
	

	
	public function getRow(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			
			$this->onQuery();

			$row = $this->model->table("news")->where("newsid",$newsid)->find();
			
			if($row){

				$user = $this->model->table("news_user")->where("newsid=$newsid")->where("userid='%s'",session("userid"))->find();

				$row["pubdate"] = ($row["pubdate"] != '') ? $row["pubdate"] : date("Y-m-d",strtotime($row["createtime"]));
				$row["liked"] = 0; //是否点赞
				$row["likes"] = 0; //点赞数
				$row["reads"] = 0; //阅读数
				$row["description"] = str_replace("\r\n","",$row["description"]);
				
					
				//判断是否点赞有效阅读
				if($user){
					
					if($user["read"] == 0){
						
						//已下发的用户记录阅读
						if($row["status"] == 3){
							$this->model->table("news_user")->where("id",$user["id"])->update(array(
								'read'=>1,
								'readtime'=>date("Y-m-d H:i:s")
							));
						}
					}
					
					if($user["like"] == 1){
						$row["liked"] = 1;
					}
					
				}else{
					
					if($row["status"] == 3){
						//未下发的人员记录阅读数
						$this->model->table("news_user")->insert(array(
							'newsid'=>$newsid,
							'userid'=>session("userid"),
							'partyid'=>session("partyid"),
							'read'=>1,
							'createtime'=>date("Y-m-d H:i:s"),
							'readtime'=>date("Y-m-d H:i:s")
						));

					}else{
						//未发布状态仅记录人员
						$this->model->table("news_user")->insert(array(
							'newsid'=>$newsid,
							'userid'=>session("userid"),
							'partyid'=>session("partyid"),
							'read'=>0,
							'createtime'=>date("Y-m-d H:i:s")
						));
					}

				}

				
				//计算点赞数、阅读数
				$counts = $this->model->table("news_user")
				->where("newsid=$newsid")
				->field("sum(`read`) as readCount,sum(`like`) as likeCount")
				->find();
				if($counts){
					$row["reads"] = $counts["readCount"];
					$row["likes"] = $counts["likeCount"];
				}
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
			return $row;
		}
	}
	
	//获取评论列表
	public function getComments(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$list = $this->model->table("news_comment as t1")
			->join("user as t2","t1.userid=t2.userid")
			->where("t1.newsid=$newsid")
			->where("t1.status<2") //1 已审核 2:屏蔽
			->field("t1.*,t2.name")
			->offset(100)
			->order("t1.createtime desc")
			->select();
			
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
			
			return $list;
		}
	}
	
	//点赞功能
	function dolike(){
		if(requestInt("newsid") > 0){
			
			$this->onQuery();
			
			$newsid = requestInt("newsid");
			$row = $this->model->table("news_user")
			->where("newsid=$newsid")
			->where("userid='%s'",session("userid"))
			->find();
			if($row){
				$liked = false;
				if($row["like"] == 0){
					$this->model->table("news_user")->where("id",$row["id"])->update(array(
						'like'=>1,
						'liketime'=>date("Y-m-d H:i:s")
					));
					$liked = true;
					
				}else{
					
					//取消点赞
					$this->model->table("news_user")->where("id",$row["id"])->update(array(
						'like'=>0
					));
				}
				$total = $this->model->table("news_user")
				->where("newsid=$newsid")
				->where("`like`=1")
				->count("userid")
				->getVal('total');
				
				echo success(array('total'=>$total,'liked'=>$liked));
			}else{
				echo fail();
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
		}
	}
	
	//保存评论数据
	function saveComment(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$content = $_POST["content"];
			$_POST["content"] = format_text($_POST["content"]);
			if(empty($_POST["content"])){
				echo fail(array('errmsg'=>'请输入内容'));
				exit;
			}
			
			$_POST["status"] = 1;
			$res = $this->model->table("news_comment")->insert(array(
				'newsid'=>$newsid,
				'userid'=>session("userid"),
				'content'=>$content,
				'createtime'=>date("Y-m-d H:i:s")
			));
			if($res){
				echo success();
			}else{
				echo fail();
			}
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		}
	}
	
	function getNewsType(){
		if(request("typeid")>0){
			$typeid = request("typeid");
			return $this->model->table("news_type")->field()->where("typeid=$typeid")->find();
		}
	}
	
}

?>