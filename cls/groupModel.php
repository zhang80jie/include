<?php

// 操作与群组相关的类
class groupModel extends parentModel{
	
	
	/**
	 * 新增群组
	 */
	
	public function addGroup($group_name,$group_desc,$group_qq_group,$group_created_by){
		
		$data = array(
			'group_name' =>$group_name,
			'group_desc' =>$group_desc,
			'group_qq_group'=>$group_qq_group,
			'group_created_by'=>$group_created_by
		);
		$result= $this->db->insert('fevent_group', $data);
		if($result>0){
			self::joinGroupWhenCreate($group_created_by);			
		}
		return $result;
		
	}
	
	/**
	 * 新建立的群组列表
	 */
		
	public function getGroupListCreatedByOpenid($openid,$limit = 10){
		$sql = "select group_id,group_name from fevent_group where group_created_by='".$openid."'  limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	/**
	 * 新建立的群组列表
	 */
	public function getGroupListCreated($limit = 10){
		$sql = "select group_id,group_name from fevent_group order by group_id desc limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	/**
	 * 查询群组根据群组号
	 */
	public function getGroupListByGroupId($groupid,$limit = 10){
		$sql = "select g.group_id,g.group_name from fevent_group g where g.group_id like '%".$groupid."%' limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	/**
	 * 查询群组根据群组名
	 */
	public function getGroupListByGroupName($groupname,$limit = 10){
			$sql = "select g.group_id,g.group_name from fevent_group g where g.group_desc like '%".$groupname."%' limit " . $limit;
		return $this->db->fetchAll($sql);
	}
	
	
	/**
	 * 用户创建群组默认加入 
	 */
	 
	 public function joinGroupWhenCreate($openid){
	 	$sql ="insert into fevent_user_group(group_id,openid)  (select max(group_id),group_created_by from fevent_group where group_created_by='".$openid."' )";
		$this->db->exec($sql);
	 }
	 
	 /**
	  * 获取热门群组列表
	  */
	 public function getHotGroupList($limit = 15){
	 	$sql ="select fg.group_id,fg.group_name,count(fug.openid) as num  from fevent_group fg ,fevent_user_group fug where fg.group_id=fug.group_id group by fg.group_id order by num desc limit ". $limit;
		return $this->db->fetchAll($sql);
	 }
	 
	 
	 /**
	  * 加入群组
	  */
	  
	  public function joinGroup($openid,$groupid){
			$data = array(
				'group_id'=>$groupid,
				'openid'=>$openid
			);
		 $result = $this->db->insert('fevent_user_group', $data);
		 return $result;
	  }
	  
	  /**
	   * 是否是小组成员
	   */
	   public function isGroupMember($openid,$groupid){
	   	$sql  = "select count(1) from fevent_user_group fa where fa.openid='".$openid."' and fa.group_id=".$groupid;
		$ismember = $this->db->fetchOne($sql);
		return $ismember ? $ismember : 0;
	   }
	   
	   /**
	    * 获取加入的群组信息
	    */
	   public function getJoinedGroupListByOpenid($openid,$limit = 5){
	   	$sql ="select g.group_id,g.group_name from fevent_group g,fevent_user_group ug where g.group_id=ug.group_id  and ug.openid='".$openid."' limit ".$limit ;
	   	return $this->db->fetchAll($sql);
	   }
	   
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new groupModel();
		}
		return $_instance;
	}		
}
?>
