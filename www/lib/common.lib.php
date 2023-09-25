<?php
if(!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가

// PC 또는 모바일 사용인지를 검사
function check_device($device)
{
    global $is_admin;

    if ($is_admin) return;

    if ($device=='pc' && G5_IS_MOBILE) {
        alert('PC 전용     입니다.', G5_URL);
    } else if ($device=='mobile' && !G5_IS_MOBILE) {
        alert('모바일 전용 게시판입니다.', G5_URL);
    }
}


// 회원 레이어
function get_sideview($mb_id, $name)
{
	// 에러방지를 위해 기호를 치환
	$name = get_text($name, 0, true);

	if(!is_admin() || !$mb_id || $mb_id == encrypted_admin())
		return $name;

	// 사이드뷰 시작
	$mb = get_member($mb_id, 'email, cellphone, grade');
	$email = get_email_address($mb['email']);
	$phone = conv_number($mb['cellphone']);

	$str = "<span class=\"sv_wrap\">\n";
	$str.= "<a href=\"javascript:void(0);\" class=\"sv_member\">{$name}</a>\n";

	$str2 = "<span class=\"sv\">\n";

	$str2.= "<a href=\"".MS_ADMIN_URL."/pop_memberform.php?mb_id={$mb_id}\" onclick=\"win_open(this,'win_member','1200','600','yes');return false;\">회원정보수정</a>\n";

	if(is_seller($mb_id))
		$str2.= "<a href=\"".MS_ADMIN_URL."/pop_sellerform.php?mb_id={$mb_id}\" onclick=\"win_open(this,'win_seller','1200','600','yes');return false;\">공급사정보수정</a>\n";

	if($email)
		$str2 .= "<a href=\"".MS_ADMIN_URL."/formmail.php?mb_id=".$mb_id."&name=".urlencode($name)."&email=".$email."\" onclick=\"win_open(this,'win_email','650','580','no'); return false;\">메일보내기</a>\n";

	if($phone)
		$str2.= "<a href=\"".MS_ADMIN_URL."/sms/sms_user.php?ph={$phone}\" onclick=\"win_open(this,'win_sms','300','360','no'); return false;\">SMS보내기</a>\n";

	$str2.= "<a href=\"".MS_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}\" target=\"_blank\">쇼핑몰로그인</a>\n";

	if(is_minishop($mb_id))
		$str2.= "<a href=\"".MS_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}&lg_type=P\" target=\"_blank\">가맹점로그인</a>\n";

	if(is_seller($mb_id))
		$str2.= "<a href=\"".MS_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}&lg_type=S\" target=\"_blank\">공급사로그인</a>\n";

	$str2.= "</span>\n";
	$str.= $str2;
	$str.= "\n<noscript class=\"sv_nojs\">".$str2."</noscript>";

	$str.= "</span>";


	return $str;
}





// 로고
function display_logo($filed='basic_logo')
{
    global $encrypted_admin, $pt_id;

	$row = sql_fetch("select $filed from shop_logo where mb_id='$pt_id'");
	if(!$row[$filed] && $pt_id != $encrypted_admin) {
		$row = sql_fetch("select $filed from shop_logo where mb_id='{$encrypted_admin}'");
	}

	$file = MS_DATA_PATH.'/banner/'.$row[$filed];
	if(is_file($file) && $row[$filed]) {
		$file = rpc($file, MS_PATH, MS_URL);
		return '<a href="'.MS_URL.'"><img src="'.$file.'"></a>';
	} else {
		return '';
	}
}

// 인기검색어
function get_keyword($rows, $pt_id)
{
	$str = "";
	$sql = " select *
			   from shop_keyword
			  where pt_id = TRIM('$pt_id')
			  order by scount desc, old_scount desc
			  limit $rows ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++){
		$str .= "<li><a href=\"javascript:fsearch_post('{$row['keyword']}');\">{$row['keyword']}</a></li>\n";
	}

	return $str;
}

// 인기 검색어 추출(모바일 복사/수정해옴 210423)
function display_tick($name, $rows)
{
	global $pt_id;

	echo "<h2>{$name}</h2>\n";
	echo "<ul id='ticker'>\n";

	$sql_common = " from shop_keyword ";
	$sql_search = " where pt_id = '$pt_id' ";
	$sql_order  = " order by scount desc, old_scount desc limit $rows ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++){
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'NEW';

		if($rank_gap > 0)
			$u_rkw = "rkw_icon rkw_up";
		else if($rank_gap < 0)
			$u_rkw = "rkw_icon rkw_dw";
		else if($rank_gap == '0')
			$u_rkw = "rkw_icon rkw_sm";
		else
			$u_rkw = "rkw_icon rkw_nw";

		$rkn = $i + 1;
		echo "<li>\n";
			echo "<a href='".MS_SHOP_URL."/search.php?ss_tx=$row[keyword]'><span class='rkw_num'>{$rkn}</span> {$row['keyword']}</a>\n";
			echo "<span class='{$u_rkw}'>{$rank_gap}</span>\n";
		echo "</li>\n";
	}

	echo "</ul>\n";
}

// 금주의 인기 검색어 추출
function get_keyword_rank()
{
	global $pt_id;

	echo "<ul>\n";

	$sql = " select *
			   from shop_keyword
			  where pt_id = '$pt_id'
			  order by scount desc, old_scount desc
			  limit 10 ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++) {
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'NEW';

		if($rank_gap > 0)
			$kw_css = " rank_up";
		else if($rank_gap < 0)
			$kw_css = " rank_down";
		else if($rank_gap == '0')
			$kw_css = "";
		else
			$kw_css = " rnew";

		$rkn = $i + 1;

		echo "<li><span class=\"rank_num\">{$rkn}</span><a href=\"".MS_SHOP_URL."/search.php?ss_tx={$row[keyword]}\">{$row['keyword']}</a><span class=\"rank_icon{$kw_css}\">{$rank_gap}</span></li>\n";
	}

	echo "</ul>\n";
}

// 금주의 인기 검색어 추출(모바일 복사/수정해옴 210423)
function display_rank()
{
	global $pt_id;

	/*echo "<div class='hdkBx'>\n";*/
	echo "<ul>\n";

	$sql_common = " from shop_keyword ";
	$sql_search = " where pt_id = '$pt_id' ";
	$sql_order  = " order by scount desc, old_scount desc limit 10 ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++) {
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'NEW';

		if($rank_gap > 0)
			$u_rkw = "rkw_icon rkw_up";
		else if($rank_gap < 0)
			$u_rkw = "rkw_icon rkw_dw";
		else if($rank_gap == '0')
			$u_rkw = "rkw_icon rkw_sm";
		else
			$u_rkw = "rkw_icon rkw_nw";

		$rkn = $i + 1;
		echo "<li>\n";
			echo "<a href='".MS_SHOP_URL."/search.php?ss_tx=$row[keyword]'><span class='rkw_num'>{$rkn}</span> {$row['keyword']}</a>\n";
			echo "<span class='{$u_rkw}'>{$rank_gap}</span>\n";
		echo "</li>\n";
	}

	echo "</ul>\n";
	echo "</div>\n";
}

// alert 메세지 출력
function alert($msg, $move='back', $myname='')
{
	if(!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	switch($move)
	{
		case "back" :
			$url = "history.go(-1);void(1);";
			break;
		case "close" :
			$url = "window.close();";
			break;
		case "parent" :
			$url = "parent.document.location.reload();";
			break;
		case "replace" :
			$url = "opener.document.location.reload();window.close();";
			break;
		case "no" :
			$url = "";
			break;
		case "shash" :
			$url = "location.hash='{$myname}';";
			break;
		case "thash" :
			$url  = "opener.document.location.reload();";
			$url .= "opener.document.location.hash='{$myname}';";
			$url .= "window.close();";
			break;
		default :
			$url = "location.href='{$move}'";
			break;
	}

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type=\"text/javascript\">alert(\"{$msg}\");{$url}</script>";
	exit;
}

// 날짜, 조회수의 경우 높은 순서대로 보여져야 하므로 $flag 를 추가
// $flag : asc 낮은 순서 , desc 높은 순서
// 제목별로 컬럼 정렬하는 QUERY STRING
function subject_sort_link($col, $query_string)
{
	global $filed, $orderby;

	if($orderby == 'asc') {
		$q2 = "&filed=$col&orderby=desc";
	} else {
		$q2 = "&filed=$col&orderby=asc";
	}

	return "<a href=\"{$_SERVER['SCRIPT_NAME']}?{$query_string}{$q2}\">";
}

// 5차카테고리
function tree_category($catecode)
{
	global $ms, $config;

	$sql_search = " and p_hide='0' ";

	// 본사카테고리 고정일때
	if($config['pf_auth_cgy'] == 1)
		$sql_search .= " and p_oper='y' ";

	$t_catecode = $catecode;

	$sql_common = " from {$ms['category_table']} ";
	$sql_where  = " where u_hide='0' {$sql_search} ";
	$sql_order  = " order by list_view asc ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_where} and upcate = '$catecode' ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$catecode = substr($catecode,0,-3);
	}

	$mod = 5; // 1줄당 노출 수
	$li_width = (int)(100 / $mod);

	$sql = "select * {$sql_common} {$sql_where} and upcate = '$catecode' {$sql_order} ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0) echo '<ul class="sub_tree">'.PHP_EOL;

		$addclass = "";
		if($t_catecode==$row['catecode'])
			$addclass = ' class="active"';

		$href = MS_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

		echo "<li style=\"width:{$li_width}%\"{$addclass}><a href=\"{$href}\">{$row['catename']}</a></li>".PHP_EOL;
	}

	if($i > 0) echo '</ul>'.PHP_EOL;
}

// get_listtype_skin('영역', '이미지가로', '이미지세로', '총 출력수', '추가 class')
function get_listtype_skin($type, $width, $height, $rows, $li_css='')
{
	global $pt_id,$member;

	$result = display_itemtype_new($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		if($i==0) {
			echo "<div class=\"pr_desc {$li_css}\">\n<ul>\n";
		}

		$usablePoint = "";

		$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		if($row['dongurl']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_image = "<video width='$width' height='$height' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
		}elseif($row['dongfile']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_image = "<video width='$width' height='$height' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
		}else{
			$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
		}
		$it_name = cut_str($row['gname'], 100);
			if($member['grade'] > '10'){
			/*	$it_sprice = "<p class=\"spr\">".number_format($row['normal_price'])."<span>원</span>"; */
				$it_price = "<p class='mpr'>회원전용가</p>";
				$sett = round((($row['normal_price'] - $row['goods_price'])/$row['normal_price'])*100);
			/*	$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>'; */
			}else{
				$it_price = get_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);

				// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
				$it_sprice = $sale = '';
				if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
					$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
					$sale = '<span class="sale">'.number_format($sett,0).'%</span>';
					$it_sprice = display_price2($row['normal_price']);
				}
			}

			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);

		echo "<li>\n";
			echo "<a href='{$it_href}'>\n";
           	echo "<dl>\n";
						echo "<dt>{$it_image}</dt>\n";
				echo "<dd class='pname'>{$it_name}</dd>\n";
				echo "<dd class='price'>";
                     echo $sale;
					echo $it_sprice;
					echo $it_price;
				echo "<dd class='icon'>";
            if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new.png\">\n";

		}
		
	if($beasong == '0'){
			echo "<span class=\"delivery\">무료배송</span>\n";
	}
				echo "</dd>\n";
			echo "</dl>\n";
			echo "</a>\n";
			echo "<p class='ic_bx'><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span> <a href='{$it_href}' target='_blank' class='nwin'></a></p>\n";
		echo "</li>\n";
    }

    if($i > 0) {
		echo "</ul>\n</div>\n";
	}
}

