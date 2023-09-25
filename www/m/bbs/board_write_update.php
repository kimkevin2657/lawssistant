<?php
include_once("./_common.php");

check_demo();

$_POST = array_map('trim', $_POST);

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}


if(!$_POST['subject']) { alert("제목을 입력하세요."); }
if(!$_POST['writer_s']) { alert("작성자명이 없습니다."); }

$writer = 0;

if($_POST['havehtml']!='Y')	$_POST['havehtml'] = "N";
if($_POST['btype']!='1') $_POST['btype'] = '2';
if($_POST['issecret']!='Y') $_POST['issecret'] = "N";
if($member['id']) $writer = $member['index_no'];

if($is_member)
	$writer = $mb_no;

if($w == "") {
	$fid = get_next_num("shop_board_{$boardid}");
if($boardid=="102") {
    if(!empty($_POST['expenses_a'])){
        $form_data = $_POST['expenses_a'];
		$expenses_a = '';
        
        for($i=0; $i < count($form_data); $i++){
            $expenses_a .= "'" . $form_data[$i] . "'" . ',';
        }        
        $expenses_a = substr($expenses_a, 0, -1);//쿼리문 오류 방지를 위해 문자열 중 마지막 문자 콤마 제거
	}
    if(!empty($_POST['add_option'])){
		$form_data = '';
        $form_data = $_POST['add_option'];
		$add_option = '';
        
        for($i=0; $i < count($form_data); $i++){
            $add_option .= "'" . $form_data[$i] . "'" . ',';
        }        
        $add_option = substr($add_option, 0, -1);//쿼리문 오류 방지를 위해 문자열 중 마지막 문자 콤마 제거
	}
    if(!empty($_POST['direction'])){
		$form_data = '';
        $form_data = $_POST['direction'];
		$direction = '';
        
        for($i=0; $i < count($form_data); $i++){
            $direction .= "'" . $form_data[$i] . "'" . ',';
        }        
        $direction = substr($direction, 0, -1);//쿼리문 오류 방지를 위해 문자열 중 마지막 문자 콤마 제거
	}
    if(!empty($_POST['facilities'])){
		$form_data = '';
        $form_data = $_POST['facilities'];
		$facilities = '';
        
        for($i=0; $i < count($form_data); $i++){
            $facilities .= "'" . $form_data[$i] . "'" . ',';
        }        
        $facilities = substr($facilities, 0, -1);//쿼리문 오류 방지를 위해 문자열 중 마지막 문자 콤마 제거
	}
    if(!empty($_POST['security'])){
		$form_data = '';
        $form_data = $_POST['security'];
		$security = '';
        
        for($i=0; $i < count($form_data); $i++){
            $security .= "'" . $form_data[$i] . "'" . ',';
        }        
        $security = substr($security, 0, -1);//쿼리문 오류 방지를 위해 문자열 중 마지막 문자 콤마 제거
	}

    $sql_commend = " , btype	= '$_POST[btype]'
			 , w_info			= '$_POST[w_info]'
             , features			= '$_POST[features]'
			 , b_zip			= '$_POST[b_zip]'
			 , b_addr1			= '$_POST[b_addr1]'
			 , b_addr2			= '$_POST[b_addr2]'
			 , b_addr3			= '$_POST[b_addr3]'
			 , transaction_type	= '$_POST[transaction_type]'
			 , deposit_lease	= '$_POST[deposit_lease]'
			 , mon_rent_de		= '$_POST[mon_rent_de]'
			 , mon_rent			= '$_POST[mon_rent]'
			 , short_rent_de	= '$_POST[short_rent_de]'
			 , short_rent		= '$_POST[short_rent]'
			 , dealing			= '$_POST[dealing]'
			 , expenses			= '$_POST[expenses]'
			 , expenses_a1		= '$_POST[expenses_a1]'
			 , expenses_a2		= '$_POST[expenses_a2]'
			 , expenses_a3		= '$_POST[expenses_a3]'
			 , expenses_a4		= '$_POST[expenses_a4]'
			 , expenses_a5		= '$_POST[expenses_a5]'
			 , expenses_a6		= '$_POST[expenses_a6]'
			 , expenses_b		= '$_POST[expenses_b]'
			 , expenses_c		= '$_POST[expenses_c]'
			 , loan_a			= '$_POST[loan_a]'
			 , loan				= '$_POST[loan]'
			 , contract			= '$_POST[contract]'
			 , floor			= '$_POST[floor]'
			 , room				= '$_POST[room]'
			 , purpose			= '$_POST[purpose]'
			 , structure		= '$_POST[structure]'
			 , double_c			= '$_POST[double]'
			 , entrance			= '$_POST[entrance]'
			 , direction_a		= '$_POST[direction_a]'
			 , add_option1		= '$_POST[add_option1]'
			 , add_option2		= '$_POST[add_option2]'
			 , add_option3		= '$_POST[add_option3]'
			 , add_option4		= '$_POST[add_option4]'
			 , add_option5		= '$_POST[add_option5]'
			 , add_option6		= '$_POST[add_option6]'
			 , add_option7		= '$_POST[add_option7]'
			 , direction1		= '$_POST[direction1]'
			 , direction2		= '$_POST[direction2]'
			 , direction3		= '$_POST[direction3]'
			 , direction4		= '$_POST[direction4]'
			 , direction5		= '$_POST[direction5]'
			 , direction6		= '$_POST[direction6]'
			 , direction7		= '$_POST[direction7]'
			 , direction8		= '$_POST[direction8]'
			 , come_date		= '$_POST[come_date]'
			 , parking			= '$_POST[parking]'
			 , households		= '$_POST[households]'
			 , whenbuild		= '$_POST[whenbuild]'
			 , heating			= '$_POST[heating]'
			 , air_conditioner	= '$_POST[air_conditioner]'
			 , facilities1		= '$_POST[facilities1]'
			 , facilities2		= '$_POST[facilities2]'
			 , facilities3		= '$_POST[facilities3]'
			 , facilities4		= '$_POST[facilities4]'
			 , facilities5		= '$_POST[facilities5]'
			 , facilities6		= '$_POST[facilities6]'
			 , facilities7		= '$_POST[facilities7]'
			 , facilities8		= '$_POST[facilities8]'
			 , facilities9		= '$_POST[facilities9]'
			 , facilities10		= '$_POST[facilities10]'
			 , facilities11		= '$_POST[facilities11]'
			 , facilities12		= '$_POST[facilities12]'
			 , facilities13		= '$_POST[facilities13]'
			 , facilities14		= '$_POST[facilities14]'
			 , facilities15		= '$_POST[facilities15]'
			 , facilities16		= '$_POST[facilities16]'
			 , facilities17		= '$_POST[facilities17]'
			 , facilities18		= '$_POST[facilities18]'
			 , facilities19		= '$_POST[facilities19]'
			 , facilities20		= '$_POST[facilities20]'
			 , security1		= '$_POST[security1]'
			 , security2		= '$_POST[security2]'
			 , security3		= '$_POST[security3]'
			 , security4		= '$_POST[security4]'
			 , security5		= '$_POST[security5]'
			 , security6		= '$_POST[security6]'
			 , security7		= '$_POST[security7]'
			 , security8		= '$_POST[security8]'
			 , security9		= '$_POST[security9]'
			 , security10		= '$_POST[security10]'
			 , security11		= '$_POST[security11]'
			 , security12		= '$_POST[security12]'
			 , security13		= '$_POST[security13]'
			 , security14		= '$_POST[security14]'
			 , classification	= '$_POST[classification]'
			 , ca_name			= '$_POST[ca_name]'
             , issecret			= '$_POST[issecret]'
             , havehtml			= '$_POST[havehtml]'
             , writer			= '$writer'
             , writer_s			= '$_POST[writer_s]'
             , subject			= '$_POST[subject]'
             , memo				= '$_POST[memo]'
             , passwd			= '$_POST[passwd]'
             , average			= '$_POST[average]'
             , product			= '$_POST[product]'
             , pt_id			= '$pt_id' ";
  } else {
	$sql_common = " btype		= '$_POST[btype]',                
					ca_name		= '$_POST[ca_name]',
					issecret	= '$_POST[issecret]',
					havehtml	= '$_POST[havehtml]',
					writer      = '$writer',
					writer_s	= '$_POST[writer_s]',
					subject		= '$_POST[subject]',
					memo		= '$_POST[memo]',
					passwd      = '$_POST[passwd]',
					average		= '$_POST[average]',
					product		= '$_POST[product]',
					pt_id		= '$pt_id' ";
  }
	   
	$sql = " insert into shop_board_{$boardid}
				set fid = '$fid',
					wdate = '".MS_SERVER_TIME."',
					wip = '{$_SERVER['REMOTE_ADDR']}',
					thread = 'A',
					$sql_common ";
	sql_query($sql);

	goto_url(MS_MBBS_URL."/board_list.php?boardid=$boardid");

} else if($w == "u") {
	$sql = "select * from shop_board_{$boardid} where index_no='$index_no'";
	$row = sql_fetch($sql);

	$m = 2;
	if(is_admin())
	{	$m = 1;	}
	else
	{
		if($row['writer']==0) {	
			if($_POST['passwd'] != $row['passwd']) {	
				alert('비밀번호가 맞지않습니다.');
			}
			$m = 1;	
		} else {
			if($row['writer']==$mb_no)
			{	$m = 1;	}
		}
	}

	if($m==1) {
if($boardid=="102") {
    if(!empty($_POST['expenses_a'])){
		$expenses_a = join("|", $_POST['expenses_a']);
	}
    if(!empty($_POST['add_option'])){
		$add_option = join("|", $_POST['add_option']);
	}
    if(!empty($_POST['direction'])){
		$direction = join("|", $_POST['direction']);
	}
    if(!empty($_POST['facilities'])){
		$facilities = join("|", $_POST['facilities']);
	}
    if(!empty($_POST['security'])){
		$security = join("|", $_POST['security']);
	}
    $sql = " update shop_board_{$boardid}
					set btype	= '$_POST[btype]'
			 , w_info			= '$_POST[w_info]'
             , features			= '$_POST[features]'
			 , b_zip			= '$_POST[b_zip]'
			 , b_addr1			= '$_POST[b_addr1]'
			 , b_addr2			= '$_POST[b_addr2]'
			 , b_addr3			= '$_POST[b_addr3]'
			 , transaction_type	= '$_POST[transaction_type]'
			 , deposit_lease	= '$_POST[deposit_lease]'
			 , mon_rent_de		= '$_POST[mon_rent_de]'
			 , mon_rent			= '$_POST[mon_rent]'
			 , short_rent_de	= '$_POST[short_rent_de]'
			 , short_rent		= '$_POST[short_rent]'
			 , dealing			= '$_POST[dealing]'
			 , expenses			= '$_POST[expenses]'
			 , expenses_a1		= '$_POST[expenses_a1]'
			 , expenses_a2		= '$_POST[expenses_a2]'
			 , expenses_a3		= '$_POST[expenses_a3]'
			 , expenses_a4		= '$_POST[expenses_a4]'
			 , expenses_a5		= '$_POST[expenses_a5]'
			 , expenses_a6		= '$_POST[expenses_a6]'
			 , expenses_b		= '$_POST[expenses_b]'
			 , expenses_c		= '$_POST[expenses_c]'
			 , loan_a			= '$_POST[loan_a]'
			 , loan				= '$_POST[loan]'
			 , contract			= '$_POST[contract]'
			 , floor			= '$_POST[floor]'
			 , room				= '$_POST[room]'
			 , purpose			= '$_POST[purpose]'
			 , structure		= '$_POST[structure]'
			 , double_c			= '$_POST[double]'
			 , entrance			= '$_POST[entrance]'
			 , direction_a		= '$_POST[direction_a]'
			 , add_option1		= '$_POST[add_option1]'
			 , add_option2		= '$_POST[add_option2]'
			 , add_option3		= '$_POST[add_option3]'
			 , add_option4		= '$_POST[add_option4]'
			 , add_option5		= '$_POST[add_option5]'
			 , add_option6		= '$_POST[add_option6]'
			 , add_option7		= '$_POST[add_option7]'
			 , direction1		= '$_POST[direction1]'
			 , direction2		= '$_POST[direction2]'
			 , direction3		= '$_POST[direction3]'
			 , direction4		= '$_POST[direction4]'
			 , direction5		= '$_POST[direction5]'
			 , direction6		= '$_POST[direction6]'
			 , direction7		= '$_POST[direction7]'
			 , direction8		= '$_POST[direction8]'
			 , come_date		= '$_POST[come_date]'
			 , parking			= '$_POST[parking]'
			 , households		= '$_POST[households]'
			 , whenbuild		= '$_POST[whenbuild]'
			 , heating			= '$_POST[heating]'
			 , air_conditioner	= '$_POST[air_conditioner]'
			 , facilities1		= '$_POST[facilities1]'
			 , facilities2		= '$_POST[facilities2]'
			 , facilities3		= '$_POST[facilities3]'
			 , facilities4		= '$_POST[facilities4]'
			 , facilities5		= '$_POST[facilities5]'
			 , facilities6		= '$_POST[facilities6]'
			 , facilities7		= '$_POST[facilities7]'
			 , facilities8		= '$_POST[facilities8]'
			 , facilities9		= '$_POST[facilities9]'
			 , facilities10		= '$_POST[facilities10]'
			 , facilities11		= '$_POST[facilities11]'
			 , facilities12		= '$_POST[facilities12]'
			 , facilities13		= '$_POST[facilities13]'
			 , facilities14		= '$_POST[facilities14]'
			 , facilities15		= '$_POST[facilities15]'
			 , facilities16		= '$_POST[facilities16]'
			 , facilities17		= '$_POST[facilities17]'
			 , facilities18		= '$_POST[facilities18]'
			 , facilities19		= '$_POST[facilities19]'
			 , facilities20		= '$_POST[facilities20]'
			 , security1		= '$_POST[security1]'
			 , security2		= '$_POST[security2]'
			 , security3		= '$_POST[security3]'
			 , security4		= '$_POST[security4]'
			 , security5		= '$_POST[security5]'
			 , security6		= '$_POST[security6]'
			 , security7		= '$_POST[security7]'
			 , security8		= '$_POST[security8]'
			 , security9		= '$_POST[security9]'
			 , security10		= '$_POST[security10]'
			 , security11		= '$_POST[security11]'
			 , security12		= '$_POST[security12]'
			 , security13		= '$_POST[security13]'
			 , security14		= '$_POST[security14]'
			 , classification	= '$_POST[classification]'
			 , ca_name			= '$_POST[ca_name]'
             , issecret			= '$_POST[issecret]'
             , havehtml			= '$_POST[havehtml]'
             , writer			= '$writer'
             , writer_s			= '$_POST[writer_s]'
             , subject			= '$_POST[subject]'
             , memo				= '$_POST[memo]'
             , passwd			= '$_POST[passwd]'
             , average			= '$_POST[average]'
             , product			= '$_POST[product]'
				  where index_no	= '$index_no'";
  } else {
		$sql = " update shop_board_{$boardid}
					set writer_s	= '$_POST[writer_s]',
						btype		= '$_POST[btype]',
						ca_name		= '$_POST[ca_name]',
						issecret	= '$_POST[issecret]',
						havehtml	= '$_POST[havehtml]',
						subject		= '$_POST[subject]',
						memo		= '$_POST[memo]',
						average		= '$_POST[average]',
						product		= '$_POST[product]'
				  where index_no	= '$index_no'";
  }
		$r = sql_query($sql);

		goto_url(MS_MBBS_URL."/board_write.php?w=u&index_no=$index_no&boardid=$boardid&page=$page");
	}

} else if($w == "r") {
	$sql = "select fid,thread,passwd from shop_board_{$boardid} where index_no=$index_no";
	$res = sql_query($sql);
	$row = sql_fetch_row($res);
	$fid = $row[0];
	$thread = $row[1];
	$passwd = $row[2];

	$sql = "select thread,right(thread,1) 
			  from shop_board_{$boardid} 
			 where fid = '$fid' 
			   and length(thread) = length('$thread')+1 
			   and locate('$thread',thread) = 1 
			 order by thread desc limit 1 ";
	$r = sql_query($sql);
	if(!$r)
		alert('리플글 작성에 실패 하였습니다.');

	$rows = sql_num_rows($r);
	if($rows) {
		$row = sql_fetch_row($r);
		$thread_head = substr($row[0],0,-1);
		$thread_foot = ++$row[1];
		$new_thread = $thread_head . $thread_foot;
	} else {	
		$new_thread = $thread . "A";	
	}

	$sql_common = " btype		= '$_POST[btype]',                
					ca_name		= '$_POST[ca_name]',
					issecret	= '$_POST[issecret]',
					havehtml	= '$_POST[havehtml]',
					writer      = '$writer',
					writer_s	= '$_POST[writer_s]',
					subject		= '$_POST[subject]',
					memo		= '$_POST[memo]',							
					passwd      = '$passwd',
					average		= '$_POST[average]',
					product		= '$_POST[product]',
					pt_id		= '$pt_id' ";
	   
	$sql = " insert into shop_board_{$boardid}
				set fid = '$fid',
					wdate = '".MS_SERVER_TIME."',
					wip = '{$_SERVER['REMOTE_ADDR']}',
					thread = '$new_thread', 
					$sql_common ";
	sql_query($sql);

	goto_url(MS_MBBS_URL."/board_list.php?boardid=$boardid");

} else {
	alert("정상적인 접근이 아닌것 같습니다.");
}
?>