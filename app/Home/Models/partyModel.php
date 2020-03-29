<?php

namespace App\Home\Models;

class partyModel extends BaseModel {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index(){

	}
	
	//支部信息
	public function getRow(){
		if(session("partyid")>0){
			
			if(request("partyid")>0){
				$partyid = request("partyid");
			}else{
				$partyid = session("partyid");
			}
			
			$row = M("party")->where("partyid",$partyid)->find();
			if($row){
				//支部班子
				//主任委员1
				//副主任委员2
				//秘书长3
				//委员4
				
				$position = M("position")->toList("positionid","name");
				$positionSort = M("position")->toList("positionid","sort");
				
				$leader = array();
				$list = M("user")->field("positionid,other_positionid,name,sort,handleSort")
				->where("partyid",$partyid)
				->where("positionid>0 and isdelete=0")
				->order("positionid asc,handleSort desc,sort asc")
				->select();
				if($list){
					foreach($list as $k=>$val){
						if(!isset($leader[$val["positionid"]])){
							$leader[$val["positionid"]]["name"] = $position[$val["positionid"]];
							$leader[$val["positionid"]]["sort"] = $positionSort[$val["positionid"]];
						}
						$leader[$val["positionid"]]["users"][] = array(
							'name'=>$val["name"],
							'sort'=>$val['sort'],
							'handleSort'=>$val['handleSort']
						);
						
						if($val["other_positionid"]>0){
							if(!isset($leader[$val["other_positionid"]])){
								$leader[$val["other_positionid"]]["name"] = $position[$val["other_positionid"]];
								$leader[$val["other_positionid"]]["sort"] = $positionSort[$val["other_positionid"]];
							}
							$leader[$val["other_positionid"]]["users"][] = array(
								'name'=>$val["name"],
								'sort'=>$val['sort'],
								'handleSort'=>$val['handleSort']
							);
						}
					}
				}
				
				//ksort($leader);
				
				//按照职位排序
				array_multisort(array_column($leader,'sort'),SORT_ASC,$leader);
				
				if($leader){
					foreach($leader as $positionid=>$users){
						
						//按照用户的导入顺序排序
						if(count($leader[$positionid]['users'])>1){
							
							$handleSort = array_column($leader[$positionid]['users'],'handleSort');
							$sort = array_column($leader[$positionid]['users'],'sort'); //导入顺序
							
							array_multisort($handleSort,SORT_DESC,$sort,SORT_ASC,$leader[$positionid]['users']);
						}
					}
				}
				
				$row["leader"] = $leader;
				
				//党员
				$row["member"] = M("user")->where("partyid",$partyid)->where("isPartyMember=1 and isdelete=0")->order("sort asc")->select();
				//部门积分
				$row["score"] = M("user")->where("partyid",$partyid)->where("isdelete=0")->sum("score")->getVal('total');
			}
			return $row;
		}
	}
	
    public function partyUser($partyid = 0,$userids = array(),$self = true){
        if ($partyid > 0) {
			
			$where = array();
			$partyids = $this->getChildId($partyid);
			$where[] = "partyid in (".implode(",",$partyids).")";
			$where[] = "isdelete=0";
			$where = implode(" and ",$where);
            $list = $this->model->table("party")->field('partyid as id,name,parentid as pId')->where($where)->order("`order` desc")->select();
        } else {
            $list = $this->model->table("party")->field('partyid as id,name as name,parentid as pId')->where("isdelete=0")->order("`order` desc")->select();
        }
        foreach ($list as $k => $val) {
            if ($val['pId'] == 0) {
                $list[$k]["open"] = TRUE;
            } else {
                $list[$k]["open"] = FALSE;
				if($partyid > 0){
					$list[$k]["open"] = TRUE;
				}
            }
            $list[$k]["title"] = $val["name"];
            if($userids){
				if(session("roleid")>2 && $self == false){
					foreach($userids as $k=>$userid){
						if(session("userid") == $userid){
							unset($userids[$k]);
						}
					}
				}
                $children = $this->model->table("user")
				->field("userid,name,weixinid")
				->where("partyid", $val["id"])
				->where("in",array('userid'=>$userids))
				->where("isdelete=0")
				->select();
            }else{
				if($self == false){
					$children = $this->model->table("user")
					->field("userid,name,weixinid")
					->where("partyid", $val["id"])
					->where("userid",session("userid"),"<>")
					->where("isdelete=0")
					->select();
				}else{
					$children = $this->model->table("user")
					->field("userid,name,weixinid")
					->where("partyid", $val["id"])
					->where("isdelete=0")
					->select();
				}
            }
            foreach ($children as $kk => $rs) {
                $children[$kk]["title"] = $rs["name"];
				$children[$kk]["icon"] = "__PUBLIC__/plugins/ztree/img/user.png";
            }
            $list[$k]["children"] = $children;
        }
        return json_encode($list);
    }
	
	public function getPartys(){
		$list = array();
		if(session("identityid")>2){
			if(session("partyid")>0){
				$partyid = session("partyid");
				$list = M("party")->where("parentid=$partyid")->where("isdelete=0")->select();
			}
		}else{
			$list = M("party")->where("isdelete=0")->select();
		}
		
		$Pinyin = $this->librarys("GPinyin");
		$data = array();
		if($list){
			foreach($list as $k=>$val){
				$str = mb_substr($val["name"],0,1);
				$str = $Pinyin->output($str);
				$list[$k]["str"] = $Pinyin->output($val["name"]);
				$str = mb_substr($str,0,1);
				$char = strtoupper($str);
				$data[$char][] = $list[$k];
			}
		}
		ksort($data);
		return $data;
	}
}

?>