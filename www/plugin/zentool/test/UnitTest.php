<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-30
 * Time: 17:33
 */

class UnitTest
{
    public static $DEBUGGING = false;

    public static function joinFromOrderTest()
    {

//        $od_id = '18113001403900';
//        $gs_id = '3';
//
//        Partner::joinFromOrder(Order::factory(get_order($od_id), get_goods($gs_id)));


        $od_id = '18113001401410';
        $gs_id = '3';

        Partner::joinFromOrder(Order::factory(get_order($od_id), get_goods($gs_id)));

    }

    public static function matchUpLineUpTest()
    {
        echo "<ol>";
        echo "<li>Matching Table 초기화</li>";
        sql_query("truncate table shop_minishop_matching");
        echo "<li>Matching Pay 초기화</li>";
        sql_query("delete from shop_minishop_pay where pp_rel_table = 'anew_match'");
        echo "<li>Matching Line Point 초기화</li>";
        sql_query("truncate table shop_minishop_line_point");
        sql_query("update shop_member set line_cnt = 0, total_line_cnt = 0");

// payment 초기화
// point 초기화
        echo "<li>회원 조회</li>";
        $sql = "select a.index_no, a.pt_id, a.id mb_id, a.grade anew_grade, b.gb_anew_price anew_price
 from shop_member a, shop_member_grade b where a.grade = b.gb_no and a.pt_id <> ''
 order by a.index_no";

        $result = sql_query($sql);
        $members = array();

        ob_flush();

        $i = 0;
        while ($member = sql_fetch_array($result)) {
            $member = (object)$member;
            array_push($members, $member);

            echo "<li class='start'>start {$member->mb_id}</li>";
            ob_flush();

            Match::matchUp($member->pt_id, $member->mb_id, $member->anew_price, $member->anew_grade);

            echo "<li class='end'>end {$member->mb_id}</li>";
            ob_flush();


            if (++$i > 100) break;

        }


        echo "</ol>";
    }

    public static function mathUpTest()
    {
        self::$DEBUGGING = true;
        $pt_id = '01051414177';
        $mb_id = '01012341236';
        Match::matchUp($pt_id, $mb_id, 400000, 4);
        self::$DEBUGGING = false;
    }

    public static function linePoint()
    {
        self::$DEBUGGING = true;
        $line = new stdClass();
        $line->line_id = '01035891768≫01035891770∥01035891771§01035891769≫01035891772∥01035891773';
        echo Match::linePoint($line);
        self::$DEBUGGING = false;
    }

