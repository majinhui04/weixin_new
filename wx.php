<?php

date_default_timezone_set('PRC');
/* 

1.笑话
2.图片
3.语音
4.糗事
*/

/**
  * wechat php test
  */
// header("Content-type: text/html; charset=utf-8"); 
logger('------------start------------');
traceHttp();
//define your token
define("ADMIN","oi4_BjvkDVp1zp5UyYxFIPnIaeZk");
define("TOKEN", "majinhui04");
define("answer", "\r\n您可以回复下面的关键字给我：\r\n 笑话 \r\n 图片 \r\n 语音 \r\n 糗事 \r\n 或者给小胖建议，格式为'建议@建议的内容'");
define("textTpl", "<xml> <ToUserName><![CDATA[%s]]></ToUserName> <FromUserName><![CDATA[%s]]></FromUserName> <CreateTime>%s</CreateTime> <MsgType><![CDATA[%s]]></MsgType> <Content><![CDATA[%s]]></Content> <FuncFlag>0</FuncFlag> </xml>");
$imageTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Image>
            <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            </xml>"; 
$voiceTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Voice>
            <MediaId><![CDATA[%s]]></MediaId>
            </Voice>
            </xml>"; 
$randomArray = array(
    '学识浅薄的我实在听不懂你在说什么，默默的面壁去······',
    '小胖现在理解能力有限，要不换点别的。',
    '唉，你输入的内容真的好深奥呀，能不能说点容易的，给人家留点面子嘛。',
    '没事耍什么深沉，害我都无法理解，来点简单的呗。',
    '没听明白，要不聊点别的如何？',
    '看不懂，大侠，能不能换个说法？？？',
    '虽然小胖读不懂你的话，但小胖却能用心感受你对我的爱。',
    '是世界变化太快，还是我不够有才？为何你说话我不明白？',
    '听的我一头雾水，阁下真是渊博呀，我需要膜拜~',
    ';)谦虚是立足之本。我很谦虚。但是我认为你说的特别尤其相当对。',
    '不要用这种眼神看我嘛，我会觉得害羞的',
    );
$pigAnswerArray = array(
    '一种和你一样聪明的动物',
    '有点胖',
    '涨价了',
    '你的鼻子有两个孔。。。',
    '喜欢猴哥',
    '你才是',
    '可以骂人',
    '其实我属蛇',
    );
$helloArray = array(
    '嗨',
    '你好',
    '小胖',
    'hello',
    '您好',
    '幸会',
    '你好啊',
    '喂',
    );
$helloAnswerArray = array(
    '在呢',
    '臣在',
    '人在塔在',
    '等候您多时了',
    '小的在',
    '您吩咐',
    '在',
    '一直在您左右',
    );
$fuckArray = array(
    'fuck',
    'fuck you',
    'fuckyou',
    '2',
    '二',
    '傻逼',
    '傻瓜',
    '王八蛋',
    '蛋',
    '草',
    '操',
    '2b',
    '2B',
    '你妹啊',
    '你妹',
    );
$fuckAnswerArray = array(
    '要讲文明，讲道理',
    'MMD,我从没见过长的这么有考古价值的',
    '哥，把你脸上的分辨率调低点好吗',
    '水至清则无鱼，人至贱则无敌',
    );
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->responseMsg()){
            //logger('echoStr 11111111111'.$echoStr);
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
       
                classifyMessage($postObj);

        }else {
            echo "";
            exit;
        }

        logger('------------end------------<br>');
    }
        
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

