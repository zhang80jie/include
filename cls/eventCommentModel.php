<?php
	/**
	 * 操作与事件评论相关的类
	 * @author zzj
	 */
	class eventCommentModel extends parentModel {
		
	
	//增加事件评论
	
	
	//获取事件评论列表
	
	
	//获取用户已评列表
	public function getEventListByUserComments($openid,$limit = 5) {
		$sql = "select f.event_id,f.event_desc,f.event_comment_count from fevent_eventcontent  f where date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d')  and event_createdby_openid='".$openid."' order by f.event_comment_count desc  limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	
	//获取用户热评列表
	public function getEventListByHotComments($limit = 5) {
		$sql = "select f.event_id,f.event_desc,f.event_comment_count from fevent_eventcontent  f where date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d')   order by f.event_comment_count desc  limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	
	
	
	//增加评论总数
	public function updateEventCommentsTotalNum($event_id){
		$this->db->exec("update fevent_eventcontent set event_comment_count=event_comment_count+1 where event_id=".$event_id);
	}
	
	
	//获取评论总数	
	public function getEventCommentsTotalNum($event_id)	{
	 	$sql = 'select event_comment_count from fevent_eventcontent where event_id=' . $event_id;
		$event_comment_count = $this->db->fetchOne($sql);
		return $event_comment_count ? $event_comment_count : 0;
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
