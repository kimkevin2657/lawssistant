<?php
if(!defined('_MALLSET_')) exit;

if(defined('_INDEX_')) { // index에서만 실행
	include_once(MS_LIB_PATH.'/popup.inc.php'); // 팝업레이어
}
?>

<div id="wrapper">
	<div id="header">
		<?php if(!get_cookie("ck_hd_banner")) { // 상단 큰배너 ?>
		<div id="hd_banner">
			<?php if($banner1 = display_banner_bg(1, $pt_id)) { // 배너가 있나? ?>
			<?php echo $banner1; ?>
			<img src="<?php echo MS_IMG_URL; ?>/bt_close.gif" id="hd_close">
			<?php } // banner end ?>
		</div>
		<?php } // cookie end ?>
		<div id="tnb">
			<div id="tnb_inner">
				<ul class="fr">
					<?php
					$tnb = array();

					if( (!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])) :
                        $tnb[] = '<li><a href="'.MS_MYPAGE_URL.'/page.php?code=mypage_minishop_payhistory">마일리지: <span class="text-blue">'.display_price(get_pay_sum($member['id']), '') .'</span>원</a></li>';
					    if( defined('USE_LINE_UP') && USE_LINE_UP ) :
                        $tnb[] = '<li><a href="'.MS_SHOP_URL.'/lpoint.php">라인점수: <span class="text-red">'.display_point($member['total_line_cnt'], '').'</span>점</a></li>';
					    endif;
                    endif;
					if($member['id']) {
						$tnb[] = '<li><a href="'.MS_BBS_URL.'/logout.php">로그아웃</a></li>';
					} else {
						$tnb[] = '<li><a href="'.MS_BBS_URL.'/login.php?url='.$urlencode.'">로그인</a></li>';
						$tnb[] = '<li><a href="'.MS_BBS_URL.'/register.php">회원가입</a></li>';
					}
                    if($is_admin) :
                        $admin_text = is_minishop($member['id']) ? '가맹점' : '관리자';
                        if((!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_seller($member['id'])) :
                            $tnb[] = '<li><a href="'.MS_MYPAGE_URL.'/page.php?code=seller_main" target="_blank" class="fc_eb7">공급사 관리</a></li>';
                        endif;
                        if((!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])) :
                            $tnb[] = '<li><a href="'.MS_MYPAGE_URL.'/page.php?code=minishop_info" target="_blank" class="fc_eb7">가맹점 관리</a></li>';
                        endif;
                        if( is_admin($member['grade'])):
                            $tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
                        endif;
                    endif;
					$tnb[] = '<li><a href="'.MS_SHOP_URL.'/mypage.php">마이페이지</a></li>';
					$tnb[] = '<li><a href="'.MS_SHOP_URL.'/cart.php">장바구니<span class="ic_num">'. get_cart_count().'</span></a></li>';
					$tnb[] = '<li><a href="'.MS_SHOP_URL.'/orderinquiry.php">주문/배송조회</a></li>';
					$tnb[] = '<li><a href="'.MS_BBS_URL.'/faq.php?faqcate=1">고객센터</a></li>';

                    if( is_minishop($member['id'])) :
                        $tnb[] = minishop::impersonation($member['id'], array('<li>', '</li>'));
                    endif;

					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
                     ?>
				</ul>
			</div>
		</div>
		<div id="hd">
			<!-- 상단부 영역 시작 { -->
			<div id="hd_inner">
				<div class="hd_bnr">
					<span><a href="/shop/cart.php"><img src="http://payshop.shop/theme/basic/img/cart1.png"></a></span>
					<span><?php echo display_banner(2, $pt_id); ?></span>
				</div>
				<h1 class="hd_logo">
					<?php echo display_logo(); ?>
				</h1>
				<div id="hd_sch">
					<fieldset class="sch_frm">
						<legend>사이트 내 전체검색</legend>
						<form name="fsearch" id="fsearch" method="post" action="<?php echo MS_SHOP_URL; ?>/search_update.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
						<input type="hidden" name="hash_token" value="<?php echo MS_HASH_TOKEN; ?>">
						<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
						<button type="submit" class="sch_submit fa fa-search" value="검색"></button>
						</form>
						<script>
						function fsearch_submit(f){
							if(!f.ss_tx.value){
								alert('검색어를 입력하세요.');
								return false;
							}
							return true;
						}
						</script>
					</fieldset>
				</div>
			</div>
			<div id="gnb">
				<div id="gnb_inner">


					<!--특가닷컴 카테고리 복붙-->
					<div class="all_cate">
						<span class="allc_bt"><i class="fa fa-bars"></i> 카테고리 보기</span>
						<div class="con_bx">
							<ul>
							<?php // 1차 카테고리
							$res = sql_query_cgy('all');
							for($i=0; $row=sql_fetch_array($res); $i++) {
								$href = MS_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

								$bannerImg = '';
								if($row['img_name']){
									$bannerImg = '<img src='.MS_DATA_URL.'/category/'.$pt_id.'/'.$row['img_name'].'>';
								}
							?>
								<li class="c_box">
									<a href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
									<?php // 2차 카테고리
									$r = sql_query_cgy($row['catecode'], 'COUNT');
									if($r['cnt'] > 0) {
									?>
									<div class="sm_body">
									<ul>
										<?php
										$res2 = sql_query_cgy($row['catecode']);
										while($row2 = sql_fetch_array($res2)) {
											$href2 = MS_SHOP_URL.'/list.php?ca_id='.$row2['catecode'];
										?>
										<li>
											<a href="<?php echo $href2; ?>"><?php echo $row2['catename']; ?></a>
											<?php // 3차 카테고리
											$r2 = sql_query_cgy($row2['catecode'], 'COUNT');
											if($r2['cnt'] > 0) {
											?>
											<div class="sm_body">
											<ul>
												<?php
												$res3 = sql_query_cgy($row2['catecode']);
												while($row3 = sql_fetch_array($res3)) {
													$href3 = MS_SHOP_URL.'/list.php?ca_id='.$row3['catecode'];
												?>
												<li>
													<a href="<?php echo $href3; ?>"><?php echo $row3['catename']; ?></a>
													<?php // 4차 카테고리
													$r3 = sql_query_cgy($row3['catecode'], 'COUNT');
													if($r3['cnt'] > 0) {
													?>
													<div class="sm_body">
													<ul>
														<?php
														$res4 = sql_query_cgy($row3['catecode']);
														while($row4 = sql_fetch_array($res4)) {
															$href4 = MS_SHOP_URL.'/list.php?ca_id='.$row4['catecode'];
														?>
														<li>
															<a href="<?php echo $href4; ?>"><?php echo $row4['catename']; ?></a>
															<?php // 5차 카테고리
															$r4 = sql_query_cgy($row4['catecode'], 'COUNT');
															if($r4['cnt'] > 0) {
															?>
															<div class="sm_body">
															<ul>
																<?php
																$res5 = sql_query_cgy($row4['catecode']);
																while($row5 = sql_fetch_array($res5)) {
																	$href5 = MS_SHOP_URL.'/list.php?ca_id='.$row5['catecode'];
																?>
																<li><a href="<?php echo $href5; ?>"><?php echo $row5['catename']; ?></a></li>
																<?php } ?>
															</ul>
															</div>
															<?php } // 5차 끝 ?>
														</li>
														<?php } ?>
													</ul>
													</div>
													<?php } // 4차 끝 ?>
												</li>
												<?php } ?>
											</ul>
											</div>
											<?php } // 3차 끝 ?>
										</li>
										<?php } ?>
									</ul>
									</div>
									<?php } // 2차 끝 ?>
								</li>
							<?php } // 1차 끝 ?>
							</ul>
						</div>
					</div>

				<!-- 원본 카테고리 보기 -->
				<!--	<div class="all_cate">
						<span class="allc_bt"><i class="fa fa-bars"></i> 전체카테고리</span>
						<div class="con_bx">
							<ul>
							<?php
							$mod = 5;
							$res = sql_query_cgy('all');
							for($i=0; $row=sql_fetch_array($res); $i++) {
								$href = MS_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

								if($i && $i%$mod == 0) echo "</ul>\n<ul>\n";
							?>
								<li class="c_box">
									<a href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
									<?php
									$r = sql_query_cgy($row['catecode'], 'COUNT');
									if($r['cnt'] > 0) {
									?>
									<ul>
										<?php
										$res2 = sql_query_cgy($row['catecode']);
										while($row2 = sql_fetch_array($res2)) {
											$href2 = MS_SHOP_URL.'/list.php?ca_id='.$row2['catecode'];
										?>
										<li><a href="<?php echo $href2; ?>"><?php echo $row2['catename']; ?></a></li>
										<?php } ?>
									</ul>
									<?php } ?>
								</li>
							<?php } ?>
							</ul>
						</div>
						<script>
						$(function(){
							$('.all_cate .allc_bt').click(function(){
								if($('.all_cate .con_bx').css('display') == 'none'){
									$('.all_cate .con_bx').show();
									$(this).html('<i class="ionicons ion-ios-close-empty"></i> 전체카테고리');
								} else {
									$('.all_cate .con_bx').hide();
									$(this).html('<i class="fa fa-bars"></i> 전체카테고리');
								}
							});
						});
						</script>
					</div>  -->



					<div class="gnb_li">
						<ul>
							<!-- <?
							$sqlp = "select * from shop_plan where pl_use = '1' ";
							$resp = sql_query($sqlp);
							if(sql_num_rows($resp)>0){
							?> 
                            
							
							<?}?>
                            <?php
                            $dpLabels = Shop::dpLabel($pt_id, array('use_yn'=>'Y','shop_main_menu'=>'Y'));
                            ?>
                            <?php foreach($dpLabels as $dpLabel ) : ?>
                                <li><a href="<?php echo MS_SHOP_URL; ?>/listtype.php?type=<?php echo $dpLabel['type_no']?>"><?php echo $dpLabel['type_label']; // 15만원 상품 ?></a></li>
                            <?php endforeach; ?>    --><!--관리자 디자인관리 메인진열관리 연동-->

							<li><a href="<?php echo MS_SHOP_URL; ?>/listtype.php?type=1">베스트셀러</a></li>
							<li><a href="<?php echo MS_SHOP_URL; ?>/listtype.php?type=2">신상품</a></li>
							<li><a href="<?php echo MS_SHOP_URL; ?>/planlist.php?pl_no=14">선물용 추천상품</a></li>
							<li><a href="<?php echo MS_SHOP_URL; ?>/listtype.php?type=3">인기상품</a></li>
							<li><a href="<?php echo MS_SHOP_URL; ?>/listtype.php?type=4">추천상품</a></li>
						</ul>
					</div>
					<span class="gnb_bt"><a href="<?php echo MS_SHOP_URL; ?>/brand.php"><i class="fa fa-tags marr5"></i> BRAND SHOP</a></span>
				</div>
			</div>
			<!-- } 상단부 영역 끝 -->
			<script>
			$(function(){
				// 상단메뉴 따라다니기
				var elem1 = $("#hd_banner").height() + $("#tnb").height() + $("#hd_inner").height();
				var elem2 = $("#hd_banner").height() + $("#tnb").height() + $("#hd").height();
				var elem3 = $("#gnb").height();
				$(window).scroll(function () {
					if($(this).scrollTop() > elem1) {
						$("#gnb").addClass('gnd_fixed');
						$("#hd").css({'padding-bottom':elem3})
					} else if($(this).scrollTop() < elem2) {
						$("#gnb").removeClass('gnd_fixed');
						$("#hd").css({'padding-bottom':'0'})
					}
				});
			});
			</script>
		</div>

		<?php
		if(defined('_INDEX_')) { // index에서만 실행
			$sql = sql_banner_rows(0, $pt_id);
			$res = sql_query($sql);
			$mbn_rows = sql_num_rows($res);
			if($mbn_rows) {
		?>
		<!-- 메인 슬라이드배너 시작 { -->
		<div id="mbn_wrap">
			<?php
			$txt_w = (100 / $mbn_rows);
			$txt_arr = array();
			for($i=0; $row=sql_fetch_array($res); $i++)
			{
				if($row['bn_text'])
					$txt_arr[] = $row['bn_text'];

				$a1 = $a2 = $bg = '';
				$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
				if(is_file($file) && $row['bn_file']) {
					if($row['bn_link']) {
						$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
						$a2 = "</a>";
					}

					$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
					if($row['bn_bg']) $bg = "#{$row['bn_bg']} ";

					$file = rpc($file, MS_PATH, MS_URL);
					echo "<div class=\"mbn_img\" style=\"background:{$bg}url('{$file}') no-repeat top center;\">{$a1}{$a2}</div>\n";
				}
			}
			?>
		</div>
		<script>
		$(document).on('ready', function() {
			<?php if(count($txt_arr) > 0) { ?>
			var txt_arr = <?php echo json_encode($txt_arr); ?>;

			$('#mbn_wrap').slick({
				autoplay: true,
				autoplaySpeed: 2000,
				dots: true,
				fade: true,
				customPaging: function(slider, i) {
					return "<span>"+txt_arr[i]+"</span>";
				}
			});
			$('#mbn_wrap .slick-dots li').css('width', '<?php echo $txt_w; ?>%');
			<?php } else { ?>
			$('#mbn_wrap').slick({
				autoplay: true,
				autoplaySpeed: 2000,
				dots: true,
				fade: true
			});
			<?php } ?>
		});
		</script>
		<!-- } 메인 슬라이드배너 끝 -->
		<?php }
		}
		?>
	</div>

	<div id="container">
		<?php
		if(!is_mobile()) { // 모바일접속이 아닐때만 노출
			Theme::get_theme_part(MS_THEME_PATH,'/quick.skin.php'); // 퀵메뉴
		}

		if(!defined('_INDEX_')) { // index가 아니면 실행
			echo '<div class="cont_inner">'.PHP_EOL;
		}
		?>
