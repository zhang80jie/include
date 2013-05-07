<?php


/**
 * 操作与积分相关的类
 * related tables: fevent_points,fevent_group_point
 * @author zzj
 */
class pointsModel extends parentModel {
	/**
	 * 增加积分
	 */
	public function addPoint($openid, $points) {
		$orignalpoints = getPointByOpenid($openid);
		if ($orignalpoints == 0) {
			$data = array (
				'openid' => $openid,
				'points' => $points
			);
			$this->db->insert('fevent_points', $data);
		} else {
			$data = array (
				'points' => $points + $orignalpoints
			);
			$this->db->update('fevent_points', $data, ' openid = "' . $openid . '"');
		}

	}

	/**
	 * 用户使用消耗积分
	 */
	public function minusPoint($openid, $points) {
		$orignalpoints = self::getPointByOpenid($openid);
		if($orignalpoints == 0){
			return 0 ;
		}
		if ($points > $orignalpoints) {
			$data = array (
				'points' => 0
			);
		} else {
			$data = array (
				'points' => $orignalpoints - $points
			);
		}
		$this->db->update('fevent_points', $data, ' openid = "' . $openid . '"');
		
		if($points> $orignalpoints){
		   return $orignalpoints;
		 }else{
			return $points ;
		 }
		
	}

	/**
	 * 用户个人积分查询
	 */

	public function getPointByOpenid($openid) {

		$sql = 'select points from fevent_points where openid="' . $openid.'"';
		$points = $this->db->fetchOne($sql);
		return $points ? $points : 0;
	}

	/**
	 * 用户积分排行榜
	 */
	public function getPointList($limit = 10) {
		$sql = 'select point_id, openid,points from fevent_points  order by points desc limit ' . $limit;
		return $this->db->fetchAll($sql);
	}

	/**
	 * 积分获取明细
	 */
	public function getPointaddedDetails() {

	}

	/**
	 * 增加推广所用所用积分
	 */
	public function addGroupPoint($openid, $groupid, $grouppoints) {
		$data = array (
			'group_id' => $groupid,
			'group_points' => $grouppoints,
			'group_point_openid' => $openid
		);
		return $this->db->insert('fevent_group_point', $data);
	}

	/**
	 * 推广群组消耗积分
	 */
	public function addGroupUsedPoint($groupid, $points) {
		$orignalusedpoints = getPointByGroupID($groupid);
		$data = array (
			'points' => $orignalusedpoints + $points
		);
		$this->db->update('fevent_group_point', $data, ' groupid = "' . $groupid . '"');
	}

	/**
	 * 根据群组号获取积分
	 */

	public function getPointByGroupID($groupid) {
		$sql = 'select group_points_used from fevent_group_point where group_id=' . $groupid;
		$points = $this->db->fetchOne($sql);
		return $points ? $points : 0;
	}

	/**
	 * 推广群组消耗积分明细
	 */

	public function getGroupPointDetail($openid, $limit = 10) {
	//	$sql = 'select group_id,g.group_name, group_points,group_points_used from fevent_group_point ,fevent_group g where group_point_openid="' . $openid . '" and group_point_id=g.group_id order by group_point_id desc limit ' . $limit;
		$sql = 'select gp.group_id,g.group_name, gp.group_points,gp.group_points_used from fevent_group_point gp ,fevent_group g where group_point_openid="'.$openid.'" and gp.group_id=g.group_id order by gp.group_point_id desc limit ' . $limit;
		return $this->db->fetchAll($sql);
	}

	/**
	 * 获取要推广的群组信息
	 */
	public function getGroupExtendedList($limit = 10) {
		$sql = "select g.group_id ,g.group_name from fevent_group_point gp1,fevent_group_point gp2 ,fevent_group g where gp1.group_point_id=gp2.group_point_id and gp1.group_id=g.group_id and gp1.group_points>gp2.group_points_used  limit " . $limit;
		return $this->db->fetchAll($sql);
	}

	/**
	 * 获取用户加入的群组
	 */
	public function getGroupsByOpenid($openid) {
		$sql ="select g.group_id,g.group_name from fevent_user_group ug ,fevent_group g where g.group_id=ug.group_id and ug.openid='".$openid."'";
		return $this->db->fetchAll($sql);

	}

	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new pointsModel();
		}
		return $_instance;
	}

}
?>
