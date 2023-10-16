<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\User as UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class User extends BaseController
{
    function result($code, $msg, $data)
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    function register(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $u = UserModel::where('username', '=', $username)->find();
        if ($u) {
            return $this->result(400, "用户已存在", null);
        }

        $user = new UserModel;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        $res = $user->save();

        if ($res) {
            return $this->result(200, "注册成功", $res);
        }
        return $this->result(400, "注册失败", $res);
    }

    function login(Request $request){
        $username = $request->post('username');
        $password = $request->post('password');
        $u = UserModel::where('username', '=', $username)->find();
        if (!$u) {
            return $this->result(400, "用户不存在", null);
        }
        if($u->is_deleted==1){
            return $this->result(400,"该用户已被冻结，请稍后重试",null);
        }
        if(password_verify($password,$u->password)){
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

    function getAll(){
        $users = UserModel::field('id,username,is_deleted')->select();
        if($users->isEmpty()){
            return $this->result(400,"没有获取到数据",null);            
        }
            return $this->result(200,"获取用户数据成功",$users);
    }

    function getById($id){
        $user = UserModel::where('id','=',$id)->field('id,username,is_deleted')->find();
        if($user){
            return $this->result(200,"查询成功",$user);
        }
        return $this->result(400,"没有找到用户",null);
    }
}