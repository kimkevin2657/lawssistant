<?php
include_once("./_common.php");

check_demo();

check_admin_token();

// input vars 체크
check_input_vars();

$upl_dir = MS_DATA_PATH."/goods";
$upl = new upload_files($upl_dir);

if(!$_POST['gname']) {
    alert("상품명을 입력하세요.");
}

if(!$_POST['new_cate_str']) {
    alert("카테고리를 하나이상 선택하세요.");
}

// 관련상품을 우선 삭제함
sql_query(" delete from shop_goods_relation where gs_id = '$gs_id' ");

// 관련상품의 반대도 삭제
sql_query(" delete from shop_goods_relation where gs_id2 = '$gs_id' ");

// 관련상품 등록
$gs_id2 = explode(",", $gs_list);
for($i=0; $i<count($gs_id2); $i++)
{
    if(trim($gs_id2[$i]))
    {
        $sql = " insert into shop_goods_relation
					set gs_id  = '$gs_id',
						gs_id2 = '$gs_id2[$i]',
						ir_no = '$i' ";
        sql_query($sql, false);

        // 관련상품의 반대로도 등록
        $sql = " insert into shop_goods_relation
					set gs_id  = '$gs_id2[$i]',
						gs_id2 = '$gs_id',
						ir_no = '$i' ";
        sql_query($sql, false);
    }
}

// 기존 선택옵션삭제
sql_query(" delete from shop_goods_option where io_type = '0' and gs_id = '$gs_id' ");

$option_count = count($_POST['opt_id']);
if($option_count) {
    // 옵션명
    $opt = array();
    for($i=0; $i<$option_count; $i++) {
        $opt_val = explode(chr(30), $_POST['opt_id'][$i]);
    }
    $it_option_subjects = array();
    for($i = 0; $i < count($opt_val); $i++){
        array_push($it_option_subjects,$_POST['opt'.($i+1).'_subject']);
    }
    $it_option_subject = join(',', $it_option_subjects);
}

// 기존 추가옵션삭제
sql_query(" delete from shop_goods_option where io_type = '1' and gs_id = '$gs_id' ");

$supply_count = count($_POST['spl_id']);
if($supply_count) {
    // 추가옵션명
    $arr_spl = array();
    for($i=0; $i<$supply_count; $i++) {
        $spl_val = explode(chr(30), $_POST['spl_id'][$i]);
        if(!in_array($spl_val[0], $arr_spl))
            $arr_spl[] = $spl_val[0];
    }

    $it_supply_subject = implode(',', $arr_spl);
}

// 상품 정보제공
$value_array = array();
for($i=0; $i<count($_POST['ii_article']); $i++) {
    $key = $_POST['ii_article'][$i];
    $val = $_POST['ii_value'][$i];
    $value_array[$key] = $val;
}
$it_info_value = addslashes(serialize($value_array));

unset($value);
if($_POST['simg_type']) { // URL 입력
    $value['simg1'] = $_POST['simg1'];
    $value['simg2'] = $_POST['simg2'];
    $value['simg3'] = $_POST['simg3'];
    $value['simg4'] = $_POST['simg4'];
    $value['simg5'] = $_POST['simg5'];
    $value['simg6'] = $_POST['simg6'];
} else {
    for($i=1; $i<=6; $i++) {
        if($img = $_FILES['simg'.$i]['name']) {
            if(!preg_match("/\.(gif|jpg|png)$/i", $img)) {
                alert("이미지가 gif, jpg, png 파일이 아닙니다.");
            }
        }
        if($_POST['simg'.$i.'_del']) {
            $upl->del($_POST['simg'.$i.'_del']);
            $value['simg'.$i] = '';
        }
        if($_FILES['simg'.$i]['name']) {
            $value['simg'.$i] = $upl->upload($_FILES['simg'.$i]);
        }
    }
}

if($dongfile = $_FILES['dongfile']['name']) {
    if(!preg_match("/\.(mp4|ogg|webm)$/i", $dongfile)) {
        alert("동영상이 mp4, ogg, webm 파일이 아닙니다.");
    }
}
if($_POST['dongfile_del']) {
    $upl->del($_POST['dongfile_del']);
    $value['dongfile'] = '';
}
if($_FILES['dongfile']['name']) {
	$value['dongfile'] = $upl->upload($_FILES['dongfile']);
}

$value['dongurl'] = $_POST['dongurl']; //동영상 url

