<?php
namespace Home\Controller;

use Think\Controller;
class TestController extends BaseController {
	
	public function test(){
		var_dump(123);
	}

	/* 插入数据 */
	public function insert_test(){
		
		//实例化数据库对象
		$Message = M('Message');

		//组装插入的数据,把数据组装称一个数组的形式
		$data = array();
		$data['user_id'] = 2;
		$data['username'] = '李四';
		$data['face_url'] = 'YYYY.jpg';
		$data['content'] = '这是插入的第二条数据';
		$data['total_likes'] = 0;
		$data['send_timestamp'] = time();

		//将数据插入到数据库中去
		$result = $Message->add($data);

		var_dump($result);
	}


	/* 查询数据，允许查询出多个符合条件的结果 */
	public function select_test(){
		//实例化数据库对象
		$Message = M('Message');

		// 设置查询条件，通过条件来查询所需要的数据
		$where = array();
		$where['user_id'] = 1; // 查询条件

		// 执行查询体，查询数据
		$all_messages = $Message->where($where)->select(); //这里我们是按条件查询所有的数据
		dump($all_messages);

		$all_messages = $Message->where($where)
						->field('id,username')->select(); //select()方法可以同时查询出多条符合条件的数据
		dump($all_messages);
	}

	/* 查询数据，只允许查询出单条数据 */
	public function find_test(){
		//实例化数据库对象
		$Message = M('Message');

		//设置查询条件
		$where = array();
		$where['user_id'] = 1;

		// 进行数据查询操作
		$one_message = $Message->where($where)->find(); // find方法会把查询出来的一条数据里的内容全部都放到一个一维数组里面
		dump($one_message);
		var_dump($Message->getLastSql());
	}

	/* 修改数据，依据查询条件修改数据 */
	public function update_test(){
		//实例化数据库对象
		$Message = M('Message');

		//设置修改 条件
		$where = array();
		$where['user_id'] = 2;

		//设置需要修改的数据集合
		$data = array();
		$data['total_likes'] = 9;

		//执行修改操作,把新数据插入到相应的数据位中去
		$update_message = $Message->where($where)->save($data);
		dump($update_message);
	}

	/* 删除数据，依据条件删除相应的数据 */
	public function delete_test(){
		//实例化数据库对象
		$Message = M('Message');

		// 设置删除条件
		$where = array();
		$where['user_id'] = 2;

		//执行删除语句
		$delete_message = $Message->where($where)->delete();
		dump($delete_message);
	}

}