// get_listtype_best('영역', '이미지가로', '이미지세로', '총 출력수', '추가 class')
function get_listtype_best($type, $width, $height, $rows, $li_css='')
{
	global $pt_id,$member;

	$result = display_itemtype_new($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		if($i==0) {
			echo "<div class=\"pr_desc2 {$li_css}\">\n<ul>\n";
		}

		$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		if($row['dongurl']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_image = "<video width='$width' height='$height' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
		}elseif($row['dongfile']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_image = "<video width='$width' height='$height' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
		}else{
			$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
		}
		$it_name = cut_str($row['gname'], 100);
			if($member['grade'] > '10'){
			/*	$it_sprice = "<p class=\"spr\">".number_format($row['normal_price'])."<span>원</span>"; */
				$it_price = "<p class='mpr'>회원전용가</p>";
				$sett = round((($row['normal_price'] - $row['goods_price'])/$row['normal_price'])*100);
			/*	$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>'; */
			}else{
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
			}

			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);

		echo "<li>\n";
			echo "<a href='{$it_href}'>\n";
			echo "<dl>\n";
				echo "<dt>{$it_image}</dt>\n";
				echo "<dd>\n<div>\n";
					echo "<p class='pname'>{$it_name}</p>\n";
					if($row['info_color']) {
						echo "<p class=\"op_color\">\n";
						$arr = explode(",", trim($row['info_color']));
						for($g=0; $g<count($arr); $g++) {
							echo get_color_boder(trim($arr[$g]), 1);
						}
						echo "</p>\n";
					}
					//echo $it_sprice;
					echo $it_price;
				echo "</div>\n</dd>\n";
			echo "</dl>\n";
			echo "</a>\n";
			echo "<p class='ic_bx'><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span> <a href='{$it_href}' target='_blank' class='nwin'></a></p>\n";
		echo "</li>\n";
	}

	if($i > 0) {
		echo "</ul>\n</div>\n";
	}
}

// get_listtype_cate('설정값', '이미지가로', '이미지세로')
function get_listtype_cate($list_best, $width, $height)
{
	global $member;
	$mod = 4;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where gcode = '$gcode' and shop_state = '0' and isopen = '1'");
			if(!$row['index_no']) continue;
			if($succ_count >= 12) break;

			$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		if($row['dongurl']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_image = "<video width='$width' height='$height' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
		}elseif($row['dongfile']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_image = "<video width='$width' height='$height' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
		}else{
			$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
		}
			$it_name = cut_str($row['gname'], 100);
			if($member['grade'] > '10'){
			/*	$it_sprice = "<p class=\"spr\">".number_format($row['normal_price'])."<span>원</span>"; */
				$it_price = "<p class='mpr'>회원전용가</p>";
				$sett = round((($row['normal_price'] - $row['goods_price'])/$row['normal_price'])*100);
			/*	$sale = '<p class="sale"><i class="arrow_drop_down"></i>'.number_format($sett,0).'<span>%</span></p>'; */
			}else{
				$it_price = get_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);

			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);

				// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
				$it_sprice = $sale = '';
				if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
					$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				    $sale = '<span class="sale">'.number_format($sett,0).'%</span>';
					$it_sprice = display_price2($row['normal_price']);
				}
			}

			$str .= "<li>\n";
			$str .=		"<a href='{$it_href}'>\n";
           	$str .=		"<dl>\n";
			$str .=			"<dt>{$it_image}</dt>\n";
			$str .=			"<dd class='pname'>{$it_name}</dd>\n";
			//$str .=			"<dd class='price'>{$it_sprice}{$it_price}{$sale}</dd>\n";
			$str .=			"<dd class='price'>{$sale}{$it_sprice}{$it_price}</dd>\n";
            $str .=			"<dd class='icon'>\n";

            	if($row['isnaver']=="1") {
				$str .= "<img class=\"naver\" src=\"/img/icon_new.png\">\n";
					}	
    
	if($beasong == '0'){
			$str .= "<span class=\"delivery\">무료배송</span>\n";
	}
			$str .=		"</dd>\n";
			$str .=		"</dl>\n";
			$str .=		"</a>\n";
			$str .=		"<p class='ic_bx'><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span> <a href='{$it_href}' target='_blank' class='nwin'></a></p>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class='empty_list'>자료가 없습니다.</li>\n";

		$ul_str .= "<ul id='bstab_c{$i}'>\n{$str}</ul>\n";
	}

	return $ul_str;
}

// 게시판 리스트 가져오기
function board_latest($boardid, $len, $rows, $pt_id)
{
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

	$sql = "select * from shop_board_{$boardid} $sql_where order by wdate desc limit $rows ";
	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = cut_str($row['subject'],$len);
		$wdate = date('Y-m-d',intval($row['wdate'],10));
		$href  = MS_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";

		$str .= "<dd><a href=\"{$href}\">{$subject}</a><span class=\"day\">{$wdate}</span></dd>\n";
	}

	return $str;
}