$value['mb_id']			= $_POST['mb_id']; //업체코드
$value['point_pay_allow'] = $_POST['point_pay_allow'];
$value['point_pay_per'] = $_POST['point_pay_per'];
$value['point_pay_point'] = $_POST['point_pay_point'];
$value['gname']			= $_POST['gname']; //상품명
$value['buy_minishop_grade']			= $_POST['buy_minishop_grade']; //가맹상품
$value['isopen']		= $_POST['isopen']; //진열상태
$value['isnaver']		= $_POST['isnaver']; //네이버최저가
$value['explan']		= $_POST['explan']; //짧은설명
$value['naver_price_url']		= $_POST['naver_price_url']; //네이버 최저가 URL
$value['keywords']		= $_POST['keywords']; //키워드
$value['icon_img']	= gnd_implode($_POST['icon_img']); //아이콘넘버
$value['admin_memo']	= $_POST['admin_memo']; //관리자메모
$value['memo']			= $_POST['memo']; //상품설명
$value['goods_price']	= conv_number($_POST['goods_price']); //판매가격
$value['goods_kv']	    = conv_number($_POST['goods_kv']); //마일리지
$value['goods_kv_per']	    = $_POST['goods_kv_per']; //마일리지
$value['supply_price']	= conv_number($_POST['supply_price']); //공급가격
$value['normal_price']	= conv_number($_POST['normal_price']); //시중가격
//$value['gpoint']		= get_gpoint($value['goods_price'],$_POST['gpoint_per'],$_POST['gpoint']);
$value['gpoint_basic'] = $_POST['gpoint_basic'];
$value['goods_kv_basic'] = $_POST['goods_kv_basic'];
$value['gpoint']		= conv_number($_POST['gpoint']);
$value['gpoint_per']		= $_POST['gpoint_per'];
$value['up_id']         = $_POST['up_id'];
$value['up_pay_value']  = conv_number($_POST['up_pay_value']);
$value['up_pay_unit']   = $_POST['up_pay_unit'];
$value['maker']			= $_POST['maker']; //제조사
$value['origin']		= $_POST['origin']; //원산지
$value['model']			= $_POST['model']; //모델명
$value['opt_subject']	= $it_option_subject; //상품 선택옵션
$value['spl_subject']	= $it_supply_subject; //상품 추가옵션
$value['ppay_type']		= $_POST['ppay_type']; //수수료적용타입
$value['ppay_rate']		= $_POST['ppay_rate']; //수수료구분
$value['ppay_fee']		= is_array($_POST['ppay_fee'])?implode(chr(30), $_POST['ppay_fee']) : '';
$value['ppay_dan']		= $_POST['ppay_dan'];
$value['stock_qty']		= conv_number($_POST['stock_qty']); //재고수량
$value['noti_qty']		= conv_number($_POST['noti_qty']); //재고 통보수량
$value['repair']		= $_POST['repair']; //A/S여부
$value['brand_uid']		= $_POST['brand_uid']; //브랜드주키
$value['brand_nm']		= get_brand($_POST['brand_uid']); //브랜드명
$value['notax']			= $_POST['notax']; //과세구분
$value['zone']			= $_POST['zone']; //판매가능지역
$value['zone_msg']		= $_POST['zone_msg']; //판매가능지역 추가설명
$value['sc_type']		= $_POST['sc_type']; //배송비 유형	0:공통설정, 1:무료, 2:조건부 무료, 3:유료
$value['sc_method']		= $_POST['sc_method']; //배송비 결제	0:선불, 1:착불, 2:사용자선택
$value['sc_amt']		= conv_number($_POST['sc_amt']); //기본 배송비
$value['sc_minimum']	= conv_number($_POST['sc_minimum']);	//조건 배송비
$value['sc_each_use']	= $_POST['sc_each_use']; //묶음배송불가
$value['info_gubun']	= $_POST['info_gubun']; //상품정보제공 구분
$value['info_value']	= $it_info_value; //상품정보제공 값
$value['info_color']	= gnd_implode($_POST['info_color']); //색상
$value['price_msg']		= $_POST['price_msg']; //가격 대체문구
$value['stock_mod']		= $_POST['stock_mod']; //수량형식
$value['odr_min']		= conv_number($_POST['odr_min']); //최소 주문한도
$value['odr_max']		= conv_number($_POST['odr_max']); //최대 주문한도
$value['buy_level']		= $_POST['buy_level']; //구매가능 레벨
$value['buy_only']		= $_POST['buy_only']; //가격공개 여부
$value['display_level']		= $_POST['display_level']; //구매가능 레벨
$value['category_name']		= $_POST['category_name']; //카테고리분류
$value['display_only']		= $_POST['display_only']; //가격공개 여부
$value['simg_type']		= $_POST['simg_type']; //이미지 등록방식
$value['sb_date']		= $_POST['sb_date']; //판매 시작일
$value['eb_date']		= $_POST['eb_date']; //판매 종료일
$value['ec_mall_pid']	= $_POST['ec_mall_pid']; //네이버쇼핑 상품ID
$value['update_time']	= MS_TIME_YMDHIS; //수정일시
$value['compare_0']		= $_POST['compare_0']; //가격비교 0
$value['compare_1']		= $_POST['compare_1']; //가격비교 1
$value['compare_2']		= $_POST['compare_2']; //가격비교 2
$value['compare_3']		= $_POST['compare_3']; //가격비교 3
$value['compare_4']		= $_POST['compare_4']; //가격비교 4
$value['compare_5']		= $_POST['compare_5']; //가격비교 5
$value['compare_6']		= $_POST['compare_6']; //가격비교 6
$value['compare_7']		= $_POST['compare_7']; //가격비교 7
$value['compare_8']		= $_POST['compare_8']; //가격비교 8
$value['compare_9']		= $_POST['compare_9']; //가격비교 9
$value['icon_text1']		= $_POST['icon_text1']; //아이콘텍스트1
$value['icon_text2']		= $_POST['icon_text2']; //아이콘텍스트2
$value['icon_text3']		= $_POST['icon_text3']; //아이콘텍스트3
$value['icon_text4']		= $_POST['icon_text4']; //아이콘텍스트4
$value['icon_text5']		= $_POST['icon_text5']; //아이콘텍스트5
$value['icon_text6']		= $_POST['icon_text6']; //아이콘텍스트6
$value['icon_text7']		= $_POST['icon_text7']; //아이콘텍스트7
$value['icon_text8']		= $_POST['icon_text8']; //아이콘텍스트8
$value['icon_text9']		= $_POST['icon_text9']; //아이콘텍스트9
$value['icon_text10']		= $_POST['icon_text10']; //아이콘텍스트10
if($_POST['compare']=="Y") {
  $value['compare'] = "Y";
} else {
  $value['compare'] = "N";
}

