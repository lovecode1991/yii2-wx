<?php

namespace abei2017\wx\mp\user;

use abei2017\wx\core\Driver;
use abei2017\wx\core\AccessToken;
use abei2017\wx\core\Exception;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
use yii\httpclient\StreamTransport;

/**
 * 备注助手
 *
 * @author abei<abei@nai8.me>
 * @package abei2017\wx\mp\user
 */
class Remark extends Driver {

    const API_UPDATE_REMARK_URL = "https://api.weixin.qq.com/cgi-bin/user/info/updateremark";

    /**
     * @var bool 接口令牌
     */
    private $accessToken = false;

    public function init(){
        parent::init();

        $this->accessToken = (new AccessToken(['conf'=>$this->conf,'httpClient'=>$this->httpClient,'extra'=>[]]))->getToken();
    }

    /**
     * 给一个用户打备注
     *
     * @param $openId
     * @param $remark
     * @return bool
     * @throws Exception
     */
    public function update($openId,$remark){
        $this->httpClient->formatters = ['uncodeJson'=>'abei2017\wx\helpers\JsonFormatter'];
        $response = $this->httpClient->createRequest()
            ->setUrl(self::API_UPDATE_REMARK_URL."?access_token={$this->accessToken}")
            ->setMethod('post')
            ->setData(['openid'=>$openId,'remark'=>$remark])
            ->setFormat('uncodeJson')->send();

        $data = $response->getData();
        if(isset($data['errcode']) && $data['errcode'] == 0){
            return true;
        }else{
            throw new Exception($data['errmsg'],$data['errcode']);
        }
    }

}