// 회원 총 주문수
function shop_count($mb_id)
{
	if(!$mb_id) return 0;

	$sql = " select count(*) as cnt
			   from shop_order
			  where mb_id = '$mb_id'
				and dan IN(1,2,3,4,5,8) ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

// 회원 총 주문액
function shop_price($mb_id)
{
	if(!$mb_id) return 0;

	$sql = "select SUM(goods_price + baesong_price) as price
			  from shop_order
			 where mb_id = '$mb_id'
			   and dan IN(1,2,3,4,5,8) ";
	$row = sql_fetch($sql);

	return (int)$row['price'];
}

// 승인완료 검사
function admRequest($table, $add_query='')
{
	if($table == 'shop_goods') {
		$filed = "shop_state";
		$value = '1';
	} else if($table == 'shop_goods_qa') {
		$filed = "iq_reply";
		$value = '0';
	} else {
		$filed = "state";
		$value = '0';
	}

	$sql = "select count(*) as cnt from $table where $filed = '$value' {$add_query} ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

function sel_count($table, $where)
{
	$row = sql_fetch("select count(*) as cnt from $table $where ");
	return (int)$row['cnt'];
}

// 주문관리(엑셀저장) 공통
function excel_order_list($row, $amount)
{
    global $encrypted_admin;
	// 결제수단
	$od_paytype = '';
	if($row['paymethod']) {
		$od_paytype = $row['paymethod'];

		if($row['paymethod'] == '간편결제') {
			switch($row['od_pg']) {
				case 'lg':
					$od_paytype = 'PAYNOW';
					break;
				case 'inicis':
					$od_paytype = 'KPAY';
					break;
				case 'kcp':
					$od_paytype = 'PAYCO';
					break;
				default:
					$od_paytype = $row['paymethod'];
					break;
			}
		}
	} else {
		$od_paytype = '결제수단없음';
	}


	// 포인트결제가 포함되어있나?
	if($row['paymethod']!="포인트") {
		if($amount['usepoint'] > 0)
			$od_paytype.= '+포인트';
	}

	// 마일리지결제가 포함되어있나?
	if($row['paymethod']!="마일리지") {
		if($amount['useppay'] > 0)
			$od_paytype.= '+마일리지';
	}

	// 에스크로 결제인가?
	if($row['od_escrow'])
		$od_paytype.= '(에스크로)';

	// 테스트 주문인가?
	$od_test = '';
	if($row['od_test'])
		$od_test = '(테스트)';

	// 모바일 주문인가?
	$od_mobile = 'PC';
	if($row['od_mobile'])
		$od_mobile = '모바일';

	// 주문자가 회원인가?
	if($row['mb_id'])
		$od_mb_id = $row['mb_id'];
	else
		$od_mb_id = '비회원';

	if(!$row['pt_id'] || $row['pt_id'] == $encrypted_admin)
		$od_pt_id = '본사';
	else {
		$mb = get_member($row['pt_id'], 'name');
		if(!$mb['name']) $mb['name'] = '정보없음';
		$od_pt_id = $mb['name'].'('.$row['pt_id'].')';
	}

	// 거래증빙 요청이있나?
	$od_taxbill = '';
	if($row['taxbill_yes'] == 'Y')
		$od_taxbill = "세금계산서 발급요청";
	else if($row['taxsave_yes'] == 'Y' || $row['taxsave_yes'] == 'S')
		$od_taxbill = "현금영수증 발급요청";

	// 배송정보 (예:배송회사|배송추적URL)
	list($delivery_company, $delivery_url) = explode('|', $row['delivery']);

	// 옵션정보
	$it_options = print_complete_options($row['gs_id'], $row['od_id'], 1);

	// 판매자정보
	if($row['seller_id'] == $encrypted_admin) {
		$od_seller_id = '본사';
	} else if(substr($row['seller_id'],0,3) == 'AP-') {
		$sr = get_seller_cd($row['seller_id'], 'company_name');
		if(!$sr['company_name']) $sr['company_name'] = '정보없음';
		$od_seller_id = $sr['company_name'].'('.$row['seller_id'].')';
	} else {
		$mb = get_member($row['seller_id'], 'name');
		if(!$mb['name']) $mb['name'] = '정보없음';
		$od_seller_id = $mb['name'].'('.$row['seller_id'].')';
	}

	// 입금일시가 시간이 비었다면 값을 비운다.
	if(is_null_time($row['receipt_time'])) {
		$row['receipt_time'] = '';
	}

	$info = array();
	$info['od_paytype']			 = $od_paytype;
	$info['od_test']			 = $od_test;
	$info['od_mobile']			 = $od_mobile;
	$info['od_mb_id']			 = $od_mb_id;
	$info['od_pt_id']			 = $od_pt_id;
	$info['od_seller_id']		 = $od_seller_id;
	$info['od_taxbill']			 = $od_taxbill;
	$info['it_options']			 = $it_options;
	$info['od_delivery_company'] = $delivery_company;
	$info['od_receipt_time']	 = $row['receipt_time'];

	return $info;
}

// 주문관리 공통
function get_order_list($row, $amount, $baesong_search='')
{
    global $encrypted_admin;
	// 결제수단
	$disp_paytype = '';
	if($row['paymethod']) {
		$disp_paytype = $row['paymethod'];

		if($row['paymethod'] == '간편결제') {
			switch($row['od_pg']) {
				case 'lg':
					$disp_paytype = 'PAYNOW';
					break;
				case 'inicis':
					$disp_paytype = 'KPAY';
					break;
				case 'kcp':
					$disp_paytype = 'PAYCO';
					break;
				default:
					$disp_paytype = $row['paymethod'];
					break;
			}
		}
	} else {
		$disp_paytype = '결제수단없음';
	}

	// 포인트결제가 포함되어있나?
	if($row['paymethod']!="포인트") {
		if($amount['usepoint'] > 0)
			$disp_paytype.= '<span class="list_point">포인트</span>';
	}

	// 마일리지결제가 포함되어있나?
	if($row['paymethod']!="마일리지") {
		if($amount['useppay'] > 0)
			$disp_paytype.= '<span class="list_point">마일리지</span>';
	}
	// 에스크로 결제인가?
	if($row['od_escrow'])
		$disp_paytype.= '<span class="list_escrow">에스크로</span>';

	// 테스트 주문인가?
	$disp_test = '';
	if($row['od_test'])
		$disp_test = '<span class="list_test">테스트</span>';

	// 모바일 주문인가?
	$disp_mobile = '';
	if($row['od_mobile'])
		$disp_mobile = '(M)';

	// 주문자가 회원인가?
	if($row['mb_id'])
		$disp_mb_id = '<span class="list_mb_id">('.$row['mb_id'].')</span>';
	else
		$disp_mb_id = '<span class="list_mb_id">(비회원)</span>';

	if(!$row['pt_id'] || $row['pt_id'] == $encrypted_admin)
		$disp_pt_id = '본사';
	else {
		$mb = get_member($row['pt_id'], 'name');
		$mb_name = get_sideview($row['pt_id'], $mb['name']);
		if(!$mb_name) $mb_name = '정보없음';
		$disp_pt_id = $mb_name.'<span class="list_mb_id">('.$row['pt_id'].')</span>';
	}

	// 거래증빙 요청이있나?
	if($row['taxbill_yes'] == 'Y')
		$disp_taxbill = "세금계산서 발급요청";
	else if($row['taxsave_yes'] == 'Y' || $row['taxsave_yes'] == 'S')
		$disp_taxbill = "현금영수증 발급요청";
	else
		$disp_taxbill = '<span class="txt_expired">요청안함</span>';

	// 부분배송이 있는가?
	$disp_baesong = '';
	if($baesong_search) {
		$sql = " select count(*) as cnt
				   from shop_order
				  where od_id = '{$row['od_id']}'
					{$baesong_search} ";
		$tmp = sql_fetch($sql);
		if($tmp['cnt'])
			$disp_baesong = '<span class="list_baesong">부분배송</span>';
	}

	$info = array();
	$info['disp_paytype']	 = $disp_paytype;
	$info['disp_test']		 = $disp_test;
	$info['disp_mobile']	 = $disp_mobile;
	$info['disp_mb_id']		 = $disp_mb_id;
	$info['disp_pt_id']		 = $disp_pt_id;
	$info['disp_taxbill']	 = $disp_taxbill;
	$info['disp_od_name']	 = get_sideview($row['mb_id'], $row['name']);
	$info['disp_baesong']	 = $disp_baesong;
	$info['disp_price']		 = number_format($amount['buyprice']);

	return $info;
}

// 주문관리 판매자
function get_order_seller_id($seller_id)
{
	if($seller_id == encrypted_admin()) {
		$disp_sr_id = '본사';
	} else if(substr($seller_id,0,3) == 'AP-') {
		$sr = get_seller_cd($seller_id, 'mb_id');
		$disp_sr_id = get_sideview($sr['mb_id'], $seller_id);
	} else {
		$disp_sr_id = get_sideview($seller_id, $seller_id);
	}

	return $disp_sr_id;
}

// 주문상태에 따른 합계 금액
function admin_order_status_sum($where)
{
	$sql = " select od_id from shop_order {$where} group by od_id ";
	$res = sql_query($sql);
	$od_count = sql_num_rows($res);

	$sql = " select SUM(goods_price + baesong_price) as price from shop_order {$where} ";
	$row = sql_fetch($sql);
	$od_price = (int)$row['price'];

	$info = array();
	$info['cnt']   = $od_count;
	$info['price'] = $od_price;

	return $info;
}

// 총 재고부족 상품
function admin_gs_jaego_bujog($add_query='')
{
	$sql = " select count(*) as cnt
			   from shop_goods
			  where stock_qty <= noti_qty and stock_mod = 1 and opt_subject = ''
				{$add_query} ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

// 총 옵션재고부족 상품
function admin_io_jaego_bujog($add_query='')
{
	$sql = " select count(*) as cnt
			   from shop_goods_option a left join shop_goods b on (a.gs_id=b.index_no)
			  where a.io_use = 1
			    and a.io_noti_qty <> '999999999'
			    and a.io_stock_qty <= a.io_noti_qty
				{$add_query} ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

// 총 주문 관리자메모
function admin_order_memo($add_query='')
{
	$sql = " select od_id from shop_order where shop_memo <> '' {$add_query} group by od_id ";
	$res = sql_query($sql);
	return sql_num_rows($res);
}

// 총 상품평점 수
function admin_goods_review($add_query='')
{
	$row = sql_fetch("select count(*) as cnt from shop_goods_review where 1 {$add_query} ");
	return (int)$row['cnt'];
}

//  주문관리에 사용될 배송업체 정보를 select로 얻음
function get_delivery_select($name, $selected='', $event='')
{
	global $config;

	$str = "<select class=\"delivery-company\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">배송사선택</option>\n";
	$info = array_filter(explode(",",trim($config['delivery_company'])));
	foreach($info as $k=>$v) {
		$arr = explode("|",trim($info[$k]));
		if(trim($arr[0])){
			$str .= option_selected($info[$k], $selected, trim($arr[0]));
		}
	}
	$str .= "</select>";

	return $str;
}

//  송장번호 일괄등록시 배송추척 URL 추출 (본사, 업체 공용)
function get_info_delivery($company)
{
	global $config;

	if(!$company) return '';

	$fld = trim($company);

	$info = array_filter(explode(",",$config['delivery_company']));
	foreach($info as $k=>$v) {
		$arr = explode("|",trim($info[$k]));
		if(trim($arr[0]) == trim($company)){
			$fld = trim($info[$k]);
			break;
		}
	}

	return $fld;
}

// 쿠폰 : 상세내역
function get_cp_contents($row)
{
	global $gw_usepart;

	$str = "";
	$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";

	// 동시사용 여부
	$str .= "<div class='fc_eb7'>&#183; ";
	if(!$row['cp_dups']) {
		$str .= '동일한 주문건에 중복할인 가능';
	} else {
		$str .= '동일한 주문건에 중복할인 불가 (1회만 사용가능)';
	}
	$str .= "</div>";

	// 쿠폰유효 기간
	$str .= "<div>&#183; 쿠폰유효 기간 : ";
	if(!$row['cp_inv_type']) {
		// 날짜
		if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
		else $cp_inv_sdate = $row['cp_inv_sdate'];

		if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
		else $cp_inv_edate = $row['cp_inv_edate'];

		if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_sdate'] == '9999999999')
			$str .= '제한없음';
		else
			$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate ;

		// 시간대
		$str .= "&nbsp;(시간대 : ";
		if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
		else $cp_inv_shour1 = $row['cp_inv_shour1'] . "시부터";

		if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
		else $cp_inv_shour2 = $row['cp_inv_shour2'] . "시까지";

		if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour1'] == '99')
			$str .= '제한없음';
		else
			$str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
		$str .= ")";
	} else {
		$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",strtotime($row['cp_wdate'])));
		$str .= '다운로드 완료 후 ' . $row['cp_inv_day']. '일간 사용가능, 만료일('.$cp_inv_day.')';
	}
	$str .= "</div>";

	// 혜택
	$str .= "<div>&#183; ";
	if($row['cp_sale_type'] == '0') {
		if($row['cp_sale_amt_max'] > 0)
			$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max'])."까지 할인)";
		else
			$cp_sale_amt_max = "";

		$str .= $row['cp_sale_percent']. '% 할인' . $cp_sale_amt_max;
	} else {
		$str .= display_price($row['cp_sale_amt']). ' 할인';
	}
	$str .= "</div>";

	// 최대금액
	if($row['cp_low_amt'] > 0) {
		$str .= "<div>&#183; ".display_price($row['cp_low_amt'])." 이상 구매시</div>";
	}

	// 사용가능대상
	$str .= "<div>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

	return $str;
}

// 상품 브랜드명 정보의 배열을 리턴
function get_brand_chk($br_name, $mb_id='')
{
	$sql_search  = " and ( br_user_yes = '0' ";
	if($mb_id) $sql_search .= " or (br_user_yes='1' and mb_id=TRIM('$mb_id')) ";
	$sql_search .= " ) ";

	$row = sql_fetch("select br_id from shop_brand where br_name=TRIM('$br_name') $sql_search " );
	if($row['br_id'])
		return $row['br_id'];
	else
		return '';
}

// 상품 가격정보의 배열을 리턴
function get_price($gs_id, $msg='<span>원</span>')
{
	global $member, $is_member;

	$gs = get_goods($gs_id, 'index_no, price_msg, buy_level, buy_only');

	$price = get_sale_price($gs_id);

	// 재고가 한정상태이고 재고가 없을때, 품절상태일때..
	if(is_soldout($gs['index_no'])) {
		$str = "<p class='soldout'>품절</p>";
	} else {
		if($gs['price_msg']) {
			$str = $gs['price_msg'];
		} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
			$str = "<p class='mpr'>회원전용가</p>";
		} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
			if(!$is_member)
				$str = "<p class='mpr'>회원전용가</p>";
			else
				$str = "<p class='mpr'>".number_format($price).$msg."</p>";
		} else {
			$str = "<p class='mpr'>".number_format($price).$msg."</p>";
		}
	}

	return $str;
}

//  상품 상세페이지 구매하기, 장바구니, 찜 버튼
function get_buy_button($msg, $gs_id, $imax=3)
{
	global $gs, $pt_id;

	$str = "";
	for($i=1; $i<=$imax; $i++) {
		switch($i){
			case '1':
				$sw_css = " wset";
				$sw_name = "구매하기";
				$sw_direct = "buy";
				break;
			case '2':
				$sw_css = " grey";
				$sw_name = "장바구니";
				$sw_direct = "cart";
				break;
			case '3':
				$sw_css = " bx-white";
				$sw_name = "찜하기";
				$sw_direct = "wish";
				break;
		}

		if($msg) {
			$str .= "<span><a href=\"javascript:alert('$msg');\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
		} else {
			if($sw_direct == "wish") {
				$str .= "<span><a href=\"javascript:item_wish(document.fbuyform);\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
			} else if($sw_direct == "cart") {
				$str .= "<span><a href=\"#\" id=\"requestBtn\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
			} else {
				$str .= "<span><a href=\"javascript:fbuyform_submit('".$sw_direct."');\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
			}
		}
	}

	return $str;
}

//  등록된 상품이미지 미리보기
function get_look_ahead($it_img, $it_img_del)
{
	if(!trim($it_img)) return;

	if(preg_match("/^(http[s]?:\/\/)/", $it_img) == true)
		$file_url = $it_img;
	else
		$file_url = MS_DATA_URL."/goods/".$it_img;

	$str  = "<a href='{$file_url}' target='_blank' class='btn_small bx-white marr7'>미리보기</a> <label class='marr7'><input type='checkbox' name='{$it_img_del}' value='{$it_img}'>삭제</label>";

	return $str;
}

function get_pagecode($code)
{
	$value_code	= is_array($code) ? $code : array($code);
	$value_code	= implode(",", $value_code);

	return $value_code;
}

// 쿠폰번호 생성함수
function get_coupon_id($reg_type='1')
{
    $len = 16;

	if($reg_type)
		$chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
	else
		$chars = "1234567890";

    srand((double)microtime()*1000000);

    $i = 0;
    $str = '';

    while($i < $len) {
        $num = rand() % strlen($chars);
        $tmp = substr($chars, $num, 1);
        $str .= $tmp;
        $i++;
    }

    $str = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "\\1-\\2-\\3-\\4", $str);

    return $str;
}

// 적립금 (상품수정)
function get_gpoint($price, $marper, $point)
{
	if($marper){
		return round($price * $marper/100);
	} else {
		return conv_number($point);
	}
}

// 카테고리 페이지경로
function get_move($ca_id)
{
	global $ms;

	$str = "";

	$len = strlen($ca_id);
	for($i=1;$i<=($len/3);$i++) {
		$cut_id = substr($ca_id,0,($i*3));
		$row = sql_fetch("select * from {$ms['category_table']} where catecode='$cut_id' ");

		$href = MS_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

		$str = $str." <i class=\"ionicons ion-ios-arrow-right\"></i> "."<a href='{$href}'>{$row['catename']}</a>";
	}

	return $str;
}

// 본사, 공급업체 공용 (상품등록, 수정폼에서 사용)
function get_move_admin($ca_id)
{
	if(!$ca_id) return '';

	$catename = array();
	for($i=1; $i<=(strlen($ca_id)/3); $i++) {
		$cut_id = substr($ca_id,0,($i*3));
		$row = sql_fetch("select * from shop_cate where catecode='$cut_id'");
		if($row['catecode']) {
			$catename[] = $row['catename'];
		}
	}

	$str = implode(" &gt; ", $catename);

	return $str;
}

// 가맹점전용 (상품등록, 수정폼에서 사용)
function get_move_aff($ca_id, $mb_id='')
{
	if(!$mb_id)
		global $member;
	else
		$member['id'] = $mb_id;

	$mb_id = $member['id'];
	$target_table = 'shop_cate_'.$mb_id;

	$catename = array();
	for($i=1; $i<=(strlen($ca_id)/3); $i++) {
		$cut_id = substr($ca_id,0,($i*3));
		$row = sql_fetch("select * from {$target_table} where catecode='$cut_id'");
		if($row['catecode']) {
			$catename[] = $row['catename'];
		}
	}

	$str = implode(" &gt; ", $catename);

	return $str;
}

// 권한체크 후 링크호출
function get_admin($mb_id)
{
    if(!$mb_id) return;

    if(is_admin())
		return MS_ADMIN_URL.'/';
    if(is_minishop($mb_id))
		return MS_MYPAGE_URL.'/page.php?code=minishop_info';
    if(is_seller($mb_id))
		return MS_MYPAGE_URL.'/page.php?code=seller_main';

	return '';
}

// 카테고리를 SELECT 형식으로 얻음 (본사, 공급사 공통)
function get_goods_sca_select($name, $selected='', $event='')
{
	$str = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";
	$str .= "<option value=''>선택</option>\n";

	$sql_common = " from shop_cate ";
	$sql_order  = " order by list_view asc ";

	$r = sql_query("select * $sql_common where upcate='' $sql_order ");
	while($row=sql_fetch_array($r))	{
		$str .= "<option value='$row[catecode]'";
		if($row['catecode'] == $selected)
			$str .= " selected";
		$str .= ">[1]$row[catename]</option>\n";

		$r2 = sql_query("select * $sql_common where upcate='$row[catecode]' $sql_order ");
		while($row2=sql_fetch_array($r2)) {
			$len = strlen($row2['catecode']) / 3 - 1;
			$nbsp = "";
			for($i=0; $i<$len; $i++) {
				$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			$str .= "<option value='$row2[catecode]'";
			if($row2['catecode'] == $selected)
				$str .= " selected";
			$str .= ">{$nbsp}[2]$row2[catename]</option>\n";

			$r3 = sql_query("select * $sql_common where upcate='$row2[catecode]' $sql_order ");
			while($row3=sql_fetch_array($r3)){
				$len = strlen($row3['catecode']) / 3 - 1;
				$nbsp = "";
				for($i=0; $i<$len; $i++) {
					$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				$str .= "<option value='$row3[catecode]'";
				if($row3['catecode'] == $selected)
					$str .= " selected";
				$str .= ">{$nbsp}[3]$row3[catename]</option>\n";

				$r4 = sql_query("select * $sql_common where upcate='$row3[catecode]' $sql_order ");
				while($row4=sql_fetch_array($r4)){
					$len = strlen($row4['catecode']) / 3 - 1;
					$nbsp = "";
					for($i=0; $i<$len; $i++) {
						$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					}
					$str .= "<option value='$row4[catecode]'";
					if($row4['catecode'] == $selected)
						$str .= " selected";
					$str .= ">{$nbsp}[4]$row4[catename]</option>\n";

					$r5 = sql_query("select * $sql_common where upcate='$row4[catecode]' $sql_order ");
					while($row5=sql_fetch_array($r5)){
						$len = strlen($row5['catecode']) / 3 - 1;
						$nbsp = "";
						for($i=0; $i<$len; $i++) {
							$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						$str .= "<option value='$row5[catecode]'";
						if($row5['catecode'] == $selected)
							$str .= " selected";
						$str .= ">{$nbsp}[5]$row5[catename]</option>\n";
					} //5
				} //4
			} //3
		} //2
	} //1
	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_member_level_select($name, $start_id=0, $end_id=10, $selected='', $event='')
{
	global $board;

	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";
	for($i=$start_id; $i<=$end_id; $i++)
	{
		$grade = get_grade($i);
		if($grade) {
			$str .= "<option value='$i'";
			if($i == $selected)
				$str .= " selected";
			$str .= ">$grade</option>\n";
		}
	}

	if($board[$name] == '99')
		$sel = " selected";
	$str .= "<option value='99'{$sel}>비회원</option>\n";
	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_level_select($name, $start_id=1, $end_id=9, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";

	$sql= "select * from shop_member where (grade>='$start_id' and grade<='$end_id') order by name";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['id']}'";
		if($row['id'] == $selected)
			$str .= " selected";
		$str .= ">{$row['name']} (".$row['id'].")</option>\n";
	}

	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_member_select($name, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";

	$sql= "select * from shop_member_grade where gb_name <> '' order by gb_no desc";
	$result = sql_query($sql);
	$str .= "<option value=''>전체</option>";
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['gb_no']}' data-anew-price='{$row['gb_anew_price']}' " ;
		if($row['gb_no'] == $selected)
			$str .= " selected";
		$str .= ">[{$row['gb_no']}] {$row['gb_name']}</option>\n";
	}

	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_goods_level_select($name, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";
	$str .= "<option value='10'>제한없음</option>\n";

	$sql= "select * from shop_member_grade where gb_name <> '' and gb_no > 1 order by gb_no desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['gb_no']}'";
		if($row['gb_no'] == $selected)
			$str .= " selected";
		$str .= ">[{$row['gb_no']}] {$row['gb_name']}</option>\n";
	}

	$str .= "</select>\n";

	return $str;
}

// 날짜를 select 박스 형식으로 얻는다
function date_select($date, $name="", $date_y, $date_m, $date_d)
{
	$s = "";
	if(substr($date, 0, 4) == "0000") {
		$date = MS_TIME_YMDHIS;
	}
	preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

	// 년
	$s .= "<select name='{$name}_y'>";
	$s .= "<option value='0000'>선택";
	for($i=$m[0]-3; $i<=$m[0]+3; $i++) {
		$s .= "<option value='$i'";
		if($date_y == $i) {
			$s .= " selected";
		}
		$s .= ">$i";
	}
	$s .= "</select>년 \n";

	// 월
	$s .= "<select name='{$name}_m'>";
	$s .= "<option value='00'>선택";
	for($i=1; $i<=12; $i++) {
		$ms = sprintf('%02d',$i);
		$s .= "<option value='$ms'";
		if($date_m == $ms) {
			$s .= " selected";
		}
		$s .= ">$ms";
	}
	$s .= "</select>월 \n";

	// 일
	$s .= "<select name='{$name}_d'>";
	$s .= "<option value='00'>선택";
	for($i=1; $i<=31; $i++) {
		$ds = sprintf('%02d',$i);
		$s .= "<option value='$ds'";
		if($date_d == $ds) {
			$s .= " selected";
		}
		$s .= ">$ds";
	}
	$s .= "</select>일 \n";

	return $s;
}

// 입력 폼 안내문
function help($help="", $addclass='fc_125')
{
	$help = str_replace("\n", "<br>", $help);

	if($addclass == 1) {
		$str = '<span class="tooltip"><i class="fa fa-question-circle"></i><span class="tooltiptext">'.$help.'</span></span>';
	} else {
		$str = '<span class="frm_info';
		if($addclass) $str.= ' '.$addclass;
		$str.= '">'.$help.'</span>';
	}

    return $str;
}

// 계좌정보를 select 박스 형식으로 얻는다
function get_bank_account($name, $selected='')
{
	global $default;

	$str  = '<select id="'.$name.'" name="'.$name.'">'.PHP_EOL;
	$str .= '<option value="">선택하십시오</option>'.PHP_EOL;

	$bank = unserialize($default['de_bank_account']);
	for($i=0; $i<5; $i++) {
		$bank_account = $bank[$i]['name'].' '.$bank[$i]['account'].' '.$bank[$i]['holder'];
		if(trim($bank_account)) {
			$str .= option_selected($bank_account, $selected, $bank_account);
		}
	}
	$str .= '</select>'.PHP_EOL;

	return $str;
}

// 게시판 그룹을 SELECT 형식으로 얻음
function get_group_select($name, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";

	$sql = " select gr_id, gr_subject from shop_board_group order by gr_id desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['gr_id']}'";
		if($row['gr_id'] == $selected) $str .= " selected";
		$str .= ">{$row['gr_subject']}</option>\n";
	}
	$str .= "</select>\n";

	return $str;
}

// 게시판 그룹을 SELECT 형식으로 얻음
function get_group_select2($name, $selected='', $event='')
{
    global $ms, $is_admin, $member;

    $sql = " select gr_id, gr_subject from g5_group a ";
    if ($is_admin == "group") {
        $sql .= " left join shop_member b on (b.id = a.gr_admin)
                  where b.id = '{$member['id']}' ";
    }
    $sql .= " order by a.gr_id ";

    $result = sql_query($sql);
    $str = "<select id=\"$name\" name=\"$name\" $event>\n";
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i == 0) $str .= "<option value=\"\">선택</option>";
        $str .= option_selected($row['gr_id'], $selected, $row['gr_subject']);
    }
    $str .= "</select>";
    return $str;
}


