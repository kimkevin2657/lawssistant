<?php
if(!defined('_MALLSET_')) exit;

if($w == "") {
	$gs['mb_id']		= encrypted_admin();
	$gs['gcode']		= time();
	$gs['sc_type']		= 0; // 배송비 유형	0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	$gs['sc_method']	= 0; // 배송비 결제	0:선불, 1:착불, 2:사용자선택
	$gs['stock_mod']	= 0;
	$gs['noti_qty']		= 999;
	$gs['simg_type']	= 0;
	$gs['isopen']		= 1;
	$gs['notax']		= 1;
	$gs['use_aff']		= 0;
	$gs['ppay_type']	= 0;
	$gs['ppay_rate']	= 0;
	$gs['zone']			= '전국';
	$gs['isnaver']		= 0;

  $compare_check = " checked";

} else if($w == "u") {
	$gs = get_goods($gs_id);
    if(!$gs)
        alert("존재하지 않은 상품 입니다.");

	$gs_id_attr = " readonly style='background-color:#ddd;'";

	if(is_null_time($gs['sb_date'])) {
		$gs['sb_date'] = '';
	}
	if(is_null_time($gs['eb_date'])) {
		$gs['eb_date'] = '';
	}
  if($gs['compare']=="Y") {
    $compare_check = " checked";
  } else {
    $compare_check = "";
  }
}

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($q_brand))			$qstr .= "&q_brand=$q_brand";
if(isset($q_zone))			$qstr .= "&q_zone=$q_zone";
if(isset($q_stock_field))	$qstr .= "&q_stock_field=$q_stock_field";
if(isset($fr_stock))		$qstr .= "&fr_stock=$fr_stock";
if(isset($to_stock))		$qstr .= "&to_stock=$to_stock";
if(isset($q_price_field))	$qstr .= "&q_price_field=$q_price_field";
if(isset($fr_price))		$qstr .= "&fr_price=$fr_price";
if(isset($to_price))		$qstr .= "&to_price=$to_price";
if(isset($q_isopen))		$qstr .= "&q_isopen=$q_isopen";
if(isset($q_option))		$qstr .= "&q_option=$q_option";
if(isset($q_supply))		$qstr .= "&q_supply=$q_supply";
if(isset($q_notax))			$qstr .= "&q_notax=$q_notax";


if($gs['use_aff']) // 가맹점 상품인가?
	$target_table = 'shop_cate_'.$gs['mb_id'];
else // 본사 상품
	$target_table = 'shop_cate';

include_once(MS_LIB_PATH."/categoryinfo.lib.php");
include_once(MS_LIB_PATH.'/goodsinfo.lib.php');
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$frm_submit = '<div class="btn_confirm">
    <input type="submit" value="저장" class="btn_large" accesskey="s">';
