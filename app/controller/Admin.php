<?php

namespace app\controller;
use app\BaseController;
use app\model\Admin as AdminModel;
use think\Request;
use Firebase\JWT\JWT;

class Admin extends BaseController{

    function result($code,$msg,$data){
        return json([
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }

    function login(Request $request){
        $username = $request->post('username');
        $password = $request->post('password');
        $user = AdminModel::where('username',$username)->find();
        if(!$user){
            return $this->result(400,"用户不存在",null);
        }
        if(password_verify($password,$user->password)){
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
                // "iss" => "http://127.0.0.1:8000",  // JWT的签发者
                // "aud" => "http://127.0.0.1:9528/",  // JWT的接收者可以省略
                "iat" => time(),  // token 的创建时间
                "nbf" =>  time(),  // token 的生效时间
                "exp" => time() + 3600*100,  // token 的过期时间100h
                "data" => [
                    // 包含的用户信息等数据
                    "username" => $username,
                ]
            );
             // 使用密钥进行签名
            $token = JWT::encode($payload, $secretKey,'HS256');
            return $this->result(200,"登录成功",$token);            
        }
        return $this->result(400,"用户名或密码错误",null);
    }

    
    function addAdmin(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $u = AdminModel::where('username', '=', $username)->find();
        if ($u) {
            return $this->result(400, "用户已存在", null);
        }

        $user = new AdminModel;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        $res = $user->save();

        if ($res) {
            return $this->result(200, "注册成功", $res);
        }
        return $this->result(400, "注册失败", $res);
    }

    function editAdmin(Request $request){
        $user = new AdminModel;
        $id = $request->post('id');
        $username = $request->post('username');
        $password = password_hash($request->post('password'),PASSWORD_DEFAULT);
        $auth = $request->post('auth');
        $data = [
            'username'=>$username,
            'password'=>$password,
            'auth'=>$auth
        ];
        $res = $user->where('id',$id)->update($data);
        if($res){
            return $this->result(200,"修改成功",null);
        }
        return $this->result(400,"修改失败",null);
    }

    function deleteAdmin($id){
        $user = AdminModel::where('id',$id)->find();
        if($user->username=='admin'){
            return $this->result(400,"管理员禁止删除",null);
        }
        $res = AdminModel::where('id',$id)->delete();
        if($res){
            return $this->result(200,"删除成功",null);
        }
        return $this->result(400,"删除失败",null);
    }

    function getAdmin(){
        $user = AdminModel::field('id,username,auth')->select();
        if($user->isEmpty()){
            return $this->result(400,"未查询到结果",null);
        }
        return $this->result(200,"查询成功",$user);
    }

    function getPage(Request $request){
        $page = $request->param('page',1);
        $pageSize = $request->param('pageSize',10);

        $user = AdminModel::field('id, username, auth')->paginate([
            'page' => $page,
            'list_rows' => $pageSize,
        ]);

        return $this->result(200,"查询成功",$user);

    }
}