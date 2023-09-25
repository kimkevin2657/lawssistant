<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

Theme::get_theme_part(MS_MTHEME_PATH,'/slideMenu.skin.php');
?>
<div class="img_gray_box" style="display:none;">

<div class="bundle_reviewImageList bundle_reviewImageList2">
	<div class="mySwiper2">
		<div class="swiper-wrapper">

			<?
				$photo2 = sql_query("SELECT * FROM review_list where booking_id = '{$id}' and review_file <> '' order by re_idx desc ");
				for($p=0; $p_row2 = sql_fetch_array($photo2); $p++){

			?>
				<button class="swiper-slide" style="margin-right: 6px;" data-img="<? echo $p_row2['review_file']; ?>" num="<? echo $p+1; ?>">
					<div style="display: block; position: static;">
						<img src="<? echo MS_URL; ?>/review_img/<? echo $p_row2['thumbnail_img']; ?>" alt="" class="img_g">
					</div>
				</button>
				
			<? } ?>

		</div>
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
	</div>
	<img src="<? echo MS_THEME_URL; ?>/img/close.png" class="review_close">
  </div>
</div>


<div id="wrapper">
	<div onclick="history.go(-1);" class="page_cover"><span class="sm_close"></span></div>
	<?php if($banner1 = mobile_slider(1, $pt_id, $member['grade'], $member['mb_category'])) { // 상단 큰배너 ?>
  <?php if(defined('_MINDEX_')) { ?>
	<div class="top_ad"><?php echo $banner1; ?></div>
	<?php }} ?>
	<header id="header">
		<div id="m_gnb">			
			<h1 class="logo"><?php echo mobile_display_logo(); ?></h1>
            <?php if( !MS_LIMIT_MYPAGE && ( $is_member || !$config['shop_intro_yes'])){ ?>
			<!--span class="btn_sidem fa fa-navicon"></span 슬라이드 카테고리 -->
            <?php if( (!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])){ ?>
           <!-- <a href="<?php echo MS_MSHOP_URL; ?>/mypage.php" class="btn_mypage fa fa-user"></a>
            <a href="<?php echo MS_MSHOP_URL; ?>/orgchart.php" class="btn_orgchart fa fa-sitemap"></a>-->
            <?php
            echo minishop::impersonation($member['id'], array('', ''));
            ?>
            <?php } ?>

			<!--div class="topCate">카테고리</div-->

            <?php if( ! MS_LIMIT_MYPAGE ){ ?>
		<!--	<span class="btn_search fa fa-search" style=""></span> -->
			<!--a href="<?php echo MS_MSHOP_URL; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><span class="ic_num"><?php echo get_cart_count(); ?></span></a 장바구니 숨김-->
            <?php } ?>
            <?php } ?>
		    
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

<!--금주검색어기능-->
				
					<!--div class="hdkeword">
                    <ul id="ticker">
					<?php echo display_tick("", 10); ?>
					</ul></div-->
         
					<div class="hdkBx">
						<h2>인기 쇼핑 키워드 <span class="btn_close">닫기 <i class="ionicons ion-close-round"></i></span></h2>
					<ul><?php echo display_rank(); ?></ul>
					</div>
			

	<script>
					// 인기검색어 펼침
					$(".sch_frm .sch_stx").click(function(){
						$(".hdkBx").show();
					});
					// 인기검색어 닫음
					$(".hdkBx .btn_close").click(function(){
						$(".hdkBx").hide();
					});
					</script>
		  			
 </div> 
<!--금주검색어기능 끝-->	

	
		
<!--관리자 시작

                <?php if( false && is_minishop($member['id'])){ ?>
                    <li><a href="<?php echo MS_MSHOP_URL; ?>/mypage.php">마이페이지</a></li>
                    <li><a href="<?php echo MS_MSHOP_URL; ?>/orgchart.php">조직도 트리</a></li>
                    <<li><a href="<?php echo MS_MSHOP_URL; ?>/orgchart.php">조직도 라인</a></li>

                <?php } ?>
                <?php
                $dpLabels = Shop::dpLabel($pt_id, array('use_yn'=>'Y','mshop_main_menu'=>'Y'));


				$sqlp = "select * from shop_plan where pl_use = '1' ";
				$resp = sql_query($sqlp);
				$count_z = sql_num_rows($resp);
				if($count_z > '0'){
				?>
				<<li><a href="<?php echo MS_SHOP_URL; ?>/plan.php">기획전</a></li> 
				<?php } ?>

                <?php foreach($dpLabels as $dpLabel ){ ?>
                <li><a href="<?php echo MS_MSHOP_URL; ?>/listtype.php?type=<?php echo $dpLabel['type_no']; ?>"><?php echo $dpLabel['type_label']; //후원상품?></a></li>
                <?php } ?>
				
<!--관리자 끝-->
         <!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
               <nav id="gnb">

			<ul>
				<!--<li><a href="<?php echo MS_SHOP_URL; ?>/brand.php"><p class="sub-name">뭉치면싸다!</p>브랜드샵</a></li> -->
				<li><a href="<?php echo MS_MSHOP_URL; ?>/list.php?ca_id=313"><p class="sub-name">베스트만 해봄!</p>#베스트50</a></li>
                <li><a href="<?php echo MS_MSHOP_URL; ?>/brand.php"><p class="sub-name">번개배송!</p>#당일출고  <i class="fa fa-bolt marr5" style="color:#2daa4a;"></i></a></li>
				<!--<li><a href="<?php echo MS_MSHOP_URL; ?>/listtype.php?type=2">#설명절특선</a></li>-->
				<li><a href="<?php echo MS_MSHOP_URL; ?>/listtype.php?type=3">#주방/생활용품</a></li>
				<li><a href="<?php echo MS_MSHOP_URL; ?>/listtype.php?type=4">#가전/디지털</a></li>
				<li><a href="<?php echo MS_MSHOP_URL; ?>/listtype.php?type=5">#식품</a></li>
                <li><a href="<?php echo MS_MSHOP_URL; ?>/brand.php">#브랜드샵</a></li>
                
               </ul>
		</nav>
        
		<!-- content -->
	<!--div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
		<nav id="gnb">
			<?php
			echo "<ul>\n";
			$res = sql_query_cgy('all');
			for($i=0; $row=sql_fetch_array($res); $i++) {
				$href = MS_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
				echo '<li><a href="'.$href.'">'.$row['catename'].'</a></li>'.PHP_EOL;
			} // 1차분류 끝
			echo "</ul>\n";
			?>
		</nav-->
		<script>
		//상단 슬라이드 메뉴
		var menuScroll = null;
		$(window).ready(function() {
			menuScroll = new iScroll('gnb', {
				hScrollbar:false, vScrollbar:false, bounce:false, click:true
			});
		});
		</script>
      </div>	       
    </header>
         
	
     <?php if(!defined("_MINDEX_")) { ?>
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $ms['title']); ?></span>
		 </div>
		<?php } ?>
	
	