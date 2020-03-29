<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$

namespace App\Admin\Models;
use System\Util\Wechat\smgArticle;
use System\Util\Wechat\smgNews;
use App\Librarys\SLSLog;

class newsModel extends BaseModel{
	
    public function __construct(SLSLog $SLSLog) {
        parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('意见建议');
		$this->onQuery();
    }
	
    public function index() {
		
		$newsTypeList = $this->model->toList('typeid','name',$this->getNewsType());
		$statusList = array('','草稿','预览','发布');
		
        $where = array();
        if (request("starttime") != '' && request("endtime") != '') {
            $starttime = request("starttime");
            $endtime = request("endtime");
            $endtime = $endtime." 23:59:59";
            $where[] = "t1.createtime between '$starttime' and '$endtime'";
        }
        if (request("keyword")) {
            $keyword = request("keyword");
            $where[] = "t1.title like '%" . $keyword . "%'";
        }
		
		if(requestInt("typeid")>0){
			$typeid = requestInt("typeid");
			$where[] = "t1.typeid=$typeid";
		}
		
		if(request("status") > 0){
			$status = requestInt("status");
			$where[] = "t1.status=$status";
		}
		
		if(requestInt("partyid")>0){
			$partyid = requestInt("partyid");
			$where[]="t1.partyid = $partyid";
		}
		
		if(session("roleid")>2){
			if(session("masterPartyid")){
				$partyids = $this->models("party")->getChildId(session("masterPartyid"));
				if($partyids){
					$where[]="t1.partyid in (".implode(",",$partyids).")";
				}
			}else{
				$partyids = $this->models("party")->getChildId(session("partyid"));
				if($partyids){
					$where[]="t1.partyid in (".implode(",",$partyids).")";
				}
			}
		}
		
		if(requestInt("status") > 0){
			$status = requestInt("status");
			$where[] = "t1.status=$status";
		}
		$where[] = "t1.isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('news as t1')
		->join("party as t2","t1.partyid=t2.partyid")
		->field("t1.*,t2.name as party_name")
		->where($where)
		->offset(30)
		->order("t1.createtime desc")->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["type"] = $newsTypeList[$val["typeid"]];
				$list[$k]["statusText"] = $statusList[$val["status"]];
			}
		}
        return $list;
    }
	
    public function save() {
		if(requestInt("newsid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
    }
	
	public function create(){
		$_POST["createtime"] = date("Y-m-d H:i:s");
		$_POST["userid"] = $this->session("userid");
		$_POST["status"] = 1;
		if ($this->session("roleid") <= 2) {
			$_POST["create_partyid"] = 0;
		}else{
			$_POST["create_partyid"] = $this->session("partyid");
		}
		$_POST["partyid"] = $this->session("partyid");
		$_POST["typeid"] = request("typeid");
		$_POST["content"] = $this->getHtmlContent($_POST["content"]);
		
		$newsid = $this->model->table("news")->insert($_POST);
		
		$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->write();
		
		if ($newsid>0) {
			
			if(request("preview") == 1){
				$urls = array();
				$preview = url('news/preview',array('newsid'=>$newsid));
				$urls["preview"] = urlencode(getHost().$preview);
				$urls["url"] = autolink(array(get('class'),'save','newsid'=>$newsid));
				echo success(array('errmsg'=>'提交成功','urls'=>$urls),'parent.preview');
			}else{
				echo success("提交成功");
			}
			
		} else {
			echo fail("提交失败");
		}
	}
	
	public function modify(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			
			$_POST["content"] = $this->getHtmlContent($_POST["content"]);
			$result = $this->model->table("news")->where("newsid=$newsid")->update($_POST);
			
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			
			if ($result) {

				if(request("preview") == 1){
					$urls = array();
					$preview = url('news/preview',array('newsid'=>$newsid));
					$urls["preview"] = urlencode(getHost().$preview);
					$urls["url"] = autolink(array(get('class'),'save','newsid'=>$newsid));
					echo success(array('errmsg'=>'提交成功','urls'=>$urls),'parent.preview');
				}else{
					echo success("提交成功");
				}
				
			} else {
				echo fail("提交失败");
			}
		}	
	}
	
	public function publish(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$data = array();
			$sendUser = $this->getSendUsers();
			if($sendUser){
				
				$userids = $this->model->table("news_user")->field("userid")->where("newsid=$newsid")->toList("userid");
				if($userids){
					foreach($sendUser as $k=>$userid){
						if(!in_array($userid,$userids)){
							$data[] = array(
								'newsid'=>$newsid,
								'userid'=>$userid
							);
						}
					}
				}else{
					foreach($sendUser as $k=>$userid){
						$data[] = array(
							'newsid'=>$newsid,
							'userid'=>$userid
						);
					}
				}
				if($data) $this->model->table("news_user")->insert($data,true);
				
				$row = $this->model->table("news")->field("typeid,title,picture,description")->where("newsid=$newsid")->find();
				if($row){
					
					$this->model->table("news")->where("newsid=$newsid")->update("status=1"); //已发布

					$smgNews = new smgNews();
					$smgNews->agentid('10000001')->touser($sendUser);
					$smgNews->article(function($article) use($row){
						$article->title($row["title"]);
						$article->description($row["description"]);
						$article->url(url("news/detail",array('newsid'=>$newsid)));
						$article->picurl(getHost().$row["picture"]);
					});
					$data = $smgNews->toArray();
					//$this->librarys("weixinAPI")->sendMessage($data);
				}
				
				$this->model->table("news")->where("newsid=$newsid")->update("status=3");
				echo success("发布成功");
				
			}
		}
	}
	
	public function getNewsType(){
		return $this->table("news_type")->select();
	}
	
    public function remove() {
        if (requestInt("newsid") > 0) {
			$newsid = requestInt("newsid");
            $result = $this->model->table("news")->where("newsid=$newsid")->update("isdelete=1");
			$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->write();
            if ($result) {
                echo success(array('errmsg' => "删除成功"));
            } else {
                echo fail(array('errmsg' => "删除失败"));
            }
        }
    }
	
    public function getRow() {
        if (requestInt("newsid") > 0) {
			$newsid = requestInt("newsid");
			
            $row = $this->model->table("news as t1")
			->join("party as t2","t1.partyid=t2.partyid")
			->field("t1.*,t2.name as party_name")
			->where("t1.newsid=$newsid")
			->find();
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
            if($row){
				$res = $this->getReadUsers('news_user','newsid',$newsid);
				if($res){
					if($res["read"] || $res["unread"]){
						$row["readUser"] = implode("、",$res["read"]);
						$row["unReadUser"] = implode("、",$res["unread"]);
						$row["toUser"] = array_merge($res["read"],$res["unread"]);
						$row["toUser"] = implode("、",$row["toUser"]);
					}
				}
			}
			return $row;
        }
    }
	
    public function setTop() {
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$res = $this->model->table("news")->where("newsid=$newsid")->update("stick=1");
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			if($res){
				echo success(array('errmsg'=>'操作成功'));
			}else{
				echo fail(array('errmsg'=>'操作失败'));
			}
		}
    }
	
    public function cancelTop() {
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$res = $this->model->table("news")->where("newsid=$newsid")->update("stick=0");
			$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuery())->where($this->getCondition())->write();
			if($res){
				echo success(array('errmsg'=>'操作成功'));
			}else{
				echo fail(array('errmsg'=>'操作失败'));
			}
		}
    }
	
    public function  statistics(){
		if(requestInt("newsid") > 0){
			$data = array();
			$newsid = requestInt("newsid");
			$list = $this->table("news_user as t1")
			->join("user as t2","t1.userid=t2.userid")
			->where("t1.newsid=$newsid")
			->field("t1.*,t2.name,t2.partyid")
			->select();
			
			$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
			
			if($list){
				foreach($list as $k=>$val){
					if(!isset($data[$val["partyid"]])){
						$partyName = $this->table("party")->where("partyid",$val["partyid"])->field("name")->getVal("name");
						$data[$val["partyid"]]["name"] = $partyName;
					}
					$data[$val["partyid"]]["user"][] = $list[$k];
				}
			}
			
			if($data){
				foreach($data as $partyid=>$vals){
					$reads = 0;
					$total = 0;
					if($vals["user"]){
						foreach($vals["user"] as $val){
							$total++;
							if($val["read"] == 1){
								$reads++;
							}
						}
					}
					$data[$partyid]["total"] = $total;
					$data[$partyid]["reads"] = $reads;
					$data[$partyid]["percent"] = ($total>0)?(round(($reads/$total),2)*100)."%":"0%";
				}
				
				uasort($data,function($x,$y){
					if($x["percent"]>$y["percent"]){
						return 1;
					}else{
						return -1;
					}
				});
			}
			return $data;
		}
    }
    
	public function handleClose(){
		if(requestInt("id") > 0){
			$id = requestInt("id");
			$result = $this->model->table("news_comment")->where("id=$id")->update("status=2");
			if($result){
				echo success();
			}else{
				echo fail();
			}
		}
	}
	
	public function handleShow(){
		if(requestInt("id") > 0){
			$id = requestInt("id");
			$result = $this->model->table("news_comment")->where("id=$id")->update("status=1");
			if($result){
				echo success();
			}else{
				echo fail();
			}
		}
	}
	
	public function getCommentList(){
		if(requestInt("newsid") > 0){
			$newsid = requestInt("newsid");
			$list = $this->model->table("news_comment as t1")
			->join("user as t2","t1.userid=t2.userid")
			->where("t1.newsid=$newsid")
			->field("t1.*,t2.name,t2.mobile,t2.email,t2.partyid")
			->offset(50)
			->select();
			if($list){
				
				if($list){
					foreach($list as $k=>$val){
						$list[$k]["party"] = '';
						$row = $this->model->table("party")->where("partyid",$val["partyid"])->find();
						if($row){
							$list[$k]["party"] = $row["name"];
						}
					}
				}
				
				echo success(array(
					'list'=>$list,
					'total'=>$this->model->pages['total']
				));
			}else{
				echo fail(array('errcode'=>200)); 
			}
		}
	}

}
?>