if($w == "") {
    $value['gcode'] = $_POST['gcode']; //상품코드
    $value['reg_time'] = MS_TIME_YMDHIS; //등록일시
    insert("shop_goods", $value);
    $gs_id = sql_insert_id();

} else if($w == "u") {
    update("shop_goods", $value," where index_no = '$gs_id'");
}

// 기존 카테고리삭제
sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' ");

// 중복되는 분류는 제거
$it_sca_tmp = "";
if($_POST['new_cate_str']) {
    $exp = explode(",", $_POST['new_cate_str']);
    $exp = array_unique($exp, SORT_STRING);
    $it_sca_tmp = implode(",", $exp);
}

// 다중 카테고리등록
if($it_sca_tmp) {
    $it_sca_list = explode(",", $it_sca_tmp);
    for($i=0; $i<count($it_sca_list); $i++) {
        $sql = " insert into shop_goods_cate
					set gcate = '".trim($it_sca_list[$i])."',
						gs_id = '$gs_id' ";
        sql_query($sql);
    }
}

// 선택옵션등록
if($option_count) {
    $comma = '';
    $sql = " insert into shop_goods_option
                    ( `io_id`, `io_type`, `gs_id`, `io_supply_price`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use`, `io_famiwel_no`, `io_famiwel_use` )
                VALUES ";
    for($i=0; $i<$option_count; $i++) {
        $sql .= $comma . " ( '{$_POST['opt_id'][$i]}', '0', '$gs_id', '{$_POST['opt_supply_price'][$i]}', '{$_POST['opt_price'][$i]}', '{$_POST['opt_stock_qty'][$i]}', '{$_POST['opt_noti_qty'][$i]}', '{$_POST['opt_use'][$i]}', '{$_POST['io_famiwel_no'][$i]}', '{$_POST['io_famiwel_use'][$i]}' )";
        $comma = ' , ';
    }
    sql_query($sql);
}

// 추가옵션등록
if($supply_count) {
    $comma = '';
    $sql = " insert into shop_goods_option
                    ( `io_id`, `io_type`, `gs_id`, `io_supply_price`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use` )
                VALUES ";
    for($i=0; $i<$supply_count; $i++) {
        $sql .= $comma . " ( '{$_POST['spl_id'][$i]}', '1', '$gs_id', '{$_POST['spl_supply_price'][$i]}', '{$_POST['spl_price'][$i]}', '{$_POST['spl_stock_qty'][$i]}', '{$_POST['spl_noti_qty'][$i]}', '{$_POST['spl_use'][$i]}' )";
        $comma = ' , ';
    }
    sql_query($sql);
}

if($w == "")
    goto_url(MS_ADMIN_URL."/goods.php?code=form&w=u&gs_id=$gs_id");
else if($w == "u")
    goto_url(MS_ADMIN_URL."/goods.php?code=form&w=u&gs_id=$gs_id$q1&page=$page&bak=$bak");
?>