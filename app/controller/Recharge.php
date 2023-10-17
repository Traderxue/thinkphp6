<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Recharge as RechargeModel;

class Recharge extends BaseController{
    function result($code,$msg,$data){
        return json([
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }

    function getPage(Request $request){
        $page = $request->param('page',1);
        $pageSize = $request->param('pageSize',10);

        $list = RechargeModel::paginate([
            'page'=>$page,
            'list_row'=>$pageSize
        ]);

        $this->result(200,"获取数据成功",$list);
    }

    function deleteRecord($id){
        $res= RechargeModel::where('id',$id)->delete();
        if($res){
            return $this->result(200,"删除成功",null);
        }
        return $this->result(400,"删除失败",null);
    }

    function addRecord(Request $request){

        $record = new RechargeModel;

        $record->time = date("Y-m-d H:i:s");
        $record->money = $request->post('money');
        $record->type = $request->post('type');
        $record->u_id = $request->post('u_id');

        $res = $record->save();

        if($res){
            return $this->result(200,"添加成功",null);
        }
        return $this->result(400,"添加失败",null);
    }
}