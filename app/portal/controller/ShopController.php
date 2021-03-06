<?php
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class ShopController extends HomeBaseController
{
    /**
     * 用户地址列表
     */
    public function index()
    {
        $user_id = cmf_get_current_user_id();
        $listgo = Db::name('address')->where('user_id', $user_id)->select();

        $this->assign('adds', $listgo);

        $user_id = cmf_get_current_user_id();

        $list = Db::name('address')->where('user_id', $user_id)->select();
        $id = $this->request->param('id', 0, 'intval'); // 商品id

        $goods = Db::name('product')->where('id', $id)->find();
        $this->assign('goods', $goods);
        $this->assign('page', $list);
        if(!empty($goods)){
            $a = array(session('cart'));
            foreach ($a as $key){
                if($key==$goods['id']){

                    $id = session('cart');
                    $id = explode(',', $id);
                    // $id = json_decode($id);

                    // 商品信息
                    $lista = Db::name('product')
                        ->field('id, name, price, sales,image')
                        ->where('id', 'in', $id)
                        ->select();
                    // echo session('cart');
                    $this->assign('list', $lista);
                    return $this->fetch("/shop");
                }else{
                }
            }
            $b = session('cart');
            $b = $b.",".$goods['id'];
           session('cart',$b);
          //  echo session('cart');
        }
        //查看所有购物车
        $id = session('cart');
        $id = explode(',', $id);
        // $id = json_decode($id);

        // 商品信息
        $lista = Db::name('product')
            ->field('id, name, price, sales,image')
            ->where('id', 'in', $id)
            ->select();
       // echo session('cart');
        $this->assign('list', $lista);
        return $this->fetch("/shop");

    }

    /**
     * 用户地址添加
     * 传: username收件人, province省份, city城市, details详细地址, phone手机号
     */
    public function add()
    {
        $data = $this->request->param(); // 商品id
        $user_id = cmf_get_current_user_id();
        $data['user_id'] = $user_id;

        $result = Db::name('address')->insert($data);

        $this->success('添加成功!');
    }

    /**
     * 用户地址删除
     * 传: 地址id
     */
    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval'); // 地址id
        $user_id = cmf_get_current_user_id();

        $result = Db::name('address')->where('id', $id)->where('user_id', $user_id)->delete();

        $this->success('删除成功!');
    }

    public function jiesuan()
    {

        $user_id = cmf_get_current_user_id();
        $address = Db::name('address')->where('user_id', $user_id)->select();
        $id = $this->request->param('id', 0, 'intval'); // 商品id

        $goods = Db::name('product')->where('id', $id)->find();

        $this->assign('goods', $goods);
        $this->assign('address', $address);
        return $this->fetch('/jiesuan');
    }
    public function qinkong()
    {
        session('cart',null);
        $user_id = cmf_get_current_user_id();
        $listgo = Db::name('address')->where('user_id', $user_id)->select();

        $this->assign('adds', $listgo);

        $user_id = cmf_get_current_user_id();

        $list = Db::name('address')->where('user_id', $user_id)->select();
        $id = $this->request->param('id', 0, 'intval'); // 商品id

        $goods = Db::name('product')->where('id', $id)->find();
        $this->assign('goods', $goods);
        $this->assign('page', $list);
        if(!empty($goods)){
            $a = array(session('cart'));
            foreach ($a as $key){
                if($key==$goods['id']){

                    $id = session('cart');
                    $id = explode(',', $id);
                    // $id = json_decode($id);

                    // 商品信息
                    $lista = Db::name('product')
                        ->field('id, name, price, sales')
                        ->where('id', 'in', $id)
                        ->select();
                    // echo session('cart');
                    $this->assign('list', $lista);
                    return $this->fetch("/shop");
                }else{
                }
            }
            $b = session('cart');
            $b = $b.",".$goods['id'];
            session('cart',$b);
            //  echo session('cart');
        }
        //查看所有购物车
        $id = session('cart');
        $id = explode(',', $id);
        // $id = json_decode($id);

        // 商品信息
        $lista = Db::name('product')
            ->field('id, name, price, sales')
            ->where('id', 'in', $id)
            ->select();
        // echo session('cart');
        $this->assign('list', $lista);
        return $this->fetch('/shop');
    }
    public function delshop(){
        $id = $this->request->param('id');
        //获取购物车中的所有商品id
       $listshop = session("cart");
       //转化为数组
       $listshop = explode(",", $listshop);
       //去掉相同的值
        $listshop = array_unique($listshop);

        //重新定义索引
        $listshop = array_values($listshop);

        //遍历去掉选中的值
       for($i=0;$i<count($listshop);$i++){

           if ($listshop[$i] ==$id){

               unset($listshop[$i]);
           }
       }
        //重新定义索引
        $listshop = array_values($listshop);
       $newlist = '';
        for($j=0;$j<count($listshop);$j++){
            $newlist = $newlist.",".$listshop[$j];
        }
       //更新数据后重新存进购物车

        session("cart",$newlist);
        //查询商品
        $lista = Db::name('product')
            ->field('id, name, price, sales,image')
            ->where('id', 'in', $id)
            ->select();
        // echo session('cart');
        //查询地址
        $user_id = cmf_get_current_user_id();
        $listgo = Db::name('address')->where('user_id', $user_id)->select();
        $this->assign('adds', $listgo);
        $this->assign('list', $lista);
        return $this->success('删除成功','/portal/shop/index');
    }
}
