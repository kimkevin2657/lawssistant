<?php
    include_once('./_common.php');
    include_once("../settle_easypay.inc.php");

    /* -------------------------------------------------------------------------- */
    /* ::: 승인요청 정보 설정                                                     */
    /* -------------------------------------------------------------------------- */
    //[헤더]
    $tr_cd            = $_POST["EP_tr_cd"];           // [필수]요청구분
    $trace_no         = $_POST["EP_trace_no"];        // [필수]추적고유번호
    $order_no         = $_POST["EP_order_no"];        // [필수]주문번호
    $g_mall_id        = $_POST["EP_mall_id"];         // [필수]몰아이디
    //[공통]
    $encrypt_data     = $_POST["EP_encrypt_data"];    // [필수]암호화 데이타
    $sessionkey       = $_POST["EP_sessionkey"];      // [필수]암호화키

    /* -------------------------------------------------------------------------- */
    /* ::: 변경관리 정보 설정                                                     */
    /* -------------------------------------------------------------------------- */
    $mgr_txtype       = $_POST["mgr_txtype"];         // [필수]거래구분
    $mgr_subtype      = $_POST["mgr_subtype"];        // [선택]변경세부구분
    $org_cno          = $_POST["org_cno"];            // [필수]원거래고유번호
    $mgr_amt          = $_POST["mgr_amt"];            // [선택]부분취소/환불요청 금액
    $mgr_rem_amt      = $_POST["mgr_rem_amt"];        // [선택]부분취소 잔액
    $mgr_bank_cd      = $_POST["mgr_bank_cd"];        // [선택]환불계좌 은행코드
    $mgr_account      = $_POST["mgr_account"];        // [선택]환불계좌 번호
    $mgr_depositor    = $_POST["mgr_depositor"];      // [선택]환불계좌 예금주명


    /* -------------------------------------------------------------------------- */
    /* ::: 전문                                                                   */
    /* -------------------------------------------------------------------------- */
    $mgr_data    = "";     // 변경정보
    $mall_data   = "";     // 요청전문

    /* -------------------------------------------------------------------------- */
    /* ::: 결제 결과                                                              */
    /* -------------------------------------------------------------------------- */
    $res_cd               = "";
    $res_msg              = "";


    /* -------------------------------------------------------------------------- */
    /* ::: EasyPayClient 인스턴스 생성 [변경불가 !!].                             */
    /* -------------------------------------------------------------------------- */
    $easyPay = new EasyPay_Client;         // 전문처리용 Class (library에서 정의됨)
    $easyPay->clearup_msg();

    $easyPay->set_home_dir($g_home_dir);
    $easyPay->set_gw_url($g_gw_url);
    $easyPay->set_gw_port($g_gw_port);
    $easyPay->set_log_dir($g_log_dir);
    $easyPay->set_log_level($g_log_level);
    $easyPay->set_cert_file($g_cert_file);

    /* -------------------------------------------------------------------------- */
    /* ::: IP 정보 설정                                                           */
    /* -------------------------------------------------------------------------- */
    $client_ip = $easyPay->get_remote_addr();    // [필수]결제고객 IP

    /* -------------------------------------------------------------------------- */
    /* ::: 승인요청(플러그인 암호화 전문 설정)                                    */
    /* -------------------------------------------------------------------------- */
    if( $TRAN_CD_NOR_PAYMENT == $tr_cd ) {

        //승인요청 전문 설정
        $easyPay->set_trace_no($trace_no);
        $easyPay->set_snd_key($sessionkey);
        $easyPay->set_enc_data($encrypt_data);

    /* -------------------------------------------------------------------------- */
    /* ::: 변경관리 요청                                                          */
    /* -------------------------------------------------------------------------- */
    }else if( $TRAN_CD_NOR_MGR == $tr_cd ) {

    $mgr_data = $easyPay->set_easypay_item("mgr_data");
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype"      , $mgr_txtype       );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_subtype"     , $mgr_subtype      );
    $easyPay->set_easypay_deli_us( $mgr_data, "org_cno"         , $org_cno          );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_amt"         , $mgr_amt          );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_rem_amt"     , $mgr_rem_amt      );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_bank_cd"     , $mgr_bank_cd      );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_account"     , $mgr_account      );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_depositor"   , $mgr_depositor    );
    $easyPay->set_easypay_deli_us( $mgr_data, "req_ip"          , $client_ip        );

    }

    /* -------------------------------------------------------------------------- */
    /* ::: 실행                                                                   */
    /* -------------------------------------------------------------------------- */
    $opt = "option value";
    $easyPay->easypay_exec($g_mall_id, $tr_cd, $order_no, $client_ip, $opt);
    $res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
    $res_msg = $easyPay->_easypay_resdata["res_msg"];   // 응답메시지

    /* -------------------------------------------------------------------------- */
    /* ::: 결과 처리                                                              */
    /* -------------------------------------------------------------------------- */

    $r_cno             = $easyPay->_easypay_resdata[ "cno"             ];    // PG거래번호
    $r_amount          = $easyPay->_easypay_resdata[ "amount"          ];    //총 결제금액
    $r_order_no        = $easyPay->_easypay_resdata[ "order_no"        ];    //주문번호
    $r_auth_no         = $easyPay->_easypay_resdata[ "auth_no"         ];    //승인번호
    $r_tran_date       = $easyPay->_easypay_resdata[ "tran_date"       ];    //승인일시
    $r_escrow_yn       = $easyPay->_easypay_resdata[ "escrow_yn"       ];    //에스크로 사용유무
    $r_complex_yn      = $easyPay->_easypay_resdata[ "complex_yn"      ];    //복합결제 유무
    $r_stat_cd         = $easyPay->_easypay_resdata[ "stat_cd"         ];    //상태코드
    $r_stat_msg        = $easyPay->_easypay_resdata[ "stat_msg"        ];    //상태메시지
    $r_pay_type        = $easyPay->_easypay_resdata[ "pay_type"        ];    //결제수단
    $r_mall_id         = $easyPay->_easypay_resdata[ "mall_id"         ];    //결제수단
    $r_card_no         = $easyPay->_easypay_resdata[ "card_no"         ];    //카드번호
    $r_issuer_cd       = $easyPay->_easypay_resdata[ "issuer_cd"       ];    //발급사코드
    $r_issuer_nm       = $easyPay->_easypay_resdata[ "issuer_nm"       ];    //발급사명
    $r_acquirer_cd     = $easyPay->_easypay_resdata[ "acquirer_cd"     ];    //매입사코드
    $r_acquirer_nm     = $easyPay->_easypay_resdata[ "acquirer_nm"     ];    //매입사명
    $r_install_period  = $easyPay->_easypay_resdata[ "install_period"  ];    //할부개월
    $r_noint           = $easyPay->_easypay_resdata[ "noint"           ];    //무이자여부
    $r_part_cancel_yn  = $easyPay->_easypay_resdata[ "part_cancel_yn"  ];    //부분취소 가능여부
    $r_card_gubun      = $easyPay->_easypay_resdata[ "card_gubun"      ];    //신용카드 종류
    $r_card_biz_gubun  = $easyPay->_easypay_resdata[ "card_biz_gubun"  ];    //신용카드 구분
    $r_cpon_flag       = $easyPay->_easypay_resdata[ "cpon_flag"       ];    //쿠폰사용유무
    $r_bank_cd         = $easyPay->_easypay_resdata[ "bank_cd"         ];    //은행코드
    $r_bank_nm         = $easyPay->_easypay_resdata[ "bank_nm"         ];    //은행명
    $r_account_no      = $easyPay->_easypay_resdata[ "account_no"      ];    //계좌번호
    $r_deposit_nm      = $easyPay->_easypay_resdata[ "deposit_nm"      ];    //입금자명
    $r_expire_date     = $easyPay->_easypay_resdata[ "expire_date"     ];    //계좌사용만료일
    $r_cash_res_cd     = $easyPay->_easypay_resdata[ "cash_res_cd"     ];    //현금영수증 결과코드
    $r_cash_res_msg    = $easyPay->_easypay_resdata[ "cash_res_msg"    ];    //현금영수증 결과메세지
    $r_cash_auth_no    = $easyPay->_easypay_resdata[ "cash_auth_no"    ];    //현금영수증 승인번호
    $r_cash_tran_date  = $easyPay->_easypay_resdata[ "cash_tran_date"  ];    //현금영수증 승인일시
    $r_cash_issue_type = $easyPay->_easypay_resdata[ "cash_issue_type" ];    //현금영수증발행용도
    $r_cash_auth_type  = $easyPay->_easypay_resdata[ "cash_auth_type"  ];    //인증구분
    $r_cash_auth_value = $easyPay->_easypay_resdata[ "cash_auth_value" ];    //인증번호
    $r_auth_id         = $easyPay->_easypay_resdata[ "auth_id"         ];    //PhoneID
    $r_billid          = $easyPay->_easypay_resdata[ "billid"          ];    //인증번호
    $r_mobile_no       = $easyPay->_easypay_resdata[ "mobile_no"       ];    //휴대폰번호
    $r_mob_ansim_yn    = $easyPay->_easypay_resdata[ "mob_ansim_yn"    ];    //안심결제 사용유무
    $r_ars_no          = $easyPay->_easypay_resdata[ "ars_no"          ];    //전화번호
    $r_cp_cd           = $easyPay->_easypay_resdata[ "cp_cd"           ];    //포인트사/쿠폰사
    $r_pnt_auth_no     = $easyPay->_easypay_resdata[ "pnt_auth_no"     ];    //포인트승인번호
    $r_pnt_tran_date   = $easyPay->_easypay_resdata[ "pnt_tran_date"   ];    //포인트승인일시
    $r_used_pnt        = $easyPay->_easypay_resdata[ "used_pnt"        ];    //사용포인트
    $r_remain_pnt      = $easyPay->_easypay_resdata[ "remain_pnt"      ];    //잔여한도
    $r_pay_pnt         = $easyPay->_easypay_resdata[ "pay_pnt"         ];    //할인/발생포인트
    $r_accrue_pnt      = $easyPay->_easypay_resdata[ "accrue_pnt"      ];    //누적포인트
    $r_deduct_pnt      = $easyPay->_easypay_resdata[ "deduct_pnt"      ];    //총차감 포인트
    $r_payback_pnt     = $easyPay->_easypay_resdata[ "payback_pnt"     ];    //payback 포인트
    $r_cpon_auth_no    = $easyPay->_easypay_resdata[ "cpon_auth_no"    ];    //쿠폰승인번호
    $r_cpon_tran_date  = $easyPay->_easypay_resdata[ "cpon_tran_date"  ];    //쿠폰승인일시
    $r_cpon_no         = $easyPay->_easypay_resdata[ "cpon_no"         ];    //쿠폰번호
    $r_remain_cpon     = $easyPay->_easypay_resdata[ "remain_cpon"     ];    //쿠폰잔액
    $r_used_cpon       = $easyPay->_easypay_resdata[ "used_cpon"       ];    //쿠폰 사용금액
    $r_rem_amt         = $easyPay->_easypay_resdata[ "rem_amt"         ];    //잔액
    $r_bk_pay_yn       = $easyPay->_easypay_resdata[ "bk_pay_yn"       ];    //장바구니 결제여부
    $r_canc_acq_date   = $easyPay->_easypay_resdata[ "canc_acq_date"   ];    //매입취소일시
    $r_canc_date       = $easyPay->_easypay_resdata[ "canc_date"       ];    //취소일시
    $r_refund_date     = $easyPay->_easypay_resdata[ "refund_date"     ];    //환불예정일시


    $payReqMap = [];
    foreach($easyPay->_easypay_resdata as $key=>$val){
        $payReqMap[$key] = iconv_utf8($val);
    }

    $_SESSION['PAYREQ_MAP'] = $payReqMap;

    die(json_encode(array('payReqMap' => $payReqMap, 'error' => '')));

