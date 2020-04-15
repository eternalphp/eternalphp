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
    }
	
    public function index() {
		$this->data = $this->order("parentid asc,sort desc")->select();
		return $this;
    }
	
	public function getMenu(){
		return $this->where("parentid=0")
		->order("sort asc")
		->select();
	}
	
	public function save(){
		if(requestInt("menuid") > 0){
			$this->modify();
		}else{
			$this->create();
		}
	}
	
	public function create(){
		$result = $this->insert($_POST);
		if($result){
			echo success(array('errmsg'=>'提交成功'));
		}else{
			echo fail(array('errmsg'=>'提交失败'));
		}
	}
	
	public function modify(){
		
	}
	
	public function formatList($pid = 0){
		foreach($this->data as $k=>$val){
			if($val["parentid"] == $pid){
				$this->dataList[] = $this->data[$k];
				$this->formatList($val["menuid"]);
			}
		}
		return $this->dataList;
	}
	
	public function getAuthMenu($pid = 0){
		foreach($this->data as $k=>$val){
			if($val["parentid"] == $pid){
				if($pid == 0){
					$this->authMenu[$val["menuid"]] = $this->data[$k];
				}else{
					$this->authMenu[$pid]["items"][] = $this->data[$k];
				}
				$this->getAuthMenu($val["menuid"]);
			}
		}
		
		return $this->authMenu;
	}
	
	public function getRow(){
		if(requestInt("menuid") > 0){
			return $this->where("menuid",requestInt("menuid"))->find();
		}
	}
}
?>
