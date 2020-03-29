<?php

namespace App\Admin\Models;
use System\Core\Model;
use App\Librarys\SLSLog;

class roleModel extends Model {
	
	const KEY = 'roleid';
	
	public function __construct(SLSLog $SLSLog) {
		parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('角色管理');
		$this->onQuery();
	}

	public function index() {
		$sort = get("sort");
		$order = get("order");
		$orderby = ($sort && $order)?"$sort $order":"sort desc,roleid desc";
		
		$where = array();
		if(request("keyword") != ''){
			$keyword = request("keyword");
			$where[] = Linq()->where('or')->like('rolename',$keyword)->sql();
		}
		$where[] = "isdelete=0";
		$where[] = "status=1";
		$where = implode(" and ",$where);
		$list = $this->model->table('role')->field()->where($where)->offset(C('offset'))->order($orderby)->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		return sql_out($list);
	}

	public function getMenu() {
		
		$list = $this->model->table('menu')->field('menuid,name')->where('parentid=0')->order("sort desc")->select();
		if($list){
			foreach($list as $k=>$rss){
				$menu = $this->model->table('menu')->field('menuid,name')->where('parentid',$rss["menuid"])->order("sort asc")->select();
				foreach($menu as $kk => $val) {
					$items = $this->model->table('menu_items')->field('itemid,name')->where('menuid',$val['menuid'])->select();
					if($items){
						foreach($items as $ks=>$res){
							$items[$ks]["action"] = $this->model->table("action as t1")->join("menu_action as t2","t1.actionid=t2.actionid")->field("t1.actionid,t1.name,t1.method")->where("t2.itemid",$res["itemid"])->order('t1.actionid asc')->select();
						}
					}
					$menu[$kk]['items'] = $items;
				}
				$list[$k]["menu"] = $menu;
			}
		}
		return $list;
	}
	
	public function save(){
		if(requestInt("id")>0){
			$id = requestInt("id");
			$this->modify($id);
		}else{
			$this->create();
		}
	}

	public function create() {

		$_POST['createtime'] = date('Y-m-d H:i:s');
		$roleid = $this->model->table('role')->insert($_POST);
		if ($roleid) {
			if(isset($_POST["menuid"])){
				$menuids = $_POST["menuid"];
				
				$data = array();
				foreach($menuids as $k=>$menuid){
					$data[] = array('roleid'=>$roleid,'menuid'=>$menuid);
				}
				
				if($data){
					$this->model->table("role_menu")->insert($data,true);
				}
			}
			
			if(isset($_POST["itemid"])){
				$itemids = $_POST["itemid"];
				
				$data = array();
				foreach($itemids as $k=>$itemid){
					$actionids = isset($_POST["actionid"][$itemid])?implode(",",$_POST["actionid"][$itemid]):'';
					$data[] = array('roleid'=>$roleid,'itemid'=>$itemid,'actionids'=>$actionids);
				}
				
				if($data){
					$this->model->table("role_item")->insert($data,true);
				}
			}
			
			$this->SLSLog->handle(SLSLog::OPT_ADD)->query($this->getQuery())->where($this->getCondition())->write();
			echo success(L("add.success"));
		}else {
			echo fail(L("add.failure"));
		}
	}

	public function modify($roleid) {
		if($roleid > 0){

			$result = $this->model->table('role')->where("roleid",$roleid)->update($_POST);
			if ($result) {
				
				$this->model->table("role_menu")->where("roleid",$roleid)->delete();
				if(isset($_POST["menuid"])){
					$menuids = $_POST["menuid"];
					
					$data = array();
					foreach($menuids as $k=>$menuid){
						$data[] = array('roleid'=>$roleid,'menuid'=>$menuid);
					}
					
					if($data){
						$this->model->table("role_menu")->insert($data,true);
					}
					
				}
				
				$this->model->table("role_item")->where("roleid",$roleid)->delete();
				if(isset($_POST["itemid"])){
					$itemids = $_POST["itemid"];
					$data = array();
					foreach($itemids as $k=>$itemid){
						$actionids = isset($_POST["actionid"][$itemid])?implode(",",$_POST["actionid"][$itemid]):'';
						$data[] = array('roleid'=>$roleid,'itemid'=>$itemid,'actionids'=>$actionids);
					}
					
					if($data){
						$this->model->table("role_item")->insert($data,true);
					}
				}
				
				$this->SLSLog->handle(SLSLog::OPT_MODIFY)->query($this->getQuerys())->where($this->getConditions())->write();
				
				echo success(L("editor.success"));
			}else {
				echo fail(L("editor.failed"));
			}
		}
	}

	public function remove() {
		if(requestInt("id") > 0){
			$id = requestInt("id");
			
			$users = $this->model->table("admin")->field("username")->where("roleid",$id)->toList('username');
			if($users){
				$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->error(SLSLog::ERR_USER_INPUT)->write();
				exit(fail(array('errmsg'=>sprintf("用户：【%s】正在使用该权限，不能删除！",implode("、",$users)))));
			}
			
			$row = $this->model->table('role')->field("rolename")->where("roleid",$id)->find();
			$result = $this->model->table('role')->where("roleid",$id)->delete();
			if ($result) {
				
				$this->model->table("role_menu")->where("roleid",$id)->delete();
				$this->model->table("role_item")->where("roleid",$id)->delete();
				
				$this->SLSLog->handle(SLSLog::OPT_DELETE)->query($this->getQuery())->where($this->getCondition())->write();

				echo success(array('errmsg' => L("remove.success")));
			}else {
				echo fail(array('errmsg' => L("remove.failed")));
			}
		}
	}

	public function getRow() {
		if(requestInt("id") > 0){
			$id = requestInt("id");
			$row = $this->model->table('role')->field()->where("roleid",$id)->find();
			$row["menuid"] = $this->model->table("role_menu")->field()->where("roleid",$id)->toList("menuid");
			$row["itemid"] = $this->model->table("role_item")->field()->where("roleid",$id)->toList("itemid");
			$list = $this->model->table("role_item")->field()->where("roleid",$id)->select();
			if($list){
				$actionids = array();
				foreach($list as $k=>$val){
					$actionids[$val["itemid"]] = ($val["actionids"]!='')?explode(",",$val["actionids"]):array();
				}
				$row["actionids"] = $actionids;
			}
			
			$this->SLSLog->query($this->getQuerys())->where($this->getConditions())->write();
			
			return sql_out($row);
		}
	}
	
	public function getRoleList(){
		$list = $this->table('role as t1')
		->where("roleid>1 and status=1 and isdelete=0")
		->where("roleid>=2")
		->order("sort desc")->select();
		return $list;
	}
	
	public function getRoles(){
		return $this->model->table('role')
		->where("roleid>1 and status=1 and isdelete=0")
		->order("sort desc")->select();
	}
}

?>