    private static function getChildList(array $arr_id, $up_nm)
    {
        return "select * from (
                  select a.reg_time, a.anew_date, a.index_no, a.id, a.grade, a.pt_id org_pt_id, a.up_id org_up_id, IFNULL(b.id, '') {$up_nm}
                  from shop_member a left join shop_member b on a.{$up_nm} = b.id
                ) a 
                where {$up_nm} in ('".join("','", $arr_id)."')";
    }

    public static function resetHierarchy()
    {
        self::$DEBUGGING = true;

        self::resetHierarchyPt();
        self::resetHierarchyUp();

        self::$DEBUGGING = false;
    }

    public static function resetHierarchyPt()
    {
        self::$DEBUGGING = true;

        sql_query("truncate table shop_minishop_hierarchy");
        sql_query("truncate table shop_minishop_hierarchy_pt");

        $arr_pt_id = [];
        $depth = 1;
        while( $result = sql_query(self::getChildList($arr_pt_id, 'pt_id')) ) {
            $arr_pt_id = [];

            while($row = sql_fetch_array($result)){
                Partner::insertHierarchy($row['id'], $row['pt_id']);
                Partner::insertHierarchyPt($row['id'], $row['pt_id']);
                array_push($arr_pt_id, $row['id']);
            }

            if( count($arr_pt_id) == 0 ) break;
            echo $depth++.":('".join("','", $arr_pt_id)."')".PHP_EOL;
        }

        self::$DEBUGGING = false;
    }

    public static function resetHierarchyUp()
    {
        self::$DEBUGGING = true;
        sql_query("truncate table shop_minishop_hierarchy_up");
        $arr_up_id = [];
        $depth = 1;
        while( $result = sql_query(self::getChildList($arr_up_id, 'up_id')) ) {
            $arr_up_id = [];

            while($row = sql_fetch_array($result)){
                Partner::insertHierarchyUp($row['id'], $row['up_id']);
                array_push($arr_up_id, $row['id']);
            }

            if( count($arr_up_id) == 0 ) break;
            echo $depth++.":('".join("','", $arr_up_id)."')".PHP_EOL;
        }
        self::$DEBUGGING = false;
    }

    public static function initAnewPay()
    {
        self::$DEBUGGING = true;
        sql_query("truncate table shop_minishop_matching");
        sql_query("truncate table shop_minishop_pay");
        sql_query("truncate table shop_minishop_shopping_pay");
        sql_query("truncate table shop_minishop_line_point");
        sql_query("truncate table shop_minishop_hierarchy");
        sql_query("truncate table shop_minishop_bonus_history");
        sql_query("update shop_member set total_line_cnt = 0, match_cnt = 0, line_point = 0, sp_point = 0, pay = 0, job_title = '', job_no = '' where 1");

        $sql = "select a.reg_time, a.anew_date, a.index_no, a.id, a.grade, point, sp_point, line_point, pay, match_cnt, total_line_cnt
                  from shop_member a, shop_minishop b
                 where a.id = b.mb_id
                   and a.use_app = 1
                   and IFNULL(a.anew_date, '') <> ''
                   and a.grade between 2 and 6
                 order by a.anew_date asc";

        $result = sql_query($sql);

        while($row = sql_fetch_array($result)){
            insert_anew_pay($row['id']);
        }

        self::$DEBUGGING = false;
    }

    public static function replaceEncToDecInMemo()
    {
        $rslt = sql_query("select id from shop_member");
        while($mb = sql_fetch_array($rslt)) :
            $enc_id = $mb['id'];
            $plain_id = $enc_id;
            sql_query("UPDATE shop_point SET po_content = replace(po_content, '{$enc_id}', '{$plain_id}') WHERE po_content like '%{$enc_id}%'");
        endwhile;
        foreach([ 'bVM1bmlublo0YTR1bWtDNGdJaHM3Zz09', 'Zkg2dTh3WkxHN0F5NURDTzkwcndsQT09', 'YUtjWUp4TGlXRjdnTzVScHh1ZXkzZz09', 'MjczS0F6RFFTYUNidnZHNVpUcVl5Zz09', 'MmtjK1VBQ2NqOUluNXc1OW1WTjhDdz09', 'SEVGVTRqWUJaTlBsZ1ZmZWRWSS9QQT09', 'KzZ4YytITUxhS0xQUEFHS2dUUlZjZz09', 'MWdHWWYrVEVveW1rSW9CbnBSMFdVZz09' ] as $enc_id) :
        $plain_id = $enc_id;

        sql_query("UPDATE shop_leave_log SET memo = replace(memo, '{$enc_id}', '{$plain_id}') WHERE memo like '%{$enc_id}%'");
        sql_query("UPDATE shop_point SET po_content = replace(po_content, '{$enc_id}', '{$plain_id}') WHERE po_content like '%{$enc_id}%'");

        endforeach;
    }

    public static function encodeSellerCode()
    {

        $result = sql_query("select seller_code from shop_seller where seller_code like 'AP%'" );
        while($seller = sql_fetch_array($result)) {
            $seller_code = $seller['seller_code'];
            $encrypted_seller_code = $seller_code;
            sql_query("update shop_seller set seller_code = '{$encrypted_seller_code}' where seller_code = '{$seller_code}'");
        }

        $result = sql_query("select mb_id seller_code from shop_goods where mb_id like 'AP%'");
        while($seller = sql_fetch_array($result)) {
            $seller_code = $seller['seller_code'];
            $encrypted_seller_code = $seller_code;
            sql_query("update shop_goods set mb_id = '{$encrypted_seller_code}' where mb_id = '{$seller_code}'");
        }

        $result = sql_query("select mb_id seller_code from shop_goods where mb_id like 'admin%'");
        while($seller = sql_fetch_array($result)) {
            $seller_code = $seller['seller_code'];
            $encrypted_seller_code = $seller_code;
            sql_query("update shop_goods set mb_id = '{$encrypted_seller_code}' where mb_id = '{$seller_code}'");
        }

    }

    public static function transSpPointToPoint(){

        $rslt = sql_query("select * from shop_minishop_shopping_pay order by sp_datetime asc");
        while($sp = sql_fetch_array($rslt)) {
            $sp_rel_action = $sp['sp_rel_table'] == 'anew' ? '추천쇼핑포인트' : '가입쇼핑포인트';
            insert_point($sp['mb_id'], $sp['sp_price'], $sp['sp_content'], $sp['sp_rel_table'], $sp['sp_rel_id'], $sp_rel_action);
        }

    }

    public static function debugging($var)
    {
        if( self::$DEBUGGING ) {
            var_dump($var);
        }
    }

    public static function changeId()
    {
        try {
//            Member::ch_mb_id('a0017', '01032490905');
            Member::ch_mb_id('0000', '01026996966');
        } catch (ExistsMemberException $e) {
            var_dump($e);
        } catch (NotExistsMemberException $e) {
            var_dump($e);
        } catch (SameSourceTargetException $e) {
            var_dump($e);
        }
    }

    public static function rollbackAnewPoint()
    {
        $rslt = sql_query("select a.mb_id, a.anew_grade, a.from_biz_type, a.receipt_price, b.po_point, b.po_content
       , b.po_rel_table, b.po_rel_id, b.po_rel_action
       , case when b.po_point = 180000 then 0 when b.po_point = 360000 then 30000 end po_point_to
  from shop_minishop a, shop_point b
 where a.mb_id = b.mb_id and a.receipt_price in ( 0 , 33000) and b.po_point in ( 180000, 360000 )
   and po_content like '가맹가입 지급 쇼핑포인트'");

        while( $row = sql_fetch_array($rslt) ) {
            delete_point($row['mb_id'], $row['po_rel_table'], $row['po_rel_id'], $row['po_rel_action']);
            insert_point($row['mb_id'], $row['po_point_to'], $row['po_content'], $row['po_rel_table'], $row['po_rel_id'], $row['po_rel_action']);
        }
    }

    public static function insertPointFb33()
    {
        $result = sql_query("select mb_id from shop_minishop where receipt_price = 33000 and state = 1");
        while($row = sql_fetch_array($result)){
            insert_point($row['mb_id'], 30000, '가맹가입 지급 쇼핑포인트', 'member', $row['mb_id'], '가입쇼핑포인트');
        }
    }

    public static function updateHierarchyUp()
    {
        self::$DEBUGGING = true;
        $mb_id = 'L0tnSHVEdmF1YlFsNmUwU2o2dkZaZz09';
        $up_id = 'MHVteHNCVHNRblBNQXM1WUpHRCs4QT09';
        $mb_id = 'MkZtaWQzNGNleTV6YUlDMWNPazNLZz09';
        Partner::updateHierarchyUp($mb_id, $up_id);
        self::$DEBUGGING = false;
    }

    public static function updateGrade()
    {
        self::$DEBUGGING = true;
        $rslt= sql_query("SELECT a.mb_id, a.goods_price, a.buy_minishop_grade trg_grade, a.buy_minishop_grade + 1 src_grade FROM shop_order a, shop_member b WHERE a.mb_id= b.id and a.buy_minishop_type = 'upgrade' and a.dan in (5, 8) ");//and a.buy_minishop_grade <> b.grade");

        while($row = sql_fetch_array($rslt)){
            Partner::do_upgrade($row['mb_id'], 0, $row['src_grade'], $row['trg_grade']);
        }

        self::$DEBUGGING = false;

    }

    public static function migUpgradePay()
    {
        self::$DEBUGGING = true;

        $rslt = sql_query("select mb_id, pp_content, pp_pay, pp_rel_id, pp_rel_action from shop_minishop_pay where pp_rel_table = 'anew' and pp_pay = 59400");
        while($row = sql_fetch_array($rslt)){
            insert_pay($row['mb_id'], - 5400, $row['pp_content'].' 조정', 'anew', $row['pp_rel_id'], $row['pp_rel_action'].'_mng', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'],0);
        }
        self::$DEBUGGING = false;
    }

    public static function migAnewUpPoint()
    {
        self::$DEBUGGING = true;

        $rslt = sql_query("select a.*, b.up_id from shop_point a, shop_member b where a.po_rel_id = b.id and a.po_rel_action = '추천쇼핑포인트' and a.po_rel_table = 'anew' and a.po_rel_id = a.mb_id");

        while($row = sql_fetch_array($rslt)){

            // 잘못 된 지급 회수
            delete_point($row['mb_id'], $row['po_rel_table'], $row['po_rel_id'], $row['po_rel_action']);
            // 재 지급
            insert_point($row['up_id'], $row['po_point'], str_replace('추천쇼쇼핑포인트', '추천쇼핑포인트', $row['po_content']), $row['po_rel_table'], $row['po_rel_id'], $row['po_rel_action']);

        }

        self::$DEBUGGING = false;
    }

    public static function migShareMonthly()
    {
        self::$DEBUGGING = true;
        foreach(['2019-02-28', '2019-03-31'] as $exec_date ) {
            Partner::shareMonthly($exec_date);
        }
        self::$DEBUGGING = false;
    }

    public static function encrypteIdToPlainId()
    {
        $result = sql_query("SELECT id, encrypted_id, plain_id from shop_member");
        while($r = sql_fetch_array($result)){
            $encrypted_id = $r['encrypted_id'];
            $plain_id     = Mcrypt::zen_decrypt($r['encrypted_id']);
            sql_query("UPDATE shop_member SET id = '{$plain_id}', plain_id = '{$plain_id}' where encrypted_id = '{$encrypted_id}'");
        }
        $result = sql_query("SELECT seller_code, encrypted_seller_code, plain_seller_code from shop_seller");
        while($r = sql_fetch_array($result)){
            $encrypted_seller_code = $r['encrypted_seller_code'];
            $plain_seller_code     = Mcrypt::zen_decrypt($r['encrypted_seller_code']);
            sql_query("UPDATE shop_seller SET seller_code = '{$plain_seller_code}', plain_seller_code = '{$plain_seller_code}' where encrypted_seller_code = '{$encrypted_seller_code}'");
        }

        $result = sql_query("SELECT encrypted_id, plain_id FROm mig_enc_dec_id_list");
        while($r = sql_fetch_array($result)){
            $encrypted_id = $r['encrypted_id'];
            $plain_id     = Mcrypt::zen_decrypt($encrypted_id);
            sql_query("UPDATE mig_enc_dec_id_list SET plain_id = '{$plain_id}' WHERE encrypted_id = '{$encrypted_id}'");
        }

    }

    public static function migPhoneChinguImages()
    {
        $prefix = 'http://phonechingu.com/';
        $upl_dir = MS_DATA_PATH."/goods";
        $upl_edt = MS_DATA_PATH."/editor/".date('Ym');

        $url_edt = MS_DATA_URL."/editor/".date('Ym');

        $upl = new upload_files($upl_dir);

        $downpath = $upl_dir.'/tmp/';

        @mkdir($downpath, MS_DIR_PERMISSION);
        @chmod($downpath, MS_DIR_PERMISSION);
        @mkdir($upl_edt, MS_DIR_PERMISSION);
        @chmod($upl_edt, MS_DIR_PERMISSION);

//        $sql   = "select index_no, simg_type, simg1, simg2, simg3, simg4, simg5, memo from shop_goods where memo like '%phonechingu%jpg%' order by index_no desc";// limit 100";
        $sql   = "select index_no, simg_type, simg1, simg2, simg3, simg4, simg5, memo from shop_goods where simg1 like '%phonechingu.com/%jpg' order by index_no desc";// limit 100";
        $rslt  = sql_query($sql);
        $imax  = 5;
        while($rset = sql_fetch_array($rslt)){

            preg_match_all('<img.*?src="(http:\/\/(?:www\.)?phonechingu\.com\/.*?)".*?>', $rset['memo'], $matches, PREG_SET_ORDER );
            $uploaded = false;

            if( $matches ) {
                foreach($matches as $i=>$match){
                    $filename = basename($match[1]);
                    $file = \App\service\ImageHostingService::downloadFromRemote($match[1], $upl_edt.DIRECTORY_SEPARATOR.$filename);
                    if($file['response_code'] != '404') {
                        $rset['memo'] = str_replace($match[1], $url_edt.'/'.$file['name'], $rset['memo']);
                    }

                }
                $uploaded = true;
            }

            for($i = 1; $i <= $imax; $i++) {
                $fieldName = 'simg'.$i;
                if( strpos($rset[$fieldName], 'http://') !== false ){
                    if($prefix = substr($rset[$fieldName], 0, strlen($prefix))) {
                        $tempnam = tempnam($downpath, 'goods-');
                        $file = \App\service\ImageHostingService::downloadFromRemote($rset[$fieldName], $tempnam);
                        if( $file['response_code'] != '404') {
                            $rset[$fieldName] = $upl->upload($file);
                            $rset['simg_type']= 0;
                            $uploaded = true;
                        }
                    }
                }
            }

//            var_dump(compact('uploaded', 'rset'));

            if( $uploaded ) update('shop_goods', $rset, ' where index_no = '.$rset['index_no']);
        }
    }

    public static function doOrderStatus()
    {
        $od = get_order('19110506590631');;
        // 상품정보
        $gs = unserialize($od['od_goods']);
        Order::insertSalePay($od, $gs);
    }

    public static function doAnewPoint()
    {
        $rslt = sql_query("SELECT * FROM shop_member WHERE grade in (5,6)");
        while($mb = sql_fetch_array($rslt)){


        }
    }

    public static function migPartnerPoint(){
        global $config;
        
        $sql = "SELECT b.id, b.pt_id FROM shop_member a, shop_member b where a.id = b.pt_id and a.id != 'admin'";
        $rslt= sql_query($sql);
        while($mb = sql_fetch_array($rslt)){
            insert_point($mb['pt_id'], $config['minishop_point'], $mb['id'].'의 추천인', '@member', $mb['id'], $mb['id'].' 추천');
        }
    }
}
