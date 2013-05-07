<?php
/**
 * 用户关注事件相关的类
 * @author zzj
 */
class eventattenionModel extends parentModel {
	
	//是否是事件关注者
	public function isEventAttention($eventid,$openid){
		$sql  = "select count(1) from fevent_attentions fa where fa.openid='".$openid."' and fa.event_id='$eventid'";
		$attention = $this->db->fetchOne($sql);
		return $attention ? $attention : 0;
	}
	
	//进行关注
	public function addEventAttention($eventid,$openid){
		if(self::isEventAttention($eventid,$openid)==0){
			$data = array(
				'event_id'=>$eventid,
				'openid'=>$openid
			);
		$result = $this->db->insert('fevent_attentions', $data);
		if($result)
			self::updateEventAttention($eventid);
		}
		return $result ; 
	}
	
	//事件关注人数加1
	public function updateEventAttention($eventid){
		$this->db->exec("update fevent_eventcontent set attention_count=attention_count+1 where event_id=".$eventid);
	}



	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new eventattenionModel();
		}
		return $_instance;
	}
	
}
?>
