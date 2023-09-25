<?php
include_once("./_common.php");

// 자료가 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

check_demo();

check_admin_token();

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(MS_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/

    /***
    * Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*
	$data->sheets[0]['numRows'] - count rows
	$data->sheets[0]['numCols'] - count columns
	$data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

	$data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

	$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
	if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
	$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
	$data->sheets[0]['cellsInfo'][$i][$j]['colspan']
	$data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

//    error_reporting(E_ALL ^ E_NOTICE);
	
    $fail_od_no = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

$gs_id= '';
$gcate= '';
$notax = '';
$gname = '';
	for($k=2; $k<=$data->sheets[0]['numRows']; $k++)
	{

//		if(trim($data->sheets[0]['cells'][$k][3]))
//			continue;

        $total_count++;
	
$gid		 = addslashes(trim($data->sheets[0]['cells'][$k][1])); // gid 값
$ct_qty		 = addslashes(trim($data->sheets[0]['cells'][$k][2])); // 수량
$name		 = addslashes(trim($data->sheets[0]['cells'][$k][3])); // 구매자이름
$telephone		 = addslashes(trim($data->sheets[0]['cells'][$k][4])); // 전화번호
$cellphone		 = addslashes(trim($data->sheets[0]['cells'][$k][5])); // 휴대번호
$zip		 = addslashes(trim($data->sheets[0]['cells'][$k][6])); // 우편번호
$addr1		 = addslashes(trim($data->sheets[0]['cells'][$k][7])); // 주소1
$addr2		 = addslashes(trim($data->sheets[0]['cells'][$k][8])); // 주소2
$b_name		 = addslashes(trim($data->sheets[0]['cells'][$k][9])); // 받는사람
$b_telephone		 = addslashes(trim($data->sheets[0]['cells'][$k][10])); // 전화번호
$b_cellphone		 = addslashes(trim($data->sheets[0]['cells'][$k][11])); // 휴대번호
$b_zip		 = addslashes(trim($data->sheets[0]['cells'][$k][12])); // 우편번호
$b_addr1		 = addslashes(trim($data->sheets[0]['cells'][$k][13])); // 주소1
$b_addr2		 = addslashes(trim($data->sheets[0]['cells'][$k][14])); // 주소2
$memo		 = addslashes(trim($data->sheets[0]['cells'][$k][15])); // 배송메세지
$jumun		 = addslashes(trim($data->sheets[0]['cells'][$k][16])); // 주문번호
$jumunp		 = addslashes(trim($data->sheets[0]['cells'][$k][17])); // 상품주문번호
$op1		 = addslashes(trim($data->sheets[0]['cells'][$k][18])); // 옵션1
$op2		 = addslashes(trim($data->sheets[0]['cells'][$k][19])); // 옵션2
$op3		 = addslashes(trim($data->sheets[0]['cells'][$k][20])); // 옵션3
$op4		 = addslashes(trim($data->sheets[0]['cells'][$k][21])); // 옵션4


$row = sql_fetch(" select * from shop_goods where gcode = '$gid' ");
$gs_id = $row['index_no'];
$gs_cd = $row['gcode'];
$notax = $row['notax'];
$gname = "상품명:".$row['gname'];
$gname1 = $row['gname'];
$baesong_price = $row['sc_amt'];
$normal_price = $row['normal_price'];
$supply_price = $row['supply_price'];
$goods_price = $row['goods_price'];
$simg1 = $row['simg1'];
$simg2 = $row['simg2'];
$baesong_price = $row['sc_amt'];
$baesong_price = $row['sc_amt'];
$baesong_price = $row['sc_amt'];


if($notax == 'Y'){
$gs_notax = '1';
}else{
$gs_notax = '0';
}
$gs_cd = $row['gcode'];
$famiwel_mb_id = $row['famiwel_seller_id'];
$od = sql_fetch(" select gcate from shop_goods_cate where gs_id = '$gs_id' ");
$ca_id = $od['gcate'];
$od_no = cart_uniqid();

$sql = " select * from shop_goods_option where gs_id = '$gs_id' ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if($cnt == '1'){
	$td = sql_fetch(" select * from shop_goods_option where gs_id = '$gs_id'");
	$io_supply_price = $td['io_supply_price'];
	$io_famiwel_no = $td['io_famiwel_no'];
	$io_id = $td['io_id'];
	$io_type = $td['io_type'];
	$io_price = $row['goods_price'] + $td['io_price'];
	$io_value = $td['io_value'];
	$io_op = $td['io_id'];
}else{
	if($op1){
	$td = sql_fetch(" select * from shop_goods_option where gs_id = '$gs_id' and io_id like '%$op1%' ");
	echo "select * from shop_goods_option where gs_id = '$gs_id' and io_id like '%$op1%'";
	$io_supply_price = $td['io_supply_price'];
	$io_famiwel_no = $td['io_famiwel_no'];
	$io_id = $td['io_id'];
	$io_type = $td['io_type'];
	$io_price = $row['goods_price'] + $td['io_price'];
	$io_value = $td['io_value'];
	$io_op = $op1;
	}
	if($op2){
	echo "select * from shop_goods_option where gs_id = '$gs_id' and io_id like '%$op2%'";
	$io_supply_price = $td['io_supply_price'];
	$io_famiwel_no = $td['io_famiwel_no'];
	$io_id = $td['io_id'];
	$io_type = $td['io_type'];
	$io_price = $row['goods_price'] + $td['io_price'];
	$io_value = $td['io_value'];
	$io_op = $op2;
	}
	if($op3){
	echo "select * from shop_goods_option where gs_id = '$gs_id' and io_id like '%$op3%'";
	$io_supply_price = $td['io_supply_price'];
	$io_famiwel_no = $td['io_famiwel_no'];
	$io_id = $td['io_id'];
	$io_type = $td['io_type'];
	$io_price = $row['goods_price'] + $td['io_price'];
	$io_value = $td['io_value'];
	$io_op = $op3;
	}
	if($op4){
	echo "select * from shop_goods_option where gs_id = '$gs_id' and io_id like '%$op4%'";
	$io_supply_price = $td['io_supply_price'];
	$io_famiwel_no = $td['io_famiwel_no'];
	$io_id = $td['io_id'];
	$io_type = $td['io_type'];
	$io_price = $row['goods_price'] + $td['io_price'];
	$io_value = $td['io_value'];
	$io_op = $op4;
	}
}
$od_id = get_uniqid();


			$sql = " insert into shop_cart
						( ca_id, od_id, mb_id, pt_id, up_id, gs_id, ct_direct, ct_time, ct_price, ct_kv, ct_supply_price, ct_qty, ct_point, io_id, io_type, io_price, ct_option, ct_send_cost, od_no, ct_ip,famiwel_mb_id,io_famiwel_no,io_supply_price,gs_cd )
					VALUES ";
			$sql.= "( '$ca_id', '{$od_id}', 'admin', '{$io_pt_id}', '{$io_up_id}', '{$row['index_no']}', '$od_no', '".MS_TIME_YMDHIS."', '{$row['goods_price']}', '{$row['goods_kv']}', '{$row['supply_price']}', '$ct_qty', '{$row['gpoint']}', '$io_id', '$io_type', '$io_price', '$gname', '$ct_send_cost', '$od_no', '{$_SERVER['REMOTE_ADDR']}','$famiwel_mb_id','$io_famiwel_no','$io_supply_price','$gs_cd' )";
			sql_query($sql);


			$sql = " insert into shop_order
						( mb_site, od_id, od_no, mb_id, shop_id, dan, paymethod, name, cellphone, telephone, email, zip, addr1, addr2, addr_jibeon, b_name, b_cellphone, b_telephone, b_zip, b_addr1, b_addr2, b_addr_jibeon, gs_id, gs_notax, seller_id, sum_qty, goods_price, supply_price, use_price, baesong_price, bank, od_time, od_pwd, od_settle_pid, od_tax_mny, od_vat_mny, od_free_mny, famiwel_op_no, famiwel_mb_id,gid)
					VALUES ";
			$sql.= "( 'kgs365', '{$od_id}', '{$od_no}', 'admin', 'admin', '1', '무통장입금', '{$name}', '{$cellphone}', '{$telephone}', '', '{$zip}', '$addr1', '$addr2', 'R', '$b_name', '$b_cellphone', '$b_telephone','$b_zip', '$b_addr1', '$b_addr2','R','$gs_id','$gs_notax','AP-000001','$ct_qty','$io_price','$io_supply_price','$io_price','$baesong_price','기업은행 455-072865-04-028 신종식(제이에스패밀리)', '".MS_TIME_YMDHIS."','*24CA7CAB8CA49FBDA1A79F88FE68C3A9EE8F616B','admin','','','','$io_famiwel_no','$famiwel_mb_id','$gs_cd' )";
			sql_query($sql);
			$succ_count++;



        if(!$gid || !$ct_qty || !$op1) {
            $fail_count++;
            $fail_od_no[] = $od_no;
            continue;
        }

	}
}
?>

<h2>총 건수</h2>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">총주문건수</th>
		<td><?php echo number_format($total_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">완료건수</th>
		<td><?php echo number_format($succ_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">실패건수</th>
		<td><?php echo number_format($fail_count); ?>건</td>
	</tr>
	<?php if($fail_count > 0) { ?>
	<tr>
		<th scope="row">실패일련번호</th>
		<td><?php echo implode(', ', $fail_od_no); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo MS_ADMIN_URL; ?>/order.php?code=excel_ing" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ형식은 <strong>작성양식 다운로드</strong>버튼을 클릭하여 엑셀파일을 다운받으신후 주문내역을 입력하시면 됩니다.</p>
			<p>ㆍ수정 완료 후 엑셀파일을 업로드하시면 주문정보가 일괄등록됩니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.</p>
			<p>ㆍ옵션이 있는 경우 옵션의 명칭과 100% 일치해야 합니다. 글자 하나라도 틀리게 입력되면 주문이 성공하지 못합니다.</p>
			<p>ㆍ엑셀데이터는 2번째 라인부터 저장되므로 타이틀은 지우시면 안됩니다.</p>
		</div>
	 </div>
</div>

<script>
$(function() {
	// 새로고침(F5) 막기
	$(document).keydown(function (e) {		
		if(e.which === 116) {
			if(typeof event == "object") {
				event.keyCode = 0;
			}
			return false;
		} else if(e.which === 82 && e.ctrlKey) {
			return false;
		}
	});
});
</script>

