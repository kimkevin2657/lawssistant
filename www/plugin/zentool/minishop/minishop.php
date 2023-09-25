<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 20:08
 */

class minishop
{

    const LEVEL_MAX      = 6;
    const LEVEL_MIN      = 2;
    const USER_LEVEL_CNT = 3;
    const LEVEL_VIP      = 6;
    const LEVEL_GUEST    = 10;
    const SHARE_ROLLUP_FEE = 2000;
    const LEVEL_GOLDEN   = 4; // LEVEL_MAX + USER_LEVEL_CNT + 1
    public static $SHARE_ROLLUP_FEE = array(5 => array(5000, 10), 4=>array(10000,20));
    const UPPER_LEVEL_MATCHED = false;

    public $minishop;
    public $mb_id;

    public static function joinFromOrder(Order $order)
    {

        /**
         *
         * 1. 최초 가입 주문 건
         *    1.1 사용자 승인
         *    1.2 가맹점 승인
         *
         * 2. 재구매 주문건
         * 2.1 신규 회원 생성 및 파트너 생성
         * 2.1.1 주문 정보를 신규 회원의 주문으로 변경
         *
         * 2.2 다른 등급 최초 주문 건
         *
         * 2.3 동일 등급 재구매 주문 건
         *
         */

        /**
         * 가맹 상품 구매가 아니라면 후원수수료 지급하지 않습니다.
         */
        if( ! $order->buy_minishop_grade ) return;
        // 전체 주문 금액이

        // 최초 가입 주문 건 인지 확인
        // 주문 전체가 동일한 stat 인지 확인
        // 주문 전체 금액이 가맹 조금에 맞는지 확인

        $grade = self::grade($order->buy_minishop_grade);

        $row = sql_fetch("SELECT od_id, min(dan) min_dan, max(dan) max_dan, sum(goods_price) goods_price FROM shop_order WHERE od_id = '{$order->od_id}'");
        $order->goods_price = $row['goods_price'];


        if( $order->buy_minishop_type != Order::PARTNER_TYPE_UPGRADE && !( $row['min_dan'] == $row['max_dan'] && $row['goods_price'] >= $grade['gb_anew_price'] )) {
            return ;
        }

        $sql= "SELECT od_id FROM shop_member WHERE od_id = '{$order->od_id}' and id = '{$order->mb_id}'";

        $first_od_id = sql_fetch($sql);

        if( $first_od_id ) :

            $order->member->setActive();
            $minishop = minishop::factory($order->member->id);
            $minishop->setActive($order->od_id . ' 주문 확인 승인 처리 되었습니다.');

            $minishop_mb_id = $minishop->mb_id;

        else:
            if( $order->buy_minishop_type == Order::PARTNER_TYPE_UPGRADE ) {

                minishop::do_upgrade($order->member->id, $order->goods_price,  $order->member->grade, $order->buy_minishop_grade);

            } else {
                $new_mb_id = Mcrypt::decrypt($order->member->family_id . Member::SEPARATOR . $order->member->parent()->nextSequence());
                $newMb = Member::copyMember($order->member->parent()->id, $new_mb_id, array(
                    'memo' => $order->od_id . '주문 승인 가입 처리 되었습니다.'
                , 'grade' => $order->buy_minishop_grade
                , 'pt_id' => $order->pt_id
                , 'up_id' => $order->up_id
                , 'od_id' => $order->od_id
                ));
                $newMb->setActive();

                $newminishop = self::copyminishop($order->member->parent()->id, $newMb->id, array(
                  'memo' => $order->od_id . '주문 승인 가입 처리 되었습니다.'
                , 'anew_grade' => $order->buy_minishop_grade
                , 'receipt_price' => $order->goods_price
                ));
                $newminishop->setActive();

                $minishop_mb_id = $newminishop->mb_id;

                // 주문 정보를 신규 회원의 주문으로 변경
                $order->replaceMbId($new_mb_id);

                self::insert_hierarchy($new_mb_id);
            }

        endif;

        // 후원수수료 지급 및 매칭 수당 지급
        if( $order->buy_minishop_type != Order::PARTNER_TYPE_UPGRADE )
            insert_anew_pay($minishop_mb_id);

    }

    public static function insert_hierarchy( $mb_id)
    {
        // 신청자의 후원인을 담고
        $mb = get_member($mb_id, 'pt_id, up_id');

        self::insertHierarchy($mb_id, $mb['pt_id']);
        self::insertHierarchyPt($mb_id, $mb['pt_id']);
        self::insertHierarchyUp($mb_id, $mb['up_id']);

    }

