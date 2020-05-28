<?php
namespace Home\Controller;

use Think\Controller;
class MessageController extends BaseController {
	
    /**
     * 发布新树洞接口
     * @return [type] [description]
     */
    public function publish_new_message(){
        // 校验参数
        if (!$_POST['user_id']) { // 检验user_id
            
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：user_id';

            $this->ajaxReturn($return_data);
        }

        if (!$_POST['username']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：username';

            $this->ajaxReturn($return_data);
        }

        if (!$_POST['face_url']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：face_url';

            $this->ajaxReturn($return_data);
        }

        if (!$_POST['content']){
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：content';

            $this->ajaxReturn($return_data);
        }


        // 实例化数据库对象
        $Message = M('Message');

        // 组装数据，插入数据库
        $data = array();
        $data['user_id'] = $_POST['user_id'];       // 用户id
        $data['username'] = $_POST['username'];     // 用户名
        $data['face_url'] = $_POST['face_url'];     // 用户头像地址
        $data['content'] = $_POST['content'];       // 用户发编辑的树洞信息
        $data['total_likes'] = 0;
        $data['send_timestamp'] = time();

        // 将直接装好的数据插入到数据库中去
        $result = $Message->add($data);

        if($result){ 
            // 树洞发布成功
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '新树洞发布成功';

            $this->ajaxReturn($return_data);
        }else{
            // 树洞发布失败
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '新树发布失败';

            $this->ajaxReturn($return_data);
        }

        dump($result);
    }


    /**
     * 获取全部树洞消息接口
     * @return [type] [description]
     */
    public function get_all_message(){

        // 实例化数据表对象
        $Message = M('Message');
        // 直接查询数据，不需要条件，全部查询，并且根据时间倒序显示
        $all_messages = $Message->order('id desc')->select();

        // 我们对时间进行一个遍历，修改时间的格式
        foreach ($all_messages as $key => $message) {
            // $key相当于是索引，$message相当于是该索引中的所有数据
            // data()函数可以设定对应的时间模板
            $all_messages[$key]['send_timestamp'] = date('Y-m-d H:i:s', $message['send_timestamp']);
        }

        // 打印一下数据回执
        $return_data = array();
        $return_data['error_code'] = 0;
        $return_data['msg'] = '数据查询成功';
        $return_data['data'] = $all_messages; // 最后我们把数据进行封装

        $this->ajaxReturn($return_data);
    }


    /**
     * 获取指定用户的所有树洞信息接口
     * @return [type] [description]
     */
    public function get_one_user_all_message(){

        // 先判断参数
        if (!$_POST['user_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：user_id';

            $this->ajaxReturn($return_data);
        }else{

            // 获取数据表实例化对象
            $Message = M('Message');

            // 设定查询条件
            $where = array();
            $where['user_id'] = $_POST['user_id'];
            // 查询数据
            $result = $Message->where($where)->select();
            
            // 判断数据是否获取成功
            if($result){
                // 成功获取数据
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '数据获取成功';
                $return_data['data'] = $result;

                $this->ajaxReturn($return_data);
            }else{
                // 数据获取失败
                $return_data = array();
                $return_data['error_code'] = 2;
                $return_data['msg'] = '该用户尚未发布树洞信息';

                $this->ajaxReturn($return_data);
            }
        }

        
    }


    /**
     * 点赞接口
     * @return [type] [description]
     */
    public function do_like(){
        
        // 判断参数
        if (!$_POST['message_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：message_id';

            $this->ajaxReturn($return_data);
        }

        if (!$_POST['user_id']) {
            
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：user_id';

            $this->ajaxReturn($return_data);
        }

        // 获取数据表实例化对象
        $Message = M('Message');
        // 设定查询条件
        $where = array();
        $where['message_id'] = $_POST['message_id'];
        // 查询相对应的数据
        $result = $Message->where($where)->find();

        // 判断要查询的树洞信息是否存在
        if ($result) {
            // 查询成功，点赞数据保存成功
            $data = array();
            $data['total_likes'] = $result['total_likes'] + 1;
            // 修改并保存数据
            $results = $Message->where($where)->save($data);
            $data['message_id'] = $_POST['message_id'];
            if ($results) {
                // 数据保存成功
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '数据保存成功';
                $return_data['data'] = $data;

                $this->ajaxReturn($return_data);
            }else{
                // 数据保存失败
                $return_data = array();
                $return_data['error_code'] = 3;
                $return_data['msg'] = '数据保存失败';

                $this->ajaxReturn($return_data);
            }

        }else{
            // 查询失败
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定数据查询不存在';

            $this->ajaxReturn($return_data);
        }
    }


    /**
     * 删除指定的树洞消息
     * @return [type] [description]
     */
    public function delete_message(){

        // 判断参数
        if (!$_POST['message_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：message_id';

            $this->ajaxReturn($return_data);
        }

        if(!$_POST['user_id']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '缺少参数：user_id';

            $this->ajaxReturn($return_data);
        }

        // 获取数据表实例化对象
        $Message = M('Message');
        // 设置查询条件
        $where = array();
        $where['id'] = $_POST['message_id'];
        // 查询数据库语句
        $result = $Message->where($where)->find();

        // 判断是否存在该条树洞
        if ($result){
            // 查询成功
            $return = $Message->where($where)->delete();
            if ($return) {
                // 删除成功
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '数据删除成功';
                $return_data['data'] = $_POST['message_id'];

                $this->ajaxReturn($return_data);
            }else{
                // 删除失败
                $return_data = array();
                $return_data['error_code'] = 3;
                $return_data['msg'] = '数据删除失败';

                $this->ajaxReturn($return_data);
            }
        }else{
            // 查询失败
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '指定树洞不存在';

            $this->ajaxReturn($return_data);
        }

    }
}