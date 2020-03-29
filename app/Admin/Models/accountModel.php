<?php

namespace App\Admin\Models;
use System\Core\Model;

class accountModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $where = array();
        if (request("starttime") != '' && request("endtime") != '') {
            $starttime = request("starttime");
            $endtime = request("endtime");
            $endtime = $endtime." 23:59:59";
            $where[] = "t1.createtime between '$starttime' and '$endtime'";
        }

        if (request("keyword") !='') {
            $keyword = request("keyword");
            $where[] = "(t2.name like '%" . $keyword . "%' or t1.username like '%" . $keyword . "%')";
        }

        if (request("partyid")>0) {
            $partyid = request("partyid");
            $where[] = "t3.partyid=$partyid";
        }

        if (request("status")>0) {
            $status = request("status");
            $where[] = "t1.status=$status";
        }
		
		$roleList = $this->table("role")->toList("roleid","rolename");
		
		$where[] = "t1.status=1";
		$where[] = "t1.isdelete=0";
		$where = implode(" and ",$where);
        $list = $this->model->table('admin as t1')
		->join("user as t2", "t1.userid=t2.userid")
		->join("party as t3", "t2.partyid=t3.partyid")
		->field("t1.*,t2.name,t3.name as party_name")
		->where($where)
		->offset(30)
		->order('t1.uid desc')->select();
		if($list){
			foreach($list as $k=>$val){
				$list[$k]["rolename"] = isset($roleList[$val["roleid"]])?$roleList[$val["roleid"]]:"/";
			}
		}
        return $list;
    }

    public function save() {
        if (request("uid")>0) {
			$this->modify();
        } else {
			$this->create();
        }
    }
	
	public function create(){
		$_POST["createtime"] = date("Y-m-d H:i:s");
		$_POST["password"] = md5($_POST["password"]);
		$username = $_POST["username"];
		$_POST["status"] = 1;
		$row = $this->model->table("admin")->field()->where("username='$username'")->where("isdelete=0")->find();
		if ($row) {
			exit(fail("帐户名已存在"));
		}
		$uid = $this->model->table("admin")->insert($_POST);
		if ($uid) {
			echo success("添加成功");
		} else {
			echo fail("添加失败");
		}
	}
	
	public function modify(){
		if(request("uid")>0){
			$uid = request("uid");
			
            if ($_POST["password"] == "") {
                unset($_POST["password"]);
            } else {
                $_POST["password"] = md5($_POST["password"]);
            }
            $username = $_POST["username"];
            $row = $this->model->table("admin")->field()->where("username='$username' and uid!=$uid")->where("isdelete=0")->find();
            if ($row) {
                exit(fail("帐户名已存在"));
            }
            $result = $this->model->table("admin")->where("uid=$uid")->update($_POST);
            if ($result) {
                echo success("编辑成功");
            } else {
                echo fail("编辑失败");
            }
		}
	}

    public function remove() {
        if (request("uid") > 0) {
			$uid = request("uid");
            $result = $this->model->table("admin")->where("uid=$uid")->update("isdelete=1");
            if ($result) {
                echo success(array(
                    'msg' => "删除成功"
                ));
            } else {
                echo fail(array(
                    'msg' => "删除失败"
                ));
            }
        }
    }

    public function getRow() {
        if (request("uid") > 0) {
			$uid = request("uid");
            $row = $this->model->table("admin as t1")->join("user as t2","t1.userid=t2.userid")->field("t1.*,t2.userid")->where("t1.uid=$uid")->find();
			return $row;
        }
    }

    public function change() {
        if (isset($_POST['pwd']) && isset($_POST['newpwd']) && isset($_POST['newpwd2'])) {
            if ($_POST['newpwd'] != $_POST['newpwd2']) {
                echo fail("新密码与确认密码不同，请确认新密码！");
                return;
            }

            $row = $this->model->table("admin")->field()->where("username='" . $this->session("username") . "' and password='" . md5($_POST['pwd']) . "'")->find();
            if ($row) {
                $row = $this->model->table("admin")->where("username='" . $this->session("username") . "'")->update("password='" . md5($_POST['newpwd']) . "'");
                echo success("修改成功！");
            } else {
                echo fail("原密码错误,请重新输入！");
            }
        } else {
            echo fail("原密码、新密码不能为空！");
        }
    }
	
	public function getRoleList(){
		return $this->model->table("role")->where("roleid>1")->select();
	}
}

?>