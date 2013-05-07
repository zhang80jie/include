<?php
/**
 * 操作与用户建议相关的类
 * @author zzj
 */
class adviceModel extends parentModel {
	
	//增加未来大事件
	public function addAdvice($openid, $advice_id,$advice_message) {
			$data = array (
				'open_id' => $openid,
				'advice_id' => $advice_id,
				'advice_message'=>$advice_message
			);
			return $this->db->insert('fevent_advice', $data);

	}
	
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new adviceModel();
		}
		return $_instance;
	}
	
}
?>
