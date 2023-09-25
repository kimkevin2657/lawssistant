<?php
if(!defined('_MALLSET_')) exit;
?>

<script src="<?php echo MS_JS_URL; ?>/shop.js"></script>

<form name="fbuyform" id="fbuyform" method="post">
<input type="hidden" name="gs_id[]" value="<?php echo $index_no; ?>">
<input type="hidden" id="it_price" value="<?php echo get_sale_price($index_no); ?>">
<?php
if( ($gs['point_pay_allow'] || $gs['point_pay_max'] || $gs['point_pay_per']) && Good::usablePoint($gs)) : ?>
<input type="hidden" id="it_price2" value="<?php echo Good::usablePoint($gs); ?>">
<?php endif; ?>
<input type="hidden" name="ca_id" value="<?php echo $ca['gcate']; ?>">
<input type="hidden" name="sw_direct">

<p class="tit_navi marb15"><?php echo $navi; ?></p>
<div class="vi_info">
	<div class="vi_img_bx" style="width:<?php echo $default['de_item_medium_wpx']; ?>px">
		<?php if($is_social_ing) { Theme::get_theme_part(MS_THEME_PATH,'/time.skin.php'); } ?>
		<?php if($is_social_end) { ?><div class="t_social"><?php echo $is_social_txt; ?></div><?php } ?>

		<div class="bimg">
			<?php echo get_it_image($index_no, $gs['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx'], "id='big'"); ?>
		</div>
		<div class="simg_li">
			<ul>
				<?php
				for($i=0; $i<=6; $i++) {
					$it_image = $gs['simg'.$i];
					if(!$it_image) continue;
					$thumbnails = get_it_image_url($index_no, $it_image, $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
				?>
				<li><img src="<?php echo $thumbnails; ?>" onmouseover="document.all['big'].src='<?php echo $thumbnails; ?>'"></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="vi_txt_bx">
		<h2 class="tit">
			<?php echo $gs['gname']; ?>
			<?php if(is_admin()) { ?><a href="<?php echo MS_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $index_no; ?>" target="_blank" class="btn_small red">수정</a><?php } ?>
			<?php if($gs['explan']) { ?>
			<p class="stxt"><?php echo $gs['explan']; ?></p>
			<?php } ?>
		</h2>

		<div class="price_bx">
			<?php if(!$is_pr_msg && !$is_buy_only && !$is_soldout && $gs['normal_price']) { ?>
			<dl>
				<dt>시중가격</dt>
				<dd class="f_price"><?php echo display_price2($gs['normal_price']); ?></dd>
			</dl>
			<?php } ?>
			<dl>
				<dt class="padt5">판매가격</dt>
				<dd class="price"><?php echo get_price($index_no); ?></dd>
			</dl>

            <?php
            if( ($gs['point_pay_allow'] || $gs['point_pay_max'] || $gs['point_pay_per']) && Good::usablePoint($gs)) : ?>
                <dl>
                    <dt class="padt5 fc_red"><?php echo('쇼핑포인트 할인(%)'); ?></dt>
                    <dd class="price fc_red"><?php echo Good::displayUsablePoint($gs); ?></dd>
                </dl>
            <?php endif; ?>
            <style>
                .static{position:absolute;}
            </style>

            <?php
            if ( false ) :
            $usablePoint = $gs['point_pay_per'] ? $gs['goods_price'] / 100 * $gs['point_pay_per'] : $gs['point_pay_max'] ;
            if( $gs['point_pay_allow'] && $usablePoint > 0) : ?>
            <dl>
                <dt class="padt5 fc_red">쇼핑포인트 전액사용</dt>
                <dd class="price fc_red"><?php echo display_price2($usablePoint, '('.ceil( $usablePoint / $gs['goods_price'] * 100 ).'%)'); ?></dd>
            </dl>
            <?php endif;
            endif;
            ?>
			<?php if( false && is_minishop($member['id']) && $config['pf_payment_yes']) { ?>
			<dl>
				<dt class="padt5">판매수익</dt>
				<dd class="pay"><?php echo display_price2(get_payment($index_no)); ?></dd>
			</dl>
			<?php } ?>
		</div>

		<div class="vi_txt_li">
             <?php if( $config['usekv_yes'] ) : ?>
                <dl>
                    <dt class="fc_197"><?php echo('마일리지 적립'); ?></dt>
                    <dd class="fc_197"><?php echo $goods_kv; ?></dd>
                </dl>
            <?php endif; ?>	
			<?php //if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $gpoint) { ?> 
			<dl>
				<dt style="color:#ff6868">쇼핑포인트 적립</li>
				<dd style="color:#ff6868"><?php echo $gpoint; ?></dd>
			</dl>
			<?php //} ?>			
			<dl>
				<dt>상품코드</li>
				<dd><?php echo $gs['gcode']; ?></dd>
			</dl>
			<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $cp_used) { ?>
			<dl>
				<dt>쿠폰발급</dt>
				<dd><?php echo $cp_btn; ?></dd>
			</dl>
			<?php } ?>
			<?php if($gs['maker']) { ?>
			<dl>
				<dt>제조사</dt>
				<dd><?php echo $gs['maker']; ?></dd>
			</dl>
			<?php } ?>
			<?php if($gs['origin']) { ?>
			<dl>
				<dt>원산지</dt>
				<dd><?php echo $gs['origin']; ?></dd>
			</dl>
			<?php } ?>
			<?php if($gs['brand_nm']) { ?>
			<dl>
				<dt>브랜드</dt>
				<dd><?php echo $gs['brand_nm']; ?></dd>
			</dl>
			<?php } ?>
			<?php if($gs['model']) { ?>
			<dl>
				<dt>모델명</dt>
				<dd><?php echo $gs['model']; ?></dd>
			</dl>
			<?php } ?>

			<dl>
				<dt>배송비</dt>
				<dd><?php echo get_sendcost_amt(); ?></dd>
			</dl>
			<dl>
				<dt>배송가능지역</dt>
				<dd><?php echo $gs['zone']; ?> <?php echo $gs['zone_msg']; ?></dd>
			</dl>
			<dl>
				<dt>고객상품평</dt>
				<dd>상품평 : <?php echo $item_use_count; ?>건, 평점 : <img src="<?php echo MS_IMG_URL; ?>/sub/view_score_<?php echo $star_score; ?>.gif"></dd>
			</dl>
			<dl>
				<!--<dt>상품URL 소셜 공유</dt>
				<dd><?php echo $sns_share_links; ?></dd>
			</dl> -->
			<?php if($gs['odr_min']) { ?>
			<dl>
				<dt>최소구매수량</dt>
				<dd><?php echo display_qty($gs['odr_min']); ?></dd>
			</dl>
			<?php } ?>
			<?php if($gs['odr_max']) { ?>
			<dl>
				<dt>최대구매수량</dt>
				<dd><?php echo display_qty($gs['odr_max']); ?></dd>
			</dl>
			<?php } ?>
             <dl>
            <dt>네이버 최저가</dt>
				<dd><?php
				$tt = sql_fetch(" select * from naver_list where gcode='{$gs['gcode']}' order by price asc limit 1");  
					if(!$tt["price"]){ echo "오마켓전용"; }else{
					echo number_format($tt["price"]-$tt["delivery"])."원"; } ?></dd>
                <!--dd><?php echo substr($gs['update_time'],2,8); ?></dd-->
			</dl>
             <dl>
            <dt>상품 업데이트 일자</dt>
				<dd><?php echo $gs['update_time']; ?></dd>
                <!--dd><?php echo substr($gs['update_time'],2,8); ?></dd-->
			</dl> 
			<?php if($member['grade'] < '7'){ ?>
            <dl>
            <dt>상품 판매정책</dt>
                <div class= "icon_txt">
				<?php 
				$arr = explode(",", $gs['icon_img']);
				for($a=1; $a<11; $a++) {
					$z = $a -1;
					if($arr[$z] != ""){
						if($a == '1'){
							echo "<span class= \"interest\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '2'){
							echo "<span class= \"mdchoice\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '3'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '4'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '5'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '6'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '7'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '8'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '9'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '10'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
					}
				}
				?> 
                </div> 
			</dl>
			<?php } ?>			
          </div>
		<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout) { ?>
		<?php if($option_item || $supply_item) { ?>
		<div class="vi_txt_li">
			<?php if($option_item) { ?>
			<dl>
				<dt>주문옵션</dt>
				<dd>아래옵션은 필수선택 옵션입니다</dd>
			</dl>
			<?php echo $option_item; ?>
			<?php } ?>

			<?php if($supply_item) { ?>
			<dl>
				<dt>추가구성</dt>
				<dd>추가구매를 원하시면 선택하세요</dd>
			</dl>
			<?php echo $supply_item; ?>
			<?php } ?>
		</div>
		<?php } ?>

		<!-- 선택된 옵션 시작 { -->
		<div id="option_set_list">
			<?php if(!$option_item) { ?>
            <input type="hidden" name="io_type[<?php echo $index_no; ?>][]" value="0">
            <input type="hidden" name="io_id[<?php echo $index_no; ?>][]" value="">
            <input type="hidden" name="io_value[<?php echo $index_no; ?>][]" value="<?php echo $gs['gname']; ?>">
            <input type="hidden" class="io_price" value="0">
            <input type="hidden" class="io_stock" value="<?php echo $gs['stock_qty']; ?>">
            <ul id="option_set_added">
				<li class="sit_opt_list vi_txt_li">
					<dl>

						<dt>
							<span class="sit_opt_subj">수량</span>
							<span class="sit_opt_prc"></span>
						</dt>
						<dd class="li_ea">
							<span>
								<button type="button" class="defbtn_minus">-</button><input type="text" name="ct_qty[<?php echo $index_no; ?>][]" value="<?php echo $odr_min; ?>" class="inp_opt" title="수량설정" size="2"><button type="button" class="defbtn_plus">+</button>
							</span>
							<span class="marl7">(재고수량 : <?php echo $gs['stock_mod'] ? display_qty($gs['stock_qty']) : '무제한'; ?>)</span>
						</dd>
					</dl>
				</li>
			</ul>
			<script>
			$(function() {
				price_calculate();
			});
			</script>
			<?php } ?>
		</div>
            <!-- } 선택된 옵션 끝 -->



        <?php // 추춴ID, 추천ID 입력 ; ?>
        <?php if( false && $gs['buy_minishop_grade'] && is_minishop($member['id']) ) : ?>
            <?php
            $myGrade  = minishop::findTopId($member['id']);

            if( isset($myGrade[$gs['buy_minishop_grade']]) ) {
                $my_top_id = $myGrade[$gs['buy_minishop_grade']];
            } else {
                $my_top_id = $encrypted_admin;
            }
            $io_pt_id = $my_top_id;
            $io_up_id = $my_top_id;
            ?>
        <input type="hidden" id="buy_minishop_grade" name="buy_minishop_grade" value="<?php echo $gs['buy_minishop_grade']; ?>">
        <input type="hidden" id="current_grade" name="current_grade" value="<?php echo $member['grade']; ?>">
            <div id="option_set_upper">
                <ul id="option_set_upper_added">
                    <li class="sit_opt_list vi_txt_li<?php echo false && $gs['buy_minishop_grade'] == $member['grade'] && is_minishop($member['id']) ? ' dpn ' : ''; ?>">
                        <dl>
                            <dt>
                                <span class="sit_opt_subj"><label for="io_up_id">추천ID</label></span>
                                <span class="sit_opt_prc"></span>
                            </dt>
                            <dd class="li_ea">
                                <input type="text" id="io_up_id" name="io_up_id" value="<?php echo $io_up_id; ?>" class="frm_input" size="20"/>
                            </dd>
                        </dl>

                    </li>

                    <li class="sit_opt_list vi_txt_li">

                        <dl>
                            <dt>
                                <span class="sit_opt_subj"><label for="io_pt_id">후원ID</label></span>
                                <span class="sit_opt_prc"></span>
                            </dt>
                            <dd class="li_ea">
                                <input type="text" id="io_pt_id" name="io_pt_id" value="<?php echo $io_pt_id; ?>" class="frm_input" size="20"/>
                            </dd>
                        </dl>

                    </li>

                </ul>
            </div>
            <script>
                (function($){
                    $(document).ready(function(){
                        $('#io_up_id, #io_pt_id').on('blur', function(){
                            var grade = $('#buy_minishop_grade').val();
                            var id    = $(this).val();
                            if( id == '' || id == 'admin' || id == 'k2k9' ) return;
                            $.ajax({
                                url : '/plugin/zentool/minishop/ajax.find_up_id.php',
                                data: { 'grade' : grade, 'id' : id },
                                type: 'POST',
                                // contentType: "application/json; charset=UTF-8",
                                dataType: 'json',
                                success : function(data){
                                    if( data.result == 'success' ) {

                                    } else {
                                        $(this).val('');
                                        alert(data.data);
                                    }
                                }.bind(this),
                                error   : function(res, data){
                                    console.log( arguments );
                                    $(this).val('');
                                }.bind(this)
                            });
                        });
                    });
                }(jQuery));
            </script>
        <?php endif; ?>
        <?php //\\ 추춴ID, 추천ID 입력 ; ?>
		<?php
		if( ($gs['point_pay_allow'] || $gs['point_pay_max'] || $gs['point_pay_per']) && Good::usablePoint($gs)) { ?>

				<div id="sit_tot_views" class="dn">
					<span class="fl">쇼핑포인트 할인 전 금액</span>
					<span id="sit_tot_price" class="prdc_price" style="color:gray;font-size:18px;"></span>
				</div>
				<div id="sit_tot_views2" class="dn" style="color:red;">
					<span class="fl">쇼핑포인트 할인 후 금액</span>
					<span id="sit_tot_price2" class="prdc_price"></span>
				</div>
				<?
			}else{
				?>
				<div id="sit_tot_views" class="dn">
					<span class="fl">총 합계 금액</span>
					<span id="sit_tot_price" class="prdc_price"></span>
				</div>
				<?
			}
		?>


		<?php } ?>
		<?php if(!$is_pr_msg) { ?>
		<div class="vi_btn">
			<?php echo get_buy_button($script_msg, $index_no, 3); //$gs['buy_minishop_grade'] ? 1 : 3); ?>
		</div>
		<?php if($naverpay_button_js) { ?>
		<div class="naverpay-item"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
		<?php } ?>
		<?php } ?>
	</div>
</div>
<?php if($gs['compare']=="Y" && ($gs['compare_0'] || $gs['compare_1'] || $gs['compare_2'] || $gs['compare_3'] || $gs['compare_4'] || $gs['compare_5'] || $gs['compare_6'] || $gs['compare_7'] || $gs['compare_8'] || $gs['compare_9'])) { ?>
<div class="CMPR">
	<p class="tit">최저가를 한방에 알 수 있는 블링뷰티 복지몰 국내 쇼핑몰 최저가 비교</p>
	<p class="des">블링뷰티 복지몰에서는 국내대표 쇼핑몰(쿠팡,네이버,11번가,옥션) 판매상품 빅데이터를 분석하여 최저가로 회원 여러분께 제공해드립니다.<br>가격비교 사이트(다나와,에누리)에서 시간만 소비하셨나요? 이젠 블링뷰티 복지몰에서 한방에 최저가 구입하세요!<br>(국내 가격비교 사이트는 최저가를 검색해서 찾아가지만 많은 옵션선택 배송비 결제방식 멤버쉽가입여부에 따라 판매가격이 달라지게 됩니다. 블링뷰티복지몰은 빅데이터로 최저가를 찾아내고 전문인력이 직접 확인하므로 정확합니다.)</p>
	<table style="width:100%;">
		<tr style="border:1px solid #EFF0F1;width:100%;height:45px;">
			<th style="width:200px;">쇼핑몰</th>
			<th>상품정보</th>
			<th style="width:150px;">판매가</th>
			<th style="width:150px;">기타정보</th>
		</tr>
    <?php if(is_array($compare_0) && $gs['compare_0'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_0['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_0['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_0['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_0['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_0['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_1) && $gs['compare_1'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_1['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_1['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_1['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_1['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_1['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_2) && $gs['compare_2'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_2['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_2['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_2['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_2['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_2['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_3) && $gs['compare_3'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_3['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_3['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_3['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_3['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_3['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_4) && $gs['compare_4'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_4['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_4['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_4['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_4['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_4['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_5) && $gs['compare_5'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_5['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_5['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_5['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_5['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_5['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_6) && $gs['compare_6'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_6['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_6['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_6['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_6['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_6['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_7) && $gs['compare_7'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_7['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_7['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_7['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_7['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_7['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_8) && $gs['compare_8'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_8['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_8['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_8['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_8['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_8['4']; ?></td>
		</tr>
    <?php } ?>
    <?php if(is_array($compare_9) && $gs['compare_9'] && $gs['compare']=="Y") { ?>
		<tr>
			<td rowspan="2" class="r2shpm"><?php echo $compare_9['0']; ?></td>
			<td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_9['1']; ?></td>
			<td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_9['3']; ?></td>
			<td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $compare_9['2']; ?></td>
			<td><img src="../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_9['4']; ?></td>
		</tr>
    <?php } ?>
	</table>
	<style>
		.CMPR{width:1200px;margin:75px 0;}
		.CMPR .tit{height:50px;font-size:26px;font-weight:600;overflow:visible;}
		.CMPR .des{font-size:14px;line-height:22px;}
		.CMPR table{text-align:center;margin-top:20px}
		.CMPR td, .CMPR th{height:30px;}
		.CMPR th{font-size:14px;font-weight:500;}
		.CMPR .left{text-align:left;}
		.CMPR .icon{width:18px;margin-right:5px;}
		.CMPR tr:nth-child(2n+1), .CMPR  .r2free{border-bottom:1px solid #eee;}
		.CMPR .r2shpm{font-size:1rem;font-weight:600;border-bottom:1px solid #eee;}
		
	</style>
</div>
<?php } ?>
</form>

<section class="mart50">
	<a name="tab1"></a>
	<div class="vi_tab">
		<ul>
			<li onclick="javascript:pg_anchor('tab1')" class="on">상품정보</li>
			<li onclick="javascript:pg_anchor('tab2')">상품평</li>
			<li onclick="javascript:pg_anchor('tab3')">상품문의</li>
			<li onclick="javascript:pg_anchor('tab4')">배송/교환/반품안내</li>
		</ul>
	</div>

	<div class="tbl_frm02 tbl_wrap mart15">
		<table>
		<colgroup>
			<col width="15%">
			<col width="35%">
			<col width="15%">
			<col width="35%">
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">상품코드</th>
			<td><?php echo $gs['gcode']; ?></td>
			<th scope="row">부가세, 면세여부</th>
			<td><?php echo ($gs['notax'])?"과세상품":"면세상품"; ?></td>
		</tr>
		<tr>
			<th scope="row">A/S문의</th>
			<td><?php echo ($gs['repair'])?$gs['repair']:"제조사 A/S문의"; ?></td>
			<th scope="row">증빙서류발급</th>
			<td><?php echo ($gs['notax'])?"세금계산서, 현금영수증 발급가능":"세금계산서, 현금영수증 발급불가능"; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="ofh tac padt10 padb10">
<?php
		if($gs['dongurl']){
			echo "<video width='400px' height='400px' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$gs['dongurl']}' type='video/mp4'></video><br>";
		}elseif($gs['dongfile']){
			echo "<video width='400px' height='400px' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$gs['dongfile']}' type='video/mp4'></video><br>";
		}
?>
	<?php if($gid){
		echo $gs['memo'];
	}else{ 
		echo get_view_thumbnail(conv_content($gs['memo'], 1), 1000); 
	}
	?>
	</div>

	<?php
	if($gs['info_value']) {
		$info_data = unserialize(stripslashes($gs['info_value']));
		if(is_array($info_data)) {
			$gubun = $gs['info_gubun'];
			$info_array = $item_info[$gubun]['article'];
	?>
	
	<div class="mart20 marb30">
		<h2 class="anc_tit">전자상거래 등에서의 상품정보제공고시</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col width="25%">
				<col width="75%">
			</colgroup>
			<?php
			foreach($info_data as $key=>$val) {
				$ii_title = $info_array[$key][0];
				$ii_value = $val;
			?>
			<tr>
				<th scope="row"><?php echo $ii_title; ?></th>
				<td><?php echo $ii_value; ?></td>
			</tr>
			<?php } //foreach ?>
			</table>
		</div>
	</div>

	<?php
			} //array
		} //if
	?>

	<?php
	$sql = " select b.*
			   from shop_goods_relation a left join shop_goods b ON (a.gs_id2=b.index_no)
			  where a.gs_id = '{$index_no}'
				and b.shop_state = '0'
				and b.isopen < 3 ";
	$res = sql_query($sql);
	$rel_count = sql_num_rows($res);
	if($rel_count > 0) {
	?>
	<div class="vi_rel">
		<h3><span>현재상품과 연관된 상품</span></h3>
		<div<?php if($rel_count <= 5) { ?> class="ofh"<?php } ?>>
			<?php
			for($i=0; $row=sql_fetch_array($res); $i++) {
				$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
				$it_image = get_it_image($row['index_no'], $row['simg1'], 174, 174);
				$it_name = cut_str($row['gname'], 100);
				$it_price = get_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);

				// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
				$it_sprice = $sale = '';
				if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
					$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
					$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
					$it_sprice = display_price2($row['normal_price']);
				}
			?>
			<dl>
			<a href="<?php echo $it_href; ?>">
				<dt><?php echo $it_image; ?></dt>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
			</a>
			</dl>
			<?php } ?>
		</div>
		<?php if($rel_count > 5) { ?>
		<script>
		$(document).ready(function(){
			$('.vi_rel div').slick({
				autoplay: false,
				dots: false,
				arrows: true,
				infinite: false,
				slidesToShow: 5,
				slidesToScroll: 1
			});
		});
		</script>
		<?php } ?>
	</div>
	<?php } ?>
</section>

<section class="mart50">
	<a name="tab2"></a>
	<div class="vi_tab">
		<ul>
			<li onclick="javascript:pg_anchor('tab1')">상품정보</li>
			<li onclick="javascript:pg_anchor('tab2')" class="on">상품평</li>
			<li onclick="javascript:pg_anchor('tab3')">상품문의</li>
			<li onclick="javascript:pg_anchor('tab4')">배송/교환/반품안내</li>
		</ul>
	</div>
	<div class="mart15">
		<?php
		Theme::get_theme_part(MS_THEME_PATH,'/view_user.skin.php');
		?>
	</div>
</section>

<section class="mart50">
	<a name="tab3"></a>
	<div class="vi_tab">
		<ul>
			<li onclick="javascript:pg_anchor('tab1')">상품정보</li>
			<li onclick="javascript:pg_anchor('tab2')">상품평</li>
			<li onclick="javascript:pg_anchor('tab3')" class="on">상품문의</li>
			<li onclick="javascript:pg_anchor('tab4')">배송/교환/반품안내</li>
		</ul>
	</div>
	<div class="mart15 vi_qa">
		<?php
		Theme::get_theme_part(MS_THEME_PATH,'/view_qa.skin.php');
		?>
	</div>
</section>

<section class="mart50">
	<a name="tab4"></a>
	<div class="vi_tab">
		<ul>
			<li onclick="javascript:pg_anchor('tab1')">상품정보</li>
			<li onclick="javascript:pg_anchor('tab2')">상품평</li>
			<li onclick="javascript:pg_anchor('tab3')">상품문의</li>
			<li onclick="javascript:pg_anchor('tab4')" class="on">배송/교환/반품안내</li>
		</ul>
	</div>
	<div class="mart15">
		<?php echo get_view_thumbnail(conv_content(get_policy_content($index_no), 1), 1000); ?>
	</div>
</section>
<div class="btn_result">
</div>
<script>
$(function() {
    $("#requestBtn").on("click", function() {
		var f = document.fbuyform;
		if(fsubmit_check(f) == true){
			var params = jQuery("#fbuyform").serialize(); // serialize() : 입력된 모든Element(을)를 문자열의 데이터에 serialize 한다.
			  $.ajax({
				url:"./cartupdate2.php",
				type:"post" , 
				dataType: 'html',
				data:params,
				success:function(data){ //통신이 성공적일때 출력
				  $(".btn_result").html(data);
				},
				error:function(xhr,status,error){ //에러시 출력
				  alert("ERROR xhr :"+xhr+", status :"+status+", error :"+error); 
				} 
			});
		}
	});
});

// 상품보관
function item_wish(f)
{
	f.action = "./wishupdate.php";
	f.submit();
}

function fsubmit_check(f)
{
    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

	if($(".sit_opt_list").size() < 1) {
		alert("주문옵션을 선택해주시기 바랍니다.");
		return false;
	}

    var val, io_type, result = true;
    var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주세요.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주세요.");
        return false;
    }

    return true;
}

// 바로구매, 장바구니 폼 전송
function fbuyform_submit(sw_direct)
{
    var f = document.fbuyform;
	f.sw_direct.value = sw_direct;
   
    if(sw_direct == "cart") {
		f.sw_direct.value = 0;
  
	} else { // 바로구매
		f.sw_direct.value = 1;
	}

	if($(".sit_opt_list").size() < 1) {
		alert("주문옵션을 선택해주시기 바랍니다.");
		return;
	}

	var val, io_type, result = true;
	var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
	var $el_type = $("input[name^=io_type]");

	$("input[name^=ct_qty]").each(function(index) {
		val = $(this).val();

		if(val.length < 1) {
			alert("수량을 입력해 주세요.");
			result = false;
			return;
		}

		if(val.replace(/[0-9]/g, "").length > 0) {
			alert("수량은 숫자로 입력해 주세요.");
			result = false;
			return;
		}

		if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
			alert("수량은 1이상 입력해 주세요.");
			result = false;
			return;
		}

		io_type = $el_type.eq(index).val();
		if(io_type == "0")
			sum_qty += parseInt(val);
	});

	if(!result) {
		return;
	}

	if(min_qty > 0 && sum_qty < min_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주세요.");
		return;
	}

	if(max_qty > 0 && sum_qty > max_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주세요.");
		return;
	}


	if( $('#io_pt_id') && $('#io_up_id') ) {
	    if( $('#io_up_id').val() == '' ){
	        alert('추천ID를 입력하세요.');
	        return;
        }
        if( $('#io_pt_id').is(':visible') && $('#io_pt_id').val() == '' ){
            alert('후원ID를 입력하세요.');
            return;
        }
    }

	f.action = "./cartupdate.php";
	f.submit();
}
</script>

