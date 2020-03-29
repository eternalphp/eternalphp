<?php

namespace App\Admin\Models;
use System\Core\Model;
use App\Librarys\SLSLog;

class adminModel extends Model{
    
	public function __construct(SLSLog $SLSLog){
		parent::__construct();
		$this->SLSLog = $SLSLog;
		$this->SLSLog->moduleName('管理中心');
		$this->onQuery();
	}
	
	public function getMenu(){
		$menu = json_decode($this->session("menu"),true);
		$menuids = array();
		foreach(array_values($menu) as $val){
			if(!is_array($val) && strstr($val,":") == false){
				$menuids[] = $val;
			}
		}
	  
		$list = $this->model->table("menu")
		->field('id,title,class')
		->where("parent_id=0")
		->where("in",array('id'=>$menuids))
		->order("sort asc")
		->select();
		
		$this->SLSLog->query($this->getQuery())->where($this->getCondition())->write();
		
		foreach($list as $key=>$val){
			$list[$key]['submenu'] = $this->model->table("menu")
			->field('id,title,class,picture,method')
			->where("parent_id",$val['id'])
			->where("in",array('id'=>$menuids))
			->order("sort asc")
			->select();
		}
		return $list;
	}

}
?>