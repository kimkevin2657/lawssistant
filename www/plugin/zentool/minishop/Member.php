<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-29
 * Time: 15:10
 */

class Member
{
    const SEPARATOR = '_';
    const SEQUENCE_FORMAT = '%04d';

    /** @var Member */
    private $parent;
    public $family_no;
    public $family_id;
    public $member = array();
    public $id;
    public $up_id;
    public $od_id;
    public $pt_id;
    public $grade;

    public static function families($id)
    {
        global $config;
        $member = self::factory($id);


        $addition = "";
        if( $config['use_app'] ) {
            $addition = " AND a.use_app <> 0";
        }

        $sql = "SELECT '{$member->family_id}' family_id, a.id, a.name, a.grade, b.gb_name, a.total_line_cnt FROM shop_member a, shop_member_grade b WHERE a.grade = b.gb_no AND a.grp_id = '{$member->family_id}' {$addition} ORDER BY grp_id, grp_no ";

        $result = sql_query($sql);

        $families = array();

        while($row = sql_fetch_array($result)) :
            $row['balance'] = get_pay_sum($row['id']);
            array_push($families, (object) $row);
        endwhile;

        return $families;
    }

    /**
     * @param $src
     * @param $trg
     * @return JsonResponseData
     */
    public static function impersonation($src, $trg)
    {
        $src_persona = self::factory($src);
        $trg_persona = self::factory($trg);


        if ($src_persona->family_id == $trg_persona->family_id ){
            set_session('ss_mb_id', $trg);
            return JsonResponse::response(JsonResult::SUCCESS, $trg);
        }


        return JsonResponse::response(JsonResult::FAIL, '권한이 충분 하지 않습니다.');
    }

    /**
     * @param $id
     * @return int
     */
    public static function currency($id)
    {
        $me = self::factory($id);
        return 0;
    }

    /**
     * @param $id
     * @param string $mode
     * @return int
     */
    public static function linePoint($id, $mode = 'total')
    {
        global $member;
        if( $mode == 'total' ) {
            return $member['line_point'];
        }

        $sql = "SELECT SUM(lp_point) cnt FROM shop_minishop_line_point WHERE mb_id = '{$id}' and DATE_FORMAT(lp_datetime, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m')";
        $row = sql_fetch($sql);

        return (int) $row['cnt'];
    }

    /**
     * @param $id
     * @param string $mode
     * @return int
     */
    public static function shoppingPay($id, $mode = 'total')
    {
        global $member;
        if( $mode == 'total' ) {
            return $member['sp_point'];
        }

        $sql = "SELECT SUM(sp_price) cnt FROM shop_minishop_shopping_pay WHERE mb_id = '{$id}' and DATE_FORMAT(sp_datetime, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m')";
        $row = sql_fetch($sql);

        return (int) $row['cnt'];
    }