if($w == "u" && $bak) {
    $frm_submit .= PHP_EOL.'<a href="./goods.php?code='.$bak.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>';
	$frm_submit .= '<a href="./goods.php?code=form" class="btn_large bx-red">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';

$pg_anchor ='<ul class="anchor">
<li><a href="#anc_sitfrm_cate">카테고리</a></li>
<li><a href="#anc_sitfrm_ini">기본정보</a></li>
<li><a href="#anc_sitfrm_option">옵션정보</a></li>
<li><a href="#anc_sitfrm_cost">가격 및 재고</a></li>
<li><a href="#anc_sitfrm_sendcost">배송비</a></li>
<li><a href="#anc_sitfrm_compact">요약정보</a></li>'.PHP_EOL;
if(!$gs['use_aff'])
	$pg_anchor .='<li><a href="#anc_sitfrm_relation">관련상품</a></li>'.PHP_EOL;
$pg_anchor .='<li><a href="#anc_sitfrm_img">상품이미지</a></li>'.PHP_EOL;
$pg_anchor .='<li><a href="#anc_compare_img">가격비교</a></li>'.PHP_EOL;
$pg_anchor .= '</ul>';
?>

<script src="<?php echo MS_JS_URL; ?>/categoryform.js?ver=<?php echo MS_JS_VER; ?>"></script>

<form name="fregform" method="post" onsubmit="return fregform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="q1" value="<?php echo $qstr; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="bak" value="<?php echo $bak; ?>">
<input type="hidden" name="new_cate_str">

<section id="anc_sitfrm_cate">
<h2>카테고리</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>선택된 카테고리에 <span class="fc_084">최상위 카테고리는 대표 카테고리로 자동설정</span>되며, 최소 1개의 카테고리는 등록하셔야 합니다.</p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row" rowspan="2">카테고리</th>
		<td>
			<div class="sub_frm01">
				<table>
				<tr>
					<th scope="col" class="tac">1차 분류</th>
					<th scope="col" class="tac">2차 분류</th>
					<th scope="col" class="tac">3차 분류</th>
					<th scope="col" class="tac">4차 분류</th>
					<th scope="col" class="tac">5차 분류</th>
				</tr>
				<tr>
					<td class="w20p">
						<select name="sel_ca1" id="sel_ca1" size="10" class="multiple-select" onclick="categorychange(this.value, 2);"></select>
					</td>
					<td class="w20p">
						<select name="sel_ca2" id="sel_ca2" size="10" class="multiple-select" onclick="categorychange(this.value, 3);"></select>
					</td>
					<td class="w20p">
						<select name="sel_ca3" id="sel_ca3" size="10" class="multiple-select" onclick="categorychange(this.value, 4);"></select>
					</td>
					<td class="w20p">
						<select name="sel_ca4" id="sel_ca4" size="10" class="multiple-select" onclick="categorychange(this.value, 5);"></select>
					</td>
					<td class="w20p">
						<select name="sel_ca5" id="sel_ca5" size="10" class="multiple-select"></select>
					</td>
				</tr>
				</table>
			</div>
			<div class="btn_confirm02">
				<button type="button" class="btn_lsmall blue" onclick="category_add();">분류추가</button>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<select name="sel_ca_id" id="sel_ca_id" size="5" class="multiple-select">
			<?php
			$sql = "select *
					  from shop_goods_cate
					 where gs_id = '$gs_id'
					 order by index_no asc";
			$res = sql_query($sql);
			while($row = sql_fetch_array($res)) {
				if(!$gs['use_aff'])
					echo "<option value='$row[gcate]'>".get_move_admin($row['gcate'])."</option>\n";
				else
					echo "<option value='$row[gcate]'>".get_move_aff($row['gcate'],$gs['mb_id'])."</option>\n";
			}
			?>
			</select>
			<div class="btn_confirm02 tal">
				<button type="button" class="btn_lsmall bx-white" onclick="category_move('sel_ca_id', 'prev');">▲ 위로</button>
				<button type="button" class="btn_lsmall bx-white" onclick="category_move('sel_ca_id', 'next');">▼ 아래로</button>
				<button type="button" class="btn_lsmall frm_option_del red fr">분류삭제</button>
			</div>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_ini">
<h2>기본정보</h2>
<?php echo $pg_anchor; ?>
<?php if($w == 'u') { ?>
<div class="local_desc02 local_desc">
	<p>상품 등록일시 : <b><?php echo $gs['reg_time']; ?></b>, 최근 수정일시 : <b><?php echo $gs['update_time']; ?></b></p>
</div>
<?php } ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">업체코드</th>
		<td>
			<input type="text" name="mb_id" value="<?php echo $gs['mb_id']; ?>" required itemname="업체코드" class="required frm_input">
			<a href="./supply.php" onclick="win_open(this,'pop_supply','550','500','no');return false" class="btn_small">업체선택</a>
		</td>
	</tr>
	<tr>
		<th scope="row">상품코드</th>
		<td>
			<input type="text" name="gcode" value="<?php echo $gs['gcode']; ?>" required itemname="상품코드" class="required frm_input"<?php echo $gs_id_attr; ?>>
			<?php if($w == "u") { ?><a href="<?php echo MS_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank" class="btn_small">미리보기</a><?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">상품명</th>
		<td><input type="text" name="gname" value="<?php echo $gs['gname']; ?>" required itemname="상품명" class="required frm_input" size="80"></td>
	</tr>

	<tr>
		<th scope="row">짧은설명</th>
		<td><input type="text" name="explan" value="<?php echo $gs['explan']; ?>" class="frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">검색키워드</th>
		<td>
			<input type="text" name="keywords" value="<?php echo $gs['keywords']; ?>" class="frm_input wfull">
			<?php echo help('단어와 단어 사이는 콤마 ( , ) 로 구분하여 여러개를 입력할 수 있습니다. 예시) 빨강, 노랑, 파랑'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">아이콘</th>
		<td>
<?php 
	$arr = explode(",", $gs['icon_img']);
	for($i=1; $i<11; $i++) {
		if(in_array($i, $arr)){
			$checked = ' checked="checked"';
		}else{
			$checked = '';
		}
if(!$gs['icon_text'.$i]){
	if($i == '1'){
		$gs['icon_text'.$i] = "1111";
	}elseif($i == '2'){
		$gs['icon_text'.$i] = "222";
	}elseif($i == '3'){
		$gs['icon_text'.$i] = "3333";
	}elseif($i == '4'){
		$gs['icon_text'.$i] = "4444";
	}elseif($i == '5'){
		$gs['icon_text'.$i] = "555";
	}elseif($i == '6'){
		$gs['icon_text'.$i] = "6666";
	}elseif($i == '7'){
		$gs['icon_text'.$i] = "7777";
	}elseif($i == '8'){
		$gs['icon_text'.$i] = "8888";
	}elseif($i == '9'){
		$gs['icon_text'.$i] = "9999";
	}elseif($i == '10'){
		$gs['icon_text'.$i] = "10101010";
	}
}
?>
			<label><input type="checkbox" name="icon_img[]" value="<?php echo $i; ?>"<?php echo $checked; ?>>&nbsp;<input type="text" name="icon_text<?php echo $i; ?>" value="<?php echo $gs['icon_text'.$i]; ?>"></label>&nbsp;&nbsp;
<?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">A/S 가능여부</th>
		<td><input type="text" name="repair" value="<?php echo $gs['repair']; ?>" class="frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">브랜드</th>
		<td>
			<select name="brand_uid">
				<option value="">선택</option>
				<?php
				$sql = " select *
						   from shop_brand
						  where sho_go = '1' and br_user_yes = 0 or (br_user_yes = 1 and mb_id = '$gs[mb_id]')
							and br_logo <> '' order by br_name asc ";
				$res = sql_query($sql);
				while($row = sql_fetch_array($res)){
					echo option_selected($row['br_id'], $gs['brand_uid'], $row['br_name']);
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">모델명</th>
		<td><input type="text" name="model" value="<?php echo $gs['model']; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">생산국(원산지)</th>
		<td><input type="text" name="origin" value="<?php echo $gs['origin']; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">제조사</th>
		<td><input type="text" name="maker" value="<?php echo $gs['maker']; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">과세설정<?=$gs['notax']?></th>
		<td class="td_label">
			<?php echo radio_checked('notax', $gs['notax'], '1', '과세'); ?>
			<?php echo radio_checked('notax', $gs['notax'], '0', '면세'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">판매여부</th>
		<td class="td_label">
			<?php echo radio_checked('isopen', $gs['isopen'], '1', '진열'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '2', '품절'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '3', '단종'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '4', '중지'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">네이버 최저가</th>
		<td class="td_label">
			<?php echo radio_checked('isnaver', $gs['isnaver'], '1', '노출'); ?>
			<?php echo radio_checked('isnaver', $gs['isnaver'], '0', '미노출'); ?>&nbsp;<img src="/img/icon_new.png"> 위치 : /img/icon_new.png
		</td>
	</tr>
	<tr>
		<th scope="row">네이버쇼핑 상품ID</th>
		<td>
			<input type="text" name="ec_mall_pid" value="<?php echo $gs['ec_mall_pid']; ?>" id="ec_mall_pid" class="frm_input">
			<?php echo help("네이버쇼핑에 입점한 경우 네이버쇼핑 상품ID를 입력하시면 네이버페이와 연동됩니다.<br>일부 쇼핑몰의 경우 네이버쇼핑 상품ID 대신 쇼핑몰 상품ID를 입력해야 하는 경우가 있습니다.<br>네이버페이 연동과정에서 이 부분에 대한 안내가 이뤄지니 안내받은 대로 값을 입력하시면 됩니다."); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>
<?php include_once(MS_ADMIN_PATH.'/goods/goods_option_metabox.php'); ?>
<?php echo $frm_submit; ?>

<section id="anc_sitfrm_cost">
<h2>가격 및 재고</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">시중가격</th>
		<td colspan="3">
			<input type="text" name="normal_price" value="<?php echo number_format($gs['normal_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
			<span class="fc_197 marl5">시중에 판매되는 가격 (판매가보다 크지않으면 시중가 표시안함)</span>
		</td>
	</tr>
	<tr>
		<th scope="row">공급가격</th>
		<td colspan="3">
			<input type="text" name="supply_price" value="<?php echo number_format($gs['supply_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
			<span class="fc_197 marl5">사입처에서 공급받은 가격</span>
		</td>
	</tr>
    <tr>
		<th scope="row">판매가격</th>
		<td colspan="3">
            <?php if( defined('USE_BUY_PARTNER_GRADE') && USE_BUY_PARTNER_GRADE ) : ?><?php echo minishop::minishopLevelSelect('buy_minishop_grade', $gs['buy_minishop_grade'], "가맹상품아님", array( "onchange"=>"setGoodsPrice(this)") ); ?><script>
                function setGoodsPrice(el) {
                    var $opt = $(el).find("option:selected");
                    if( $opt.data('anewPrice') > 0 ) {
                        $("[name=goods_price]").val( $opt.data('anewPriceFormat'));
                    }
                }
            </script><?php endif; ?>
         <input type="text" name="goods_price" value="<?php echo number_format($gs['goods_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
					<a href="./naver_price_url.php?gcode=<?php echo $gs['gcode']; ?>" onclick="win_open(this,'pop_naver_price_url','550','500','no');return false" class="btn_small">최저가가져오기</a>
			<span class="fc_197 marl5">실제 판매가 입력 (대표가격으로 사용)</span>
		</td> 
	</tr>
<?php
$tt = sql_fetch(" select count(*) as cnt from naver_list where gcode='{$gs['gcode']}'"); 
if($tt['cnt'] > 0){
?>
    <tr>
		<th scope="row">네이버최저가</th>
		<td colspan="3">
<?php
	$sql1 = " select * from naver_list where gcode='{$gs['gcode']}' order by price asc limit 5";
	$result1 = sql_query($sql1);
	for($i=0; $row1=sql_fetch_array($result1); $i++) {
		echo $row1["img_com"].":";
		echo $row1["price"]-$row1["delivery"]."원&nbsp;|&nbsp;&nbsp;&nbsp;";
	}
?><a href="<?php echo $gs['naver_price_url']; ?>" target="_blank" class="btn_small">URL확인하기</a>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<th scope="row">네이버최저가URL</th>
		<td><input type="text" name="naver_price_url" value="<?php echo $gs['naver_price_url']; ?>" class="frm_input" size="150">
			<?php echo help('https까지 모두 입력하셔야합니다. https://search.shopping.naver.com (O), search.shopping.naver.com (X)'); ?></td>
	</tr>
    <tr> 
     <th scope="row">마일리지 적립</th>
        <td colspan="3">
            <input type="text" name="goods_kv" value="<?php echo number_format($gs['goods_kv']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
			<input type="text" name="goods_kv_per" class="frm_input w50" value="<?php echo number_format($gs['goods_kv_per']); ?>"> %&nbsp;&nbsp;<label><input type="checkbox" name="goods_kv_basic" value="1" <?if($gs['goods_kv_basic']=="1")		echo " checked";?>>마일리지 개별 적용</label>
			<span class="fc_197 marl5">원과 % 둘다 입력되어있는 경우 %를 기준으로합니다.</span>


            <!--<span class="fc_197 marl5">공급가격, 판매가격 수정시 자동으로 수정 됩니다.</span>
            <script>
                function calcGoodsKv(){
                    var supply_price = parseInt($('[name=supply_price]').val().replace(/[^0-9]/g, ''));
                    var goods_price  = parseInt($('[name=goods_price]').val().replace(/[^0-9]/g, ''));
                    if( goods_price > supply_price ) $('[name=goods_kv]').val( commaStr( new String(Math.round((goods_price - supply_price)*0.4))) ); // 40%
                }
            </script>
        </td> 
    </tr> -->
	<tr> 
		<th scope="row">쇼핑포인트 적립</th>
		<td colspan="3">
			<input type="text" name="gpoint" value="<?php echo number_format($gs['gpoint']); ?>" class="frm_input w80" onkeyup="addComma(this);"> P
			<input type="text" name="gpoint_per" class="frm_input w50" value="<?php echo number_format($gs['gpoint_per']); ?>"> %&nbsp;&nbsp;<label><input type="checkbox" name="gpoint_basic" value="1"
			<?php if($gs['gpoint_basic']=="1"){	echo " checked"; }?>>쇼핑포인트 개별 적용</label>
			<span class="fc_197 marl5">P와 % 둘다 입력되어있는 경우 %를 기준으로합니다.</span>
		</td>
	</tr>
    <tr>
        <th scope="row">쇼핑포인트결제 허용</th>
        <td colspan="3">
            <input type="hidden" name="point_pay_allow" id="point_pay_allow" value="<?php echo $gs['point_pay_allow']?>">
            <input type="checkbox" id="point_pay_allow_checker" name="point_pay_allow_checker" <?php if( $gs['point_pay_allow'] == '1' || $w=='') echo ' checked="checked" '; ?> value="1">
            <label for="point_pay_allow_checker">쇼핑포인트결제 허용</label>

            <label for="point_pay_point" class="marl30">사용쇼핑포인트</label>
            <input type="number" name="point_pay_point" id="point_pay_point" class="frm_input w80" size="10" value="<?php echo $gs['point_pay_point']; ?>">P
            <input type="number" name="point_pay_per" id="point_pay_per" class="frm_input w50" size="2" value="<?php echo $gs['point_pay_per']; ?>">%
			<span class="fc_197 marl5">P와 % 둘다 입력되어있는 경우 %를 기준으로합니다.</span>
            <script>
                (function($){
                    $(document).on('ready', function(){
                        $('#point_pay_allow_checker').on('click', function(){
                            $('#point_pay_allow').val( $(this).is(':checked') ? 1 : 0);
                        });
                    });
                }(jQuery));
            </script>
        </td>
    </tr>
    <tr>
    <!--    <th scope="row"><?php echo('추천ID'); ?></th>
        <td><input type="text" _readonly placeholder="ID" class="frm_input" _required="required" id="up_id" name="up_id" value="<?php echo $gs['up_id']; ?>">
            <a href="./seller/seller_reglist.php?target=up_id" onclick="win_open(this,'seller_reglist','550','500','1'); return false" class="btn_small grey">회원검색</a><script>
                var setUser = function(mb){
                    $('#up_id').val(mb.plain_id);
                }
            </script></td>
        <th scope="row"><?php echo('추천판매수수료'); ?></th>
        <td>
            <select id="up_pay_value" name="up_pay_value">
                <?php for($i = 0; $i <= 10; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php echo $gs['up_pay_value'] == $i ? ' selected' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <select name="up_pay_unit" id="up_pay_unit">
                <option value="%">%</option>
                <?php if( false ) : ?>
                    <option value="P"<?php echo $gs['up_pay_unit'] == 'P' ? ' selected': ''; ?>><?php echo CURRENCY_UNIT; ?></option>
                <?php endif; ?>
            </select>
            (판매수수료를 개별적으로 추가 적립하실 수 있습니다.)
        </td>
    </tr>
	<tr> -->
		<th scope="row">가격 대체문구</th>
		<td colspan="3">
			<input type="text" name="price_msg" value="<?php echo $gs['price_msg']; ?>" class="frm_input">
			<span class="fc_197 marl5">가격대신 보여질 문구를 노출할 때 입력, 주문불가</span>
		</td>
	</tr>
	<tr>
		<th scope="row">수량</th>
		<td colspan="3">
			<input type="radio" name="stock_mod" value="0" id="ids_stock_mode1"<?php echo get_checked('0', $gs['stock_mod']); ?> onclick="chk_stock(0);">
			<label for="ids_stock_mode1" class="marr10">무제한</label>
			<input type="radio" name="stock_mod" value="1" id="ids_stock_mode2"<?php echo get_checked('1', $gs['stock_mod']); ?> onclick="chk_stock(1);">
			<label for="ids_stock_mode2">한정</label>
			<input type="text" name="stock_qty" value="<?php echo number_format($gs['stock_qty']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 개,
			<b class="marl10">재고 통보수량</b> <input type="text" name="noti_qty" value="<?php echo number_format($gs['noti_qty']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 개
			<p class="fc_197 mart7">상품의 재고가 통보수량보다 작을 때 상품 재고관리에 표시됩니다.<br>옵션이 있는 상품은 개별 옵션의 통보수량이 적용됩니다. 설정이 무제한이면 재고관리에 표시되지 않습니다.</p>
		</td>
	</tr>
	<tr>
		<th scope="row">주문한도</th>
		<td colspan="3">
			최소 <input type="text" name="odr_min" value="<?php echo $gs['odr_min']; ?>" class="frm_input w80"> ~
			최대 <input type="text" name="odr_max" value="<?php echo $gs['odr_max']; ?>" class="frm_input w80">
			<span class="fc_197 marl5">미입력시 무제한</span>
		</td>
	</tr>
	<tr>
		<th scope="row">판매기간 설정</th>
		<td colspan="3">
			<label for="sb_date" class="sound_only">시작일</label>
			<input type="text" name="sb_date" value="<?php echo $gs['sb_date']; ?>" id="sb_date" class="frm_input w80" maxlength="10"> ~
			<label for="eb_date" class="sound_only">종료일</label>
			<input type="text" name="eb_date" value="<?php echo $gs['eb_date']; ?>" id="eb_date" class="frm_input w80" maxlength="10">
			<a href="javascript:void(0);" class="btn_small is_reset">기간초기화</a>
			<div class="fc_197 mart7">
				설정된 기간 동안만 판매 가능하며, 설정된 종료일 이후에는 판매되지 않습니다.<br>
				일시 판매중지 처리하실 경우, 종료일을 현재날짜 이전의 과거 날짜를 넣어주시면 됩니다.
			</div>
			<script>
			$(function(){
				// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
				$("#sb_date,#eb_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});

				// 기간초기화
				$(document).on("click", ".is_reset", function() {
					$("#sb_date, #eb_date").val("");
				});
			});
			</script>
		</td>
	</tr>
	<tr>
		<th scope="row">구매가능 레벨</th>
		<td colspan="3">
			<?php echo get_goods_level_select('buy_level', $gs['buy_level']); ?>
			<label class="marl5"><input type="checkbox" name="buy_only" value="1"<?php echo get_checked('1', $gs['buy_only']); ?>> 현재 레벨이상 가격공개</label>
		</td>
	</tr>
	<tr>
		<th scope="row">검색시 공개 레벨</th>
		<td colspan="3">
			<?php echo get_goods_level_select('display_level', $gs['display_level']); ?>
			<select name="category_name">
				<option value="">전체</option>
				<?
					$sql2 = sql_query("SELECT * FROM category_manage");
					while($row2 = sql_fetch_array($sql2)){
				?>
				<option value="<? echo $row2['category_name']; ?>" <? echo $row2['category_name'] == $gs['category_name'] ? "selected":""; ?> ><? echo $row2['category_name']; ?></option>
				<? } ?>
			</select>
		</td>
	</tr>

	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_pay">
<h2>가맹점수수료</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">수수료 적용타입</th>
		<td>
			<select name="ppay_type" onChange="chk_ppay_type(this.value);">
				<?php echo option_selected('0', $gs['ppay_type'], '공통설정'); ?>
				<?php echo option_selected('1', $gs['ppay_type'], '개별설정'); ?>
			</select>
			<a href="./minishop.php?code=pbasic" target="_blank" class="btn_small grey">설정</a>
		</td>
	</tr>
	<tr>
		<th scope="row">수수료 적립단계</th>
		<td>
			<select name="ppay_rate">
				<?php echo option_selected('0', $gs['ppay_rate'], '퍼센트로 적립'); ?>
				<?php echo option_selected('1', $gs['ppay_rate'], '금액으로 적립'); ?>
			</select>
			<input type="text" name="ppay_dan" value="<?php echo $gs['ppay_dan']; ?>" onkeyup="chk_ppay_dan(this.value,'<?php echo $gs_id; ?>')" class="frm_input w50"> <span>단계</span>
		</td>
	</tr>
	<tr>
		<th scope="row">수수료입력</th>
		<td><span id="chk_ppay_auto"><span></td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_sendcost"> 
<h2>배송비</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>※ <span>참고사항) : 고객이 동일 판매자의 상품을 복수 구매시 배송비는 단 한번만 부과 됩니다. 단! 배송비는 가장 큰값을 산출하여 적용 됩니다.</span></p>
	<p>※ <span>조건부무료배송) : 고객이 동일 판매자의 상품을 복수 구매시 가장 큰 값의 (조건 배송비) 금액을 산출하여 최종배송비가 자동 적용 됩니다.</span></p>
	<p>※ <span>유료배송) : 고객이 동일 판매자의 상품을 복수 구매시 가장 큰 값의 (기본 배송비) 금액을 산출하여 최종배송비가 자동 적용 됩니다.</span></p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">배송정보</th>
		<td>
			<select name="sc_type" onChange="chk_sc_type(this.value);">
				<?php echo option_selected('0', $gs['sc_type'], '공통설정'); ?>
				<?php echo option_selected('1', $gs['sc_type'], '무료배송'); ?>
				<?php echo option_selected('2', $gs['sc_type'], '조건부무료배송'); ?>
				<?php echo option_selected('3', $gs['sc_type'], '유료배송'); ?>
			</select>
			<a href="./config.php?code=baesong" target="_blank" class="btn_small grey">설정</a>
			<div id="sc_method" class="mart7">
				배송비결제
				<select name="sc_method" class="marl10">
				<?php echo option_selected('0', $gs['sc_method'], '선불'); ?>
				<?php echo option_selected('1', $gs['sc_method'], '착불'); ?>
				<?php echo option_selected('2', $gs['sc_method'], '사용자선택'); ?>
				</select>
			</div>
			<div id="sc_amt" class="padt5">
				기본배송비 <input type="text" name="sc_amt" value="<?php echo number_format($gs['sc_amt']); ?>" class="frm_input w80 marl10" onkeyup="addComma(this);"> 원
				<label class="marl10"><input type="checkbox" name="sc_each_use" value="1"<?php echo get_checked('1', $gs['sc_each_use']); ?>> 묶음배송불가</label>
			</div>
			<div id="sc_minimum" class="padt5">
				조건배송비 <input type="text" name="sc_minimum" value="<?php echo number_format($gs['sc_minimum']); ?>" class="frm_input w80 marl10" onkeyup="addComma(this);"> 원 이상이면 무료배송
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row">배송가능 지역</th>
		<td>
			<select name="zone">
				<?php echo option_selected('전국', $gs['zone'], '전국'); ?>
				<?php echo option_selected('강원도', $gs['zone'], '강원도'); ?>
				<?php echo option_selected('경기도', $gs['zone'], '경기도'); ?>
				<?php echo option_selected('경상도', $gs['zone'], '경상도'); ?>
				<?php echo option_selected('서울/경기도', $gs['zone'], '서울/경기도'); ?>
				<?php echo option_selected('서울특별시', $gs['zone'], '서울특별시'); ?>
				<?php echo option_selected('전라도', $gs['zone'], '전라도'); ?>
				<?php echo option_selected('제주도', $gs['zone'], '제주도'); ?>
				<?php echo option_selected('충청도', $gs['zone'], '충청도'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">추가설명</th>
		<td><input type="text" name="zone_msg" value="<?php echo $gs['zone_msg']; ?>" class="frm_input" size="50" placeholder="예 : 제주 (도서지역 제외)"></td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_compact">
<h2>요약정보</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p><strong>전자상거래 등에서의 상품 등의 정보제공에 관한 고시</strong>에 따라 총 35개 상품군에 대해 상품 특성 등을 양식에 따라 입력할 수 있습니다.</p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">상품군 선택</th>
		<td>
			<select name="info_gubun" id="info_gubun">
				<option value="">상품군 카테고리 선택</option>
				<?php
				if(!$gs['info_gubun']) $gs['info_gubun'] = 'etc';
				foreach($item_info as $key=>$value) {
					$opt_value = $key;
					$opt_text  = $value['title'];
					echo '<option value="'.$opt_value.'" '.get_selected($opt_value, $gs['info_gubun']).'>'.$opt_text.'</option>'.PHP_EOL;
				}
				?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<script>
$(function(){
	// 상품정보제공 상품군선택
	$(document).on("change", "#info_gubun", function() {
		var gubun = $(this).val();
		$.post(
			tb_admin_url+"/goods/goods_info.php",
			{ gs_id: "<?php echo $gs['index_no']; ?>", gubun: gubun },
			function(data) {
				$("#sit_compact_fields").empty().html(data);
			}
		);
	});
});
</script>
<div id="sit_compact_fields" class="tbl_frm02 mart7">
	<?php include_once(MS_ADMIN_PATH.'/goods/goods_info.php'); ?>
</div>
</section>

<?php echo $frm_submit; ?>

<?php if(!$gs['use_aff']) { ?>
<section id="anc_sitfrm_relation">
<h2>관련상품</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>
		<span class="fc_red">관련상품은 본사 상품만 등록가능하며, 가맹점 상품은 등록하실 수 없습니다.</span><br>
		등록된 전체상품 목록에서 카테고리를 선택하면 해당 상품 리스트가 연이어 나타납니다.<br>
		상품리스트에서 관련 상품으로 추가하시면 선택된 관련상품 목록에 <strong>함께</strong> 추가됩니다.<br>
		예를 들어, A 상품에 B 상품을 관련상품으로 등록하면 B 상품에도 A 상품이 관련상품으로 자동 추가되며, <strong>저장 버튼을 누르셔야 정상 반영됩니다.</strong>
	</p>
</div>
<div class="srel">
	<div class="compare_wrap">
		<section class="compare_left">
			<h3>등록된 전체상품 목록</h3>
			<label for="sch_relation" class="sound_only">카테고리</label>
			<span class="srel_pad">
				<?php echo get_goods_sca_select('sch_relation'); ?>
				<label for="sch_name" class="sound_only">상품명</label>
				<input type="text" name="sch_name" id="sch_name" class="frm_input" size="15">
				<button type="button" id="btn_search_item" class="btn_small">검색</button>
			</span>
			<div id="relation" class="srel_list">
				<p>카테고리를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>
			</div>
			<script>
			$(function() {
				$("#btn_search_item").click(function() {
					var gcate = $("#sch_relation").val();
					var gname = $.trim($("#sch_name").val());
					var $relation = $("#relation");

					if(gcate == "" && gname == "") {
						$relation.html("<p>카테고리를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>");
						return false;
					}

					$("#relation").load(
						tb_admin_url+"/goods/goods_form_relation.php",
						{ gs_id: "<?php echo $gs_id; ?>", gcate: gcate, gname: gname }
					);
				});

				$(document).on("click", "#relation .add_item", function() {
					// 이미 등록된 상품인지 체크
					var $li = $(this).closest("li");
					var gs_id = $li.find("input:hidden").val();
					var gs_id2;
					var dup = false;
					$("#reg_relation input[name='re_gs_id[]']").each(function() {
						gs_id2 = $(this).val();
						if(gs_id == gs_id2) {
							dup = true;
							return false;
						}
					});

					if(dup) {
						alert("이미 선택된 상품입니다.");
						return false;
					}

					var cont = "<li>"+$li.html().replace("add_item", "del_item").replace("추가", "삭제")+"</li>";
					var count = $("#reg_relation li").size();

					if(count > 0) {
						$("#reg_relation li:last").after(cont);
					} else {
						$("#reg_relation").html("<ul>"+cont+"</ul>");
					}

					$li.remove();
				});

				$(document).on("click", "#reg_relation .del_item", function() {
					// if(!confirm("상품을 삭제하시겠습니까?"))
					//    return false;

					$(this).closest("li").remove();

					var count = $("#reg_relation li").size();
					if(count < 1)
						$("#reg_relation").html("<p>선택된 상품이 없습니다.</p>");
				});
			});
			</script>
		</section>

		<section class="compare_right">
			<h3>선택된 관련상품 목록</h3>
			<span class="srel_pad"></span>
			<div id="reg_relation" class="srel_sel">
				<?php
				$str = array();
				$sql = " select b.index_no, b.gname, b.simg1
						   from shop_goods_relation a left join shop_goods b ON (a.gs_id2=b.index_no)
						  where a.gs_id = '$gs_id'
						  order by ir_no asc ";
				$result = sql_query($sql);
				for($g=0; $row=sql_fetch_array($result); $g++)
				{
					$gname = get_it_image($row['index_no'], $row['simg1'], 50, 50).' '.$row['gname'];

					if($g==0)
						echo '<ul>';
				?>
					<li>
						<input type="hidden" name="re_gs_id[]" value="<?php echo $row['index_no']; ?>">
						<div class="list_item"><?php echo $gname; ?></div>
						<div class="list_item_btn"><button type="button" class="del_item btn_small">삭제</button></div>
					</li>
				<?php
					$str[] = $row['index_no'];
				}
				$str = implode(",", $str);

				if($g > 0)
					echo '</ul>';
				else
					echo '<p>선택된 상품이 없습니다.</p>';
				?>
			</div>
			<input type="hidden" name="gs_list" value="<?php echo $str; ?>">
		</section>
	</div>
</div>
</section>

<?php echo $frm_submit; ?>
<?php } ?>

<section id="anc_sitfrm_img">
<h2>상품이미지 및 상세정보</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">동영상 url주소</th>
		<td class="td_label">
			<input type="text" name="dongurl" class="frm_input" size="100" value="<?php echo $gs['dongurl']; ?>" placeholder="http://">
		</td>
	</tr>
	<tr>
		<th scope="row">동영상 첨부</th>
		<td class="td_label">
			<input type="file" name="dongfile">
			<?php echo get_look_ahead($gs['dongfile'], "dongfile_del"); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">이미지 등록방식</th>
		<td class="td_label">
			<input type="radio" name="simg_type" id="simg_type_1" value="0"<?php echo get_checked('0', $gs['simg_type']); ?> onclick="chk_simg_type(0);">
			<label for="simg_type_1">직접 업로드</label>
			<input type="radio" name="simg_type" id="simg_type_2" value="1"<?php echo get_checked('1', $gs['simg_type']); ?> onclick="chk_simg_type(1);">
			<label for="simg_type_2">URL 입력</label>
		</td>
	</tr>
	<?php
	for($i=1; $i<=6; $i++) {
		if($i == 1) {
			$item_wpx = $default['de_item_small_wpx'];
			$item_hpx = $default['de_item_small_hpx'];
		} else {
			$item_wpx = $default['de_item_medium_wpx'];
			$item_hpx = $default['de_item_medium_hpx'];
		}
	?>
	<tr class="item_img_fld">
		<th scope="row">이미지<?php echo $i; ?> <span class="fc_197">(<?php echo $item_wpx; ?> * <?php echo $item_hpx; ?>)</span></th>
		<td>
			<div class="item_file_fld">
				<input type="file" name="simg<?php echo $i; ?>">
				<?php echo get_look_ahead($gs['simg'.$i], "simg{$i}_del"); ?>
			</div>
			<div class="item_url_fld">
				<input type="text" name="simg<?php echo $i; ?>" value="<?php echo $gs['simg'.$i]; ?>" class="frm_input" size="80" placeholder="http://">
			</div>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">상세설명</th>
		<td>
			<?php echo editor_html('memo', get_text(stripcslashes($gs['memo']), 0)); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">관리자메모</th>
		<td><textarea name="admin_memo" class="frm_textbox"><?php echo $gs['admin_memo']; ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_compare_img">
<h2>가격비교</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>항목과 항목사이에는 ">" 기호로 구분해주세요 (예:지마켓>제주 삼다수 생수 2L 1병>2리터/수량:1병/로켓배송 상품 6병당 배송비 추가>3000원>2500원>2만원이상 구매시 무료배송)</p>
  <p style="color:#ff0000;">입력항목 : 쇼핑몰명>상품명>상품정보>판매가>배송비>기타정보</p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">가격비교 노출</th>
		<td><input type="checkbox" name="compare" id="compare" value="Y"<?php echo $compare_check; ?>></td>
	</tr>
	</tbody>
	</table>
</div>
<div id="sit_compact_fields" class="tbl_frm02 mart7">
<table>
<colgroup>
	<col class="w180">
	<col>
</colgroup>
<tbody>
<tr>
	<th scope="row">가격비교 1</th>
	<td><textarea name="compare_0" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_0']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 2</th>
	<td><textarea name="compare_1" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_1']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 3</th>
	<td><textarea name="compare_2" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_2']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 4</th>
	<td><textarea name="compare_3" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_3']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 5</th>
	<td><textarea name="compare_4" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_4']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 6</th>
	<td><textarea name="compare_5" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_5']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 7</th>
	<td><textarea name="compare_6" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_6']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 8</th>
	<td><textarea name="compare_7" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_7']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 9</th>
	<td><textarea name="compare_8" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_8']; ?></textarea></td>
</tr>
<tr>
	<th scope="row">가격비교 10</th>
	<td><textarea name="compare_9" class="frm_textbox" style="height:50px;"><?php echo $gs['compare_9']; ?></textarea></td>
</tr>
</tbody>
</table>
</div>
</section>

<?php echo $frm_submit; ?>
</form>

<script>
function fregform_submit(f) {
	var f = document.fregform;

	// 다중분류처리
	var multi_caid = new Array();
	var gcate_list = ca_id = "";

	$("select#sel_ca_id option").each(function() {
        ca_id = $(this).val();
        if(ca_id == "")
            return true;

        multi_caid.push(ca_id);
    });

    if(multi_caid.length > 0)
        gcate_list = multi_caid.join();

    $("input[name=new_cate_str]").val(gcate_list);

	if(!f.new_cate_str.value) {
        alert("카테고리를 하나이상 선택하세요.");
        return false;
    }

	<?php if(!$gs['use_aff']) { ?>
	var item = new Array();
    var re_item = gs_id = "";

    $("#reg_relation input[name='re_gs_id[]']").each(function() {
        gs_id = $(this).val();
        if(gs_id == "")
            return true;

        item.push(gs_id);
    });

    if(item.length > 0)
        re_item = item.join();

    $("input[name=gs_list]").val(re_item);
	<?php } ?>

	<?php echo get_editor_js('memo'); ?>

	f.action = "./goods/goods_form_update.php";
    return true;
}

// 배송비 설정
function chk_sc_type(ergFun) {
	var f = document.fregform;
	switch (ergFun) {
		case "0" : // 공통설정
			eval('sc_amt').style.display = 'none';
			eval('sc_minimum').style.display = 'none';
			eval('sc_method').style.display = 'block';
			f.sc_amt.disabled = true;
			f.sc_minimum.disabled = true;
			f.sc_method.disabled = false;
			break;
		case "1" : // 무료배송
			eval('sc_amt').style.display = 'none';
			eval('sc_minimum').style.display = 'none';
			eval('sc_method').style.display = 'none';
			f.sc_amt.disabled = true;
			f.sc_minimum.disabled = true;
			f.sc_method.disabled = true;
			break;
		case "2" : // 조건부무료배송
			eval('sc_amt').style.display = 'block';
			eval('sc_minimum').style.display = 'block';
			eval('sc_method').style.display = 'block';
			f.sc_amt.disabled = false;
			f.sc_minimum.disabled = false;
			f.sc_method.disabled = false;
			break;
		case "3" : // 유료배송
			eval('sc_amt').style.display = 'block';
			eval('sc_minimum').style.display = 'none';
			eval('sc_method').style.display = 'block';
			f.sc_amt.disabled = false;
			f.sc_minimum.disabled = true;
			f.sc_method.disabled = false;
			break;
	}
}

//수수료 적용
function chk_ppay_type(argFun) {
	var f = document.fregform;
	switch (argFun) {
		case "0" :
			f.ppay_dan.disabled = true;
			f.ppay_dan.style.backgroundColor = "dddddd";
			f.ppay_rate.disabled = true;
			eval("chk_ppay_auto").innerHTML = "가맹점관리 > 가맹점 수수료정책 (기본설정 사용중)";
			break;
		case "1" :
			f.ppay_dan.disabled = false;
			f.ppay_dan.style.backgroundColor = "";
			f.ppay_rate.disabled = false;
			eval("chk_ppay_auto").innerHTML = "수수료를 적용할 단계를 입력하세요!";
			break;
	}
}

// 이미지 등록방식
function chk_simg_type(n) {
	if(n == 0) { // 직접업로드
		$(".item_file_fld").show();
		$(".item_url_fld").hide();
	} else { // URL 입력
		$(".item_img_fld").show();
		$(".item_file_fld").hide();
		$(".item_url_fld").show();
	}
}

function chk_ppay_dan(no, index){
	$.post(
		tb_admin_url+"/goods/goods_form_auto.php",
		{ "no": no, "index": index },
		function(data) {
			$("#chk_ppay_auto").empty().html(data);
		}
	);
}

// 재고수량 체크
function chk_stock(n) {
	var f = document.fregform;

	if(n == 0) {
		f.stock_qty.disabled = true;
		f.noti_qty.disabled = true;
		f.stock_qty.style.backgroundColor = "dddddd";
		f.noti_qty.style.backgroundColor = "dddddd";
	} else {
		f.stock_qty.disabled = false;
		f.noti_qty.disabled = false;
		f.stock_qty.style.backgroundColor = "";
		f.noti_qty.style.backgroundColor = "";
	}
}
</script>

<script>
chk_sc_type('<?php echo $gs[sc_type]; ?>');
chk_simg_type('<?php echo $gs[simg_type]; ?>');
chk_stock('<?php echo $gs[stock_mod]; ?>');
chk_ppay_type('<?php echo $gs[ppay_type]; ?>');
<?php if($gs[ppay_dan]) { ?>
chk_ppay_dan("<?php echo $gs[ppay_dan]; ?>","<?php echo $gs[index_no]; ?>");
<?php } ?>
category_first_select();
</script>