function isWrite(){
    $fp = fopen("admin.txt", "r");

    $content = fgets($fp);
    $key = substr($content,10,1);
    fclose($fp); 
    if('w' == $key){
        return true;
    }else{
        return false;
    }
}
function setWrite(){
    $fp = fopen("admin.txt", "w+");//文件被清空后再写入
    fwrite($fp,"wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww"); 
    fclose($fp); 
}
function setRead(){
    $fp = fopen("admin.txt", "w+");//文件被清空后再写入
    fwrite($fp,"rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr"); 
    fclose($fp); 
}
function classifyMessage($postObj){
    $textTpl = textTpl;

    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $content = $postObj->Content;
    $time = time();

    if($fromUsername == ADMIN and $msgType == 'text' and '1'==$content){
        user_logger($postObj);
        $contentStr = "测试一下\r\n测试一下\r\n";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
        echo $resultStr;
        return true;
    }
    if($fromUsername == ADMIN and $msgType == 'text' and 'write'==$content){
        setWrite();
        $contentStr = '开始录入';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
        echo $resultStr;
        return true;
    }
    if($fromUsername == ADMIN and $msgType == 'text' and 'exit'==$content){
        setRead();
        $contentStr = '退出了';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
        echo $resultStr;
        return true;
    }

    if($fromUsername == ADMIN and isWrite() ){
         switch($msgType){
            case 'text':
                createTextMsg($postObj);
                break;
            case 'image':
                createImageMsg($postObj);
                break;
            case 'voice':
                createVoiceMsg($postObj);
                break;
            case 'video':
                //createVideoMsg($postObj);
                break;
            default:
                break;
        }
        return true;
    }

    logger('----------type:'.$msgType);
    user_logger($postObj);
    switch($msgType){
        case 'text':
            handleTextMsg($postObj);
            break;
        case 'image':
            handleImageMsg($postObj);
            break;
        case 'voice':
            handleVoiceMsg($postObj);
            break;
        case 'video':
            handleVideoMsg($postObj);
            break;
        case 'location':
            handleLocationMsg($postObj);
            break;
        case 'link':
            handleLinkMsg($postObj);
            break;
        case 'event':
            handleEventMsg($postObj);
            break;
        default:
            handleMsg($postObj);
            break;
    }
    
        
}
function getQiushi(){
    $arr =  getJokeList('qiushi.txt');

    return getRandomMsg($arr);
}
function getTextJoke(){
    $jokeList =  getJokeList('joke_text.txt');

    return getRandomMsg($jokeList);
}
function getImageJoke(){
    $jokeList =  getJokeList('joke_image.txt');
    $jokeStr = getRandomMsg($jokeList);
    logger($jokeStr);
    $joke = json_decode($jokeStr, true);
    return $joke;
}
function getVoiceJoke(){
    $jokeList =  getJokeList('joke_voice.txt');
    $jokeStr = getRandomMsg($jokeList);
    logger($jokeStr);
    $joke = json_decode($jokeStr, true);
    return $joke;
}
function getJokeList($file){
    $arr=array();
    $jokeList = array();
    $fp=fopen($file,'r');

    while ($arr[] = fgets($fp)) {
   
    }
    fclose($fp);
    
    foreach ($arr as $key => $value) {
      if(strlen($value) >2){
        $jokeList[] = $value;
      }
      //echo $key.'=>'.$value.',count:'.strlen($value).'<br>';
    }
    return $jokeList;
}
function createVoiceJoke($content){
    file_put_contents("joke_voice.txt",$content."\r\n\r\n",FILE_APPEND);
}
function createTextJoke($content){
    file_put_contents("joke_text.txt",$content."\r\n\r\n",FILE_APPEND);
}
function createImageJoke($content){
    file_put_contents("joke_image.txt",$content."\r\n\r\n",FILE_APPEND);
}
function createTextMsg($postObj){
    $textTpl = textTpl;
    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $content = trim($postObj->Content);
    $time = time();

    if(!empty( $content )){

        createTextJoke($content);
        $contentStr = 'ok';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
        echo $resultStr;
    }
}
function createImageMsg($postObj){
    $textTpl = textTpl;

    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $picUrl = $postObj->PicUrl;
    $mediaId = $postObj->MediaId;
    $msgId = $postObj->MsgId;
    $time = time();

    $arr = array();
    
    $arr[] = '"fromUsername":"'.$fromUsername.'"';
    $arr[] = '"picUrl":"'.$picUrl.'"';
    $arr[] = '"mediaId":"'.$mediaId.'"';
    $arr[] = '"msgId":"'.$msgId.'"';
    $arr[] = '"time":"'.$time.'"';

    $content = '{'.implode(',',$arr).'}';
    createImageJoke($content);
    $contentStr = 'ok';
    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
    echo $resultStr;
    
}
function createVoiceMsg($postObj){
    global $voiceTpl ;

    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $mediaId = $postObj->MediaId;
    $msgId = $postObj->MsgId;
    $time = time();

    $arr = array();
    
    $arr[] = '"fromUsername":"'.$fromUsername.'"';
    $arr[] = '"mediaId":"'.$mediaId.'"';
    $arr[] = '"msgId":"'.$msgId.'"';
    $arr[] = '"time":"'.$time.'"';
    $arr[] = '"msgType":"voice"';

    $content = '{'.implode(',',$arr).'}';
    createVoiceJoke($content);
    $contentStr = 'ok';
    $resultStr = sprintf($voiceTpl, $fromUsername, $toUsername, $time, 'voice', $mediaId);
    echo $resultStr;
    
}

