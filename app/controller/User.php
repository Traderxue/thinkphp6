<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\User as UserModel;
use Firebase\JWT\JWT;

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

    function resetPwd(Request $request){
        $username = $request->post('username');
        $password = $request->post('password');
        $new_password = password_hash($request->post('new_password'),PASSWORD_DEFAULT);
        $user = UserModel::where('username','=',$username)->find();
        if(!$user){
            return $this->result(400,"用户不存在",null);
        }
        if(!password_verify($password,$user->password)){
            return $this->result(400,"旧密码错误",null);
        }
        $res = UserModel::where('username','=',$username)->update(['password'=>$new_password]);
        if($res){
            return $this->result(200,"修改成功",null);
        }
        return $this->result(400,"修改失败",null);

    }

    function deleteUserById($id){
        $user = UserModel::where('id','=',$id)->where('is_deleted','=',0)->find();
        if(!$user){
            return $this->result(400,"用户不存在",null);
        }
        $res = UserModel::where('id',$id)->update(['is_deleted'=>1]);
        if($res){
            return $this->result(200,"删除成功",null);
        }
        return $this->result(400,"删除失败",null);
    }

    function editUser(Request $request){
        $id = $request->post('id');
        $is_deleted = $request->post('is_deleted');
        $money = $request->post('money');
        $res = UserModel::where('id','=',$id)->update(['id_deleted'=>$is_deleted,'money'=>$money]);
        if($res){
            return $this->result(200,"更新成功",null);
        }
        return $this->result(400,"更新失败",null);
    }

    function getPage(Request $request){
        $page = $request->param('page',1);
        $pageSize = $request->param('pageSize',10);

        $user = UserModel::field('id, username, is_deleted,money')->paginate([
            'page' => $page,
            'list_rows' => $pageSize,
        ]);

        return $this->result(200,"查询成功",$user);

    }
}