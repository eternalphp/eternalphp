<?php

/**
 * RoleService
 * @author yuanzhongyi
 * @date 2020-07-31
 */

namespace App\Admin\Services;

use App\Admin\Models\Role;
use App\Admin\Models\Menu;

class RoleService {
	
    function __construct(Role $model,Menu $menu) {
		
		$this->model = $model;
		$this->menu = $menu;
    }
	
	function getList(){
		$list = $this->model->index();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["statusText"] = ($val["status"] == 1)?'启用':'未启用';
				
				$links = array();
				$links[] = '<a href=""><span class="label label-primary">编辑</span></a>';
				$links[] = '<a href=""><span class="label label-danger">删除</span></a>';
				
				$list[$k]["links"] = implode(" ",$links);
			}
		}
		
		$data["rows"] = $list;
		$data["total"] = $this->model->pages["count"];
		return $data;
	}
	
	function getRow(){
		return $this->model->find(requestInt("roleid"));
	}
	
	function save(){
		if(requestInt("roleid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
	}
	
	function create(){
		$_POST["createtime"] = date("Y-m-d H:i:s");
		return $this->model->insert($_POST);
	}
	
	function modify(){
		return $this->model->where("roleid",requestInt("roleid"))->update($_POST);
	}
	
	function getMenus(){
		$list = $this->menu->where("parentid=0")->where("hidden=0")->select();
		if($list){
			foreach($list as $k=>$val){
				$menus = $this->menu->table("menu")->where("parentid",$val["menuid"])->where("hidden=0")->select();
				if($menus){
					foreach($menus as $kk=>$menu){
						$items = $this->menu->table("menu")->where("parentid",$menu["menuid"])->where("hidden=0")->select();
						if($items){
							foreach($items as $kkk=>$item){
								$items[$kkk]["actions"] = $this->menu->table("menu_action as t1")
								->join("action as t2","t1.actionid=t2.actionid")
								->where("t1.menuid",$item["menuid"])
								->field("t2.*")
								->select();
							}
						}
						$menus[$kk]["items"] = $items;
					}
				}
				$list[$k]["menus"] = $menus;
			}
		}
		return $list;
	}
}
?>