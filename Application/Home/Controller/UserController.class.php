<?php
namespace Home\Controller;

use Think\Controller;
class UserController extends BaseController {
	
    /**
     * 测试函数
     * @return [type] [description]
     */
    
    /*
        用户注册接口
     */
    public function sign(){

        // 校验参数是否存在
        if(!$_POST['username']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：username';

            $this->ajaxReturn($return_data);
        }

        if(!$_POST['phone']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：phone';

            $this->ajaxReturn($return_data);
        }

        if(!$_POST['password']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：password';

            $this->ajaxReturn($return_data);
        }

        if(!$_POST['password_again']){

            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：password_again';

            $this->ajaxReturn($return_data); 
        }

        // 检验两次的输入的密码输入是否一致
        if($_POST['password'] != $_POST['password_again']){

            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '两次输入的密码不一致，请重新输入！';

            $this->ajaxReturn($return_data);
        }

        
        // 检验所使用的电话号码是否已经被注册了
        // 先要查询是否已经被注册
        $User = M('User'); // 实例化user表对象
        $where = array();
        $where['phone'] = $_POST['phone'];
        
        $user = $User->where($where)->find(); // 查询数据表

        if($user){
            // 如果手机号已经被注册
            $return_data = array();
            $return_data['error_code'] = 3;
            $return_data['msg'] = '您输入的手机号已经被注册，请更换手机号注册！';

            $this->ajaxReturn($return_data);
        }else{
            // 如果未被注册，我们就把它写入数据库中
            $data = array(); // 组装数据，成一个一维数组
            $data['username'] = $_POST['username']; // 用户名
            $data['phone'] = $_POST['phone']; // 电话号码
            $data['password'] = md5($_POST['password']); // 密码,我们通过md5加密，得到32位字符串
            $data['face_url'] = $_POST['face_url']; // 用户头像

            // 数据组装完成之后，插入数据
            $result = $User->add($data);

            if($result){ // 判断插入数据是否成功
                // 数据插入成功
                $return_data = array();
                $return_data['data']['user_id'] = $result;
                $return_data['data']['username'] = $_POST['username'];
                $return_data['data']['phone'] = $_POST['phone'];
                $return_data['data']['face_url'] = $_POST['face_url'];

                $this->ajaxReturn($return_data);

            }else{
                // 数据插入失败
                $return_data = array();
                $return_data['error_code'] = 4;
                $return_data['msg'] = '注册失败！';

                $this->ajaxReturn($return_data);
            }


        }


        // dump($_POST);
    }




    /*
        用户登录接口
     */
    public function login(){

        //参数校验
        if(!$_POST['phone']){
            //账号校验
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：phone';

            $this->ajaxReturn($return_data);
            
        }

        if(!$_POST['password']){
            //密码校验
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '参数不足：password';

            $this->ajaxReturn($return_data);
        }

        

        // 校验登录账号是否存在
        // 判断用户是否已经注册，并允许登录
        $User = M('User'); // 获取User表的实例化对象
        $where = array();
        $where['phone'] = $_POST['phone'];

        $user = $User->where($where)->find();

        if($user){
            // 该用户已经注册，继续判断密码是否正确
            if($user['password'] != md5($_POST['password'])){
                // 密码不正确
                $return_data = array();
                $return_data['error_code'] = 3;
                $return_data['msg'] = '您输入的密码不正确，请重新输入！';

                $this->ajaxReturn($return_data);
                
            }else{
                // 密码正确，登录成功
                $return_data = array();
                $return_data['error_code'] = 0;
                $return_data['msg'] = '登录成功！';

                $return_data['data']['user_id'] = $user['id'];
                $return_data['data']['username'] = $user['username'];
                $return_data['data']['phone'] = $user['phone'];
                $return_data['data']['face_url'] = $user['face_url'];

                $this->ajaxReturn($return_data);
            }
        }else{
            // 该用户未被注册
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '该手机号用户还未注册，请注册！';

            $this->ajaxReturn($return_data);
        }


        dump($_POST);
        
    }

}