<?php


/**
 * 操作与积分相关的类
 * related tables: fevent_user_info
 * @author zzj
 */
class userModel extends parentModel {
	
	
	

	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new userModel();
		}
		return $_instance;
	}

}
?>
