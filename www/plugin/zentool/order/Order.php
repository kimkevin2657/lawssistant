<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 01:26
 */
class Order
{
    public $order = array();
    public $goods = array();
    /** @var Member */
    public $member;
    public $od_id;
    public $mb_id;
    public $pt_id;
    public $up_id;
    public $od_no;
    public $buy_minishop_grade;
    public $buy_minishop_type;

    const PARTNER_TYPE_UPGRADE = "upgrade";
    const PARTNER_TYPE_ANEW    = "anew";

    public static function factory($od, $gs){
        $instance = new self;

        $instance->od_id = $od['od_id'];
        $instance->od_no = $od['od_no'];
        $instance->pt_id = $od['pt_id'];
        $instance->up_id = $od['up_id'];
        $instance->mb_id = $od['mb_id'];

        $instance->order = $od;
        $instance->goods = $gs;

        if( $od['buy_minishop_grade'] ) $instance->buy_minishop_grade = $od['buy_minishop_grade'];
        else $instance->buy_minishop_grade = $gs['buy_minishop_grade'];

        $instance->buy_minishop_type = $od['buy_minishop_type'];

        $instance->member = Member::factory($od['mb_id']);

        return $instance;
    }



    public static function insertSalePay($od, $gs)
    {
        global $config;

		// 판매수수료를 사용을 하지 않는다면 리턴
        if(!$config['pf_sale_use']) return;

        // 가맹점상품이면 리턴
        if($gs['use_aff']) return;

        $up_id = $od['up_id'];
        $pt_id = $od['pt_id'];
        if( empty($up_id) ) $up_id = $pt_id;
        // 가맹점이 아니면 리턴
        // if(!is_minishop($pt_id)) return;
        /**
         * INFO: 판매 수수료는 가맹상품을 제외하고 지급 합니다.
         */
        if( $gs['buy_minishop_grade'] ) return;

        // 가맹점 정보
        //$mb = get_member($pt_id, 'grade');
		// 본인의 회원등급을 체크하여 단계별로 지급
        $mb = get_member($od['mb_id'], 'grade');

        $amount = 0;

        // 원가 계산
        /**
         * $config['pf_sale_flag']
         *
         * 0 결제액 - 배송비 - 쿠폰 - 쇼핑페이 - 쇼핑포인트결제액 = 순수결제액 에서 판매수수료를 배분
         * 1 판매가 - 공급가 - 쿠폰 - 쇼핑페이 - 쇼핑포인트결제액 = 마진 에서 판매수수료를 배분(마진이 없으면 적립되지 않음)
         * 2 판매가 - 공급가 = 마진 에서 판매수수료를 배분(쿠폰 및 쇼핑포인트 사용액은 무시하고 무조건 적립)
         * 3 마일리지
         */
        if( $config['pf_sale_flag'] == 3 ) {
            $amount = $od['goods_kv'];
        } else {
            if ($config['pf_sale_flag']) {
                if ($od['supply_price'] > 0) // 공급가
                    $amount = $od['goods_price'] - $od['supply_price'];

                if ($config['pf_sale_flag'] == 1)
                    $amount = $amount - ($od['coupon_price'] + $od['use_sp_point'] + $od['use_point']); // 할인쿠폰 + 쇼핑포인트결제
            } else {
                $amount = $od['use_price'] - $od['baesong_price']; // 순수결제액 - 배송비
            }

            // 50% 로 사용 한다.
            $amount = $amount / 2;

        }
        // 본인에게 적립
        insert_pay($od['mb_id'], $amount, "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료 마일리지", 'sale', $od['od_no'], $od['od_id']);

        // 적용할 금액이 없다면 리턴
        if($amount < 1) return;

        if($gs['ppay_type']) { // 개별설정
            $sale_benefit_dan  = $gs['ppay_dan'];
            $sale_benefit_type = $gs['ppay_rate'];
            $sale_benefit = explode(chr(30), $gs['ppay_fee']);
        } else { // 공통설정
            $sale_benefit_dan  = $config['pf_sale_benefit_dan'];
            $sale_benefit_type = $config['pf_sale_benefit_type'];
            $sale_benefit = explode(chr(30), $config['pf_sale_benefit_'.$mb['grade']]);
        }

        // 판매수수료를 적용할 단계가 없다면 리턴
        if($sale_benefit_dan < 1) return;

        for($i=0; $i<$sale_benefit_dan; $i++)
        {
            // 추천인이 없거나 최고관리자라면 중지
            if(!$pt_id || $pt_id == encrypted_admin())
                break;

            // 적용할 인센티브가 없다면 건너뜀
            $benefit = (float)trim($sale_benefit[$i]);
            if($benefit <= 0) continue;


            if($sale_benefit_type)
                $pt_pay = (int)($benefit * $od['sum_qty']); // 설정금액(원)
            else
                $pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

            // 추천인 정보
            $mb = get_member($pt_id, 'pt_id, up_id, payment, payflag');

            // 개별 추가 판매수수료
            if($mb['payment']) {
                if($mb['payflag'])
                    $pt_pay += (int)($mb['payment'] * $od['sum_qty']); // 설정금액(원)
                else
                    $pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
            }

            // 적용할 수수료가 없다면 건너뜀
            if($pt_pay <= 0) continue;

            if( defined('MS_USE_UP_ID') && MS_USE_UP_ID ) {
                $to_id = $up_id;
            } else {
                $to_id = $pt_id;
            }

            // 추천인이 최고관리자라면 지급 하지 않음.
            if($to_id != encrypted_admin()) {
                insert_pay($to_id, $pt_pay, "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료 추천마일리지", 'sale', $od['od_no'], $od['od_id']);
            }
            // 상위 추천인을 담고 다시 배열로 돌린다
            $pt_id = $mb['pt_id'];
            $up_id = $mb['up_id'];
            if( empty($up_id) ) $up_id = $pt_id;

        } // for

    }

