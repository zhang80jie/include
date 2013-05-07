<?php
/**
 * 操作与事件相关的类
 * @author zzj
 */
class eventModel extends parentModel {
	
	//增加未来大事件
	public function addEvent($openid, $event_name,$event_date,$arrange,$groupid,$event_detail,$content_catalog) {
			if($arrange==2){
				$arrange = $groupid;
			}
			$data = array (
				'event_desc' => $event_name,
				'event_detail' => $event_detail,
				'event_happened_date'=>$event_date,
				'event_createdtimestamp'=>date("Y/m/d H:i:s"),
				'event_createdby_openid'=>$openid,
				'event_isprove'=>0,
				'event_open_arrage'=>$arrange,
				'catalog_id'=>$content_catalog
			);
			return $this->db->insert('fevent_eventcontent', $data);

	}
	
	//增加事件分类的使用状况
	public function updateCatalogUsed($content_catalog){
		$this->db->exec("update fevent_content_catalog set content_cata_usednum=content_cata_usednum+1 where content_cata_id=".$content_catalog);
	}
	
	
	//新增大事件列表
	public function getAddedEventList($limit = 10) {
		$sql = 'select event_desc, event_happened_date ,event_createdby_openid from  fevent_eventcontent  order by event_id desc limit ' . $limit;
		return $this->db->fetchAll($sql);
	}
	
	//查询事件类别
	public function getEventCatalogList($limit = 10){
	 	$sql = 'select f.content_cata_id,f.content_cata_name,f.content_cata_usednum from fevent_content_catalog f  order by f.content_cata_display limit ' . $limit;
		return $this->db->fetchAll($sql);
		
	}
	
	//获取发布的大事件列表
	public function getEventList($openid,$limit = 10){
		$sql ="select event_desc, event_happened_date ,event_createdby_openid from  fevent_eventcontent  where event_createdby_openid='".$openid."' order by event_id desc limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new eventModel();
		}
		return $_instance;
	}
	
}
?>
