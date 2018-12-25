<?php
/**
 * Created by PhpStorm.
 * @name:
 * @author: weikai
 * @date: dt
 */

namespace Weikaiii\Express;

use http\Env\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class Express
 * @package Weikaiii\Express100
 * @name:快递100查询类
 * @author: weikai
 * @date: 2018/12/25 12:25
 */
class ExpressHundred
{

    /**
     * @param $company
     * @param $number
     * @param $customer
     * @return mixed
     * @name:实时查询api
     * @author: weikai
     * @date: 2018/12/25 12:25
     */
    public function logistics($company,$number,$customer,$key,$mode='test')
    {
        $post_data = [];
        $post_data["customer"] = $customer;
        $post_data['param']=<<<TEXT
            {
                "com":"$company",
                "num":"$number"
            }
TEXT;
        $mode == 'test' ? $url='http://poll.kuaidi100.com/poll/query.do' : $url='https://poll.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data=substr($o,0,-1);
        $data = $this->curlPost($url,$post_data);
        return $data;
    }

    /**
     * @param $company
     * @param $number
     * @param $callbackurl
     * @return mixed
     * @name:订阅物流信息
     * @author: weikai
     * @date: 2018/12/25 12:38
     */
    public function subscribe($company,$number,string $callbackurl,$key,$mode='test')
    {
        $mode == 'test' ? $url='http://poll.kuaidi100.com/poll' : $url='https://poll.kuaidi100.com/poll';
        $post_data["schema"] = 'json' ;
        $post_data['param'] = <<<TEXT
        {
        "company":"$company",
        "number":"$number",
        "key":"$key",
        "parameters":{
            "callbackurl":"$callbackurl"
        }}
TEXT;
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }

        $post_data=substr($o,0,-1);
        $result = $this->curlPost($url,$post_data);
        return $result;

    }

    /**
     * @name:快递推送
     * @author: weikai
     * @date: 2018/12/25 12:45
     */
    public function push()
    {
        header("Content-Type:text/html;charset=utf-8");
        $param = $_POST['param'];
        $param = json_decode($param,true);
        try{
            return $param;
            echo  '{"result":"true",	"returnCode":"200","message":"成功"}';
            //要返回成功（格式与订阅时指定的格式一致），不返回成功就代表失败，没有这个30分钟以后会重推
        } catch(\Exception $e)
        {
            echo  '{"result":"false",	"returnCode":"500","message":"失败"}';
            //保存失败，返回失败信息，30分钟以后会重推
        }
    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     * @name:curl post
     * @author: weikai
     * @date: 2018/12/25 12:37
     */
    public function curlPost($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = str_replace("\"",'"',$result );
        $data = json_decode($data,true);
        return $data;
    }
}