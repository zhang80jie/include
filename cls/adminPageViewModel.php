<?php
/**
 * 记录各个页面被访问的次数
 * @author zzj
 */
class adminPageViewModel extends parentModel {
	
	
	
	//更新页面被访问数量
	static function updatePageViewNum($page_view_id){
	 	$this->db->exec("update admin_page_view set page_view_num=page_view_num+1 where page_view_id='".$page_view_id."'");
	}
	
	
	//获取页面被访问次数
	public function getPageViewList(){
		$sql ="select pv.page_file, pv.page_file_desc,pv.page_view_num from admin_page_view pv order by pv.page_view_num";
		return $this->db->fetchAll($sql);
	}
	
	
	
	/**
	 * 返回实例化对象
	 * @return object
	 */
	public final static function getInstace() {
		static $_instance = NULL;
		if (NULL === $_instance) {
			$_instance = new adminPageViewModel();
		}
		return $_instance;
	}
	
}
?>
