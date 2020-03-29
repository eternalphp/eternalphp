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
use System\Core\Model;

class menuModel extends Model {
	
    public function __construct() {
        parent::__construct();
    }
	
    public function index() {
		
		$menuids = array();
		$itemids = array();
		if(request("keyword") != ''){
			$keyword = request('keyword');
			$menuids = $this->model->table("menu")->field("menuid")->where("name like '%".$keyword."%'")->toList('menuid');
			$menu_items = $this->model->table("menu_items")->field("menuid,itemid")->where("name like '%".$keyword."%'")->select();
			
			if($menu_items){
				
				foreach($menu_items as $k=>&$val){
					$itemids[] = $val["itemid"];
					$val["name"] = sql_out($val["name"]);
					
					if(!in_array($val["menuid"],$menuids)){
						$menuids[] = $val["menuid"];
						
						$res = $this->model->table("menu")->field("parentid")->where("menuid",$val["menuid"])->find();
						if($res){
							$menuids[] = $res["parentid"];
						}
					}
				}
			}
		}
		
		$where = array();
		$where[] = "parentid=0";
		if($menuids){
			$where[] = "menuid in (".implode(",",$menuids).")";
		}
		$where = implode(" and ",$where);
		
		$list = $this->model->table('menu')->field()->where($where)->order('`sort` desc')->select();
		if($list){
			foreach($list as $k=>$val){
				
				$where = array();
				$where[] = "parentid=".$val["menuid"];
				if($menuids){
					$where[] = "menuid in (".implode(",",$menuids).")";
				}
				$where = implode(" and ",$where);
				
				$menus = $this->model->table('menu')->field()->where($where)->order('`sort` asc')->select();
				if($menus){
					foreach($menus as $kk=>$res){
						
						$menus[$kk]["name"] = sql_out($res["name"]);
						$where = array();
						$where[] = "menuid=".$res["menuid"];
						if($itemids){
							$where[] = "itemid in (".implode(",",$itemids).")";
						}
						$where = implode(" and ",$where);
						
						$items = $this->model->table("menu_items")->field()->where($where)->order("`sort` asc")->select();
						if($items){
							foreach($items as &$item){
								$item["name"] = sql_out($item["name"]);
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
	
    public function save() {
		if(requestInt("menuid")>0 || requestInt("itemid")>0){
			$this->modify();
		}else{
			$this->create();
		}
    }
	
	public function create(){
		if(requestInt("item_menuid") > 0){
			$_POST["menuid"] = requestInt("item_menuid");
			$itemid = $this->model->table("menu_items")->insert($_POST);
			if ($itemid) {
				
				if(isset($_POST["actionid"])){
					$data = array();
					foreach($_POST["actionid"] as $k=>$actionid){
						$data[] = array(
							'itemid'=>$itemid,
							'actionid'=>$actionid
						);
					}
					
					if($data){
						$this->model->table("menu_action")->insert($data,true);
					}
				}
				
				echo success(L("add.success"));
			} else {
				echo fail(L("add.failure"));
			}
		}else{
			$menuid = $this->model->table("menu")->insert($_POST);
			if ($menuid) {	
				echo success(L("add.success"));
			} else {
				echo fail(L("add.failure"));
			}
		}
	}
	
	public function modify(){
		if(requestInt("item_menuid") > 0){
			$itemid = requestInt("itemid");
			$_POST["menuid"] = requestInt("item_menuid");
			$result = $this->model->table("menu_items")->where("itemid",$itemid)->update($_POST);
			if ($result) {
				
				$this->model->table("menu_action")->where("itemid=$itemid")->delete();
				if(isset($_POST["actionid"])){
					$actionids = $_POST["actionid"];
					$data = array();
					foreach($actionids as $k=>$actionid){
						$data[] = array(
							'itemid'=>$itemid,
							'actionid'=>$actionid
						);
					}
					if($data){
						$this->model->table("menu_action")->insert($data,true);
					}
				}
				echo success(L("editor.success"));
			} else {
				echo fail(L("editor.failed"));
			}
		}else{
			$menuid = requestInt("menuid");
			$result = $this->model->table("menu")->where("menuid",$menuid)->update($_POST);
			if ($result) {
				echo success(L("editor.success"));
			} else {
				echo fail(L("editor.failed"));
			}
		}
	}
	
    public function remove() {
        if (requestInt("menuid") > 0) {
			$menuid = requestInt("menuid");
			
			$menu = $this->model->table("menu")
			->field("name")->where("parentid=$menuid")->toList('name');
			if($menu){
				exit(fail(array('errmsg'=>sprintf("该栏目下存在子栏目：【%s】，不能删除！",implode("、",$menu)))));
			}
			
			$menu = $this->model->table("menu_items")
			->field("name")->where("menuid=$menuid")->toList('name');
			if($menu){
				exit(fail(array('errmsg'=>sprintf("该栏目下存在子栏目：【%s】，不能删除！",implode("、",$menu)))));
			}
			
            $result = $this->model->table("menu")->where("menuid=$menuid")->delete();
            if ($result) {
                echo success(array('errmsg' => L("remove.success")));
            } else {
                echo fail(array('errmsg' => L("remove.failed")));
            }
			
        }elseif(requestInt("itemid") > 0){
			
			$itemid = requestInt("itemid");
            $result = $this->model->table("menu_items")->where("itemid=$itemid")->delete();
            if ($result) {
				$this->model->table("menu_action")->where("itemid=$itemid")->delete();
                echo success(array('errmsg' => L("remove.success")));
            } else {
                echo fail(array('errmsg' => L("remove.failed")));
            }
			
		}
    }
	
    public function getRow() {
		if(request("itemid")>0){
			$itemid = request("itemid");
            $row = $this->model->table("menu_items")->field()->where("itemid=$itemid")->find();
			if($row){
				$row["actionid"] = $this->model->table("menu_action")->field()->where("itemid",$itemid)->toList('actionid');
			}
			return $row;
		}elseif(request("menuid")>0){
			$menuid = request("menuid");
            $row = $this->model->table("menu")->field()->where("menuid=$menuid")->find();
			return $row;
        }
    }
	
    public function topMenu() {
        $list = $this->model->table("menu")->field()->order("`sort` desc")->select();
		if($list){
			foreach($list as &$val){
				$val["name"] = sql_out($val["name"]);
			}
		}
		$params = array('menuid','parentid','name','name');
		$category = $this->librarys("Category",array($params));
		return $category->getTree($list);
    }
	
	public function getAction(){
		return $this->model->table("action")->field()->select();
	}
	
    public function getAuthMenu() {
		
		if(!$this->session("isAdmin")){
			$menuids = $this->model->table("role_menu")
			->field("menuid")->where("roleid",$this->session("roleid"))
			->toList('menuid');
			if($menuids){
				$list = $this->model->table("menu")
				->field('menuid as id')
				->where("parentid=0 and hidden=0")
				->where("in",array('menuid'=>$menuids))
				->order("sort desc,menuid desc")->select();
			}else{
				$this->session();
				exit("没有权限");
				return false;
			}
		}else{
			$list = $this->model->table("menu")
			->field('menuid as id')
			->where("parentid=0 and hidden=0")
			->order("sort desc,menuid desc")->select();
		}
       
        foreach ($list as $k => $val) {
			if(!$this->session("isAdmin")){
				$menu = $this->model->table("menu")
				->field("name as text,menuid as id")
				->where("parentid", $val["id"])
				->where("in",array('menuid'=>$menuids))
				->order("sort asc")->select();
            }else{
				$menu = $this->model->table("menu")
				->field("name as text,menuid as id")
				->where("parentid", $val["id"])
				->order("sort asc")->select();
			}
			
			$menuid = null;
            foreach ($menu as $kk => $row) {
				$menu[$kk]["text"] = sql_out($row["text"]);
				if(!$this->session("isAdmin")){
					$itemids = $this->model->table("role_item")
					->field("itemid")->where("roleid",$this->session("roleid"))
					->toList('itemid');
					$items = $this->model->table("menu_items")
					->field("itemid as id,name as text,class,method")
					->where("menuid", $row["id"])
					->where("in",array('itemid'=>$itemids))
					->where("hidden=0")
					->order("sort asc,itemid asc")->select();
				}else{
					$items = $this->model->table("menu_items")
					->field("itemid as id,name as text,class,method")
					->where("menuid", $row["id"])
					->where("hidden=0")
					->order("sort asc,itemid asc")->select();
				}
				
                foreach ($items as $kkk => $item) {
                    if ($menuid == null) $menuid = $item["id"];
                    $items[$kkk]["href"] = autolink(array(
                        $item["class"],
                        empty($item["method"]) ? "index" : $item["method"]
                    ));
                    unset($items[$kkk]["class"], $items[$kkk]["method"]);
					$items[$kkk]["text"] = sql_out($item["text"]);
                }
                $menu[$kk]["items"] = $items;
            }
            $list[$k]["homePage"] = $menuid;
            $list[$k]["menu"] = $menu;
        }
        return $list;
    }
	
    public function getTopMenu() {
		$where = array();
		$where[] = "parentid=0 and hidden=0";
		
		if(!$this->session("isAdmin")){
			$menuids = $this->model->table("role_menu")
			->field("menuid")
			->where("roleid",$this->session("roleid"))
			->toList('menuid');
			$where[] = "menuid in (".implode(",",$menuids).")";
		}
		$where = implode(" and ",$where);
        $list = $this->model->table("menu")
		->field("menuid,name")
		->where($where)
		->order("sort desc,menuid desc")->select();
		if($list){
			foreach($list as &$val){
				$val["name"] = sql_out($val["name"]);
			}
		}
		return $list;
    }
}
?>
