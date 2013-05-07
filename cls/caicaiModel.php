<?php
class caicaiModel extends parentModel
{
	/**
	 * 随机查询一个水果
	 */
	public function getQuestionByRand()
	{
		$sql = 'SELECT * FROM `caicai_fruits` AS t1 
				JOIN (SELECT ROUND(RAND() * ((SELECT MAX(fruit_id) FROM `caicai_fruits`)-(SELECT MIN(fruit_id) FROM `caicai_fruits`))+(SELECT MIN(fruit_id) FROM `caicai_fruits`)) AS fruit_id) 
				AS t2
				WHERE t1.fruit_id >= t2.fruit_id 
				ORDER BY t1.fruit_id LIMIT 1';
		
		return $this->db->fetchRow($sql);
	}
	
	/**
	 * 根据ID 返回水果信息
	 * @param int $fruit_id
	 */
	public function getFruitById($fruit_id)
	{
		return $this->db->fetchRow('select * from caicai_fruits where fruit_id='.$fruit_id);
	}
	
	/**
	 * 获取成绩
	 * @param string $openid
	 */
	public function getCaiCaiResult($openid)
	{
		return $this->db->fetchRow('select * from caicai_result where openid="'.$openid.'"');
	}
	
	/**
	 * 个人历史总成绩
	 * @param string $openid
	 * @param int $total_all
	 * @param int $total_right
	 * @param int $total_wrong
	 * @param int $right_rate
	 */
	public function addUserResult($openid,$total_all,$total_right,$total_wrong,$right_rate)
	{
		$data = array(
						'openid'=>$openid,
						'total_all'=>$total_all,
						'total_right'=>$total_right,
						'total_wrong'=>$total_wrong,
						'right_rate'=>$right_rate
				);
		$this->db->insert('caicai_result',$data);
	}
	
	/**
	 * 更新个人成绩
	 * @param string $openid
	 * @param int $total_all
	 * @param int $total_right
	 * @param int $total_wrong
	 * @param int $right_rate
	 */
	public function updateUserResult($openid,$total_all,$total_right,$total_wrong,$right_rate)
	{
		$data = array(
						'total_all'=>$total_all,
						'total_right'=>$total_right,
						'total_wrong'=>$total_wrong,
						'right_rate'=>$right_rate
				);
		$this->db->update('caicai_result',$data,' openid = "'.$openid.'"');
	}
	
	/**
	 * 添加成绩
	 * @param string $open_id
	 * @param int $total
	 * @param int $right
	 * @param int $wrong
	 * @param int $right_rate
	 */
	public function addQuestionGroup($openid,$total,$right,$wrong,$right_rate)
	{
		$data = array(
					'openid'=>$openid,
					'total'=>$total,
					'right'=>$right,
					'wrong'=>$wrong,
					'right_rate'=>$right_rate,
					'test_date'=>date('Y-m-d',time())
				);
		return $this->db->insert('caicai_question_group',$data);
	}
	
	/**
	 * 添加问题
	 * @param string $openid
	 * @param int $group_id
	 * @param string $answer
	 * @param string $user_answer
	 * @param int $is_right
	 */
	public function addQuestion($openid,$group_id,$answer,$user_answer,$is_right)
	{
		$data = array(
					'openid'=>$openid,
					'group_id'=>$group_id,
					'answer'=>$answer,
					'user_answer'=>$user_answer,
					'is_right'=>$is_right
				);
		$this->db->insert('caicai_questions',$data);
	}
	
	/**
	 * 获取最近的测试成绩
	 * @param string $openid
	 * @param int $limit
	 */
	public function getLatestInfo($openid,$limit = 10)
	{
		$sql = 'select * from caicai_question_group where openid="'.$openid.'" order by group_id desc limit '.$limit;
		return $this->db->fetchAll($sql);
	}
	
	/**
	 * 获取排名
	 * @param int $right_rate
	 */
	public function getUserRank($right_rate)
	{
		$sql = 'select count(1) from caicai_result where right_rate > '.$right_rate;
		$count = $this->db->fetchOne($sql);
		return $count ? $count + 1 : 1;
	}
		/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace()
	{
		static $_instance = NULL;
		if(NULL === $_instance){
		   $_instance = new caicaiModel();
		}
		return $_instance;
	}
}