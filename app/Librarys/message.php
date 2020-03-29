<?php 

namespace App\Librarys;

class message {
	
	private $msgSourceId;
	private $messageType;
	private $msgTitle;
	private $msgDescription;
	private $contents = array();
	private $pushUserIds = array();
	private $serverUrl = 'http://15.75.7.239/msg/push';
	
	const CARD_TEXT = 0101;
	const CARD_NEWS = 0102;
	const CARD_IMAGE = 0103;
	const CARD_UNITNAME = 0104;
	const NEWS = 0110;
	const TEXT = 0111;
	const IMAGE = 0112;
	const VIDEO = 0113;
	const FILE = 0114;
	const VOICE = 0115;
	
	public function __construct(){
		$this->msgSourceId = C("msgSourceId");
		$this->messageType = self::TEXT;
	}
	
	public function title($title){
		$this->msgTitle = $title;
		return $this;
	}
	
	public function description($description){
		$this->msgDescription = $description;
		return $this;
	}
	
	public function toUser($user = array()){
		$this->pushUserIds = $user;
		return $this;
	}
	
	public function messageType($messageType){
		$this->messageType = $messageType;
		return $this;
	}
	
	public function createNews($imageUrl,$linkUrl){
		$this->contents[] = array(
			'type'=>'IMAGE',
			'content'=>$imageUrl
		);
		$this->contents[] = array(
			'type'=>'TEXT',
			'content'=>$linkUrl
		);
		return $this;
	}
	
	public function createImageText($imageUrl,$text){
		$this->contents[] = array(
			'type'=>'IMAGE',
			'content'=>$imageUrl
		);
		$this->contents[] = array(
			'type'=>'TEXT',
			'content'=>$text
		);
		return $this;
	}
	
	public function createImage($imageUrl){
		$this->contents[] = array(
			'type'=>'IMAGE',
			'content'=>$imageUrl
		);
		return $this;
	}
	
	public function createText($text){
		$this->contents[] = array(
			'type'=>'TEXT',
			'content'=>$text
		);
		return $this;
	}
	
	public function createFile($filename){
		$this->contents[] = array(
			'type'=>'FILE',
			'content'=>$filename
		);
		return $this;
	}
	
	public function createVideo($filename){
		$this->contents[] = array(
			'type'=>'VIDEO',
			'content'=>$filename
		);
		return $this;
	}
	
	public function createVoice($filename){
		$this->contents[] = array(
			'type'=>'VOICE',
			'content'=>$filename
		);
		return $this;
	}
	
	public function toArray(){
		return array(
			'msgSourceId'=>$this->msgSourceId,
			'messageType'=>$this->messageType,
			'msgList'=>array(
				'msgTitle'=>$this->msgTitle,
				'msgDescription'=>$this->msgDescription,
				'contents'=>$this->contents,
				'pushUserIds'=>$this->pushUserIds
			)
		);
	}
	
	public function send(){
		$data = $this->toArray();
		$res = https_request($this->serverUrl,json_encode($data));
		echo $res;
	}
}
?>