// 주문 진행상태를 select로 얻음
function get_change_select($name, $selected='', $event='')
{
	global $gw_status, $gw_array_status;

	// 취소,반품,교환,환불 건은 텍스트형식으로만 노출
	if(!in_array($selected, array(2,3,4,5))) {
		return $gw_status[$selected];
	}

	$str = "<select name=\"{$name}\"{$event}>\n";
	foreach($gw_array_status as $key=>$val) {
		if($key != $selected) continue;

		$str .= option_selected($key, $selected, $gw_status[$key]);
		foreach($val as $dan) {
			$str .= option_selected($dan, '', $gw_status[$dan]);
		}
	}
	$str .= "</select>";

	return $str;
}

// 상품 선택옵션
function get_item_options($gs_id, $subject)
{
	if(!$gs_id || !$subject)
		return '';

	$amt = get_sale_price($gs_id);

	$sql = " select * from shop_goods_option where io_type = '0' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';
	$subj = explode(',', $subject);
	$subj_count = count($subj);

	if($subj_count > 1) {
		$options = array();

		// 옵션항목 배열에 저장
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$opt_id = explode(chr(30), $row['io_id']);

			for($k=0; $k<$subj_count; $k++) {
				if(!is_array($options[$k]))
					$options[$k] = array();

				if($opt_id[$k] && !in_array($opt_id[$k], $options[$k]))
					$options[$k][] = $opt_id[$k];
			}
		}

		// 옵션선택목록 만들기
		for($i=0; $i<$subj_count; $i++) {
			$opt = $options[$i];
			$opt_count = count($opt);
			$disabled = '';
			if($opt_count) {
				$seq = $i + 1;
				if($i > 0)
					$disabled = ' disabled="disabled"';
				$str .= '<dl>'.PHP_EOL;
				$str .= '<dt><label for="it_option_'.$seq.'">'.$subj[$i].'</label></dt>'.PHP_EOL;

				$select  = '<select id="it_option_'.$seq.'" class="it_option wfull"'.$disabled.'>'.PHP_EOL;
				$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
				for($k=0; $k<$opt_count; $k++) {
					$opt_val = $opt[$k];
					if($opt_val) {
						$select .= '<option value="'.$opt_val.'">'.$opt_val.'</option>'.PHP_EOL;
					}
				}
				$select .= '</select>'.PHP_EOL;

				$str .= '<dd class="li_select">'.$select.'</dd>'.PHP_EOL;
				$str .= '</dl>'.PHP_EOL;
			}
		}
	} else {
		$str .= '<dl>'.PHP_EOL;
		$str .= '<dt><label for="it_option_1">'.$subj[0].'</label></dt>'.PHP_EOL;

		$select  = '<select id="it_option_1" class="it_option wfull">'.PHP_EOL;
		$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';

			if(!$row['io_stock_qty'])
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$select .= '<option value="'.$row['io_id'].','.$row['io_price'].','.$row['io_stock_qty'].','.$amt.'">'.$row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
		}
		$select .= '</select>'.PHP_EOL;

		$str .= '<dd class="li_select">'.$select.'</dd>'.PHP_EOL;
		$str .= '</dl>'.PHP_EOL;
	}

	return $str;
}

// 상품 추가옵션
function get_item_supply($gs_id, $subject)
{
	if(!$gs_id || !$subject)
		return '';

	$sql = " select * from shop_goods_option where io_type = '1' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';

	$subj = explode(',', $subject);
	$subj_count = count($subj);
	$options = array();

	// 옵션항목 배열에 저장
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$opt_id = explode(chr(30), $row['io_id']);

		if($opt_id[0] && !array_key_exists($opt_id[0], $options))
			$options[$opt_id[0]] = array();

		if($opt_id[1]) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';
			$io_stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

			if($io_stock_qty < 1)
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$options[$opt_id[0]][] = '<option value="'.$opt_id[1].','.$row['io_price'].','.$io_stock_qty.',0">'.$opt_id[1].$price.$soldout.'</option>';
		}
	}

	// 옵션항목 만들기
	for($i=0; $i<$subj_count; $i++) {
		$opt = $options[$subj[$i]];
		$opt_count = count($opt);
		if($opt_count) {
			$seq = $i + 1;
			$str .= '<dl>'.PHP_EOL;
			$str .= '<dt><label for="it_supply_'.$seq.'">'.$subj[$i].'</label></dt>'.PHP_EOL;

			$select = '<select id="it_supply_'.$seq.'" class="it_supply wfull">'.PHP_EOL;
			$select .= '<option value="">선택안함</option>'.PHP_EOL;
			for($k=0; $k<$opt_count; $k++) {
				$opt_val = $opt[$k];
				if($opt_val) {
					$select .= $opt_val.PHP_EOL;
				}
			}
			$select .= '</select>'.PHP_EOL;

			$str .= '<dd class="li_select">'.$select.'</dd>'.PHP_EOL;
			$str .= '</dl>'.PHP_EOL;
		}
	}

	return $str;
}

// 주문완료 옵션호출
function print_complete_options($gs_id, $od_id, $xls='')
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where od_id = '$od_id' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$comma = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0 && !$xls)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		if(!$xls) {

			if($row['io_type'])
				$str .= "<li class='ny'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
			else
				$str .= "<li class='ty'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
		} else {

			$str .= $comma.$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")".PHP_EOL;

			$str = trim($str);
			$comma = '|';
		}
	}

	if($i > 0 && !$xls)
		$str .= '</ul>';

	return $str;
}

