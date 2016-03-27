<?php
/**
 * 商品表模型
 *
 * @author chance Jaw
 * @date 2015-10-29
 */
namespace Common\Model;

use Think\Model;

class GoodsModel extends BaseModel
{
    protected $trueTableName = "Goods";

    /**
     * 商品详情
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        //@todo 需要加上其它数据
        return self::where('id = %d', $id)->find();
    }

    /**
     * 获取指定商品价格
     *
     * @param array $goodsId
     * @param string $field
     * @return mixed
     */
    public function getGoodsInfo(Array $goodsId, $field = 'id, price')
    {
        return self::where('id in (%s)', implode(',' ,$goodsId))->getField($field);
    }

    /**
     * 获取完整商品详情，包括图片，车型
     *
     * @param $goodsIds
     * @param $fields
     * @return mixed
     * @internal param $id
     */
    public function goodsExtraInfo($goodsIds, $fields = 'id, code, brand, car_series, series_start, series_end'){
        if (!is_array($goodsIds) || empty($goodsIds))
        {
            return false;
        }
        $goodsInfo  = D('Common/Goods')->where('id in (%s)', implode(',', $goodsIds))->getField($fields);
        $goodsExt   = D('Common/GoodsExt')->where('goods_id in (%s)', implode(',', $goodsIds))->getField('goods_id, spec, orien');
        $result = array();
        foreach($goodsIds as $gid)
        {
            $goodsInfo[$gid]['series_end'] = $goodsInfo[$gid]['series_end'] == 2888 ? date('Y') + 1 : $goodsInfo[$gid]['series_end'];
            $result[$gid] = array_merge($goodsInfo[$gid], $goodsExt[$gid]);
        }

        return $this->tranEndYear($result);
    }

    /**
     * 获取商品列表
     *
     * @param $cid 分类ID
     * @return mixed
     */
    public function goodsList($cid, $field='g.*,c.id as cid,c.name', $limit =''){
        load('Common/category');
        $cate   = D('Category')->where('if_show=1')->order('sort_order asc,id asc')->select();
        $idArr  = getChildIdArr($cate, $cid);
        if( !empty($cid) ){
            array_push($idArr, $cid);
        }
        if( !empty($idArr) ){
            $where = 'g.if_show=1 and g.cat_id in ("'.implode('","',$idArr).'")';
        }else{
            $where = 'g.if_show=1';
        }
        $join = 'LEFT JOIN Category c ON g.cat_id=c.id';
        $join2= 'LEFT JOIN GoodsExt as e ON g.id = e.goods_id';
        $tmp = D('Goods')->alias('g')->field($field)->where($where)->limit($limit)->join($join)->join($join2)->select();
        if (empty($tmp)) {
            return $tmp;
        }
        return $this->tranEndYear($tmp);
    }

    public function tranEndYear($result){
        foreach($result as &$t) {
            if (!isset($t['series_end'])) {
                continue;
            }
            if ($t['series_end'] == '2888') {
                $t['series_end'] = date('Y') + 1;
            }
        }
        return $result;
    }

    public function searchAllGoods($carEntity, $catIds,  $pageNo = 1, $pageSize = 8)
    {
        if ($pageNo < 1 || $pageSize <= 0)
        {
            return false;
        }
        $offset  = ($pageNo - 1) * $pageSize;

        if (!empty($catIds)) {
            $param['cat_id'] = array('IN', implode(',', $catIds));
        }
        $param['brand']  = array('EQ', $carEntity->brand);
        $param['car_series']    = array('EQ', $carEntity->series);
        $param['series_start']  = array('ELT', $carEntity->yearStart);
        $param['series_end']    = array('EGT', $carEntity->yearEnd);

        $goodsId = D('Common/Goods')->where($param)->limit($offset, $pageSize)->getField('id',true);
        $total   = D('Common/Goods')->where($param)->count();

        $goodsTotalPage  = ceil($total / $pageSize);

        $leftNum = $pageSize;
        $offset  = 0;

        if (0 < $goodsTotalPage && $goodsTotalPage <= $pageNo) {
            $pageLeft   = $total % $pageSize;
            $leftNum = $pageSize - $pageLeft;
            //保证下面realPage - 1大于0
            $realPage = ($pageNo - $goodsTotalPage) == 0 ? 1 : $pageNo - $goodsTotalPage;

            $offset = ($realPage - 1) * $pageSize;

            if ($goodsTotalPage < $pageNo && 0 < $leftNum)
            {
                $offset += $leftNum;
            }
        }

        if ($leftNum != 0 && $goodsTotalPage <= $pageNo) {
            $param['cg.brand'] = $param['brand'];
            $param['cg.series'] = $param['car_series'];
            $param['cg.series_start'] = $param['series_start'];
            $param['cg.series_end']   = $param['series_end'];
            unset($param['car_series'], $param['brand'], $param['series_start'], $param['series_end']);
            $total += D('Common/CarModelGoods')->alias('cg')->join('left join Goods g on cg.fdGoodsId=g.id')->where($param)->count();
            if ($leftNum)
            {
                $tmp = D('Common/CarModelGoods')->alias('cg')->join('left join Goods g on cg.fdGoodsId=g.id')->where($param)->limit($offset, $leftNum)->distinct(true)->getField('fdGoodsId', true);
                if (!empty($goodsId) && !empty($tmp))
                {
                    $goodsId = array_merge($goodsId, $tmp);
                }
                else if (empty($goodsId) && !empty($tmp))
                {
                    $goodsId = $tmp;
                }

            }
        }
        return ['data' => $goodsId, 'total' => $total];
    }
}
