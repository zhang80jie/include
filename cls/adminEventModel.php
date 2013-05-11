<?php
/**
 * 管理员审核发布的事件
 * @author zzj
 */
class adminEventModel extends parentModel {
	
	
	
	//审核事件成功与否
	public function updateAdminEvent($event_id,$command){
		
		
	 return	$this->db->exec("update fevent_eventcontent set event_isprove='".$command."' where event_id='".$event_id."'");
	}
	
	
	//获取发布的大事件列表
	public function getAdminEventList(){
		$sql ="select event_id,event_desc, event_happened_date ,event_detail from  fevent_eventcontent where  event_isprove=0 and event_open_arrage>0";
		return $this->db->fetchAll($sql);
	}
	
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new adminEventModel();
		}
		return $_instance;
	}
	
}
?>
