<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
	alert("�α��� �� �ۼ� �����մϴ�.");
}

if($w == "" || $w == "u") {
	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// ������ ������ ���� �ٽ� �Է����� ���ؼ� �������� �Ѵ�.
		set_session("ss_token", "");
	} else {
		alert("�߸��� ���� �Դϴ�.");
		exit;
	}

	$gs_id = trim(strip_tags($_POST['gs_id']));
	$me_id = trim(strip_tags($_POST['me_id']));
	$wr_score = trim(strip_tags($_POST['wr_score']));
	$seller_id = trim(strip_tags($_POST['seller_id']));

	if(substr_count($_POST['wr_content'], "&#") > 50) {
		alert("���뿡 �ùٸ��� ���� �ڵ尡 �ټ� ���ԵǾ� �ֽ��ϴ�.");
	}

	if(!get_magic_quotes_gpc()) {
		$wr_content = addslashes($_POST['wr_content']);
	}
}

if($w == "") 
{ 
	$sql = "insert into shop_goods_review 
			   set gs_id	 = '$gs_id', 
				   mb_id	 = '$member[id]',
				   memo		 = '$wr_content',
				   score	 = '$wr_score',
				   reg_time	 = '".MS_TIME_YMDHIS."',
				   seller_id = '$seller_id',
				   pt_id	 = '$pt_id' ";
	sql_query($sql);

	sql_query("update shop_goods set m_count = m_count + 1 where index_no='$gs_id'");

	alert("���������� ��� �Ǿ����ϴ�.","replace");
}
else if($w == "u")
{
    $sql = " update shop_goods_review
                set memo	= '$wr_content',
					score	= '$wr_score'
			  where index_no = '$me_id' ";
    sql_query($sql);

	alert("���������� ���� �Ǿ����ϴ�.","replace");
}
else if($w == "d")
{
	if(!is_admin())
    {
        $sql = " select * from shop_goods_review where mb_id = '{$member['id']}' and index_no = '$me_id' ";
        $row = sql_fetch($sql);
        if(!$row)
            alert("�ڽ��� �۸� �����Ͻ� �� �ֽ��ϴ�.");
    }

	// �����ı� ����
    $sql = "delete from shop_goods_review 
			 where index_no='$me_id' 
			    and md5(concat(index_no,reg_time,mb_id)) = '{$hash}' ";
	sql_query($sql);
	
	// �����ı� ������ ��ǰ���̺� ��ǰ�� ī���͸� �����Ѵ�
	sql_query("update shop_goods set m_count=m_count - 1 where index_no='$gs_id'");
	
	if($p == "1")
		goto_url(MS_MSHOP_URL."/view_user.php?gs_id=$gs_id");
	else
		goto_url(MS_MSHOP_URL."/view.php?gs_id=$gs_id");		
}
?>