function handleMsg($postObj){
    $textTpl = textTpl;
    $msgType = $postObj->$MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $content = trim($postObj->Content);
    $time = time();

    $contentStr = '你妹啊';
    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
    echo $resultStr;
}
function handleTextMsg($postObj){
    global $imageTpl ;
    global $voiceTpl;
    global $randomArray;
    global $helloArray;
    global $helloAnswerArray;
    global $fuckArray;
    global $fuckAnswerArray;
    global $pigAnswerArray;

    $textTpl = textTpl;
    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $content = trim($postObj->Content);
    $time = time();

    
    if(!empty( $content ))
    {
        
        logger('MsgType:'.$msgType);
        logger('ToUserName:'.$toUsername);
        logger('FromUserName:'.$fromUsername);
        logger('CreateTime:'.$time);
        logger('$Content:'.$content);


        /*if(check_str( $content ) == 1 ){
            $contentStr = "哎，会说英文，不错呦，看你这么努力，再来句日文啊~~";
        }else{
            $contentStr = "您好! 我是龙胖子。我不明白'".$content."'是什么意思，请翻译成英文，谢谢~";
            
        }*/
        if($content == '/:,@P'){
            $contentStr = '笑什么笑啊/:,@P';
        }
        else if(strlen($content) > 10 and substr($content,0,7) == '建议@') {
            $advice = substr($content,7);
            addAdvice($fromUsername,$advice);
            $contentStr ='您的建议小胖已签收，请您坐等好评';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            echo $resultStr;
        }
        else if ($content == '猪' or $content == 'pig') {
            $contentStr = getRandomMsg($pigAnswerArray);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            echo $resultStr;
        }
        else if (in_array($content,$helloArray)) {
            $contentStr = getRandomMsg($helloAnswerArray);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            echo $resultStr;
        }
        else if (in_array($content,$fuckArray)) {
            $contentStr = getRandomMsg($fuckAnswerArray);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            echo $resultStr;
        }
        else if($content == '啊'){
            $contentStr = '<a href="http://www.zjtcmiec.net/mobile.html">mobile.html</a>';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            echo $resultStr;
        }
        else if($content == '妹子'){
            $mediaId = 'cSsaazxBcR1lnyB_9yzaFCiYAvsMJDhndGkaY8O92FrEZvlM-8BYp1v3B-u1vWHv';
            $resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, 'image', $mediaId);
            echo $resultStr;
        }else if($content == '语音'){
            $joke = getVoiceJoke();
            $mediaId = $joke['mediaId'];
           
            $resultStr = sprintf($voiceTpl, $fromUsername, $toUsername, $time, 'voice', $mediaId);
            echo $resultStr;
        }
        else if($content == '图片'){
            $joke = getImageJoke();
            $mediaId = $joke['mediaId'];
           
            $resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, 'image', $mediaId);
            echo $resultStr;
        }
        else if($content == '糗事'){
            $contentStr = getQiushi();
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            
            echo $resultStr;
        }
        else if($content == '笑话'){
            $rand = mt_rand(1,3);
            if($rand==11){
                $joke = getVoiceJoke();
                $mediaId = $joke['mediaId'];
                $resultStr = sprintf($voiceTpl, $fromUsername, $toUsername, $time, 'voice', $mediaId);
            }else if($rand == 2){
                $joke = getImageJoke();
                $mediaId = $joke['mediaId'];
                $resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, 'image', $mediaId);
            }else{
                $contentStr = getTextJoke();

                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
                
            }
            
            
            echo $resultStr;
        }
        else{
            $contentStr = getRandomMsg($randomArray).answer;

        }
        
            
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
        
        echo $resultStr;
    }
}
function handleImageMsg($postObj){
    global $imageTpl ;
    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $picUrl = $postObj->PicUrl;
    $mediaId = $postObj->MediaId;
    $msgId = $postObj->MsgId;
    $time = time();

    logger('MsgType:'.$msgType);
    logger('picUrl:'.$picUrl);
    logger('mediaId:'.$mediaId);
    logger('msgId:'.$msgId);
    logger('ToUserName:'.$toUsername);
    logger('FromUserName:'.$fromUsername);
    logger('CreateTime:'.$time);

    $contentStr = '这是在给我发福利吗？';
    $resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, 'image', $mediaId);
        
    echo $resultStr;
}
function handleVoiceMsg($postObj){
    global $voiceTpl ;
    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $format = $postObj->Format;
    $recognition = $postObj->Recognition;
    $mediaId = $postObj->MediaId;
    $msgId = $postObj->MsgId;
    $time = time();

    logger('MsgType:'.$msgType);
    logger('format:'.$format);
    logger('recognition:'.$recognition);
    logger('mediaId:'.$mediaId);
    logger('msgId:'.$msgId);
    logger('ToUserName:'.$toUsername);
    logger('FromUserName:'.$fromUsername);
    logger('CreateTime:'.$time);

    //$contentStr = '这是在给我发福利吗？';
    $resultStr = sprintf($voiceTpl, $fromUsername, $toUsername, $time, 'voice', $mediaId);
        
    echo $resultStr;
}
function handleVideoMsg($postObj){

}
function handleLocationMsg($postObj){

}
function handleLinkMsg($postObj){

}
function handleEventMsg($postObj){
    $textTpl = textTpl;
    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $event = $postObj->Event;
    $time = time();

    logger('MsgType:'.$msgType);
    logger('Event:'.$event);
    logger('ToUserName:'.$toUsername);
    logger('FromUserName:'.$fromUsername);
    logger('CreateTime:'.$time);

    if($event == "subscribe"){
        $contentStr = "土豪，我们做朋友吧~~~~".answer;
    }
    if($event == "unsubscribe"){
        $contentStr = "爱我别走，如果你说，你不爱我~~~也请关注我";
    }
    
    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
    
    echo $resultStr;
    
}