// 장바구니 옵션호출
function print_item_options($gs_id, $set_cart_id)
{

	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where gs_id = '$gs_id' and ct_direct='$set_cart_id' and ct_select='0' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id'] && mb_substr($row['ct_option'], 0, 4) != '후원ID') continue;

        $price_plus = '';
        if($row['io_price'] > 0)
            $price_plus = ' (+'.display_price($row['io_price']).')';

		// 추가상품일때
		if($row['io_type'])
			$str .= "<li class='ny'>".$row['ct_option']." ".display_qty($row['ct_qty']).$price_plus."</li>".PHP_EOL;
		else
			$str .= "<li class='ty'>".$row['ct_option']." ".display_qty($row['ct_qty']).$price_plus."</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 상품상세페이지 : 배송비 구함
function get_sendcost_amt()
{
	global $gs, $config, $sr;

	// 공통설정
	if($gs['sc_type']=='0') {
		if($gs['mb_id'] == encrypted_admin()) {
			$delivery_method  = $config['delivery_method'];
			$delivery_price   = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else {
			$delivery_method  = $sr['delivery_method'];
			$delivery_price	  = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) {
			case '1':
				$str = "무료배송";
				break;
			case '2':
				$str = "상품수령시 결제(착불)";
				break;
			case '3':
				$str = display_price($delivery_price);
				break;
			case '4':
				$str = display_price($delivery_price2)."&nbsp;(".display_price($delivery_minimum)." 이상 구매시 무료)";
				break;
		}

		// sc_type(배송비 유형)   0:공통설정, 1:무료배송, 2:조건부무료배송, 3:유료배송
		// sc_method(배송비 결제) 0:선불, 1:착불, 2:사용자선택
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1)
				$str = '상품수령시 결제(착불)';
			else if($gs['sc_method'] == 2) {
				$str = "<select name=\"ct_send_cost\">
							<option value='0'>주문시 결제(선결제)</option>
							<option value='1'>상품수령시 결제(착불)</option>
						</select>";
			}
		}
	}

	// 무료배송
	else if($gs['sc_type']=='1') {
		$str = "무료배송";
	}

	// 조건부 무료배송
	else if($gs['sc_type']=='2') {
		$str = display_price($gs['sc_amt'])."&nbsp;(".display_price($gs['sc_minimum'])." 이상 구매시 무료)";
	}

	// 유료배송
	else if($gs['sc_type']=='3') {
		$str = display_price($gs['sc_amt']);
	}

	// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1)
			$str = '상품수령시 결제(착불)';
		else if($gs['sc_method'] == 2) {
			$str = "<select name=\"ct_send_cost\">
						<option value='0'>주문시 결제(선결제)</option>
						<option value='1'>상품수령시 결제(착불)</option>
					</select>";
		}
	}

	return $str;
}

// 배송비 구함
function get_sendcost_amt2($gs_id, $it_price)
{
	global $config;

	$gs = get_goods($gs_id);

    if(!$gs['index_no'])
        return 0;

	if($gs['use_aff'])
		$sr = get_minishop($gs['mb_id']);
	else
		$sr = get_seller_cd($gs['mb_id']);

	// 공통설정
	if($gs['sc_type']=='0') {

		if($gs['mb_id'] == encrypted_admin()) {
			$delivery_method  = $config['delivery_method'];
			$delivery_price	  = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else {
			$delivery_method  = $sr['delivery_method'];
			$delivery_price	  = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) {
			case '1':
			case '2':
				$sendcost = 0;
				break;
			case '3':
				$sendcost = (int)$delivery_price;
				break;
			case '4':
                if($it_price >= (int)$delivery_minimum)
                    $sendcost = 0;
                else
                    $sendcost = (int)$delivery_price2;
				break;
		}

		// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부무료배송, 3:유료배송
		// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1) {
				$sendcost = 0;
			}
		}
	}

	// 무료배송
	else if($gs['sc_type']=='1') {
		$sendcost = 0;
	}

	// 조건부 무료배송
	else if($gs['sc_type']=='2') {
		if($it_price >= (int)$gs['sc_minimum'])
			$sendcost = 0;
		else
			$sendcost = (int)$gs['sc_amt'];
	}

	// 유료배송
	else if($gs['sc_type']=='3') {
		$sendcost = (int)$gs['sc_amt'];
	}

	// sc_type(배송비 유형)   0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제) 0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1) {
			$sendcost = 0;
		}
	}

	return $sendcost;
}

// 카테고리번호 생성
function get_ca_depth($tablename, $upcate)
{
	$sql_fld = " MAX(catecode) as max_caid ";

	$ca = sql_fetch("select {$sql_fld} from {$tablename} where upcate = '$upcate' ");
	$max_caid = $ca['max_caid'] + 1;

	if(strlen($max_caid)%3 == 1) {
		$new_code = '00'.$max_caid;
	} else if(strlen($max_caid)%3 == 2) {
		$new_code = '0'.$max_caid;
	} else {
		$new_code = $max_caid;
	}

	$new_code = substr($new_code,-3);
	$new_code = $upcate.$new_code;

	return $new_code;
}

//  카테고리번호 생성
function get_up_depth($tablename, $upcate)
{
	$sql = "select catecode,upcate from {$tablename} where p_catecode = '$upcate' and p_oper = 'y' ";
	$ca = sql_fetch($sql);

	$len = strlen($ca['catecode']);

	$sql = "select MAX(catecode) as max_caid
			  from {$tablename}
			 where left(catecode,$len) = '$ca[catecode]'
			   and upcate = '$ca[catecode]'
			   and upcate <> '' ";
	$row = sql_fetch($sql);
	$max_caid = substr($row['max_caid'],-3) + 1;

	if(strlen($max_caid)%3 == 1) {
		$new_code = '00'.$max_caid;
	} else if(strlen($max_caid)%3 == 2) {
		$new_code = '0'.$max_caid;
	} else {
		$new_code = $max_caid;
	}

	$new_code = $ca['catecode'].$new_code;

	return $new_code;
}

// 분류별 상단배너
function get_category_head_image($ca_id)
{
	global $ms, $pt_id;

	$cgy = array();

	$sql = "select * from {$ms['category_table']} where catecode = '".substr($ca_id,0,3)."' limit 1 ";
	$row = sql_fetch($sql);

	$file = MS_DATA_PATH.'/category/'.$pt_id.'/'.$row['img_head'];
	if(is_file($file) && $row['img_head']) {
		if($row['img_head_url']) {
			$a1 = '<a href="'.$row['img_head_url'].'">';
			$a2 = '</a>';
		}

		$file = rpc($file, MS_PATH, MS_URL);
		$cgy['img_head'] = $a1.'<img src="'.$file.'">'.$a2;
	}

	return $cgy;
}

// 날짜검색
function get_search_date($fr_date, $to_date, $fr_val, $to_val, $is_last=true)
{
	$input_end = ' class="frm_input w80" maxlength="10">'.PHP_EOL;
	$js = " onclick=\"search_date('{$fr_date}','{$to_date}',this.value);\"";

	$frm = array();
	$frm[] = '<label for="'.$fr_date.'" class="sound_only">시작일</label>'.PHP_EOL;
	$frm[] = '<input type="text" name="'.$fr_date.'" value="'.$fr_val.'" id="'.$fr_date.'"'.$input_end;
	$frm[] = ' ~ '.PHP_EOL;
	$frm[] = '<label for="'.$to_date.'" class="sound_only">종료일</label>'.PHP_EOL;
	$frm[] = '<input type="text" name="'.$to_date.'" value="'.$to_val.'" id="'.$to_date.'"'.$input_end;
	$frm[] = '<span class="btn_group">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="오늘">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="어제">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="일주일">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="지난달">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="1개월">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="3개월">';
	if($is_last) $frm[] = '<input type="button"'.$js.' class="btn_small white" value="전체">';
	$frm[] = '</span>';

	return implode('', $frm);
}

