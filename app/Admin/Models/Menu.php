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
use framework\Database\Eloquent\Model;

class Menu extends Model {
	
	protected $table = 'menu';
	protected $primaryKey = 'menuid';
	
	private $data = array();
	
    public function __construct() {
        parent::__construct();
		
		$this->menuAction = new MenuAction();
    }
	
    /**
     * 获取菜单数据
     *
     * @return $this;
     */
    public function index() {
		$this->data = $this->order("parentid asc,sort desc")->select();
		return $this;
    }
	
    /**
     * 获取顶级菜单
     *
     * @return array
     */
	public function getMenu(){
		return $this->where("parentid=0")
		->order("sort asc")
		->select();
	}
	
    /**
     * 创建菜单
     *
     * @return json
     */
	public function save(){
		if(requestInt("menuid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
	}
	
    /**
     * 创建菜单
     *
     * @return json
     */
	public function create(){
		$menuid = $this->insert($_POST);
		if($menuid){
			
 			$menuActions = array();
			if($_POST["actionids"]){
				foreach($_POST["actionids"] as $k=>$actionid){
					$menuActions[] = array(
						'menuid'=>$menuid,
						'actionid'=>$actionid
					);
				}
			} 
			
			if($menuActions) $this->menuAction->insert($menuActions,true);
			
			
			
			echo success(array('errmsg'=>'提交成功'));
		}else{
			echo fail(array('errmsg'=>'提交失败'));
		}
	}
	
    /**
     * 修改菜单
     *
     * @return json
     */
	public function modify(){
		if(requestInt("menuid") > 0){
			$menuid = requestInt("menuid");
			$result = $this->where("menuid",$menuid)->update($_POST);
			if($result){
				
 				$this->menuAction->where("menuid",$menuid)->delete();
				$menuActions = array();
				if($_POST["actionids"]){
					foreach($_POST["actionids"] as $k=>$actionid){
						$menuActions[] = array(
							'menuid'=>$menuid,
							'actionid'=>$actionid
						);
					}
				} 
				
				if($menuActions) $this->menuAction->insert($menuActions,true);
				
				
				echo success(array('errmsg'=>'保存成功'));
			}else{
				echo fail(array('errmsg'=>'保存失败'));
			}
		}
	}
	
    /**
     * 获取树形列表结构
     *
     * @return array
     */
	public function formatList($pid = 0,$leave = 0){
		foreach($this->data as $k=>$val){
			if($val["parentid"] == $pid){
				$this->data[$k]["space"] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$leave);
				$this->dataList[] = $this->data[$k];
				$num = $leave+1;
				$this->formatList($val["menuid"],$num);
			}
		}
		return $this->dataList;
	}
	
    /**
     * 获取树形结构
     *
     * @return array
     */
	public function getAuthMenu($pid = 0){
		$menus = array();
		foreach($this->data as $row){
			if($row["parentid"] == $pid){
				
				$data = $this->getAuthMenu($row["menuid"]);
				if($data){
					$row["items"] = $data;
				}
				
				$menus[] = $row;
			}
		}
		return $menus;
	}
	
    /**
     * 获取菜单数据详情
     *
     * @return array
     */
	public function getRow(){
		if(requestInt("menuid") > 0){
			$menuid = requestInt("menuid");
			$row = $this->find($menuid);
			$row["actionids"] = $this->menuAction->where("menuid",$menuid)->toList("actionid");
			return $row;
		}
	}
}
?>
