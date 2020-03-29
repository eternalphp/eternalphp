<?php 

namespace App\Librarys;

class SLSLog {
	
	const OPT_LOGIN = 0;
	const OPT_QUERY = 1;
	const OPT_ADD = 2;
	const OPT_MODIFY = 3;
	const OPT_DELETE = 4;
	const OPT_LOGOUT = 5;
	const ERR_USER = 1000; //用户非法操作
	const ERR_USER_INPUT = 1001; //用户输入不符合标准
	const ERR_SYSTEM = 2000; //应用系统自身错误导致
	const ERR_SAFE = 3000; //不符合安全规范
	const ERR_IP = 3001; //由于IP受安全策略控制，导致无法操作
	const ERR_TIME = 3002; //由于操作时间不在允许范围内，导致无法操作
	const ERR_TIMES = 3003; //操作次数受限
	const ERR_ACCOUNT = 3004; //用户名与密码不匹配
	const ERR_KEY = 3005; //数字证书被注销
	const ERR_FREEZE = 3006; //用户账号被冻结
	const ERR_AUTH = 3007; //无操作权限
	const ERR_OTHER = 3999; //其他安全检查方面的错误
	
	private $ProductID; //应用ID
	private $ProductName; //应用名称
	private $LogID; //日志ID
	private $OperatorID; //操作人ID 操作人在应用中的ID号
	private $OperatorAccount; //操作人帐号 操作人居民身份证号
	private $OperatorIdentity; //操作人身份 0.管理员；1.普通用户
	private $OperatorName; //操作人名 姓名
	private $OrganizationName; //单位名称
	private $OrganizationID; //单位机构代码
	private $OpTime; //操作时间
	private $ResTime; //响应时间
	private $TerminalType; //终端类型 0.非移动终端；1.移动终端
	private $TerminalID; //终端标识  非移动终端为ip 政务微信 为空
	private $TerminalNum; //移动终端号码  政务微信 为空
	private $TerminalMac; //移动终端MAC 政务微信 为空
	private $OpType; //操作行为类型 0：登录；1：查询；2：新增；3：修改；4：删除；5：退出
	private $OperateCondition; //操作条件 where条件
	private $OperateResult; //操作结果 0：失败；1：成功
	private $ErrCode; //失败原因代码
	private $ClientIp; //客户端IP
	private $ClientPort; //客户端Port
	private $URL; //目标URL
	private $ObjectParams; //目标URL参数
	private $SessionID; //会话ID
	private $FuncModuleName; //功能模块名称
	private $ObjectIP; //目标IP
	private $ObjectPort; //目标Port
	private $QuerySql; //查询语句
	private $storagePath = 'operator_logs';
	private $logName = 'lhyzj_operator_log.log';
	
	public function __construct(){
		$this->ProductID = C("appid");
		$this->ProductName = C("APPNAME");
		$this->LogID = build_order_no();
		$this->OperatorID = session("userid");
		$this->OperatorAccount = session("idcard");
		$this->OperatorIdentity = (session("roid") > 0)? 0 : 1;
		$this->OperatorName = session("name");
		$this->OrganizationName = session("orgFullName");
		$this->OrganizationID = session("orgCode");
		$this->OpTime = $this->starttime();
		$this->ResTime = $this->endtime();
		$this->TerminalType = is_mobile() ? 1 : 0;
		$this->OperateCondition = array(); //
		$this->OperateResult = 1; //
		$this->ErrCode = 0; //
		$this->TerminalID = is_mobile() ? '' : get_ip();
		$this->TerminalNum = '';
		$this->TerminalMac = '';
		$this->OpType = 1; //
		$this->ClientIp = get_ip();
		$this->ClientPort = $_SERVER["SERVER_PORT"];
		$this->URL = $_SERVER["REQUEST_URI"];
		$this->ObjectParams = array();
		if($_SERVER["REQUEST_METHOD"] == 'GET'){
			if(isset($_SERVER["QUERY_STRING"])){
				$this->ObjectParams[] = $_SERVER["QUERY_STRING"];
			}
		}else{
			$this->ObjectParams[] = http_build_query($_POST);
		}
		$this->SessionID = session_id();
		$this->FuncModuleName = ''; //
		$this->ObjectIP = get_ip();
		$this->ObjectPort = $_SERVER["SERVER_PORT"];
		$this->QuerySql = array(); //
	}
	
	/**
	 * 获取时间戳到毫秒
	 * @return bool|string
	 */
	private function starttime($time = null){
		list($sec,$msec) = explode(".",$_SERVER["REQUEST_TIME_FLOAT"]);
		return (string)(date("YmdHis",$sec) . str_pad(sprintf("%.0f",$msec),4,'0',STR_PAD_LEFT));
	}
	
	private function endtime(){
		list($msec, $sec) = explode('.', microtime(true));
		return (string)(date("YmdHis",$sec) . str_pad(sprintf("%.0f",$msec),4,'0',STR_PAD_LEFT));
	}
	
	public function handle($type = self::OPT_QUERY){
		$this->OpType = $type;
		return $this;
	}
	
	public function where($condition){
		if(in_array($this->OpType,array(1,3,4))){
			if(is_array($condition)){
				$this->OperateCondition[] = implode(";",$condition);
			}else{
				$this->OperateCondition[] = $condition;
			}
		}
		return $this;
	}
	
	public function query($sql){
		if(is_array($sql)){
			$this->QuerySql[] = implode(";",$sql);
		}else{
			$this->QuerySql[] = $sql;
		}
		return $this;
	}
	
	public function error($code){
		$this->OperateResult = 0;
		$this->ErrCode = $code;
		return $this;
	}
	
	public function moduleName($name){
		$this->FuncModuleName = $name;
		return $this;
	}
	
	public function toArray(){
		return array(
			$this->ProductID,
			$this->ProductName,
			$this->LogID,
			$this->OperatorID,
			$this->OperatorAccount,
			$this->OperatorIdentity,
			$this->OperatorName,
			$this->OrganizationName,
			$this->OrganizationID,
			$this->OpTime,
			$this->ResTime,
			$this->TerminalType,
			$this->TerminalID,
			$this->TerminalNum,
			$this->TerminalMac,
			$this->OpType,
			implode(";",$this->OperateCondition),
			$this->OperateResult,
			$this->ErrCode,
			$this->ClientIp,
			$this->ClientPort,
			$this->URL,
			implode(";",$this->ObjectParams),
			$this->SessionID,
			$this->FuncModuleName,
			$this->ObjectIP,
			$this->ObjectPort,
			implode(";",$this->QuerySql),
		);
	}
	
	public function write(){

		$storagePath = ROOT . sprintf('/storage/%s/',$this->storagePath);
		$storageMonthPath = ROOT . sprintf('/storage/%s/%s/',$this->storagePath,date("Ym"));
		if(!file_exists($storagePath)){
			mkdir($storagePath,0777,true);
		}
		$filename = $storagePath . $this->logName;
		
		if(file_exists($filename)){
			$days = round((time() - filectime($filename)) / (24*60*60));
			if($days > 7){
				if(!file_exists($storageMonthPath)){
					mkdir($storageMonthPath,0777,true);
				}
				rename($filename,$storageMonthPath.date("YmdHis").".log");
			}
		}
		
		$data = $this->toArray();
		file_put_contents($filename,implode("|",$data)."\n",FILE_APPEND);
	}
}
?>