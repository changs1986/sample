<?php
namespace NN\Service;

use NN\Lib\SphinxClient;

class SearchService
{
    /**
     *  获取一个新的sphinx client实例
     *
     *  @return SphinxClient
     **/
    private function getSearchClient()
    {
        $client = new SphinxClient();
        $client->setServer(C('SEARCH_HOST'), 9312);
        $client->setMatchMode(SPH_MATCH_EXTENDED2);
        $client->SetArrayResult(true);
        return $client;
    }

    /**
     *  执行搜索操作并返回结果
     *
     *  @param SphinxClient $obj     搜索实例
     *  @param string       $keyword 关键字
     *  @param string       $index   索引名
     *
     *  @return array|Exception
     **/
    private function doSearch($obj, $keyword, $index)
    {
        if (!empty($keyword)) {
            $keyword = str_replace(['（', '）', '{', '}', '-'], ['(', ')', '', '', '\-'], $keyword);
        }
        $result = $obj->query($keyword, $index);
        if (!$result) {
            throw new \Exception($obj->GetLastError());
        }
        return $result;
    }

    public function getGoods($keyword)
    {
        $client = $this->getSearchClient();
        return $this->doSearch($client, $keyword, 'modelgoods');
    }

}
