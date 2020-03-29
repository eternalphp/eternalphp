<?php

namespace App\Admin\Models;
use System\Core\Model;

class authModel extends Model {
	
	public function __construct() {
		parent::__construct();
		$this->menuAction = $this->getAuthMenu();
	} 
	
	public function getAuthMenu(){
		
		$class = get('class');
		$method = get('method');
		
		$where = array();
		$where[] = "class='$class'";
		$where = implode(' and ',$where);
		
		$itemid = 0;
		$list = $this->model->table("menu_items")->field("itemid,method")->where($where)->select();
		if($list){
			foreach($list as $k=>$val){
				if($val["method"] == $method){
					$itemid = $val["itemid"];
				}
				if($itemid == 0 && $val["method"] == ''){
					$itemid = $val["itemid"];
				}
			}
		}
		
		$menuAction = array();
		
		if($itemid > 0){
			if(!$this->session("isAdmin")){
				
				$res = $this->model->table("role_item")
				->field("itemid,actionids")
				->where("roleid",$this->session("roleid"))
				->where("itemid",$itemid)
				->find();
				
				if($res){
					if($res["actionids"] != ''){
						$where = Linq()->in('actionid',explode(',',$res["actionids"]))->sql();
						$menuAction = $this->model->table("action")->field("method")->where($where)->toList('method');
					}
				}
				
			}else{
				$menuAction = $this->getActionAll();
			}
		}
		
		return $menuAction;
	}
	
	// 获取按钮控制权
	public function getMenuAction() {
		return json_encode($this->menuAction);
	}
	
	//权限验证
	public function checkAuthMethod($method){
		
		if($method == 'index'){
			if(count($this->menuAction) > 0){
				return true;
			}else{
				return false;
			}
		}
		
		if(in_array($method,['add','edit','save','remove'])){
			if(in_array('add',$this->menuAction) || in_array('edit',$this->menuAction)){
				$this->menuAction[] = 'save';
			}
			if(in_array($method,$this->menuAction)){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}

	}

	public function getActionAll() {
		return $this->model->table("action")->field("method")->toList('method');
	}
	
}

?>