    public static function register(array $param)
    {
        global $config, $super, $super_hp, $ca_id, $set_cart_id;
        if(!$param['id']) {
            alert('회원아이디가 없습니다. 올바른 방법으로 이용해 주십시오.');
        }

        if( empty($param['up_id']) ) {
            $param['up_id'] = $param['pt_id'];
        }

        if( $param['id'] == $param['pt_id'] ) {
            alert('본인을 추천인으로 설정 할 수 없습니다.');
        }
        
        if( $param['id'] == $param['up_id'] ) {
            alert('본인을 후원인으로 설정 할 수 없습니다.');
        }
        $encrypted_id = $param['id'];
        $sql = " select count(*) as cnt from shop_member where id = '{$encrypted_id}' ";
        $row = sql_fetch($sql);
        if($row['cnt'])
            alert("이미 사용중인 회원아이디 입니다.");

// 미성년자 체크

        $birth_year = '19'. substr($param['jumin6'], 0, 2);
        $birth_month= substr($param['jumin6'], 2, 2);
        $birth_day  = substr($param['jumin6'], 4, 2);
        if($birth_year && $birth_month && $birth_day) {
            $mb_birth = trim($birth_year);
            $mb_birth.= sprintf('%02d',trim($birth_month));
            $mb_birth.= sprintf('%02d',trim($birth_day));

            $todays = date("Ymd", MS_SERVER_TIME);

            // 오늘날짜에서 생일을 빼고 거기서 140000 을 뺀다.
            // 결과가 0 이상의 양수이면 만 14세가 지난것임
            $check = $todays - (int)$mb_birth - 140000;
            if($check < 0) {
                alert("만 14세가 지나지 않은 어린이는 정보통신망 이용촉진 및 정보보호 등에 관한 법률\\r\\n제 31조 1항의 규정에 의하여 법정대리인의 동의를 얻어야 하므로\\r\\n법정대리인의 이름과 연락처를 '자기소개'란에 별도로 입력하시기 바랍니다.");
            }
        }

        if( empty($param['anew_grade'])) {
            $param['anew_grade'] = $param['grade'];
        }
        unset($value);
        $value['id']			= $encrypted_id; //회원아이디
        $value['pc_no']         = $param['pc_no']; // 지점
        $value['name']			= $param['name']; //회원명
        $value['passwd']		= $param['passwd']; //비밀번호
        $value['birth_year']	= $birth_year;//년
        $value['birth_month']	= sprintf('%02d',$birth_month); //월
        $value['birth_day']		= sprintf('%02d',$birth_day); //일
        $value['age']			= substr(date("Y")-$birth_year,0,1).'0'; //연령대
        $value['birth_type']	= strtoupper($param['birth_type']); //음력,양력
        $value['gender']		= strtoupper($param['gender']); //성별
        $value['email']			= $param['email']; //이메일
        $value['cellphone']		= replace_tel($param['id']);	 //핸드폰
        $value['telephone']		= replace_tel($param['id']);	 //전화번호
        $value['zip']			= $param['zip']; //우편번호
        $value['addr1']			= $param['addr1']; //주소
        $value['addr2']			= $param['addr2']; //상세주소
        $value['addr3']			= $param['addr3']; //참고항목
        $value['addr_jibeon']	= $param['addr_jibeon']; //지번주소
        $value['mailser']		= $param['mailser'] ? $param['agree3']: 'N'; //E-Mail을 수신
        $value['smsser']		= $param['smsser'] ? $param['agree3'] : 'N'; //SMS를 수신
        $value['pt_id']			= $param['pt_id']; //추천인
        $value['up_id']			= $param['up_id']; //추천인
        $value['reg_time']		= MS_TIME_YMDHIS; //가입일
        $value['grade']			= !empty($param['anew_grade']) ? $param['anew_grade'] : $param['grade']; //레벨

        $value['bank_holder']   = $param['bank_holder'];
        $value['bank_account']  = $param['bank_account'];
        $value['bank_name']     = $param['bank_name'];

// 주민번호
        $value['jumin6']        = Mcrypt::jumin_encrypt($param['jumin6']);
        $value['jumin7']        = Mcrypt::jumin_encrypt($param['jumin7']);
        $value['marketing_yn']  = $param['marketing_yn'];

        $gs_ids                 = $param['gs_id'];

        insert("shop_member", $value);
        $mb_no = sql_insert_id();

        $mb = get_member_no($mb_no);

        // 회원가입 쇼핑포인트 부여
        insert_point($mb['id'], $config['register_point'], '회원가입 축하', '@member', $mb['id'], '회원가입');


        // 신규회원가입 쿠폰발급
        if($config['coupon_yes']) {
            $cp_used = false;
            $cp = sql_fetch("select * from shop_coupon where cp_type = '5'");
            if($cp['cp_id'] && $cp['cp_use']) {
                if(($cp['cp_pub_sdate'] <= MS_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
                    ($cp['cp_pub_edate'] >= MS_TIME_YMD || $cp['cp_pub_edate'] == '9999999999'))
                    $cp_used = true;

                if($cp_used)
                    insert_used_coupon($mb['id'], $mb['name'], $cp);
            }
        }

        // 회원가입 문자발송
        icode_sms_send($mb['id'], '1');

        // 회원 승인 처리시
        if( $config['cert_admin_yes']) {
            $msg = "회원가입이 완료 되었습니다. 승인 처리 이후 로그인 가능합니다";
        } else {

            // 추천인에게 쇼핑포인트 부여
            if( empty($mb['up_id']) ) $mb['up_id'] = $mb['pt_id'];
            if( defined('MS_USE_UP_ID') && MS_USE_UP_ID ) {
                $to_id = $mb['up_id'];
            } else {
                $to_id = $mb['pt_id'];
            }
            if($to_id != encrypted_admin()) {
                insert_point($to_id, $config['minishop_point'], $mb['id'].'의 추천인', '@member', $mb['id'], $mb['id'].' 추천');
            }

            // 세션 생성
            set_session('ss_mb_id', $mb['id']);
        }

        // 회원가입 메일발송
        if($mb['email']) {
            // 회원님께 메일 발송
            $subject = '['.$config['company_name'].'] 회원가입을 축하드립니다.';

            ob_start();
            include_once(MS_BBS_PATH.'/register_form_update_mail1.php');
            $content = ob_get_contents();
            ob_end_clean();

            mailer($config['company_name'], $super['email'], $mb['email'], $subject, $content, 1);

            // 최고관리자님께 메일 발송
            $subject = '['.$config['company_name'].'] '.$mb['name'] .'님께서 회원으로 가입하셨습니다.';

            ob_start();
            $mb['id'] = $mb['id'];
            include_once(MS_BBS_PATH.'/register_form_update_mail2.php');
            $mb['id'] = $mb['id'];
            $content = ob_get_contents();
            ob_end_clean();

            mailer($mb['name'], $mb['email'], $super['email'], $subject, $content, 1);
        }

        // minishop 정보 입력
        unset($value);

        $value['mb_id'] = $mb['id'];
        $value['from_biz_name'] = $param['from_biz_name'];
        $value['from_biz_id']   = $param['from_biz_id'];
        $value['from_biz_job_title'] = $param['from_biz_job_title'];
        $value['from_biz_grade'] = $param['from_biz_grade'];
        $value['bank_holder'] = $param['bank_holder'];
        $value['bank_account'] = $param['bank_account'];
        $value['bank_name'] = $param['bank_name'];
        $value['pay_bank_holder'] = $param['pay_bank_holder'];
        $value['pay_bank_account'] = $param['pay_bank_account'];
        $value['pay_bank_name'] = $param['pay_bank_name'];
        $value['memo'] = $param['memo']; // 남기는 말씀
        $value['anew_grade'] = $param['anew_grade'];       // 가맹등급
        $value['receipt_price'] = $param['receipt_price']; // 가맹비
        $value['deposit_name'] = $param['deposit_name']; // 입금하실 입금자명
        $value['pay_settle_case'] = $param['pay_settle_case']; // 결제방법 1 무통장 입금
        $value['bank_acc'] = $param['bank_acc']; // 입금하실 게좌번호
        $value['reg_signature_json'] = $param['signatureJSON'];
        $value['reg_ip'] = $_SERVER['REMOTE_ADDR'];
        $value['reg_time'] = MS_TIME_YMDHIS;
        $value['update_time'] = MS_TIME_YMDHIS;

        insert("shop_minishop", $value);

        $wr_content = conv_content(conv_unescape_nl(stripslashes($param['memo'])), 0);

        $wr_name = get_text($mb['name']);
        $subject = $wr_name . '님께서 분양신청을 하셨습니다.';

        if($mb['email']) {

            ob_start();
            include_once(MS_BBS_PATH.'/minishop_reg_update_mail.php');
            $content = ob_get_contents();
            ob_end_clean();

            mailer($mb['name'], $mb['email'], $super['email'], $subject, $content, 1);
        }

        icode_member_send($super_hp, $subject);

        if( is_array($gs_ids) ) {
            // 상품 주문 화면으로 이동
            $msg .= "\\n상품주문을 완료해 주십시오.";
            set_session('ss_mb_id', $mb['id']);

            set_session("ss_expire", "after-order-complete");


            // 카트 초기화
            sql_query("DELETE FROM shop_cart WHERE mb_id = '${mb['id']}'");


// 상품정보
            $ss_cart_id = array();
            foreach($gs_ids as $gs_id) {
                $gs = get_goods($gs_id);
                $gs['goods_price'] = get_sale_price($gs_id);

// 중복되지 않는 유일키를 생성
                $od_no = cart_uniqid();
                $ct_qty = 1;
// 배송비
                $ct_send_cost = 0;
// 상품옵션
                $io_id = '';
                $io_type = '';
                $io_price = '';
                $io_value = '';
                $sql = "INSERT INTO shop_cart
						( ca_id, mb_id, pt_id, up_id, gs_id, ct_direct, ct_time, ct_price, ct_supply_price, ct_qty, ct_point, io_id, io_type, io_price, ct_option, ct_send_cost, od_no, ct_ip )
					VALUES ";
                $sql .= "( '$ca_id', '{$mb['id']}', '{$mb['pt_id']}', '{$mb['up_id']}', '{$gs['index_no']}', '$set_cart_id', '" . MS_TIME_YMDHIS . "', '{$gs['goods_price']}', '{$gs['supply_price']}', '$ct_qty', '{$gs['gpoint']}', '$io_id', '$io_type', '$io_price', '$io_value', '$ct_send_cost', '$od_no', '{$_SERVER['REMOTE_ADDR']}' )";
                sql_query($sql);
                array_push($ss_cart_id, sql_insert_id());
            }

            set_session('ss_cart_id', join(',', $ss_cart_id));

        }

        return $msg;

    }

    public static function get_grade_by_price($gb_anew_price)
    {
        if( empty($gb_anew_price) ) return false;
        $sql = "SELECT * FROM shop_member_grade WHERE gb_anew_price > 0 and gb_anew_price  <= '{$gb_anew_price}' and gb_name <> '' order by gb_no asc limit 0, 1";
        return sql_fetch($sql);
    }

    /**
     * @return Member
     */
    public function parent() {



        if( $this->family_id == $this->id ) {
            $this->parent = $this;
        }

        if( ! $this->parent ) {
            $this->parent = self::factory($this->family_id);
        }

        return $this->parent;
    }

    public static function copyMember($src_id, $new_id, $additional = array(), $important = array())
    {
        $new = get_member($src_id);

        $additional = array_merge($additional, array(
            'reg_date' => date('Y-m-d H:i:s'),
            'id'       => $new_id,
            'point'    => 0,
            'match_cnt'=> 0,
            'line_cnt' => 0,
            'total_line_cnt'=>0,
            'job_title'=> '',
            'marketing_yn'=>'0',
            'login_sum'=>0,
            'pay'      =>0,
            'payment'  =>0,
            'vi_today' =>0,
            'vi_yesterday'=>0,
            'vi_max'   =>0,
            'vi_sum'   =>0,
            'vi_history'=>'',
            'smsser'  => 'N',
            'mailser' => 'N',
            'auth_1'  => 0,
            'auth_2'  => 0,
            'auth_3'  => 0,
            'auth_4'  => 0,
            'auth_5'  => 0,
            'auth_6'  => 0,
            'auth_7'  => 0,
            'auth_8'  => 0,
            'auth_9'  => 0,
            'auth_10' => 0
        ), $important);

        foreach( $additional as $name=>$value) {
            if( isset($new[$name])) {
                $new[$name] = $value;
            }
        }

        // 자동 증가키 제외
        unset($new['index_no']);

        insert('shop_member', $new);

        self::updateFamilyInfo($new_id);

        return self::factory($new_id);
    }

    public static function updateFamilyInfo($id)
    {
        $plain_id = $id;
        $arr_id   = explode(self::SEPARATOR, $plain_id.self::SEPARATOR.'0000');
        $grp_id   = $arr_id[0];
        $grp_no   = $arr_id[1];

        update('shop_member', array('grp_id' => $grp_id, 'grp_no'=>$grp_no), " WHERE id = '{$id}' ");

    }

    public static function factory($mb_id)
    {
        $instance = new self;
        $instance->member = get_member($mb_id);

        if( empty($instance->member['grp_id']) ) {
            self::updateFamilyInfo($mb_id);
            $instance->member = get_member($mb_id);
        }

        $instance->id     = $mb_id;
        $instance->od_id  = $instance->member['od_id'];
        $instance->pt_id  = $instance->member['pt_id'];
        $instance->up_id  = $instance->member['up_id'];

        $instance->family_id = $instance->member['grp_id'];
        $instance->family_no = $instance->member['grp_no'];
        $instance->grade     = $instance->member['grade'];

        return $instance;
    }

    public function nextSequence(){
        $sql = "SELECT MAX(grp_no) max_grp_no FROM shop_member WHERE grp_id = '{$this->family_id}'";
        $max_child_id= sql_fetch($sql);
        if( $max_child_id['max_grp_no'] == '' ) {
            return $this->makeSequenceFormat(1);
        } else {
            return $this->makeSequenceFormat( ((int) $max_child_id['max_grp_no'] ) + 1 );
        }
    }

    private function makeSequenceFormat($num){
        return sprintf(self::SEQUENCE_FORMAT, $num);
    }

    public function setActive()
    {
        sql_query("UPDATE shop_member SET use_app = 1 WHERE id = '{$this->id}'");
    }

    public static function get_grade($grade)
    {
        $sql = "SELECT * FROM shop_member_grade WHERE gb_no = {$grade}";
        return sql_fetch($sql);
    }

    public static function do_login($plain_id, $plain_password)
    {
        global $config;

        $mb_id = trim($plain_id);
//set_session('ss_mb_id', 'admin');
        $mb_password = trim($plain_password);

        if(!$mb_id || !$mb_password)
            return [ApiResponse::KEY_RSLT_CODE => ApiResponse::INVALID_LOGIN_USER, 'RsltMessage' => '회원아이디나 비밀번호가 공백이면 안됩니다.'];

        $mb = get_member($mb_id);

// 가입된 회원이 아니다. 패스워드가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 패스워드를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 패스워드가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
        if(!$mb['id'] || !check_password($mb_password, $mb['passwd'])) {
            return [ApiResponse::KEY_RSLT_CODE => ApiResponse::INVALID_LOGIN_USER, 'RsltMessage' => '가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.'];
        }

// 차단된 아이디인가?
        if($mb['intercept_date'] && $mb['intercept_date'] <= date("Ymd", MS_SERVER_TIME)) {
            $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['intercept_date']);
            return [ApiResponse::KEY_RSLT_CODE => ApiResponse::INVALID_LOGIN_USER, 'RsltMessage' => '회원님의 아이디는 접근이 금지되어 있습니다.\\n처리일 : '.$date];
        }


// 인트로 사용시 승인된 회원인지 체크

        if(!is_admin($mb['grade']) && !$mb['use_app'] && $config['cert_admin_yes']) {
            return [ApiResponse::KEY_RSLT_CODE => ApiResponse::INVALID_LOGIN_USER, 'RsltMessage' => "승인 된 회원만 로그인 가능합니다."];
        }

// 관리비를 사용중일때 기간이 만료되었다면 로그인 차단
        if($config['pf_expire_use'] && $config['pf_login_no']) {
            if(is_minishop($mb['id']) && !is_null_time($mb['term_date'])) {
                if($mb['term_date'] < MS_TIME_YMD) {
                    return [ApiResponse::KEY_RSLT_CODE => ApiResponse::INVALID_LOGIN_USER, 'RsltMessage' => "회원님의 아이디는 관리비 미납으로 접근이 금지되어 있습니다."];
                }
            }
        }

        return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::SUCCESS, 'point' => get_point_sum($mb_id)];
    }

    /**
     * 회원 ID 변경
     * @param $src_id
     * @param $trg_id
     * @return void
     *
     * @throws ExistsMemberException
     * @throws NotExistsMemberException
     * @throws SameSourceTargetException
     */
    public static function ch_mb_id($src_id, $trg_id)
    {
        global $member;
        // 변경전 후가 같으면
        if( $src_id == $trg_id ) throw new SameSourceTargetException('변경 전 후 ID가 동일 합니다.');

        // 변경전 사용자가 없는 경우
        $src = sql_fetch("SELECT * FROM shop_member WHERE id = '{$src_id}'");
        if(!$src  ) throw new NotExistsMemberException('존재 하지 않는 ID 입니다.');

        // 변경후 사용자가 없는 경우
        $trg = sql_fetch("SELECT * FROM shop_member WHERE id = '{$trg_id}'");
        if( $trg ) throw new ExistsMemberException('존재 하는 ID 입니다.');

        update('shop_member', ['id'=>$trg_id], "where id='{$src_id}'");
        insert('shop_member_change_id', ['src_id'=>$src_id,
                                               'trg_id'=>$trg_id,
                                               'mng_id'=>$member['id'],
                                               'remote_addr'=>$_SERVER['REMOTE_ADDR'],
                                               'reg_at'=>MS_TIME_YMDHIS]
              );

        $rst = sql_query("SELECT * FROM mig_id_columns WHERE is_id_column = 1");
        while($rs = sql_fetch_array($rst)) {
            $sql = "UPDATE {$rs['table_name']} SET {$rs['column_name']} = '{$trg_id}' WHERE {$rs['column_name']} = '{$src_id}'";
            sql_query($sql);
        }

    }
}