    public static function do_upgrade($mb_id, $addition_price, $src_grade, $trg_grade)
    {
        $curr_grade = Member::get_grade($src_grade);
        $upgr_grade = Member::get_grade($trg_grade);
        $mb         = get_member($mb_id);
        $up_id      = $mb['up_id'];
        $pt_id      = $mb['pt_id'];
        if( empty($up_id)) $up_id = $pt_id;

        if( defined('MS_USE_UP_ID') && MS_USE_UP_ID ) {
            $to_id  = $up_id;
        } else {
            $to_id  = $pt_id;
        }

        if( $addition_price > 0 )
            sql_query("UPDATE shop_minishop SET receipt_price = receipt_price + {$addition_price}, anew_grade = {$trg_grade} WHERE mb_id = '{$mb_id}'");

        $pt = get_minishop($mb_id);

        // upgrade biz_type 변경
        if( $pt['from_biz_type'] == 'fb_33' )
            sql_query("UPDATE shop_minishop SET from_biz_type = 'fb_33_upgrade' WHERE mb_id = '{$mb_id}'");

        sql_query("UPDATE shop_member SET grade = {$trg_grade} WHERE id = '{$mb_id}'");

        $point = $upgr_grade['gb_pf_point'] - $curr_grade['gb_pf_point'];
        insert_point($mb_id, $point, '가맹승급 지급 쇼핑 포인트', 'member', $mb_id, '승급쇼핑포인트-'.$trg_grade);

        if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) {
            $sp_point = $upgr_grade['gb_pf_sp_point'] - $curr_grade['gb_pf_sp_point'];
            insert_shopping_pay($mb_id, $sp_point, "가맹승급 지급페이", "member", $mb_id, "승급쇼핑페이-".$trg_grade, $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);
        }

        // 가맹점수 UP
        $line_point = $upgr_grade['gb_line_point'] - $curr_grade['gb_line_point'];
        insert_line_point_rollup($upgr_grade['gb_line_point_rollup_level'], $pt_id, $line_point,$mb_id.'님 가맹점승급 축하', 'anew', $mb_id, '승급점수-'.$trg_grade, $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);