    public static function insertSalePay2($mb_id, $pp_pay, $pp_content)
    {
        global $config, $member;

		// 본인의 회원등급을 체크하여 단계별로 지급
        $mb = get_member($mb_id, 'up_id, pt_id, grade');

        $up_id = $mb['up_id'];
        $pt_id = $mb['pt_id'];
        if( empty($up_id) ) $up_id = $pt_id;

		$amount = $pp_pay;

        // 적용할 금액이 없다면 리턴
        if(!$amount) return;

		$sale_benefit_dan  = $config['pf_sale_benefit_dan'];
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit = explode(chr(30), $config['pf_sale_benefit_'.$mb['grade']]);

        // 판매수수료를 적용할 단계가 없다면 리턴
        if($sale_benefit_dan < 1) return;

        for($i=0; $i<$sale_benefit_dan; $i++)
        {
            // 추천인이 없거나 최고관리자라면 중지
            if(!$pt_id || $pt_id == encrypted_admin())
                break;

            // 적용할 인센티브가 없다면 건너뜀
            $benefit = (float)trim($sale_benefit[$i]);
            if($benefit <= 0) continue;

            if($sale_benefit_type)
                $pt_pay = (int)$benefit; // 설정금액(원)
            else
                $pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

            // 추천인 정보
            $mb = get_member($pt_id, 'pt_id, up_id, payment, payflag');

            // 개별 추가 판매수수료
            if($mb['payment']) {
                if($mb['payflag'])
                    $pt_pay += (int)$mb['payment']; // 설정금액(원)
                else
                    $pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
            }

            // 적용할 수수료가 없다면 건너뜀
            if(!$pt_pay) continue;

            if( defined('MS_USE_UP_ID') && MS_USE_UP_ID ) {
                $to_id = $up_id;
            } else {
                $to_id = $pt_id;
            }

            // 추천인이 최고관리자라면 지급 하지 않음.
            if($to_id != encrypted_admin()) {
				insert_pay($to_id, $pt_pay, $pp_content, 'passive', $mb_id, $member['id'].'-'.uniqid(''));
            }
            // 상위 추천인을 담고 다시 배열로 돌린다
            $pt_id = $mb['pt_id'];
            $up_id = $mb['up_id'];
            if( empty($up_id) ) $up_id = $pt_id;

        } // for

    }

