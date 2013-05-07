<?php

/**
 * 与qq API交互的工具类
 */
 
class QQAPIModel  extends parentModel {
	
	//验证输入是否合法的方法
	static function validateword($sdk, $openid, $openkey, $appid, $appkey, $script_name, $pf, $content, $msgid, $actionid) {
		$script_name = '/v3/csec/word_filter';
		$url = 'appid=' . $appid . '&openid=' . $openid . '&openkey=' . $openkey . '&pf=' . $pf;
		$url = 'post&' . urlencode($script_name) . '&' . urlencode($url);
		$url = hash_hmac('sha1', $url, $appkey . '&', true);
		$url = base64_encode($url);

		$params = array (

			'openid' => $openid,
			'openkey' => $openkey,
			'appid' => $appid,
			'sig' => $url,
			'pf' => $pf,
			'content' => $content,
			'msgid' => $msgid,
			'actionid' => $actionid,

			
		);
		$sdk->setServerName('openapi.tencentyun.com');
		return $sdk->api($script_name, $params, 'post', 'http');
	}
	
	//验证输入是否合法简化
	static function validateword_simple($openid, $openkey, $content) {
		 $script_name ='';
		 global $config;
		 $sdk = new OpenApiV3($config['appid'],$config['appkey']);
		 $pf = 'qzone';
		 $msgid = 4;
		 $actionid = 6; 
		return  self::validateword($sdk, $openid, $openkey, $config['appid'], $config['appkey'], $script_name, $pf, $content, $msgid, $actionid);
	}

	//获取用户基本资料的API
	static function get_user_info($sdk, $openid, $openkey, $pf,$appid,$appkey) {
		$script_name = '/v3/user/get_info';
		
		$url = 'appid=' . $appid . '&openid=' . $openid . '&openkey=' . $openkey . '&pf=' . $pf;
		$url = 'post&' . urlencode($script_name) . '&' . urlencode($url);
		$url = hash_hmac('sha1', $url, $appkey . '&', true);
		$url = base64_encode($url);
		
		$params = array (
			'openid' => $openid,
			'openkey' => $openkey,
			'appid' => $appid,
			'sig' => $url,
			'pf' => $pf,
			'format'=>'json'
			
		);
		$server_name = 'openapi.tencentyun.com';
		$sdk->setServerName($server_name);
		return $sdk->api($script_name, $params, 'post','http');
	}
	//获取用户基本资料的API  不传入基本信息
   static function get_user_info_simple($openid,$openkey) {
		 global $config;
		 $sdk = new OpenApiV3($config['appid'],$config['appkey']);
		 $pf = 'qzone';
		 $userinfo =  self::get_user_info($sdk, $openid, $openkey, $pf,$config['appid'],$config['appkey']);
		 
		 if($userinfo['ret'] ==0){
		 	 self::update_user_info($userinfo,$openid); //更新用户信息
		 	 return $userinfo ;
		 }else{
		 	return  self::get_user_info_by_openid($openid) ;
		 }
		
	 
	}
	
	
	//记录用户基本信息
	 function update_user_info($userinfo,$openid){
		$infoid = self::user_info_isexists($openid);
		if($infoid == 0) {
			$data = array (
				'openid' => $openid,
				'nickname' => $userinfo['nickname'],
				'gender' => $userinfo['gender'],
				'country' => $userinfo['country'],
				'province' => $userinfo['province'],
				'city' => $userinfo['city'],
				'figureurl' => $userinfo['figureurl'],
				'createdtimestamp'=>date("Y/m/d H:i:s")
			);
			$sqlresult =self::getInstace()->db->insert('fevent_user_info', $data);
		} else {
			$data = array (
				'openid' => $openid,
				'nickname' => $userinfo['nickname'],
				'gender' => $userinfo['gender'],
				'country' => $userinfo['country'],
				'province' => $userinfo['province'],
				'city' => $userinfo['city'],
				'figureurl' => $userinfo['figureurl']
			);
		 $sqlresult =self::getInstace()->db->update('fevent_user_info', $data, ' infoid = ' . $infoid . '');
		}
		return $sqlresult;
	}
	
	//查询用户信息 
	 function get_user_info_by_openid($openid){
		$sql ="select u.nickname,u.gender,u.country,u.province,u.city,u.figureurl from fevent_user_info u where u.openid='".$openid."'";
		return  self::getInstace()->db->fetchOne($sql);
	}
	
	//查询是否已记录了用户信息
	 function user_info_isexists($openid){
		$sql ="select u.infoid, u.nickname,u.gender,u.country,u.province,u.city,u.figureurl from fevent_user_info u where u.openid='".$openid."'";
		$userinfo = self::getInstace()->db->fetchOne($sql);
		if(empty($userinfo)){
			return '0';
		}else{
			return $userinfo['infoid'] ;
		}
		
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