// 필수 옵션 Excel 일괄등록
function insert_option($gs_id, $opt, $qty, $prc, $opt_use)
{
	if(!$gs_id || !$opt || !$qty || !$prc)
		return;

	$gs_id = trim($gs_id);
	$opt = trim($opt);
	$qty = trim($qty);
	$prc = trim($prc);

	$arr = explode('/', $opt);
	$opt1_val = $arr[0];
	$opt2_val = $arr[1];
	$opt3_val = $arr[2];
	$opt4_val = $arr[3];
	$opt5_val = $arr[4];

	$arr = explode('/', $prc);
	$prc1_val = $arr[0];
	$prc2_val = $arr[1];
	$prc3_val = $arr[2];
	$prc4_val = $arr[3];
	$prc5_val = $arr[4];

	$qty1 = explode('^', $qty);

	$opt1_count = $opt2_count = $opt3_count = $opt4_count = $opt5_count = 0;

	if($opt1_val) {
		$prc1 = explode('^', $prc1_val);
		$opt1 = explode('^', $opt1_val);
		$opt1_count = count($opt1);
	}
	if($opt2_val) {
		$prc2 = explode('^', $prc2_val);
		$opt2 = explode('^', $opt2_val);
		$opt2_count = count($opt2);
	}
	if($opt3_val) {
		$prc3 = explode('^', $prc3_val);
		$opt3 = explode('^', $opt3_val);
		$opt3_count = count($opt3);
	}
	if($opt4_val) {
		$prc4 = explode('^', $prc4_val);
		$opt4 = explode('^', $opt4_val);
		$opt4_count = count($opt4);
	}
	if($opt5_val) {
		$prc5 = explode('^', $prc5_val);
		$opt5 = explode('^', $opt5_val);
		$opt5_count = count($opt5);
	}

	for($i=0; $i<$opt1_count; $i++) {
		$j = 0;
		do {
			$k = 0;
			do {
				$m = 0;
				do {
					$n = 0;
					do {
						$opt_1 = strip_tags(trim($opt1[$i]));
						$opt_2 = strip_tags(trim($opt2[$j]));
						$opt_3 = strip_tags(trim($opt3[$k]));
						$opt_4 = strip_tags(trim($opt4[$m]));
						$opt_5 = strip_tags(trim($opt5[$n]));

						$prc_1 = (int)(trim($prc1[$i]));
						$prc_2 = (int)(trim($prc2[$j]));
						$prc_3 = (int)(trim($prc3[$k]));
						$prc_4 = (int)(trim($prc4[$m]));
						$prc_5 = (int)(trim($prc5[$n]));

						$opt_stock_qty = (int)(trim($qty1[$i]));

						$opt_id = $opt_1;
						$opt_price = $prc_1;

						if($opt_2) {
							$opt_id .= chr(30).$opt_2;
							$opt_price = $prc_2;
						}
						if($opt_3) {
							$opt_id .= chr(30).$opt_3;
							$opt_price = $prc_3;
						}
						if($opt_4) {
							$opt_id .= chr(30).$opt_4;
							$opt_price = $prc_4;
						}
						if($opt_5) {
							$opt_id .= chr(30).$opt_5;
							$opt_price = $prc_5;
						}

						// 옵션등록
						$sql = " insert into shop_goods_option
										( `io_id`, `io_type`, `gs_id`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use` )
								 VALUES ( '$opt_id', '0', '$gs_id', '$opt_price', '$opt_stock_qty', '0', '$opt_use' )";
						sql_query($sql);

						$n++;
					} while($n < $opt5_count);

					$m++;
				} while($m < $opt4_count);

				$k++;
			} while($k < $opt3_count);

			$j++;
		} while($j < $opt2_count);
	} // for
}

// 카테고리 공통
function get_admin_category($target_table, $upcate='')
{
	global $adm_category_yes;

	if( $target_table != 'shop_cate' ) {
	    $mb_id = str_replace('shop_cate_', '', $target_table);
	    sql_member_category($mb_id);
    }
    $sql = " select catecode, catename from {$target_table} where upcate = '$upcate' ";
    if($target_table != 'shop_cate') $sql .= " and p_hide = '0' ";
	if($adm_category_yes) $sql .= " and p_oper = 'y' ";
	$sql .= " order by list_view, catecode ";

    return $sql;
}

// 카테고리정보 불러오기
function get_cgy_info($gs)
{
	$str = "";
	$tCount = -1;

	$sql = "select * from shop_goods_cate where gs_id='$gs[index_no]' order by index_no asc ";
	$result = sql_query($sql);
	while($row = sql_fetch_array($result)) {
		if($gs['use_aff'] && is_minishop($gs['mb_id'])) {
			$info = '<span class="fsitem">'.get_move_aff($row['gcate'], $gs['mb_id']).'</span>';
		} else {
			$info = '<span class="fsitem">'.get_move_admin($row['gcate']).'</span>';
		}
		if(!$str) $str = $info;
		$tCount++;
	}

	if($tCount > 0) $str .= ' 외 '.$tCount.'건';

	return $str;
}

function get_seller_name($mb_id)
{
	global $config;

	$sellerName = '';

	if(substr($mb_id,0,3) == 'AP-') {
		$row = sql_fetch("select company_name from shop_seller where seller_code = '$mb_id'");
		$sellerName = $row['company_name'];
	} else if($mb_id == encrypted_admin()) {
		$sellerName = $config['company_name'];
	} else if($mb_id != encrypted_admin()) {
		$row = sql_fetch("select company_name from shop_minishop where mb_id = '$mb_id'");
		$sellerName = $row['company_name'];
	}

	return $sellerName;
}

// input vars 체크
function check_input_vars()
{
    $max_input_vars = ini_get('max_input_vars');

    if($max_input_vars) {
        $post_vars = count($_POST, COUNT_RECURSIVE);
        $get_vars = count($_GET, COUNT_RECURSIVE);
        $cookie_vars = count($_COOKIE, COUNT_RECURSIVE);

        $input_vars = $post_vars + $get_vars + $cookie_vars;

        if($input_vars > $max_input_vars) {
            alert('폼에서 전송된 변수의 개수가 max_input_vars 값보다 큽니다.\\n전송된 값중 일부는 유실되어 DB에 기록될 수 있습니다.\\n\\n문제를 해결하기 위해서는 서버 php.ini의 max_input_vars 값을 변경하십시오.');
        }
    }
}


/*************************************************************************
**
**  쇼핑몰 배너관련 함수 모음
**
*************************************************************************/

// 배너 자체만 리턴
function display_banner($code, $mb_id, $mb_grade = '', $mb_category = '')
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	if($mb_grade == 1){ //관리자

		//echo "감자a";

		$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}
	
			$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
			if($row['bn_bg']) {
				$bg1 = "<p style=\"background-color:#{$row['bn_bg']};\">";
				$bg2 = "</p>";
			}
	
			$file = rpc($file, MS_PATH, MS_URL);
			$str = "{$bg1}{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}{$bg2}";
		}

	}else{

		//echo "감자b";

		if($row['mb_grade'] == 0){

			$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
		
				$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
				if($row['bn_bg']) {
					$bg1 = "<p style=\"background-color:#{$row['bn_bg']};\">";
					$bg2 = "</p>";
				}
		
				$file = rpc($file, MS_PATH, MS_URL);
				$str = "{$bg1}{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}{$bg2}";
			}
	
		}else if($mb_grade == "5" && $row['mb_grade'] == $mb_grade && $row['category_name'] == $mb_category){
	
			//echo "감자c";

			$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
		
				$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
				if($row['bn_bg']) {
					$bg1 = "<p style=\"background-color:#{$row['bn_bg']};\">";
					$bg2 = "</p>";
				}
		
				$file = rpc($file, MS_PATH, MS_URL);
				$str = "{$bg1}{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}{$bg2}";
			}
	
		}else if($mb_grade != "5" && $row['mb_grade'] == $mb_grade){

			$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
		
				$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
				if($row['bn_bg']) {
					$bg1 = "<p style=\"background-color:#{$row['bn_bg']};\">";
					$bg2 = "</p>";
				}
		
				$file = rpc($file, MS_PATH, MS_URL);
				$str = "{$bg1}{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}{$bg2}";
			}

		}

	}

	return $str;
}

// 배너 bg
function display_banner_bg($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
		if($row['bn_bg']) $bg = "#{$row['bn_bg']} ";

		$file = rpc($file, MS_PATH, MS_URL);
		$str = "<p style=\"background:{$bg}url({$file}) no-repeat center;height:{$row['bn_height']}px;\">{$a1}{$a2}</p>";
	}

	return $str;
}

// 배너 (동일한 배너코드가 부여될경우 세로로 계속하여 출력)
function display_banner_rows($code, $mb_id, $mb_grade = '', $mb_category = '')
{
	$str = "";

	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);

	$row2 = sql_fetch($sql);

	//echo $sql; 

	if($mb_grade == 1){

		for($i=0; $row=sql_fetch_array($result); $i++)
		{
			$a1 = $a2 = $bg = '';
	
			$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
	
				$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
				if($row['bn_bg']) $bg = " style=\"background-color:#{$row['bn_bg']};\"";
	
				$file = rpc($file, MS_PATH, MS_URL);
				$str .= "<li{$bg}>{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}</li>\n";
			}
		}

	}else{ //관리자 아닌경우

		if($row2['mb_grade'] == 0){

			for($i=0; $row=sql_fetch_array($result); $i++)
			{
				$a1 = $a2 = $bg = '';
		
				$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
				if(is_file($file) && $row['bn_file']) {
					if($row['bn_link']) {
						$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
						$a2 = "</a>";
					}
		
					$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
					if($row['bn_bg']) $bg = " style=\"background-color:#{$row['bn_bg']};\"";
		
					$file = rpc($file, MS_PATH, MS_URL);
					$str .= "<li{$bg}>{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}</li>\n";
				}
			}
	
		}else if($row2['mb_grade'] == $mb_grade && $row2['category_name'] == $mb_category){
			
			for($i=0; $row=sql_fetch_array($result); $i++)
			{
				$a1 = $a2 = $bg = '';
		
				$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
				if(is_file($file) && $row['bn_file']) {
					if($row['bn_link']) {
						$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
						$a2 = "</a>";
					}
		
					$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
					if($row['bn_bg']) $bg = " style=\"background-color:#{$row['bn_bg']};\"";
		
					$file = rpc($file, MS_PATH, MS_URL);
					$str .= "<li{$bg}>{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}</li>\n";
				}
			}
	
		}

	}

	if($i > 0)
		$str = "<ul>\n{$str}</ul>\n";

	return $str;
}

// 배너 (동일한 배너코드가 부여될경우 세로로 계속하여 출력)
function display_banner_rows2($code, $mb_id, $mb_grade = '', $mb_category = '')
{
	$str = "";

/* 	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);

	$row2 = sql_fetch($sql); */

	//echo $sql; 

	if($mb_grade == 1){ //관리자

		$sql = sql_banner_rows($code, $mb_id);
		$result = sql_query($sql);

		for($i=0; $row=sql_fetch_array($result); $i++)
		{
			$a1 = $a2 = $bg = '';
	
			$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
	
				$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
				if($row['bn_bg']) $bg = " style=\"background-color:#{$row['bn_bg']};\"";
	
				$file = rpc($file, MS_PATH, MS_URL);
				$str .= "<li{$bg}>{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}</li>\n";
			}
		}

	}else{ //관리자 아닌경우

		$sql = sql_banner_rows($code, $mb_id, $mb_grade, $mb_category);
		$result = sql_query($sql);

		for($i=0; $row=sql_fetch_array($result); $i++)
		{
			$a1 = $a2 = $bg = '';
	
			$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
	
				$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
				if($row['bn_bg']) $bg = " style=\"background-color:#{$row['bn_bg']};\"";
	
				$file = rpc($file, MS_PATH, MS_URL);
				$str .= "<li{$bg}>{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}</li>\n";
			}
		}

	}

	if($i > 0)
		$str = "<ul>\n{$str}</ul>\n";

	return $str;
}

// 이미지 배경고정 텍스트 입력 배너
function mask_banner($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = MS_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$file = rpc($file, MS_PATH, MS_URL);
		$str = "<div class=\"mask_bn\" style=\"background:url('{$file}') no-repeat fixed center;background-size:cover;\">{$a1}<p><span>{$row['bn_text']}.</span></p>{$a2}</div>";
	}

	return $str;
}

// $dir 을 포함하여 https 또는 http 주소를 반환한다.

function https_url($dir, $https=true)
{
    if ($https) {
        if (G5_HTTPS_DOMAIN) {
            $url = G5_HTTPS_DOMAIN.'/'.$dir;
        } else {
            $url = MS_URL.'/'.$dir;
        }
    } else {
        if (G5_DOMAIN) {
            $url = G5_DOMAIN.'/'.$dir;
        } else {
            $url = MS_URL.'/'.$dir;
        }
    }

    return $url;
}

function push_send($to = '', $pu_subject, $pu_content, $open_url = '', $image_url = '', $sound_url = ''){

	global $ms;
    $headers = array("Content-Type: application/json","Accept: application/json");

    $arr   = array();

    $params = array();
	$payload = array();

	$arr['id'] = '1';
	$arr['jsonrpc'] = '2.0';
	
	if($to != ""){
		$arr['method'] = 'Message.Unicast';
	}else{
		$arr['method'] = 'Message.Broadcast';
	}


	$arr['params']['project_id'] = 'project-3b6969c5dccd';
	$arr['params']['api_key'] = 'e568d559f010ba6635d83729bf1f8bad4a924550d7a3fb435cd0d306ea8d0f3a';
	
	if($to != ""){
		$arr['params']['to'] = $to;
	}


	$arr['params']['payload']['title'] = $pu_subject;
	$arr['params']['payload']['body']   =  $pu_content; 
	$arr['params']['payload']['open_url']   =  $open_url; 
	$arr['params']['payload']['image_url']   =  $image_url; 
	$arr['params']['payload']['sound_url']   =  $sound_url; 
	$arr['params']['payload']['priority']   =  "HIGH"; 


	//$arr = $params

    /*푸쉬 대상자 최대 1000명*/
    //$arr['registration_ids'] = $device_id_arr;
    
    //print_r($arr);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,    'https://minishop-api.gopush.app');
    curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
    curl_setopt($ch, CURLOPT_POST,    true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
    $response = curl_exec($ch);
    curl_close($ch);

	//$device_id_arr = $device_id_arr[0];
	//sql_query("insert into push_log set url = '".$_SERVER['REQUEST_URI']."', token = '".$device_id_arr."', title = '".$subject."', content = '".$content."', sub_data = '".$sub_data."', pl_datetime = NOW()");

	//echo json_encode($arr); 
	return json_decode($response);
}

function aligo_sms($tpl_code, $receiver_1, $recvname_1, $subject_1, $message_1){ //템플릿코드, 받는사람번호, 받는사람 이름, 제목, 내용

	$_apiURL    =	'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
	$_hostInfo  =	parse_url($_apiURL);
	$_port      =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables =	array(
		'apikey'      => 'tcjpb8gb4vkkog5p63608lucnrnci97q', 
		'userid'      => 'ink6067', 
		'token'       => '1df8cbae319b2d6e34f7811681ef75d033db4e4c8a7d4e56ca8aaf6d223519ef0205bb7696681ccfe700f5d654316d9d9af295746f77310465433955ba8aa7b8qE2bT1tAOufK5qJpkRpay6KvZZxTxGxzLdc4Gi3D4f5hjXMyMV1F0AE0zHdVLJkY5SFfMkESOUUk1KJAePVpHw==', 
		'senderkey'   => 'ecea570a946a7b73377b06ffcffd07cfacd7f5b7', 
		'tpl_code'    => $tpl_code,
		'sender'      => '1661-2550',
		'receiver_1'  => $receiver_1,
		'recvname_1'  => $recvname_1,
		'subject_1'   => $subject_1,
		'message_1'   => $message_1
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);
}


/* function get_board_sort_fields($board=array(), $make_key_return=''){
    $bo_sort_fields = run_replace('get_board_sort_fields', array(
        array('wr_num, wr_reply', '기본'),
        array('wr_datetime asc', '날짜 이전것 부터'),
        array('wr_datetime desc', '날짜 최근것 부터'),
        array('wr_hit asc, wr_num, wr_reply', '조회수 낮은것 부터'),
        array('wr_hit desc, wr_num, wr_reply', '조회수 높은것 부터'),
        array('wr_last asc', '최근글 이전것 부터'),
        array('wr_last desc', '최근글 최근것 부터'),
        array('wr_comment asc, wr_num, wr_reply', '댓글수 낮은것 부터'),
        array('wr_comment desc, wr_num, wr_reply', '댓글수 높은것 부터'),
        array('wr_good asc, wr_num, wr_reply', '추천수 낮은것 부터'),
        array('wr_good desc, wr_num, wr_reply', '추천수 높은것 부터'),
        array('wr_nogood asc, wr_num, wr_reply', '비추천수 낮은것 부터'),
        array('wr_nogood desc, wr_num, wr_reply', '비추천수 높은것 부터'),
        array('wr_subject asc, wr_num, wr_reply', '제목 오름차순'),
        array('wr_subject desc, wr_num, wr_reply', '제목 내림차순'),
        array('wr_name asc, wr_num, wr_reply', '글쓴이 오름차순'),
        array('wr_name desc, wr_num, wr_reply', '글쓴이 내림차순'),
        array('ca_name asc, wr_num, wr_reply', '분류명 오름차순'),
        array('ca_name desc, wr_num, wr_reply', '분류명 내림차순'),
    ), $board, $make_key_return);

    if( $make_key_return ){
        
        $returns = array();
        foreach( $bo_sort_fields as $v ){
            $key = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $v[0]);
            $returns[$key] = $v[0];
        }
        
        return $returns;
    }
    return $bo_sort_fields;
} */

// XSS 어트리뷰트 태그 제거
function clean_xss_attributes($str)
{
    $xss_attributes_string = 'onAbort|onActivate|onAttribute|onAfterPrint|onAfterScriptExecute|onAfterUpdate|onAnimationCancel|onAnimationEnd|onAnimationIteration|onAnimationStart|onAriaRequest|onAutoComplete|onAutoCompleteError|onAuxClick|onBeforeActivate|onBeforeCopy|onBeforeCut|onBeforeDeactivate|onBeforeEditFocus|onBeforePaste|onBeforePrint|onBeforeScriptExecute|onBeforeUnload|onBeforeUpdate|onBegin|onBlur|onBounce|onCancel|onCanPlay|onCanPlayThrough|onCellChange|onChange|onClick|onClose|onCommand|onCompassNeedsCalibration|onContextMenu|onControlSelect|onCopy|onCueChange|onCut|onDataAvailable|onDataSetChanged|onDataSetComplete|onDblClick|onDeactivate|onDeviceLight|onDeviceMotion|onDeviceOrientation|onDeviceProximity|onDrag|onDragDrop|onDragEnd|onDragEnter|onDragLeave|onDragOver|onDragStart|onDrop|onDurationChange|onEmptied|onEnd|onEnded|onError|onErrorUpdate|onExit|onFilterChange|onFinish|onFocus|onFocusIn|onFocusOut|onFormChange|onFormInput|onFullScreenChange|onFullScreenError|onGotPointerCapture|onHashChange|onHelp|onInput|onInvalid|onKeyDown|onKeyPress|onKeyUp|onLanguageChange|onLayoutComplete|onLoad|onLoadedData|onLoadedMetaData|onLoadStart|onLoseCapture|onLostPointerCapture|onMediaComplete|onMediaError|onMessage|onMouseDown|onMouseEnter|onMouseLeave|onMouseMove|onMouseOut|onMouseOver|onMouseUp|onMouseWheel|onMove|onMoveEnd|onMoveStart|onMozFullScreenChange|onMozFullScreenError|onMozPointerLockChange|onMozPointerLockError|onMsContentZoom|onMsFullScreenChange|onMsFullScreenError|onMsGestureChange|onMsGestureDoubleTap|onMsGestureEnd|onMsGestureHold|onMsGestureStart|onMsGestureTap|onMsGotPointerCapture|onMsInertiaStart|onMsLostPointerCapture|onMsManipulationStateChanged|onMsPointerCancel|onMsPointerDown|onMsPointerEnter|onMsPointerLeave|onMsPointerMove|onMsPointerOut|onMsPointerOver|onMsPointerUp|onMsSiteModeJumpListItemRemoved|onMsThumbnailClick|onOffline|onOnline|onOutOfSync|onPage|onPageHide|onPageShow|onPaste|onPause|onPlay|onPlaying|onPointerCancel|onPointerDown|onPointerEnter|onPointerLeave|onPointerLockChange|onPointerLockError|onPointerMove|onPointerOut|onPointerOver|onPointerUp|onPopState|onProgress|onPropertyChange|onqt_error|onRateChange|onReadyStateChange|onReceived|onRepeat|onReset|onResize|onResizeEnd|onResizeStart|onResume|onReverse|onRowDelete|onRowEnter|onRowExit|onRowInserted|onRowsDelete|onRowsEnter|onRowsExit|onRowsInserted|onScroll|onSearch|onSeek|onSeeked|onSeeking|onSelect|onSelectionChange|onSelectStart|onStalled|onStorage|onStorageCommit|onStart|onStop|onShow|onSyncRestored|onSubmit|onSuspend|onSynchRestored|onTimeError|onTimeUpdate|onTimer|onTrackChange|onTransitionEnd|onToggle|onTouchCancel|onTouchEnd|onTouchLeave|onTouchMove|onTouchStart|onTransitionCancel|onTransitionEnd|onUnload|onURLFlip|onUserProximity|onVolumeChange|onWaiting|onWebKitAnimationEnd|onWebKitAnimationIteration|onWebKitAnimationStart|onWebKitFullScreenChange|onWebKitFullScreenError|onWebKitTransitionEnd|onWheel';
    
    do {
        $count = $temp_count = 0;

        $str = preg_replace(
            '/(.*)(?:' . $xss_attributes_string . ')(?:\s*=\s*)(?:\'(?:.*?)\'|"(?:.*?)")(.*)/ius',
            '$1-$2-$3-$4',
            $str,
            -1,
            $temp_count
        );
        $count += $temp_count;

        $str = preg_replace(
            '/(.*)(?:' . $xss_attributes_string . ')\s*=\s*(?:[^\s>]*)(.*)/ius',
            '$1$2',
            $str,
            -1,
            $temp_count
        );
        $count += $temp_count;

    } while ($count);

    return $str;
}


// include 하는 경로에 data file 경로나 안전하지 않은 경로가 있는지 체크합니다.
function is_include_path_check($path='', $is_input='')
{
    if( $path ){

        if( strlen($path) > 255 ){
            return false;
        }

        if ($is_input){
            // 장태진 @jtjisgod <jtjisgod@gmail.com> 추가
            // 보안 목적 : rar wrapper 차단

            if( stripos($path, 'rar:') !== false || stripos($path, 'php:') !== false || stripos($path, 'zlib:') !== false || stripos($path, 'bzip2:') !== false || stripos($path, 'zip:') !== false || stripos($path, 'data:') !== false || stripos($path, 'phar:') !== false || stripos($path, 'file:') !== false || stripos($path, '://') !== false ){
                return false;
            }

            $replace_path = str_replace('\\', '/', $path);
            $slash_count = substr_count(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/');
            $peer_count = substr_count($replace_path, '../');

            if ( $peer_count && $peer_count > $slash_count ){
                return false;
            }

            try {
                // whether $path is unix or not
                $unipath = strlen($path)==0 || substr($path, 0, 1) != '/';
                $unc = substr($path,0,2)=='\\\\'?true:false;
                // attempts to detect if path is relative in which case, add cwd
                if(strpos($path,':') === false && $unipath && !$unc){
                    $path=getcwd().DIRECTORY_SEPARATOR.$path;
                    if(substr($path, 0, 1) == '/'){
                        $unipath = false;
                    }
                }

                // resolve path parts (single dot, double dot and double delimiters)
                $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
                $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
                $absolutes = array();
                foreach ($parts as $part) {
                    if ('.'  == $part){
                        continue;
                    }
                    if ('..' == $part) {
                        array_pop($absolutes);
                    } else {
                        $absolutes[] = $part;
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, $absolutes);
                // resolve any symlinks
                // put initial separator that could have been lost
                $path = !$unipath ? '/'.$path : $path;
                $path = $unc ? '\\\\'.$path : $path;
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                return false;
            }

            if( preg_match('/\/data\/(file|editor|qa|cache|member|member_image|session|tmp)\/[A-Za-z0-9_]{1,20}\//i', $replace_path) ){
                return false;
            }
            if( preg_match('/'.G5_PLUGIN_DIR.'\//i', $replace_path) && (preg_match('/'.G5_OKNAME_DIR.'\//i', $replace_path) || preg_match('/'.G5_KCPCERT_DIR.'\//i', $replace_path) || preg_match('/'.G5_LGXPAY_DIR.'\//i', $replace_path)) || (preg_match('/search\.skin\.php/i', $replace_path) ) ){
                return false;
            }
            if( substr_count($replace_path, './') > 5 ){
                return false;
            }
            if( defined('G5_SHOP_DIR') && preg_match('/'.G5_SHOP_DIR.'\//i', $replace_path) && preg_match('/kcp\//i', $replace_path) ){
                return false;
            }
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if($extension && preg_match('/(jpg|jpeg|png|gif|bmp|conf|php\-x)$/i', $extension)) {
            return false;
        }
    }

    return true;
}

function filter_input_include_path($path){
    return str_replace('//', '/', $path);
}

function option_array_checked($option, $arr=array()){
    $checked = '';

    if( !is_array($arr) ){
        $arr = explode(',', $arr);
    }

    if ( !empty($arr) && in_array($option, (array) $arr) ){
        $checked = 'checked="checked"';
    }

    return $checked;
}

// $_POST 형식에서 checkbox 엘리먼트의 checked 속성에서 checked 가 되어 넘어 왔는지를 검사
function is_checked($field)
{
    return !empty($_POST[$field]);
}


// 파일명에서 특수문자 제거
function get_safe_filename($name)
{
    $pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
    $name = preg_replace($pattern, '', $name);

    return $name;
}

// 파일명 치환
function replace_filename($name)
{
    @session_start();
    $ss_id = session_id();
    $usec = get_microtime();
    $file_path = pathinfo($name);
    $ext = $file_path['extension'];
    $return_filename = sha1($ss_id.$_SERVER['REMOTE_ADDR'].$usec); 
    if( $ext )
        $return_filename .= '.'.$ext;

    return $return_filename;
}

// mysqli_real_escape_string 의 alias 기능을 한다.
function sql_real_escape_string($str, $link=null)
{
    global $ms;

    if(!$link)
        $link = $ms['connect_db'];
    
    if(function_exists('mysqli_connect') && G5_MYSQLI_USE) {
        return mysqli_real_escape_string($link, $str);
    }

    return mysql_real_escape_string($str, $link);
}

// 제목을 변환
function conv_subject($subject, $len, $suffix='')
{
    return get_text(cut_str($subject, $len, $suffix));
}

// 게시판 테이블에서 하나의 행을 읽음
function get_write($write_table, $wr_id, $is_cache=false)
{
    global $ms, $g5_object;

    $wr_bo_table = preg_replace('/^'.preg_quote($g5['write_prefix']).'/i', '', $write_table);

    $write = $g5_object->get('bbs', $wr_id, $wr_bo_table);

    if( !$write || $is_cache == false ){
        $sql = " select * from {$write_table} where wr_id = '{$wr_id}' ";
        $write = sql_fetch($sql);

        $g5_object->set('bbs', $wr_id, $write, $wr_bo_table);
    }

    return $write;
}


// 그룹 설정 테이블에서 하나의 행을 읽음
function get_group($gr_id, $is_cache=false)
{
    global $ms;
    
    if( is_array($gr_id) ){
        return array();
    }

    static $cache = array();

    $gr_id = preg_replace('/[^a-z0-9_]/i', '', $gr_id);
    $cache = run_replace('get_group_db_cache', $cache, $gr_id, $is_cache);
    $key = md5($gr_id);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    $sql = " select * from {$g5['group_table']} where gr_id = '$gr_id' ";

    $group = run_replace('get_group', sql_fetch($sql), $gr_id, $is_cache);
    $cache[$key] = array_merge(array('gr_device'=>'', 'gr_subject'=>''), (array) $group);

    return $cache[$key];
}

// 게시글에 첨부된 파일을 얻는다. (배열로 반환)
function get_file($bo_table, $wr_id)
{
    global $ms, $qstr, $board;

    $file['count'] = 0;
    $sql = " select * from g5_board_file where bo_table = '$bo_table' and wr_id = '$wr_id' order by bf_no ";
    $result = sql_query($sql);

    while ($row = sql_fetch_array($result))
    {
        $no = (int) $row['bf_no'];
        $bf_content = $row['bf_content'] ? html_purifier($row['bf_content']) : '';
        $file[$no]['href'] = MS_BBSORG_URL."/download.php?bo_table=$bo_table&amp;wr_id=$wr_id&amp;no=$no" . $qstr;
        $file[$no]['download'] = $row['bf_download'];
        // 4.00.11 - 파일 path 추가
        $file[$no]['path'] = G5_DATA_URL.'/file/'.$bo_table;
        $file[$no]['size'] = get_filesize($row['bf_filesize']);
        $file[$no]['datetime'] = $row['bf_datetime'];
        $file[$no]['source'] = addslashes($row['bf_source']);
        $file[$no]['bf_content'] = $bf_content;
        $file[$no]['content'] = get_text($bf_content);
        //$file[$no]['view'] = view_file_link($row['bf_file'], $file[$no]['content']);
        $file[$no]['view'] = view_file_link($row['bf_file'], $row['bf_width'], $row['bf_height'], $file[$no]['content']);
        $file[$no]['file'] = $row['bf_file'];
        $file[$no]['image_width'] = $row['bf_width'] ? $row['bf_width'] : 640;
        $file[$no]['image_height'] = $row['bf_height'] ? $row['bf_height'] : 480;
        $file[$no]['image_type'] = $row['bf_type'];
        $file[$no]['bf_fileurl'] = $row['bf_fileurl'];
        $file[$no]['bf_thumburl'] = $row['bf_thumburl'];
        $file[$no]['bf_storage'] = $row['bf_storage'];
        $file['count']++;
    }

	//print_r($file);
	return $file;
    //return run_replace('get_files', $file, $bo_table, $wr_id);
}

// 게시판의 공지사항을 , 로 구분하여 업데이트 한다.
function board_notice($bo_notice, $wr_id, $insert=false)
{
    $notice_array = explode(",", trim($bo_notice));

    if($insert && in_array($wr_id, $notice_array))
        return $bo_notice;

    $notice_array = array_merge(array($wr_id), $notice_array);
    $notice_array = array_unique($notice_array);
    foreach ($notice_array as $key=>$value) {
        if (!trim($value))
            unset($notice_array[$key]);
    }
    if (!$insert) {
        foreach ($notice_array as $key=>$value) {
            if ((int)$value == (int)$wr_id)
                unset($notice_array[$key]);
        }
    }
    return implode(",", $notice_array);
}

// 게시판 최신글 캐시 파일 삭제
function delete_cache_latest($bo_table)
{
    if (!preg_match("/^([A-Za-z0-9_]{1,20})$/", $bo_table)) {
        return;
    }

    g5_delete_cache_by_prefix('latest-'.$bo_table.'-');
}

// 게시물 정보($write_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
function get_list($write_row, $board, $skin_url, $subject_len=40)
{
    global $g5, $config, $g5_object;
    global $qstr, $page;

    //$t = get_microtime();

    $g5_object->set('bbs', $write_row['wr_id'], $write_row, $board['bo_table']);

    // 배열전체를 복사
    $list = $write_row;
    unset($write_row);

    $board_notice = array_map('trim', explode(',', $board['bo_notice']));
    $list['is_notice'] = in_array($list['wr_id'], $board_notice);

    if ($subject_len)
        $list['subject'] = conv_subject($list['wr_subject'], $subject_len, '…');
    else
        $list['subject'] = conv_subject($list['wr_subject'], $board['bo_subject_len'], '…');

    if( ! (isset($list['wr_seo_title']) && $list['wr_seo_title']) && $list['wr_id'] ){
        seo_title_update(get_write_table_name($board['bo_table']), $list['wr_id'], 'bbs');
    }

    // 목록에서 내용 미리보기 사용한 게시판만 내용을 변환함 (속도 향상) : kkal3(커피)님께서 알려주셨습니다.
    if ($board['bo_use_list_content'])
	{
		$html = 0;
		if (strstr($list['wr_option'], 'html1'))
			$html = 1;
		else if (strstr($list['wr_option'], 'html2'))
			$html = 2;

        $list['content'] = conv_content($list['wr_content'], $html);
	}

    $list['comment_cnt'] = '';
    if ($list['wr_comment'])
        $list['comment_cnt'] = "<span class=\"cnt_cmt\">".$list['wr_comment']."</span>";

    // 당일인 경우 시간으로 표시함
    $list['datetime'] = substr($list['wr_datetime'],0,10);
    $list['datetime2'] = $list['wr_datetime'];
    if ($list['datetime'] == G5_TIME_YMD)
        $list['datetime2'] = substr($list['datetime2'],11,5);
    else
        $list['datetime2'] = substr($list['datetime2'],5,5);
    // 4.1
    $list['last'] = substr($list['wr_last'],0,10);
    $list['last2'] = $list['wr_last'];
    if ($list['last'] == G5_TIME_YMD)
        $list['last2'] = substr($list['last2'],11,5);
    else
        $list['last2'] = substr($list['last2'],5,5);

    $list['wr_homepage'] = get_text($list['wr_homepage']);

    $tmp_name = get_text(cut_str($list['wr_name'], $config['cf_cut_name'])); // 설정된 자리수 만큼만 이름 출력
    $tmp_name2 = cut_str($list['wr_name'], $config['cf_cut_name']); // 설정된 자리수 만큼만 이름 출력
    if ($board['bo_use_sideview'])
        $list['name'] = get_sideview($list['mb_id'], $tmp_name2, $list['wr_email'], $list['wr_homepage']);
    else
        $list['name'] = '<span class="'.($list['mb_id']?'sv_member':'sv_guest').'">'.$tmp_name.'</span>';

    $reply = $list['wr_reply'];

    $list['reply'] = strlen($reply)*20;

    $list['icon_reply'] = '';
    if ($list['reply'])
        $list['icon_reply'] = '<img src="'.$skin_url.'/img/icon_reply.gif" class="icon_reply" alt="답변글">';

    $list['icon_link'] = '';
    if ($list['wr_link1'] || $list['wr_link2'])
        $list['icon_link'] = '<i class="fa fa-link" aria-hidden="true"></i> ';

    // 분류명 링크
    $list['ca_name_href'] = get_pretty_url($board['bo_table'], '', 'sca='.urlencode($list['ca_name']));

    $list['href'] = get_pretty_url($board['bo_table'], $list['wr_id'], $qstr);
    $list['comment_href'] = $list['href'];

    $list['icon_new'] = '';
    if ($board['bo_new'] && $list['wr_datetime'] >= date("Y-m-d H:i:s", G5_SERVER_TIME - ($board['bo_new'] * 3600)))
        $list['icon_new'] = '<img src="'.$skin_url.'/img/icon_new.gif" class="title_icon" alt="새글"> ';

    $list['icon_hot'] = '';
    if ($board['bo_hot'] && $list['wr_hit'] >= $board['bo_hot'])
        $list['icon_hot'] = '<i class="fa fa-heart" aria-hidden="true"></i> ';

    $list['icon_secret'] = '';
    if (strstr($list['wr_option'], 'secret'))
        $list['icon_secret'] = '<i class="fa fa-lock" aria-hidden="true"></i> ';

    // 링크
    for ($i=1; $i<=G5_LINK_COUNT; $i++) {
        $list['link'][$i] = set_http(get_text($list["wr_link{$i}"]));
        $list['link_href'][$i] = G5_BBS_URL.'/link.php?bo_table='.$board['bo_table'].'&amp;wr_id='.$list['wr_id'].'&amp;no='.$i.$qstr;
        $list['link_hit'][$i] = (int)$list["wr_link{$i}_hit"];
    }

    // 가변 파일
	/* echo "bo_use_list_file:".$board['bo_use_list_file']."<br>";
	echo 'wr_file:'.$list['wr_file'];  */
    if ($board['bo_use_list_file'] || ($list['wr_file'] && $subject_len == 255) /* view 인 경우 */) {
		//echo "a";
        $list['file'] = get_file($board['bo_table'], $list['wr_id']);
		//print_r($list['file']);
		//echo "3";
    } else {
		//echo "b";
        $list['file']['count'] = $list['wr_file'];
    }

    if ($list['file']['count'])
        $list['icon_file'] = '<i class="fa fa-download" aria-hidden="true"></i> ';

    return $list;
}

// get_list 의 alias
function get_view($write_row, $board, $skin_url)
{
    return get_list($write_row, $board, $skin_url, 255);
}

function get_filesize($size)
{
    //$size = @filesize(addslashes($file));
    if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}

// 파일을 보이게 하는 링크 (이미지, 플래쉬, 동영상)
function view_file_link($file, $width, $height, $content='')
{
    global $config, $board;
    global $ms;
    static $ids;

    if (!$file) return;

    $ids++;

    // 파일의 폭이 게시판설정의 이미지폭 보다 크다면 게시판설정 폭으로 맞추고 비율에 따라 높이를 계산
    if ($board && $width > $board['bo_image_width'] && $board['bo_image_width'])
    {
        $rate = $board['bo_image_width'] / $width;
        $width = $board['bo_image_width'];
        $height = (int)($height * $rate);
    }

    // 폭이 있는 경우 폭과 높이의 속성을 주고, 없으면 자동 계산되도록 코드를 만들지 않는다.
    if ($width)
        $attr = ' width="'.$width.'" height="'.$height.'" ';
    else
        $attr = '';

    if (preg_match("/\.({$config['cf_image_extension']})$/i", $file) && isset($board['bo_table'])) {
        $attr_href = run_replace('thumb_view_image_href', G5_BBS_URL.'/view_image.php?bo_table='.$board['bo_table'].'&amp;fn='.urlencode($file), $file, $board['bo_table'], $width, $height, $content);
        $img = '<a href="'.$attr_href.'" target="_blank" class="view_image">';
        $img .= '<img src="'.G5_DATA_URL.'/file/'.$board['bo_table'].'/'.urlencode($file).'" alt="'.$content.'" '.$attr.'>';
        $img .= '</a>';

        return $img;
    }
}


// view_file_link() 함수에서 넘겨진 이미지를 보이게 합니다.
// {img:0} ... {img:n} 과 같은 형식
function view_image($view, $number, $attribute)
{
    if ($view['file'][$number]['view'])
        return preg_replace("/>$/", " $attribute>", $view['file'][$number]['view']);
    else
        //return "{".$number."번 이미지 없음}";
        return "";
}


/*
// {link:0} ... {link:n} 과 같은 형식
function view_link($view, $number, $attribute)
{
    global $config;

    if ($view['link'][$number]['link'])
    {
        if (!preg_match("/target/i", $attribute))
            $attribute .= " target='$config['cf_link_target']'";
        return "<a href='{$view['link'][$number]['href']}' $attribute>{$view['link'][$number]['link']}</a>";
    }
    else
        return "{".$number."번 링크 없음}";
}
*/

function famiwel_status_send_go($dl_comcode,$order_id,$op_no,$dan){
/*
블링뷰티
value="1"> 입금대기</label>
value="2"> 입금완료</label>
value="3"> 배송준비</label>
value="4"> 배송중</label>
value="5"> 배송완료</label>
value="6"> 취소</label>
value="7"> 반품</label>
value="8"> 교환</label>
value="9"> 환불</label>

파미웰
1: 입금대기
2: 취소요청
3: 취소완료
4: 입금완료
5: 배송중
6: 교환요청
7: 교환완료
8: 반품요청
9: 반품완료
10: 구매결정
11: 정산완료 (사용안함)
12 : 배송준비중
13 : 배송완료
*/

		switch ($dan) {
		  case '1':
			$step = '1';
			break;
		  case '2':
			$step = '4';
			break;
		  case '6':
			$step = '2';
			break;
		  case '7':
			$step = '8';
			break;
		  case '8':
			$step = '6';
			break;
		  case '9':
			$step = '8';
			break;
		}


	$url = 'http://www.famiwel.co.kr/_prozn/_system/connect_data/order/na_linksite_status_json.php'; //접속할 url 입력
	$post_data["dl_comcode"] = $dl_comcode; //업체코드
	$post_data["order_id"] = $order_id; //주문번호
	$post_data["op_no"] = $op_no; //옵션번호
	$post_data["step"] = $step; //주문단계
	$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
	$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
	 
	curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
	curl_setopt($ch, CURLOPT_POST, 1); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기
	curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
	$res = curl_exec ($ch);
	//return var_dump($res);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($res, 0, $header_size);
	$json = substr($res, $header_size);    
	$row = json_decode($json, true);
	$regdate = date("Y-m-d H:i:s");
	return $row['code'];

	//var_dump($res);//결과값 확인하기
	//echo '<br>';
	//print_r(curl_getinfo($ch));//마지막 http 전송 정보 출력
	//echo curl_errno($ch);//마지막 에러 번호 출력
	//echo curl_error($ch);//현재 세션의 마지막 에러 출력
	curl_close($ch);
}
// 메인메뉴
function main_menu($type, $rows)
{
	global $default, $pt_id, $member;


	$sql = " select count(*) as cnt from shop_banner where bn_device='pc' and bn_code='$type' and bn_use = '1' and mb_id = '$pt_id' order by bn_order asc limit $rows ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$pt_id = "admin";
	}

	$sql = "select * from shop_banner where bn_device='pc' and bn_code='$type' and bn_use = '1' and mb_id = '$pt_id' order by bn_order asc limit $rows";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {

		if($member['grade'] == $row['mb_grade'] or $row['mb_grade'] == '0' or $member['grade'] == '1'){
		echo"<li>\n";
			echo"<a href=\"{$row['bn_link']}\">\n";
				echo"<dl>\n";
					echo"<dt><img src=\"/data/banner/{$row['bn_file']}\"></dt>\n";
					echo"<dd>{$row['bn_text']}</dd>\n";
				echo"</dl>\n";
			echo"</a>\n";
		echo"</li>\n";
		}
	}
}
?>