        $chk_ptype= get_minishop($to_id);
        $ptype = get_minishop_type($chk_ptype['from_biz_type']);
        if( ! ( $ptype && $ptype['use_minishop_pay'] == 0 )) {
            // 영업수수료
            $pay = ($upgr_grade['gb_anew_price'] - $curr_grade['gb_anew_price']) / 100 * $upgr_grade['gb_pf_per_up_pay'];
            $reg_price_without_tax = $pay / 11 * 10;
            insert_pay($to_id, $reg_price_without_tax, $mb_id.'님 승급 영업수수료', 'anew', $mb_id, 'upgrade-to-'.$trg_grade, $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'],0);
        }

        $gb_pf_up_point= $upgr_grade['gb_pf_up_point'] - $curr_grade['gb_pf_up_point'];
        if( $gb_pf_up_point > 0 ) {
            insert_point($to_id, $gb_pf_up_point, $mb_id.'님 가맹점가입 추천쇼핑포인트', 'anew', $mb_id,'추천쇼핑포인트');
        }

        $sp_up_point= $upgr_grade['gb_pf_up_sp_point'] - $curr_grade['gb_pf_up_sp_point'];
        if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY && $sp_up_point > 0 ) {
            insert_shopping_pay($to_id, $sp_up_point, $mb_id."님 가맹점가입 추천쇼핑페이", "anew", $mb_id, "추천쇼핑페이", $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);
        }

    }

    // 회원권한을 SELECT 형식으로 얻음
    public static function minishopLevelSelect($nameId, $selected='', $label0 = '해당없음', $attr = array(), $blank = '')
    {
        if( is_array($nameId) ) {
            $name = $nameId['name'];
            $id   = $nameId['id'];
        } else {
            $id = $nameId;
            $name=$nameId;
        }
        $str  = "<select id=\"{$id}\" name=\"{$name}\"";
        if( count($attr) > 0 ) :
            foreach( $attr as $key=>$val) :
                $str .= " ${key}=\"${val}\"";
            endforeach;
        endif;
        $str .= ">\n";

        if( strlen($blank) > 0 ) {
            $str .= "<option value='' data-anew-price='' data-anew-price-format=''>${blank}</option>\n";
        }

        $selected0 = $selected == '0' ? ' selected ' : '';
        $str .= "<option value='0' $selected0 data-anew-price='0' data-anew-price-format='0'>${label0}</option>\n";
        $sql= "select * from shop_member_grade where gb_name <> '' and gb_no > 1 and gb_anew_price > 0 order by gb_no desc ";
        $result = sql_query($sql);
        for($i=0; $row=sql_fetch_array($result); $i++)
        {
            $str .= "<option value='{$row['gb_no']}' ";
            $str .= " data-anew-price='{$row['gb_anew_price']}' ";
            $number_format_anew_price = number_format($row['gb_anew_price'], 0);
            $str .= " data-anew-price-format='{$number_format_anew_price}' ";
            if($row['gb_no'] == $selected)
                $str .= " selected";
            $str .= ">[{$row['gb_no']}] {$row['gb_name']}</option>\n";
        }

        $str .= "</select>\n";

        return $str;
    }

    public static function check($id, $grade)
    {
        $sql = "select id from shop_member where id = '{$id}'";
        if( self::UPPER_LEVEL_MATCHED ) $sql.= " and grade = '${grade}'";
        $rid = sql_fetch($sql);

        if( $rid ) {
            $rslt = JsonResult::SUCCESS;
            $data = $id;
        } else {
            $rslt = JsonResult::FAIL;
            $data = "";
            if( self::UPPER_LEVEL_MATCHED ) $data.= get_grade($grade).' 등급의 ';
            $data.= $id.' 사용자가 존재 하지 않습니다.';
        }

        return JsonResponse::response($rslt, $data);
    }


    public static function findTopId($id = '')
    {
        $grade = array();

        if( $id == '' ) {
           $id = Current::getLoggedId();
        }

        if( $id == '' ) return $grade;

        $families = Member::families($id);

        foreach($families as $family){
            if( !isset($grade[$family->grade]) ) {
                $grade[$family->grade] = $family->id;
            }
        }

        return $grade;
    }

    public static function factory($id)
    {
        $instance = new self;
        $instance->minishop = get_minishop($id);
        $instance->mb_id   = $id;
        return $instance;
    }

    public function setActive($memo = '')
    {
        sql_query("UPDATE shop_minishop SET state = 1, memo = '{$memo}' WHERE mb_id = '{$this->mb_id}'");
        // 등업 일시 업데이트
        sql_query("UPDATE shop_member SET anew_date = '".MS_TIME_YMD."' WHERE id = '{$this->mb_id}'");
    }

    public static function copyminishop($src_id, $new_id, $additional = array(), $important = array())
    {

        $new = get_minishop($src_id);

        // 부모가 fb_33 이면 child 로 변경 한다.
        if( $new['from_biz_type'] == 'fb_33' ) {
            $new['from_biz_type'] = 'fb_33_child';
        } else {
            $new['from_biz_type'] = '';
        }

        $additional = array_merge($additional, array(
            'reg_date' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'mb_id'       => $new_id
        ), $important);

        foreach( $additional as $name=>$value) {
            if( isset($new[$name])) {
                $new[$name] = $value;
            }
        }

        // 자동 증가키
        unset($new['index_no']);

        $qry = array();

        foreach($new as $name=>$val) {
            $qry[] = "{$name} = '{$val}'";
        }

        if( count($qry) > 0 ) {
            $sql = "INSERT INTO shop_minishop SET ";
            $sql.= join(", ", $qry);
            sql_query($sql);
        }
        return self::factory($new_id);
    }

    public static function impersonation($id, $surround = array('', ''))
    {
        ob_start();

        $families = Member::families($id);

        if( !is_array($surround ) ) $surround = array($surround);

        array_push($surround, '');

        if( count($families) > 1 ) :

            echo $surround[0];

        ?>
        <style>
            #impersionation { display: none;
                position: absolute;
                background: rgba(38, 167, 131, 0.7);
                border-radius: 0 0 5px 5px;
                top: 30px;
                margin-left: -230px;
                min-width: 170px;
                z-index: 999;
                max-height: 485px;
                padding-bottom: 5px;
                overflow: auto;}
            #impersionation dl {display: block;padding: 5px; color:#fff; cursor:pointer; font-weight: bold;}
            #impersionation dl.active{background: rgba(90, 70, 70, 0.5);}
            #impersionation dl:hover {background: rgba(1, 13, 90, 0.5);}
            #impersionation dl dt, #impersionation dl dd { display: inline-block; }
            #impersionation dl dt { width: 60px; }
            #impersionation dl dd { text-align: right; min-width:73px;}
            #impersionation dl dd.grade-name { float: left; }
            #impersionation dl dd.balance  { color:blue; float:right;}
            #impersionation dl dd.line-cnt { min-width:50px; color:red; float:right;}

            #impersionation.active { display: block;}
            body.mobile #impersionation {
                margin-top:15px;
                left:50%;
                margin-left:-50%;
                width:100%;
                background:rgba(38, 167, 131, .9);
                border-radius: 0;
                padding-bottom:0;
            }

            body.mobile #impersionation dl{ padding : 10px; }

            .btn_users.active { color:rgba(255, 0, 0, 0.5) !important; }

        </style>
        <a href="javascript:;" id="changeUser" class="btn_users fa fa-users"></a>
        <div id="impersionation">
        <?php foreach( $families as $m ) :
            $mb_id = $m->family_id != $m->id ? str_replace($m->family_id, '', $m->id) : $m->id;
            ?>
            <dl class="<?php echo $id == $m->id ? ' active ' : '' ?>" data-id="<?php echo $m->id; ?>">
                <dt class="grade-name">[<?php echo $m->gb_name; ?>]</dt>
                <dd class="mb-id"><?php echo $mb_id; ?></dd>
                <dd class="line-cnt"><?php echo display_point($m->line_point, '점'); ?></dd>
                <dd class="balance"><?php echo display_price($m->balance, '원'); ?></dd>
            </dl>
        <?php endforeach; ?>
        </div>
        <script>

            (function($){
                $(document).ready(function(){

                    $('#changeUser').on('click', function(){
                        $('#impersionation').toggleClass('active');
                        $(this).toggleClass('active');
                    });

                    $("#impersionation>dl").not('.active').on('click', function(){
                        var id = $(this).data('id');
                        // if( confirm(id + ' 가맹점주로 변경 하시겠습니까?'))
                        $.ajax({
                            url:'/plugin/zentool/minishop/ajax.impersonation.php',
                            data:{id:id},
                            type:'POST',
                            dataType:'json',
                            success: function(data){
                                if( data.result == 'success' )
                                document.location.reload();
                                else
                                    alert(data.data);
                            }
                        });

                    });

                });
            }(jQuery));
        </script>
            <?php

            echo $surround[1];

        endif;

        $content = ob_get_clean();

        return $content;
    }


    /**
     * @param $mb_grade
     * @return bool
     */
    public static function isUserGrade($mb_grade)
    {
        return self::LEVEL_MAX < $mb_grade &&  $mb_grade <= self::LEVEL_MAX + self::USER_LEVEL_CNT;
    }

    /**
     * @param $mb_grade
     * @return bool
     */
    public static function isminishopGrade($mb_grade)
    {
        return self::LEVEL_MIN <= $mb_grade && $mb_grade <= self::LEVEL_MAX;
    }

    /**
     * @param $grade
     * @return bool
     */
    public static function isVipminishopGrade($grade)
    {
        return self::LEVEL_VIP == $grade;
    }

    public static function grades()
    {
        $result = sql_query("SELECT * FROM shop_member_grade WHERE gb_name <> '' AND gb_no BETWEEN ".self::LEVEL_MIN." AND ".(self::LEVEL_MAX - 1) ." ORDER BY gb_no desc");
        $grades = array();
        while($row = sql_fetch_array($result)){
            array_push($grades, $row);
        }
        return $grades;
    }

    public static function grade($gb_no)
    {
        return sql_fetch("SELECT * FROM shop_member_grade WHERE gb_no = {$gb_no}");
    }

    /**
     * @param $mb_id
     * @param $exec_date
     */
    private static function rollUp($mb_id, $exec_date)
    {
        global $config;

        // 후원수수료를 사용을 하지 않는다면 리턴
        if(!$config['pf_anew_use']) return;

        // 후원수수료를 적용할 단계가 없다면 리턴
        if(!$config['pf_anew_benefit_dan']) return;
        // 신청자가 가맹점이 아니면 리턴
        if(!is_minishop($mb_id)) return;
        // 신청자 정보
        $pt = get_minishop($mb_id, 'mb_id, anew_grade, receipt_price');

        // 가맹점개설비가 없다면 리턴
        $reg_price = (int)$pt['receipt_price'];
        if($reg_price == 0) return;
        // 신청자의 추천인을 담고
        $mb = get_member($mb_id, 'pt_id, up_id, grade, match_cnt');
        // 신청자의 추천인
        $cur_pt_id = $mb['pt_id'];

        // 추천인이 가맹점이 아니면 리턴
        if(!is_minishop($cur_pt_id)) return;
        // 추천인은 본인이 될 수 없음
        if($mb_id == $cur_pt_id) return;

        // 상위 추천인 찾아가기
        $pt_id     = $cur_pt_id;
        $up_id     = $mb['up_id'];

        if(empty($up_id)) $up_id = $pt_id;

        if( !isset(self::$SHARE_ROLLUP_FEE[$mb['grade']]) ) return;

        $pt_pay = self::$SHARE_ROLLUP_FEE[$mb['grade']][0];//(int)trim($anew_benefit[$i]);
        $pt_dan = self::$SHARE_ROLLUP_FEE[$mb['grade']][1];

        for($i=0; $i<$pt_dan; $i++)
        {
            // 추천인이 없거나 최고관리자라면 중지
            if(!$pt_id || $pt_id == encrypted_admin())
                break;


            // 적용할 인센티브가 없다면 건너뜀
            $up = get_member($up_id, 'pt_id, up_id, grade, match_cnt');
            if( $up['match_cnt'] > 0 ) {
                // up_id 에게 후원 수수료
                insert_pay($up_id, $pt_pay, $mb_id.'님 '.$exec_date.' 정기 롤업', 'anew', $mb_id.'|'.$exec_date, '후원수수료');
            }

            // 단계별 상위 추천인을 담고 다시 배열로 돌린다
            $mb = get_member($pt_id, 'pt_id, up_id, grade, match_cnt');
            $pt_id = $mb['pt_id'];
            $up_id = $mb['up_id'];
            if( empty($up_id) ) $up_id = $pt_id;
        }
    }
    /**
     *
     */
    public static function maybeRollUp()
    {
        if(
                (defined('USE_ROLLUP') && USE_ROLLUP)
            // &&  (date('d') == '15' || date('d') == date('d', mktime(0,0,0, date('m')+1, 0, date('Y'))))
            && (date('w') == '5') // 매주 금요일
        ){
            $exec_date = date("Y-m-d");

            $check     = sql_fetch("select count(*) cnt from shop_minishop_rollup where rollup_date = '{$exec_date}'");

            if( $check['cnt'] > 0 ) return;

            $sql = "select a.* 
                   from shop_member a
                  inner join shop_minishop b
                     on a.id = b.mb_id 
                    and (a.term_date is null or a.term_date > now() )
                    and a.grade between 2 and 6
                    and a.match_cnt > 0
                    and DATE_FORMAT(a.anew_date,'%Y-%m-%d') 
                        BETWEEN DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), '%Y-%m-%d') 
                            AND DATE_FORMAT(DATE_ADD(now(), INTERVAL -1 DAY), '%Y-%m-%d')
                  order by a.index_no desc";

            $rslt=sql_query($sql);
            while($mb = sql_fetch_array($rslt)){
                self::rollUp($mb['id'], $exec_date);
            }

            sql_query("insert into shop_minishop_rollup(rollup_date, rollup_at) values('{$exec_date}', now())");
        }

    }


    /**
     * Line 점수 수익 분배
     */
    public static function maybeSharePoint()
    {
        global $config;

        /**
         * "1"=>"입금대기",
         * "2"=>"입금완료",
         * "3"=>"배송준비",
         * "4"=>"배송중",
         * "5"=>"배송완료",
         * "6"=>"취소",
         * "7"=>"반품",
         * "8"=>"교환",
         * "9"=>"환불"
         *
         * $config['pf_sale_flag']
         *
         * 0 결제액 - 배송비 - 쿠폰 - 쇼핑페이 - 쇼핑포인트결제액 = 순수결제액 에서 판매수수료를 배분
         * 1 판매가 - 공급가 - 쿠폰 - 쇼핑페이 - 쇼핑포인트결제액 = 마진 에서 판매수수료를 배분(마진이 없으면 적립되지 않음)
         * 2 판매가 - 공급가 = 마진 에서 판매수수료를 배분(쿠폰 및 쇼핑포인트 사용액은 무시하고 무조건 적립)
         */
        $sale_dans = static::getSaleDans();
        $sale = static::getSalesFetchQuery();
        $exec_date = MS_TIME_YMD;

        $sql_member_minishop_from_where = static::getSqlMemberminishopFromWhere();
        /**
         * 1. 매주 금요일 주간 쇼핑매출 공유(line_point)
         *
         * 2. 매월 ( 직급자 가맹매출 공유 )
         */

        $sql = "SELECT SUM(IFNULL(a.line_point,0)) line_point {$sql_member_minishop_from_where}";
        $total = sql_fetch($sql);

        $total_line_point = $total['line_point'];

        if( $total_line_point < 1 ) return;

        if(
            (date('w') == '5') // 매주 금요일
        ) {

            $job_name  = 'share_point';
            $check     = sql_fetch("select count(1) cnt from shop_minishop_crond_log where job_name = '{$job_name}' and exec_date = '{$exec_date}'");

            if( $check['cnt'] > 0 ) return;

            sql_query("insert into shop_minishop_crond_log(job_name, exec_date, exec_at) values('{$job_name}', '{$exec_date}', now())");

            // 매주 수익(20%) 공유
            $sql = "
            SELECT IFNULL(SUM({$sale}),0) * 0.2 amount 
              FROM shop_order 
             WHERE dan IN ( {$sale_dans} ) 
               AND DATE_FORMAT(od_time, '%Y-%m-%d') 
                   BETWEEN DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), '%Y-%m-%d')
                       AND DATE_FORMAT(DATE_ADD(now(), INTERVAL -1 DAY), '%Y-%m_%d')
            ";
            $amount = sql_fetch($sql);

            if( $amount ) {
                $sql = "SELECT a.id, {$amount['amount']} / {$total_line_point} * a.line_point pay {$sql_member_minishop_from_where}";
                $result = sql_query($sql);
                while($row = sql_fetch_array($result)){
                    insert_pay($row['id'], $row['pay'], '유지보너스 '.$exec_date, 'share_point', $exec_date, 'share', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'], 7);
                }

            }


        }

        if(
                '1' == date('d') // 매월 말일
            // '31' == date('d', mktime(0,0,0, date('m')+1, 0, date('Y')))//매월 말일
        ){
            // 지난말일( 1일 기준으로)
            $exec_date  = date('Y-m-d', mktime(0,0,0, date('m'), 0, date('Y')));//date("Y-m-d");
            static::shareMonthly($exec_date);
        }
    }

    /**
     * @param $exec_date
     */
    public static function shareMonthly($exec_date)
    {
        return false;
        $logger = logger(ZEN_DIR.'/logs/share_monthly.log');

        $sql_member_minishop_from_where = static::getSqlMemberminishopFromWhere();
        $sale_dans  = static::getSaleDans();
        $sale       = static::getSalesFetchQuery();
        $exec_month = substr($exec_date, 0, 7);

        $job_name  = 'share_month';

        $check     = sql_fetch("select count(1) cnt from shop_minishop_crond_log where job_name = '{$job_name}' and exec_date = '{$exec_date}'");

        if( $check['cnt'] > 0 ) return;

        sql_query("insert into shop_minishop_crond_log(job_name, exec_date, exec_at) values('{$job_name}', '{$exec_date}', now())");

        $sql = "SELECT IFNULL(SUM(b.receipt_price),0) amount {$sql_member_minishop_from_where} AND DATE_FORMAT(a.anew_date,'%Y-%m') = '{$exec_month}'";

        $anew = sql_fetch($sql);

        $sql = "SELECT IFNULL(SUM({$sale}),0) amount FROM shop_order WHERE dan IN ( {$sale_dans} ) AND DATE_FORMAT(od_time, '%Y-%m') = '{$exec_month}'";
        $sale = sql_fetch($sql);

        if( $anew['amount'] < 1 ) $anew['amount'] = 0;//return;
        if( $sale['amount'] < 1 ) $sale['amount'] = 0;//return;

        $anew_sum = $anew['amount'];
        $sale_sum = $sale['amount'];

        $sums = array('anew'=>$anew_sum, 'sale'=>$sale_sum);

        $qry = "select a.mb_id,
                       a.job_no,
                       a.total_line_point mb_total_line_point,
                       b.job_title,
                       b.benefit,
                       b.benefit_type
                from (
                         select a.mb_id, a.total_line_point, max(b.job_no) job_no
                         from (
                                  select a.mb_id, sum(a.lp_point) total_line_point
                                  from shop_minishop_line_point a
                                  where a.lp_datetime < date_add('{$exec_date}', interval 1 day)
                                  group by a.mb_id
                                  order by sum(a.lp_point) desc
                              ) a,
                              shop_minishop_bonus b
                         where a.total_line_point > b.up_point
                         group by a.mb_id
                     ) a,
                     shop_minishop_bonus_title b,
                     shop_member c,
                     shop_minishop d
                where a.job_no = b.job_no
                  and a.mb_id = c.id 
                  and c.id    = d.mb_id
                  and d.state = 1
                  and b.benefit > 0
                order by a.job_no desc";


        $job_qry = "select job_no, sum(mb_total_line_point) total_line_point from ( {$qry} ) a group by job_no";
        // echo $job_qry;
        $rslt = sql_query($job_qry);
        $total_line_points = [];
        while($job = sql_fetch_array($rslt)){
            $total_line_points[$job['job_no']] = $job['total_line_point'];
        }

        $rslt = sql_query($qry);
        $logger->debug('Execute Share Monthly:', ['exec_date'=>$exec_date,'total_line_points'=>$total_line_points, 'total_sum'=>$sums, 'rows'=>sql_num_rows($rslt)]);
        try{
            while($row = sql_fetch_array($rslt)) {
                $sum = $sums[$row['benefit_type']];
                $total_line_point = $total_line_points[$row['job_no']];
                $share_price = floor($sum * $row['benefit'] / 100 / $total_line_point * $row['mb_total_line_point']);
                $args = ['mb_id'=>$row['mb_id'], 'pp_pay'=>$share_price, 'content'=>"{$exec_date} 권리소득 {$sum} / {$total_line_point} * {$row['mb_total_line_point']} * {$row['benefit']} / 100", 'rel_table'=>'share_month', 'rel_id'=>"{$exec_date}-{$row['mb_id']}", 'rel_action'=>'share', 'referer'=>$_SERVER['HTTP_REFERER'], 'agent'=>$_SERVER['HTTP_USER_AGENT']];
                call_user_func_array('insert_pay', $args);
                //insert_pay($row['mb_id'], $share_price, "{$exec_date} 권리소득 {$sum} / {$total_line_point} * {$row['mb_total_line_point']} * {$row['benefit']} / 100", 'share_month', "{$exec_date}-{$row['mb_id']}", 'share', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);
                $logger->info('insert_pay', [$row, $args, $sums, $total_line_points]);
            }
        } catch ( Exception $ex ) {
            $logger->error($ex->getMessage(), []);
        }



    }

    public static function canPayCalcStatus($id)
    {
        global $config;

        $mb = get_member($id);

        $jumin_check = sql_fetch("select jumin6, jumin7 from shop_member where id = '{$id}'");
        if( $jumin_check && (empty($jumin_check['jumin6']) || empty($jumin_check['jumin7']))) return false;

        // 관리비 미납
        if($config['pf_expire_use'] && $mb['term_date'] < MS_TIME_YMD)  return false;

        return true;
    }


    public static function center($pc_no = '')
    {
        $where_addition = ( !empty($pc_no) ) ? " and a.pc_no = {$pc_no} " : "";
        $sql = "select a.pc_no, a.pc_nm, a.pc_cc_no, a.pc_state, b.id pc_cc_id, b.name pc_cc_nm  from shop_minishop_center a inner join shop_member b on a.pc_cc_no = b.index_no where a.pc_state = 1 {$where_addition} order by a.pc_nm asc";

        if( !empty($pc_no) ) {
            $center = sql_fetch($sql);
            return $center;
        }

        $rslt= sql_query($sql);
        $centers = array();
        while($center = sql_fetch_array($rslt)){
            array_push($centers, $center);
        }
        return $centers;
    }

    public static function selectBoxCenter($selectBoxName, $value, $blankValue, $blankLabel)
    {
        $centers = self::center();
        $options = array();
        if( $blankLabel ) {
            array_push($options, "<option value=\"{$blankValue}\">{$blankLabel}</option>");
        }
        foreach($centers as $center){
            $selected = $center['pc_no'] == $value ? ' selected="selected" ' : '';
            array_push( $options, "<option value=\"{$center['pc_no']}\" {$selected}>{$center['pc_nm']}({$center['pc_cc_nm']})</option>");
        }
        return "<select name='{$selectBoxName}' id='{$selectBoxName}'>".join('', $options)."</select>";
    }

    public static function displayFromBizName($from_biz_name)
    {
        return str_replace('가입회원', '', $from_biz_name);
    }

    /**
     * @param $mb_id
     * @param $pt_id
     */
    public static function _updateHierarchy($mb_id, $pt_id)
    {
        sql_query("DELETE FROM shop_minishop_hierarchy WHERE dn_id = '{$mb_id}'");
        self::insertHierarchy($mb_id, $pt_id);

        sql_query("DELETE FROM shop_minishop_hierarchy_pt WHERE dn_id = '{$mb_id}'");
        self::insertHierarchyPt($mb_id, $pt_id);
    }

    public static function updateHierarchy($mb_id, $pt_id)
    {
        // 상위 변경
        self::_updateHierarchy($mb_id, $pt_id);

        // 하위 변경
        $rslt = sql_query("SELECT a.dn_id, b.pt_id, a.up_lv FROM shop_minishop_hierarchy_pt a, shop_member b WHERE a.dn_id = b.id AND a.pt_id = '{$mb_id}' ORDER BY a.up_lv ASC");
        while($row = sql_fetch_array($rslt)){
            self::_updateHierarchy($row['dn_id'], $row['pt_id']);
        }

    }

    public static function _updateHierarchyUp($mb_id, $up_id)
    {
        // 상위 변경
        sql_query("DELETE FROM shop_minishop_hierarchy_up WHERE dn_id = '{$mb_id}'");
        self::insertHierarchyUp($mb_id, $up_id);

    }

    public static function updateHierarchyUp($mb_id, $up_id)
    {
        // 상위 변경
        self::_updateHierarchyUp($mb_id, $up_id);
        // 하위 변경

        $rslt = sql_query("SELECT a.dn_id, b.up_id, a.up_lv FROM shop_minishop_hierarchy_up a, shop_member b WHERE a.dn_id = b.id AND a.up_id = '{$mb_id}' ORDER BY a.up_lv ASC");
        while($row = sql_fetch_array($rslt)){
            self::_updateHierarchyUp($row['dn_id'], $row['up_id']);
        }
    }

    public static function insertHierarchy($mb_id, $pt_id)
    {
        if( empty($pt_id) || $mb_id == $pt_id) return;
        if( sql_num_rows( sql_query("select 1 from shop_minishop_hierarchy where dn_id = '{$mb_id}'"))) return;
        insert('shop_minishop_hierarchy', [
            'dn_id'=>$mb_id,
            'pt_id'=>$pt_id,
            'up_lv'=>1,
            'up_idx'=>1
        ]);
        sql_query("insert into shop_minishop_hierarchy(dn_id, pt_id, up_lv, up_idx, reg_date) select '{$mb_id}', pt_id, up_lv + 1, up_idx, now() from shop_minishop_hierarchy where dn_id = '{$pt_id}'");
    }

    public static function insertHierarchyPt($mb_id, $pt_id)
    {
        if( empty($pt_id) || $mb_id == $pt_id) return;
        if( sql_num_rows( sql_query("select 1 from shop_minishop_hierarchy_pt where dn_id = '{$mb_id}'"))) return;
        insert('shop_minishop_hierarchy_pt', [
            'dn_id'=>$mb_id,
            'pt_id'=>$pt_id,
            'up_lv'=>1,
            'up_idx'=>1
        ]);
        sql_query("insert into shop_minishop_hierarchy_pt(dn_id, pt_id, up_lv, up_idx, reg_date) select '{$mb_id}', pt_id, up_lv + 1, up_idx, now() from shop_minishop_hierarchy_pt where dn_id = '{$pt_id}'");
    }

    public static function insertHierarchyUp($mb_id, $up_id)
    {
        if( empty($up_id) || $mb_id == $up_id) return;
        if( sql_num_rows( sql_query("select 1 from shop_minishop_hierarchy_up where dn_id = '{$mb_id}'"))) return;
        insert('shop_minishop_hierarchy_up', [
            'dn_id'=>$mb_id,
            'up_id'=>$up_id,
            'up_lv'=>1,
            'up_idx'=>1
        ]);
        sql_query("insert into shop_minishop_hierarchy_up(dn_id, up_id, up_lv, up_idx, reg_date) select '{$mb_id}', up_id, up_lv + 1, up_idx, now() from shop_minishop_hierarchy_up where dn_id = '{$up_id}'");
    }

    /**
     * @return string
     */
    public static function getSqlMemberminishopFromWhere()
    {
        $sql_member_minishop_from_where = "
          FROM shop_member a, shop_minishop b 
         WHERE a.id = b.mb_id and a.use_app = 1 AND b.state = 1 AND ( a.term_date is null or a.term_date > NOW() ) AND a.grade BETWEEN " . minishop::LEVEL_MIN . " AND " . minishop::LEVEL_MAX . "  
        ";
        return $sql_member_minishop_from_where;
    }

    private static function getSaleDans()
    {
        return '5,8';
    }

    /**
     * @return string
     */
    public static function getSalesFetchQuery()
    {
        global $config;
        if ($config['pf_sale_flag']) {
            $sale = 'IFNULL(goods_price,0)-IFNULL(supply_price,0)';
            if ($config['pf_sale_flag'] == 1) {
                $sale .= '-(IFNULL(coupon_price,0)+IFNULL(use_point,0)+IFNULL(use_sp_point,0))';
            }
        } else {
            $sale = 'IFNULL(use_price,0)-IFNULL(baesong_price,0)';
        }
        return $sale;
    }

    public static function maybeCreateCategory($mb_id)
    {
        sql_member_category($mb_id);
    }

    private static function getTotalLinePoint($exec_date)
    {
        $exec_date = date('Y-m-d', strtotime($exec_date) + ( 60 * 60 * 24 ) );
        if( $row = sql_fetch("SELECT SUM(lp_point) total FROM shop_minishop_line_point WHERE lp_datetime < '{$exec_date}'") ) {
            return $row['total'];
        }
        return 0;
    }

}
