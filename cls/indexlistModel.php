<?php
/**
 * 首页显示相关的类
 * @author zzj
 */
class indexlistModel extends parentModel {
	
	
	
	//获得全部公开未来大事件
	/**
	public function getEventList(){
		$sql = "select f.event_id,f.event_desc,f.event_happened_date,f.attention_count,f.event_detail from fevent_eventcontent  f where  date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d') and f.event_isprove=1 and f.event_open_arrage=1 order by f.attention_count desc";
		return $this->db->fetchAll($sql);
	}**/
	
	//获取sql对应的事件列表
	public function getEventList($sql){
		return $this->db->fetchAll($sql);
	}
	
	//获取查询未来大事件的总数
	public function getEventListNum($sql){
		//$sql = "select f.event_id,f.event_desc,f.event_happened_date,f.attention_count,f.event_detail from fevent_eventcontent  f where  date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d') and f.event_isprove=1 and f.event_open_arrage=1 order by f.attention_count desc";
		return $this->db->getCount($sql);
	}
	
	
	
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new indexlistModel();
		}
		return $_instance;
	}
	
}
?>
