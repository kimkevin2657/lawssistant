<?php


namespace App\service;

class MessageService
{
    static $file_path   = TB_PATH . "/data/resource/languages/translate.php";
    static $file_path_js= TB_PATH . "/data/resource/languages/translate.js";
    static $replace_src = ['=', '+', '/'];
    static $replace_trg = ['ê¹€', 'ë�™', 'ì£¼'];
    static $prefix_key  = "K";
    static $languages   = [];
    static $lang        = 'ko';

    public static function loadLanguages(){
        return static::$languages = include_once(self::$file_path);
    }

    public static function getMessage()
    {
        global $languages, $TB_LANG;
        static::$lang    = $TB_LANG;
        $args            = func_get_args();
        $msg             = $args[0];
        $enc_key         = self::encodeKey($msg);
        if(isset($languages[$enc_key])) {
            if( isset($languages[$enc_key][static::$lang])){
                $msg = $languages[$enc_key][static::$lang];
            } else if( isset($languages[$enc_key]['def'])){
                $msg = $languages[$enc_key]['def'];
                static::maybeInsert($msg);
            } else {
                static::maybeInsert($msg);
            }
        } else {
            static::maybeInsert($msg);
        }

        $argc = count($args);
        if( $argc > 1 ) {
            for( $i = 0; $i < $argc - 1; $i++ ) {
                $msg = str_replace('{'.$i.'}', $args[$i+1], $msg);
            }
        }

        return $msg;
    }

    public static function encodeKey($msg){
        return static::$prefix_key.str_replace(static::$replace_src, static::$replace_trg, base64_encode($msg));
    }

    public static function decodeKey($msg){
        return base64_decode(substr(str_replace(static::$replace_trg, static::$replace_src, $msg), strlen(static::$prefix_key), strlen($msg)));
    }

    public static function maybeInsert($msg){
        global $tb;


        if( $tb && $tb['connect_db'] ) {

//            $msg_key      = self::encodeKey($msg);
//            $safe_msg_key = mysqli_real_escape_string($tb['connect_db'], $msg);
//            $safe_msg_def = mysqli_real_escape_string($tb['connect_db'], $msg);
//            $safe_msg_ko  = mysqli_real_escape_string($tb['connect_db'], $msg);
//            $safe_msg_en  = mysqli_real_escape_string($tb['connect_db'], YandexTranslateApiService::translate($msg, 'ko','en'));
//            $safe_msg_zh  = mysqli_real_escape_string($tb['connect_db'], YandexTranslateApiService::translate($msg, 'ko','zh'));
//            $safe_msg_vi  = mysqli_real_escape_string($tb['connect_db'], YandexTranslateApiService::translate($msg, 'ko','vi'));
//            $safe_msg_ja  = mysqli_real_escape_string($tb['connect_db'], YandexTranslateApiService::translate($msg, 'ko','ja'));
//
//            if( ! sql_fetch("select * from shop_messages where msg_key = '{$safe_msg_key}'") ) {
//                insert('shop_messages', ['msg_key'=>$msg_key, 'msg_def'=>$safe_msg_def, 'msg_ko'=>$safe_msg_ko, 'msg_en'=>$safe_msg_en, 'msg_zh'=>$safe_msg_zh, 'msg_vi'=>$safe_msg_vi, 'msg_ja'=>$safe_msg_ja, 'msg_remark'=>'']);
//            }

//            static::generateResource();

        } else {
            logger()->warning($msg);
        }

    }

    public static function generateResource()
    {
        global $tb;
        $rslt = sql_query("SELECT DISTINCT msg_key, msg_def, msg_ko, msg_en, msg_zh, msg_vi, msg_ja, msg_remark FROM shop_messages");

        $translate = [];
        while($row = sql_fetch_array($rslt)){

            $msg_key = $row['msg_key'];//self::encodeKey($row['msg_key']);
            $translate[$msg_key]['def'] = $row['msg_def'];
            $translate[$msg_key]['ko'] = $row['msg_ko'];
            $translate[$msg_key]['en'] = $row['msg_en'];
            $translate[$msg_key]['zh'] = $row['msg_zh'];
            $translate[$msg_key]['vi'] = $row['msg_vi'];
            $translate[$msg_key]['ja'] = $row['msb_ja'];

            //update('shop_messages', ['msg_key'=>$msg_key], "where msg_key = '".mysqli_real_escape_string($tb['connect_db'], $row['msg_key'])."'");

        }

        umask(0);
        unlink(static::$file_path);
        file_put_contents(static::$file_path   , '<?php return '.var_export($translate, true).';');
        file_put_contents(static::$file_path_js, 'var translates = '.json_encode($translate).';');
        static::loadLanguages();
    }


}
