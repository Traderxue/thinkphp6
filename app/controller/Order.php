<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Order as OrderModel;

class Order extends BaseController{
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

        $orderList = OrderModel::paginate([
            'page'=>$page,
            'list_rows'=>$pageSize
        ]);

        return $this->result(200,"查询成功",$orderList);

    }

    function editPage(Request $request){
        $id = $request->post('id');
        $margin = $request->post('margin');
        $num = $request->post('num');
        $direction = $request->post('direction');

        $res = OrderModel::where('id',$id)->update([
            'margin'=>$margin,
            'num'=>$num,
            'direction'=>$direction
        ]);

        if($res){
            return $this->result(200,"修改成功",null);
        }
        return $this->result(400,"修改订单失败",null);
    }

    function addOrder(Request $request){
        $order = new OrderModel;
        $order->time = date("Y-m-d H:i:s");; 
        $order->buy_price = $request->post('buy_price');
        $order->margin = $request->post('margin');
        $order->num = $request->post('num');
        $order->direction = $request->post('direction');
        $order->u_id = $request->post('u_id');
        $order->position = $request->post('position');  

        $res = $order->save();

        if($res){
            return $this->result(200,"添加订单成功",null);
        }
        return $this->result(400,"添加失败",null);

    }

    function deleteOrder($id){
        $res = OrderModel::where('id','=',$id)->delete();
        if($res){
            return $this->result(200,"删除成功",null);
        }
        return $this->result(400,"删除失败",null);
    }

    function getByUserId($u_id){
        $list = OrderModel::where('u_id',$u_id)->select();
        if($list->isEmpty()){
            return $this->result(400,"没有查询到订单",null);
        }
        return $this->result(200,"获取订单成功",$list);
    }
}