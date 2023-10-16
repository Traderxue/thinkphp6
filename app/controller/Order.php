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


}