function traceHttp(){
    logger('REMOTE :'.$_SERVER['REMOTE_ADDR']);
    logger('QUERY_STRING :'.$_SERVER['QUERY_STRING']);

}
function logger($content){
    file_put_contents("log.html",date('Y-m-d H:i:s ').$content.'<br>',FILE_APPEND);
}
function addAdvice($user,$advice){
    $contentStr = '';
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'user:'.$user."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'advice:'.$advice."\r\n\r\n";
    file_put_contents("advice.txt",$contentStr,FILE_APPEND);
}
function user_logger($postObj){
    $msgType = $postObj->MsgType;
    $fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
    $content = $postObj->Content;
    $mediaId = $postObj->MediaId;
    $msgId = $postObj->MsgId;
    $picUrl = $postObj->PicUrl;
    $time = time();
    $contentStr = '';
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'msgType:'.$msgType."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'fromUsername:'.$fromUsername."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'toUsername:'.$toUsername."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'content:'.$content."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'mediaId:'.$mediaId."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'msgId:'.$msgId."\r\n";
    $contentStr = $contentStr.date("Y-m-d G:i:s ").'picUrl:'.$picUrl."\r\n\r\n";
   
    file_put_contents("./dialogue/".$fromUsername.".txt",$contentStr,FILE_APPEND);
}
/* 
*function：检测字符串是否由纯英文，纯中文，中英文混合组成 
*param string 
*return 1:纯英文;2:纯中文;3:中英文混合 
*/  
function check_str($str){  
    if(trim($str) == ''){  
        return '';  
    }  
    $m = mb_strlen($str,'utf-8');  
    $s = strlen($str);  
    if($s == $m){  
        return 1;  
    }  
    if($s%$m==0&&$s%3==0){  
        return 2;  
    }  
    return 3;  
}
function getRandomMsg($array){
   
    $len=count($array);
    $index = mt_rand(0,$len-1);
    return $array[$index];
}
?>