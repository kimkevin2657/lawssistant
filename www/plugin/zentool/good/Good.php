<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 20:06
 */

class Good
{
    public $UPL_DIR = "";
    public $upl     = "";

    public static function pointPayAllow($gs, $sell_price, $sell_qty)
    {
        if( self::isPointPayAllow($gs) ) {

            $point_pay_per   = (int) $gs['point_pay_per'];
            $point_pay_point = (int) $gs['point_pay_point'];

            if( is_numeric($point_pay_per) && $point_pay_per > 0 ) {
                return floor($sell_price * $point_pay_per / 1000 ) * 10;
            } else if( is_numeric($point_pay_point) && (int) $point_pay_point > 0 ) {
                return $point_pay_point * $sell_qty;
            }

        }

        return 0;
    }

    public static function usablePoint($gs)
    {
        return $gs['point_pay_per'] ? $gs['goods_price'] / 100 * $gs['point_pay_per'] : $gs['point_pay_point'];
    }

    public static function displayUsablePoint($gs)
    {
        $usablePoint = static::usablePoint($gs);
        return ( $usablePoint > 0 )  ? display_point($usablePoint, 'P('.ceil($gs['point_pay_per'] ? $gs['point_pay_per'] : $usablePoint/$gs['goods_price']*100).'%)') : display_point(0, 'P(0%)');
    }

    public static function isPointPayAllow($gs)
    {
        return $gs['point_pay_allow'] == 1  ? true : false;
        // 반값 아니"고
        // 가맹 상품 아니고
        // 쇼핑포인트 결제 허용 인 경우 에만 가능
        $cnt=sql_fetch('select count(1) cnt from shop_goods_type where gs_id = \''.$gs['index_no'].'\' and it_type3 = 1');
        return $gs['point_pay_allow'] == 1 && $gs['buy_minishop_grade'] == 0 && $cnt['cnt'] == 0;
    }

//    public function Good()
//    {
//        $this->__construct();
//    }

    public function __construct()
    {
        $this->upl = new upload_files($this->UPL_DIR);
        $this->upl->err_mode = 'memory';
        $this->UPL_DIR = MS_DATA_PATH."/goods";
    }

    public static function factory()
    {
        return new self;
    }

    public static function maybeDownloadRemoteImage(array $good)
    {
        $instance = self::factory();
        $imax     = 5;

        $downloaded = 0;

        for( $i = 1; $i <= $imax ; $i++ ) {

            $arr_key = "simg{$i}";


            if( ! $good[$arr_key] ) continue;


            if(preg_match("/^(http[s]?:\/\/).*/", $good[$arr_key]) == true) {




                $name = basename($good[$arr_key]);



                if(!preg_match("/\.(gif|jpg|png)$/i", $name)) {
                    // alert("이미지가 gif, jpg, png 파일이 아닙니다.");
                    continue;
                }

                $tmp_name = $instance->UPL_DIR .'/'.$good['gs_id'].'_'.MS_TIME_YHS.'_tmp_'.$name;

                $tmp_file = fopen($tmp_name, "w");

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);;
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                curl_setopt($ch, CURLOPT_URL, $good[$arr_key]);
                curl_setopt($ch, CURLOPT_FILE, $tmp_file);

                $save = curl_exec($ch);
                curl_close($ch);
                fclose($tmp_file);

                /*
                $imageString = file_get_contents($good[$arr_key]);
                $save = file_put_contents($tmp_name, $imageString);
                */

                /// die( var_dump( compact('arr_key', 'name', 'tmp_name', 'tmp_file', 'file') ) );
                if( $save ) {
                    $file = compact('name', 'tmp_name');
                    $good[$arr_key] = $instance->upl->upload($file);
                    $downloaded++;
                }

            }

        }

        if( $downloaded > 0 ){
            $gs_id = $good['gs_id'];
            unset($good['gs_id']);

            $prev = "";
            // 상세 보기에서는 2번쨰 이미지 부터 보여 줍니다.
            for( $i = 1; $i <= 2 ; $i++ ) {
                $curr = $good['simg'.$i];
                if( !empty($curr )) {
                    $prev = $curr;
                } else {
                    $curr = $prev;
                }
                $good['simg'.$i] = $curr;
            }
            update("shop_goods", $good, " where index_no = '{$gs_id}'");

        }

    }

    public static function dpLabel()
    {
        global $shop_id;

    }
}
