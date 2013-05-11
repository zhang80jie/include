<?php
/**
 * 管理平台
 * 管理人员登录验证
 * tables
 * @author zzj
 */
class adminUserModel extends parentModel {
	

	//判断是否是管理员
	
	  public function isAdminUser($username,$passsword){
	   	$sql  = "select count(1) from admin_users fa where fa.admin_username='".$username."' and fa.admin_password='".$passsword."'";
		$isadmin = $this->db->fetchOne($sql);
		return $isadmin ? $isadmin : 0;
	   }
	
	
	
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new adminUserModel();
		}
		return $_instance;
	}
	
}
?>