    public static function doOrder(array $param, $shop_url = MS_SHOP_URL, $is_mobile = false)
    {
        global $member, $name, $is_member, $gs_id, $cart_id, $gs_price, $default, $pt_settle_pid, $config, $gs_notax, $seller_id, $gs_kv, $supply_price
               , $sum_point, $sum_tk_point, $sum_ppay, $sum_sp_point, $sum_qty, $super, $io_famiwel_no, $famiwel_mb_id; // input


        // 삼성페이 요청으로 왔다면 현재 삼성페이는 이니시스 밖에 없으므로
        if( $param['paymethod'] == '삼성페이' && $default['de_pg_service'] != 'inicis') {
            alert("이니시스를 사용중일때만 삼성페이 결제가 가능합니다.", $shop_url."/cart.php");
        }

        // 장바구니 상품 재고 검사
        $error = "";
        $sql = " select * from shop_cart where index_no IN ({$param['ss_cart_id']}) and ct_select = '0' ";
        $result = sql_query($sql);
        for($i=0; $row=sql_fetch_array($result); $i++) {

			$stock_qty = (int)get_it_stock_qty($row['gs_id']); // 한정수량 조사

			if($stock_qty > '0'){
				$it_stock_qty = $stock_qty; //한정수량이 존재한다면 모든 재고보다 우선한다. //2022-06-27 타임세일 관련 수정 나영균
			}else{
				// 상품에 대한 현재고수량
				if($row['io_id']) {
					$it_stock_qty = (int)get_option_stock_qty($row['gs_id'], $row['io_id'], $row['io_type']);
				} else {
					$it_stock_qty = (int)get_it_stock_qty($row['gs_id']);
				}
			}
            // 장바구니 수량이 재고수량보다 많다면 오류
            if($row['ct_qty'] > $it_stock_qty)
                $error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
        }

        if($i == 0){
            alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', MS_SHOP_URL.'/cart.php');
		}

        if($error != "") {
            $error .= "다른 고객님께서 {$name}님 보다 먼저 주문하신 경우입니다. 불편을 끼쳐 죄송합니다.";
            alert($error);
        }

        $dan = 0;
        if($param['paymethod'] == '무통장')
            $dan = 1; // 주문접수 단계로 적용

        if((int)$param['tot_price'] == 0) { // 총 결제금액이 0 이면
            $dan = 2; // 입금확인 단계로 적용

            // 쇼핑포인트로 전액 결제시는 쇼핑포인트결제로 값을 바꾼다.
            if($param['paymethod'] != '포인트' && (int)$param['org_price'] == (int)$param['use_point']) {
                $param['paymethod'] = '포인트';
            }

            // 쇼핑페이로 전액 결제시는 쇼핑페이결제로 값을 바꾼다.
            if($param['paymethod'] != '쇼핑페이' && (int)$param['org_price'] == (int)$param['use_sp_point']) {
                $param['paymethod'] = '쇼핑페이';
            }

            // 쇼핑페이로 전액 결제시는 쇼핑페이결제로 값을 바꾼다.
            if($param['paymethod'] != '쇼핑페이' && $param['paymethod'] != '포인트' && $param['paymethod'] != '마일리지'
                && (int)$param['org_price'] == ((int)$param['use_sp_point'] + (int)$param['use_point'] + (int)$param['use_ppay'])) {
                if( (int)$param['use_sp_point'] > 0 && (int)$param['use_point'] > 0  ) $param['paymethod'] = '마일리지';
                if( (int)$param['use_sp_point'] > 0 && (int)$param['use_ppay'] > 0 ) $param['paymethod'] = '포인트';
                else $param['paymethod'] = '쇼핑페이';
            }

        }

		 if($param['paymethod'] == '무통장'){
			  if($param['refund_account'] != ''){
				unset($value);
				$value['refund_account']				= $param['refund_account']; //환불계좌번호
				$value['refund_account_bank_name']		= $param['refund_account_bank_name']; //은행명
				$value['refund_account_name']			= $param['refund_account_name']; //예금주
				update("shop_member", $value, "where id='$member[id]'");
			  }
		 }

		 if($param['paymethod'] == '가상계좌'){
			  if($param['refund_account'] != ''){
				unset($value);
				$value['refund_account']				= $param['refund_account']; //환불계좌번호
				$value['refund_account_bank_name']		= $param['refund_account_bank_name']; //은행명
				$value['refund_account_name']			= $param['refund_account_name']; //예금주
				update("shop_member", $value, "where id='$member[id]'");
			  }
		 }

        set_session('tot_price', (int)$param['tot_price']);
        set_session('use_point', (int)$param['use_point']);
        set_session('use_sp_point', (int)$param['use_sp_point']);


        $baesong_price	= explode("|",$param['baesong_price']); // 상품별 배송비
        $coupon_price	= explode("|",$param['coupon_price']); // 상품별 할인가
        $coupon_lo_id	= explode("|",$param['coupon_lo_id']); // 상품별 쿠폰 shop_coupon_log (필드:lo_id)
        $coupon_cp_id	= explode("|",$param['coupon_cp_id']); // 상품별 쿠폰 shop_coupon_log (필드:cp_id)
        $ss_cart_id		= explode(",",$param['ss_cart_id']); // 장바구니 idx

        $use_point      = (int)$param['use_point']; // 쇼핑포인트결제
        $use_sp_point   = (int)$param['use_sp_point'];
        $baesong_price2 = (int)$param['baesong_price2']; // 추가배송비
        $use_ppay       = (int)$param['use_ppay'];// 수수료결제

        if( $member['pay'] < $use_ppay ) {
            alert("마일리지가 부족 합니다.");
        }
        set_session('use_ppay', (int)$param['use_ppay']);

        if($is_member)
            $od_pwd = $member['passwd'];
        else
            $od_pwd = get_encrypt_string($param['od_pwd']);


        $od_id = get_uniqid(); // 주문번호

        for($i=0; $i<count($gs_id); $i++) {

            // 주문 일련번호
            $od_no = $cart_id[$i];
			$od_ck = sql_fetch("select count(*) as cnt from shop_order where od_no = '$od_no'");

			if($od_ck['cnt'] < '1'){

				if($i==0) {
					$t_point = $use_point;  // 쇼핑포인트 결제금액
					$t_sp_point = $use_sp_point;
					$t_ppay  = $use_ppay;
					for($k=0; $k<count($gs_id); $k++) {
						if($k == 0 && $baesong_price2 > 0) {
							$baesong_price[$k] = (int)$baesong_price[$k] + $baesong_price2; // 배송비 + 추가배송비
						}

						$t_baesong = (int)$baesong_price[$k]; // 배송비 결제금액
						$t_price = (int)$gs_price[$k] - (int)$coupon_price[$k]; // 상품 판매가 - 쿠폰 할인가
						if($t_point > 0) {
							if(($t_price+$t_baesong) >= $t_point) {
								if($param['salebaesong_price'] != '0'){
									$t_baesong = $t_baesong - $param['salebaesong_price'];
									$i_use_price[$k] = ($t_price+$t_baesong)-$t_point;
								}else{
									$i_use_price[$k] = ($t_price+$t_baesong)-$t_point;
								}
								$i_use_point[$k] = $t_point;
								$t_point = 0;

							} else if(($t_price+$t_baesong) < $t_point) {
								$i_use_price[$k] = 0;
								$i_use_point[$k] = $t_price+$t_baesong;
								$t_point = $t_point-($t_price+$t_baesong);
							}

						} else {
							$t_point = 0;
							$i_use_point[$k] = 0;
							$i_use_price[$k] = $t_price+$t_baesong;
						}


						// 쇼핑페이 결제분
						if( $t_sp_point > 0 ){
							if( $i_use_price[$k] > $t_sp_point ){
								$i_use_sp_point[$k]  = $t_sp_point;
								$i_use_price[$k] = $i_use_price[$k] - $t_sp_point;
								$t_sp_point = 0;
							} else {
								$i_use_sp_point[$k]  = $i_use_price[$k];
								$i_use_price[$k] = 0;
								$t_sp_point = $t_sp_point - $i_use_sp_point[$k];
							}
						} else {
							$i_use_sp_point[$k] = 0;
						}


						// 수수료 결제분
						if( $t_ppay > 0 ){
							if( $i_use_price[$k] > $t_ppay ){
								$i_use_ppay[$k]  = $t_ppay;
								$i_use_price[$k] = $i_use_price[$k] - $t_ppay;
								$t_ppay = 0;
							} else {
								$i_use_ppay[$k]  = $i_use_price[$k];
								$i_use_price[$k] = 0;
								$t_ppay = $t_ppay - $i_use_ppay[$k];
							}
						} else {
							$i_use_ppay[$k] = 0;
						}

					}
				} else {
					$baesong_price2 = 0;
				}

				$sql = "insert into shop_order
				   set od_id				= '{$od_id}'
					 , od_no				= '{$od_no}'
					 , mb_id				= '{$member['id']}'
					 , name					= '{$param['name']}'
					 , cellphone			= '{$param['cellphone']}'
					 , telephone			= '{$param['telephone']}'
					 , email				= '{$param['email']}'
					 , zip					= '{$param['zip']}'
					 , addr1				= '{$param['addr1']}'
					 , addr2				= '{$param['addr2']}'
					 , addr3				= '{$param['addr3']}'
					 , addr_jibeon			= '{$param['addr_jibeon']}'
					 , b_name				= '{$param['b_name']}'
					 , b_cellphone			= '{$param['b_cellphone']}'
					 , b_telephone			= '{$param['b_telephone']}'
					 , b_zip				= '{$param['b_zip']}'
					 , b_addr1				= '{$param['b_addr1']}'
					 , b_addr2				= '{$param['b_addr2']}'
					 , b_addr3				= '{$param['b_addr3']}'
					 , b_addr_jibeon		= '{$param['b_addr_jibeon']}'
					 , gs_id				= '{$gs_id[$i]}'
					 , gs_notax				= '{$gs_notax[$i]}'
					 , seller_id			= '{$seller_id[$i]}'
					 , famiwel_op_no		= '{$io_famiwel_no[$i]}'
					 , famiwel_mb_id		= '{$famiwel_mb_id[$i]}'
					 , famiwel_res_cd		= ''
					 , goods_price			= '{$gs_price[$i]}'
					 , goods_kv 			= '{$gs_kv[$i]}'
					 , supply_price			= '{$supply_price[$i]}'
					 , sum_point			= '{$sum_point[$i]}'
					 , sum_sp_point			= '{$sum_sp_point[$i]}'
					 , sum_qty				= '{$sum_qty[$i]}'
					 , coupon_price			= '{$coupon_price[$i]}'
					 , coupon_lo_id			= '{$coupon_lo_id[$i]}'
					 , coupon_cp_id			= '{$coupon_cp_id[$i]}'
					 , use_price			= '{$i_use_price[$i]}'
					 , use_point			= '{$i_use_point[$i]}'
					 , use_sp_point			= '{$i_use_sp_point[$i]}'
					 , use_ppay			    = '{$i_use_ppay[$i]}'
					 , baesong_price		= '{$baesong_price[$i]}'
					 , salebaesong_price	= '{$param['salebaesong_price']}'
					 , baesong_price2		= '{$baesong_price2}'
					 , paymethod			= '{$param['paymethod']}'
					 , bank					= '{$param['bank']}'
					 , deposit_name			= '{$param['deposit_name']}'
					 , dan					= '{$dan}'
					 , memo					= '{$param['memo']}'
					 , taxsave_yes			= '{$param['taxsave_yes']}'
					 , taxbill_yes			= '{$param['taxbill_yes']}'
					 , company_saupja_no	= '{$param['company_saupja_no']}'
					 , company_name			= '{$param['company_name']}'
					 , company_owner		= '{$param['company_owner']}'
					 , company_addr			= '{$param['company_addr']}'
					 , company_item			= '{$param['company_item']}'
					 , company_service		= '{$param['company_service']}'
					 , tax_hp				= '{$param['tax_hp']}'
					 , tax_saupja_no		= '{$param['tax_saupja_no']}'
					 , od_time				= '".MS_TIME_YMDHIS."'
					 , od_pwd				= '{$od_pwd}'
					 , od_ip				= '{$_SERVER['REMOTE_ADDR']}'
					 , od_test				= '{$default['de_card_test']}'
					 , od_tax_flag			= '{$default['de_tax_flag_use']}'
					 , od_settle_pid		= '{$pt_settle_pid}'
					 , pt_id				= '".$param['pt_id']."'
					 , up_id				= '".$param['up_id']."'
					 , buy_minishop_grade    = '{$param['buy_minishop_grade']}'
					 , buy_minishop_type     = '{$param['buy_minishop_type']}'
					 , shop_id				= '{$param['shop_id']}' ";


				if( $is_mobile )
					$sql .= ", od_mobile        = '1'";

				if( $param['ad_sel_addr'] == '4' ) $sql .= ", delivery = '방문수령'";

//return $sql;

				sql_query($sql, FALSE);
				$insert_id = sql_insert_id();

				// 고객이 주문/배송조회를 위해 보관해 둔다.
				save_goods_data($gs_id[$i], $insert_id, $od_id);

				// 쿠폰 사용함으로 변경 (무통장, 쇼핑포인트결제일 경우만)
				if($coupon_lo_id[$i] && $is_member && in_array($param['paymethod'],array('무통장','포인트','마일리지','쇼핑페이'))) {
					sql_query("update shop_coupon_log set mb_use='1',od_no='$od_no',cp_udate='".MS_TIME_YMDHIS."' where lo_id='$coupon_lo_id[$i]'");
				}

				// 쿠폰 주문건수 증가
				if($coupon_cp_id[$i] && $is_member) {
					sql_query("update shop_coupon set cp_odr_cnt=(cp_odr_cnt + 1) where cp_id='$coupon_cp_id[$i]'");
				}

				// 주문완료 후 쿠폰발행
				$gs = get_goods($gs_id[$i], 'use_aff');
				if(!$gs['use_aff'] && $config['coupon_yes'] && $is_member) {
					$cp_used = is_used_coupon('1', $gs_id[$i]);
					if($cp_used) {
						$cp_id = explode(",", $cp_used);
						for($g=0; $g<count($cp_id); $g++) {
							if($cp_id[$g]) {
								$cp = sql_fetch("select * from shop_coupon where cp_id='$cp_id[$g]'");
								insert_used_coupon($member['id'], $member['name'], $cp);
							}
						}
					}
				}
			}
        }

        $od_pg = $default['de_pg_service'];
        if($param['paymethod'] == 'KAKAOPAY')
            $od_pg = 'KAKAOPAY';

// 복합과세 금액
        if($default['de_tax_flag_use']) {
            $info = comm_tax_flag($od_id);
            $od_tax_mny  = $info['comm_tax_mny'];
            $od_vat_mny  = $info['comm_vat_mny'];
            $od_free_mny = $info['comm_free_mny'];
        } else {
            $od_tax_mny  = round($param['tot_price'] / 1.1);
            $od_vat_mny  = $param['tot_price'] - $od_tax_mny;
            $od_free_mny = 0;
        }

// 주문서에 UPDATE
        $sql = " update shop_order
            set od_pg		 = '$od_pg'
			  , od_tax_mny	 = '$od_tax_mny'
			  , od_vat_mny	 = '$od_vat_mny'
			  , od_free_mny	 = '$od_free_mny'
		  where od_id = '$od_id'";
        sql_query($sql, false);

        if(in_array($param['paymethod'],array('무통장','포인트','쇼핑페이','마일리지'))) {
            $cart_select = " , ct_select = '1' ";
        }

// 장바구니 주문완료 처리 (무통장, 쇼핑포인트결제, '쇼핑페이')
        $sql = " update shop_cart set od_id = '$od_id' {$cart_select} where index_no IN ({$param['ss_cart_id']}) ";
        sql_query($sql);

// 재고수량 감소
        for($i=0; $i<count($ss_cart_id); $i++) {
            $ct = get_cart_id($ss_cart_id[$i]);
			$gs = get_goods($ct['gs_id']);

			if($gs['stock_mod'] == '1'){
				$stock_qty = (int)get_it_stock_qty($row['gs_id']); // 한정수량 조사
				if($stock_qty > '0'){
						$sql = " update shop_goods
							set stock_qty = stock_qty - '{$ct['ct_qty']}'
						  where index_no = '{$ct['gs_id']}'
							and stock_mod = '1' ";
						sql_query($sql, FALSE); //한정수량이 존재한다면 모든 재고보다 우선한다. //2022-06-27 타임세일 관련 수정 나영균
				}else{

					if($ct['io_id']) { // 옵션 : 재고수량 감소
						$sql = " update shop_goods_option
							set io_stock_qty = io_stock_qty - '{$ct['ct_qty']}'
						  where io_id = '{$ct['io_id']}'
							and gs_id = '{$ct['gs_id']}'
							and io_type = '{$ct['io_type']}'
							and io_stock_qty <> '999999999' ";
						sql_query($sql, FALSE);
					} else { // 상품 : 재고수량 감소
						$sql = " update shop_goods
							set stock_qty = stock_qty - '{$ct['ct_qty']}'
						  where index_no = '{$ct['gs_id']}'
							and stock_mod = '1' ";
						sql_query($sql, FALSE);
					}
				}
			}else{
					if($ct['io_id']) { // 옵션 : 재고수량 감소
						$sql = " update shop_goods_option
							set io_stock_qty = io_stock_qty - '{$ct['ct_qty']}'
						  where io_id = '{$ct['io_id']}'
							and gs_id = '{$ct['gs_id']}'
							and io_type = '{$ct['io_type']}'
							and io_stock_qty <> '999999999' ";
						sql_query($sql, FALSE);
					} else { // 상품 : 재고수량 감소
						$sql = " update shop_goods
							set stock_qty = stock_qty - '{$ct['ct_qty']}'
						  where index_no = '{$ct['gs_id']}'
							and stock_mod = '1' ";
						sql_query($sql, FALSE);
					}
			}


        }

        if(in_array($param['paymethod'],array('무통장','포인트','쇼핑페이','마일리지'))) {
            self::doOrderDone($param, $is_member, $use_point, $member, $od_id, $use_sp_point, $use_ppay, $config, $super);
        }

// 장바구니 session 삭제
        set_session('ss_cart_id', '');

// orderinquiryview 에서 사용하기 위해 session에 넣고
        $uid = md5($od_id.MS_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
        set_session('ss_orderview_uid', $uid);


// 최초 가입 상품 주문 인 경우
        if( get_session("ss_expire") == "after-order-complete"){
            // 주문 번호를 업데이트 해준다.
            sql_query("UPDATE shop_member SET od_id = '${od_id}' WHERE id = '${member['id']}'");
        }

        return ['od_id'=>$od_id, 'uid'=>$uid];
    }

    /**
     * @param array $param
     * @param $is_member
     * @param $use_point
     * @param array $member
     * @param $od_id
     * @param $use_sp_point
     * @param $config
     * @param $super
     */
    public static function doOrderDone(array $param, $is_member, $use_point, array $member, $od_id, $use_sp_point, $use_ppay, $config, $super)
    {
        // 회원이면서 쇼핑포인트를 사용했다면 테이블에 사용을 추가
        if ($is_member && $use_point) {
            insert_point($member['id'], (-1) * $use_point, "주문번호 $od_id 결제");
        }
        // 회원이면서 쇼핑페이를 사용했다면 테이블에 사용을 추가
        if ($is_member && $use_sp_point) {
            insert_shopping_pay($member['id'], (-1) * $use_sp_point, "주문번호 $od_id 결제", 'order', $od_id, 'ordered', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);
        }

        if( $is_member && $use_ppay ) {
            insert_pay($member['id'], (-1) * $use_ppay, "주문번호 {$od_id} 결재", 'order', $od_id, 'ordered', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);
        }

        // 쿠폰사용내역기록
        if ($is_member) {
            $sql = "select * from shop_order where od_id='$od_id'";
            $res = sql_query($sql);
            for ($i = 0; $row = sql_fetch_array($res); $i++) {
                if ($row['coupon_price']) {
                    $sql = "update shop_coupon_log
						   set mb_use = '1',
							   od_no = '$row[od_no]',
							   cp_udate	= '" . MS_TIME_YMDHIS . "'
						 where lo_id = '$row[coupon_lo_id]' ";
                    sql_query($sql);
                }
            }
        }

        $od = sql_fetch("select * from shop_order where od_id='$od_id'");

        // 주문완료 문자전송
	if($od['dan'] != '1'){
        icode_order_sms_send($od['cellphone'], '2', $od_id);
	}

        // 무통장 입금 때 고객에게 계좌정보 보냄
        if ($param['paymethod'] == '무통장' && (int)$param['tot_price'] > 0) {
            $sms_content = $od['name'] . "님의 입금계좌입니다.\n금액:" . number_format($param['tot_price']) . "원\n계좌:" . $od['bank'] . "\n" . $config['company_name'];
            icode_member_send($od['cellphone'], $sms_content);
        }

        // 메일발송
        if ($od['email']) {
            $subject1 = get_text($od['name']) . "님 주문이 정상적으로 처리되었습니다.";
            $subject2 = get_text($od['name']) . " 고객님께서 신규주문을 신청하셨습니다.";

            ob_start();
            include_once(MS_SHOP_PATH . '/orderformupdate_mail.php');
            $content = ob_get_contents();
            ob_end_clean();

            // 주문자에게 메일발송
            mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);

            // 관리자에게 메일발송
            if ($super['email'] != $od['email']) {
                mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
            }
        }
    }

    public function replaceMbId($new_mb_id)
    {
        $sql = "UPDATE shop_order SET mb_id = '{$new_mb_id}' WHERE od_id = '{$this->od_id}' AND mb_id = '{$this->mb_id}'";

        sql_query($sql);
    }

}
