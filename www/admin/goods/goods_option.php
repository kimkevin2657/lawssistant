<?php
include_once('./_common.php');

$po_run = false;

if($gs['index_no']) {
    $opt_subject = explode(',', $gs['opt_subject']);
    $opt1_subject = $opt_subject[0];
    $opt2_subject = $opt_subject[1];
    $opt3_subject = $opt_subject[2];

    $sql = " select * 
			   from shop_goods_option 
			  where io_type = '0'
			    and gs_id = '{$gs['index_no']}' 
			  order by io_no asc ";
    $result = sql_query($sql);
    if(sql_num_rows($result))
        $po_run = true;

} else if(!empty($_POST)) {

    $i = 1;
    $opt_key = 'opt' . $i;
    $sub_key = $opt_key . '_subject';

    $options = array();
    while(isset($_POST[$opt_key]) && !empty($_POST[$opt_key])){
        $opt_key_nm = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST[$opt_key])));
        $sub_key_nm = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST[$sub_key])));
        $options[$sub_key_nm] = explode(',', $opt_key_nm);
        $i++;
        $opt_key = 'opt' . $i;
        $sub_key = $opt_key . '_subject';
    }

    $io_ids = array();
    $is_first = true;



    // 1차 부터 만들려니 안되서....

    foreach($options as $option){

        foreach($option as $opt){
            if( ! $is_first ) {
                $new_io_ids = array();
                foreach($io_ids as $key ) {
                    $new_io_ids[$key][$opt] = $key.chr(30).$opt;
                }
                $io_ids = $new_io_ids;
            } else {
                $io_ids[$opt] = $opt;
            }
        }
        $is_first = false;
    }

    // 마지막 부터 만들면....
    $reverse_values_options = array_reverse(array_values($options));
    $tmp2 = array();
    $key2 = array();
    foreach($reverse_values_options as $option){
        $tmp2 = array();
        foreach($option as $opt){
            if( count($key2) > 0 )
            foreach($key2 as $key){
                $tmp2[$opt.chr(30).$key] = $opt.chr(30).$key;
            }
            else
                $tmp2[$opt] = $opt;
        }

        $key2 = $tmp2;

    }

    $opt1_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt1_subject'])));
    $opt2_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt2_subject'])));
    $opt3_subject = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt3_subject'])));

    $opt1_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt1'])));
    $opt2_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt2'])));
    $opt3_val = preg_replace(MS_OPTION_ID_FILTER, '', trim(stripslashes($_POST['opt3'])));

    if(!$opt1_subject || !$opt1_val) {
        echo '옵션1과 옵션1 항목을 입력해 주십시오.';
        exit;
    }

    $po_run = true;

    $opt1_count = $opt2_count = $opt3_count = 0;

    if($opt1_val) {
        $opt1 = explode(',', $opt1_val);
        $opt1_count = count($opt1);
    }

    if($opt2_val) {
        $opt2 = explode(',', $opt2_val);
        $opt2_count = count($opt2);
    }

    if($opt3_val) {
        $opt3 = explode(',', $opt3_val);
        $opt3_count = count($opt3);
    }
}

if($po_run) {

?>

<table class="mart20">
<colgroup>
	<col class="w40">
	<col>
	<col class="w80">
    <col class="w80">
	<col class="w70">
	<col class="w70">
	<col class="w90">
</colgroup>
<thead>
<tr>
	<th scope="col" class="tac"><input type="checkbox" name="opt_chk_all" value="1" id="opt_chk_all"></th>
	<th scope="col" class="tac">옵션</th>
	<th scope="col" class="tac">추가공급금액</th>
    <th scope="col" class="tac">추가판매금액</th>
	<th scope="col" class="tac">재고수량</th>
	<th scope="col" class="tac">통보수량</th>
	<th scope="col" class="tac">사용여부</th>
</tr>
</thead>
<tbody>
<?php
if($gs['index_no']) {
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$opt_id = $row['io_id'];
		$opt_supply_price = $row['io_supply_price'];
		$io_famiwel_no = $row['io_famiwel_no'];
		$io_famiwel_use = $row['io_famiwel_use'];
        $opt_price = $row['io_price'];
		$opt_stock_qty = $row['io_stock_qty'];
		$opt_noti_qty = $row['io_noti_qty'];
		$opt_use = $row['io_use'];
		$tmp_opt  = str_replace(chr(30), ' &gt; ' , $opt_id);
?>
<tr>
	<td class="tac">
		<input type="hidden" name="opt_id[]" value="<?php echo $opt_id; ?>">
		<input type="hidden" name="io_famiwel_no[]" value="<?php echo $io_famiwel_no; ?>">
		<input type="hidden" name="io_famiwel_use[]" value="<?php echo $io_famiwel_use; ?>">
		<input type="checkbox" name="opt_chk[]" value="1">
	</td>
	<td class="opt-cell"><?php echo $tmp_opt; ?></td>
    <td class="tac">
        <input type="text" name="opt_supply_price[]" value="<?php echo $opt_supply_price; ?>" class="frm_input wfull">
    </td>
    <td class="tac">
		<input type="text" name="opt_price[]" value="<?php echo $opt_price; ?>" class="frm_input wfull">
	</td>
	<td class="tac">
		<input type="text" name="opt_stock_qty[]" value="<?php echo $opt_stock_qty; ?>" class="frm_input wfull">
	</td>
	<td class="tac">
		<input type="text" name="opt_noti_qty[]" value="<?php echo $opt_noti_qty; ?>" class="frm_input wfull">
	</td>
	<td class="tac">
		<select name="opt_use[]">
			<option value="1"<?php echo get_selected('1', $opt_use); ?>>사용함</option>
			<option value="0"<?php echo get_selected('0', $opt_use); ?>>미사용</option>
		</select>
	</td>
</tr>
<?php
	} // for
} else {

    if( true )
    foreach($key2 as $opt_id){
        $opt_price = 0;
        $opt_supply_price = 0;
        $opt_stock_qty = 0;
        $opt_noti_qty = 0;
        $opt_use = 1;

        // 기존에 설정된 값이 있는지 체크
        if($_POST['w'] == 'u') {
            $sql = " select io_no, io_supply_price, io_price, io_stock_qty, io_noti_qty, io_use, io_famiwel_no, io_famiwel_use
							   from shop_goods_option
							  where gs_id = '{$_POST['gs_id']}'
								and io_id = '$opt_id'
								and io_type = '0' ";
            $row = sql_fetch($sql);
            if($row['io_no']) {
                $opt_supply_price = (int)$row['io_supply_price'];
				$io_famiwel_no = $row['io_famiwel_no'];
				$io_famiwel_use = $row['io_famiwel_use'];
                $opt_price = (int)$row['io_price'];
                $opt_stock_qty = (int)$row['io_stock_qty'];
                $opt_noti_qty = (int)$row['io_noti_qty'];
                $opt_use = (int)$row['io_use'];
            }
        }

        $tmp_opt = str_replace(chr(30), ' &gt; ', $opt_id);
        ?>
        <tr>
            <td class="tac">
                <input type="hidden" name="opt_id[]" value="<?php echo $opt_id; ?>">
				<input type="hidden" name="io_famiwel_no[]" value="<?php echo $io_famiwel_no; ?>">
				<input type="hidden" name="io_famiwel_use[]" value="<?php echo $io_famiwel_use; ?>">
                <input type="checkbox" name="opt_chk[]" value="1">
            </td>
            <td class="opt1-cell"><?php echo $tmp_opt; ?></td>
            <td class="tac">
                <input type="text" name="opt_supply_price[]" value="<?php echo $opt_supply_price; ?>" class="frm_input wfull">
            </td>
            <td class="tac">
                <input type="text" name="opt_price[]" value="<?php echo $opt_price; ?>" class="frm_input wfull">
            </td>
            <td class="tac">
                <input type="text" name="opt_stock_qty[]" value="<?php echo $opt_stock_qty; ?>" class="frm_input wfull">
            </td>
            <td class="tac">
                <input type="text" name="opt_noti_qty[]" value="<?php echo $opt_noti_qty; ?>" class="frm_input wfull">
            </td>
            <td class="tac">
                <select name="opt_use[]">
                    <option value="1"<?php echo get_selected('1', $opt_use); ?>>사용함</option>
                    <option value="0"<?php echo get_selected('0', $opt_use); ?>>미사용</option>
                </select>
            </td>
        </tr>
        <?php
    }
    else
	for($i=0; $i<$opt1_count; $i++) {
		$j = 0;
		do {
			$k = 0;
			do {
				$opt_1 = strip_tags(trim($opt1[$i]));
				$opt_2 = strip_tags(trim($opt2[$j]));
				$opt_3 = strip_tags(trim($opt3[$k]));

				$opt_id = $opt_1;
				if($opt_2)
					$opt_id .= chr(30).$opt_2;
				if($opt_3)
					$opt_id .= chr(30).$opt_3;
				$opt_price = 0;
				$opt_supply_price = 0;
				$opt_stock_qty = 0;
				$opt_noti_qty = 0;
				$opt_use = 1;

				// 기존에 설정된 값이 있는지 체크
				if($_POST['w'] == 'u') {
					$sql = " select io_no, io_supply_price, io_price, io_stock_qty, io_noti_qty, io_use, io_famiwel_no, io_famiwel_use
							   from shop_goods_option
							  where gs_id = '{$_POST['gs_id']}'
								and io_id = '$opt_id'
								and io_type = '0' ";
					$row = sql_fetch($sql);
					if($row['io_no']) {
						$opt_supply_price = (int)$row['io_supply_price'];
						$io_famiwel_no = $row['io_famiwel_no'];
						$io_famiwel_use = $row['io_famiwel_use'];
                        $opt_price = (int)$row['io_price'];
						$opt_stock_qty = (int)$row['io_stock_qty'];
						$opt_noti_qty = (int)$row['io_noti_qty'];
						$opt_use = (int)$row['io_use'];
					}
				}

				$tmp_opt  = "";
				$tmp_opt .= $opt_1;
				if($opt_2) $tmp_opt .= ' &gt; '.$opt_2;
				if($opt_3) $tmp_opt .= ' &gt; '.$opt_3;

?>
<tr>
	<td class="tac">
		<input type="hidden" name="opt_id[]" value="<?php echo $opt_id; ?>">
		<input type="hidden" name="io_famiwel_no[]" value="<?php echo $io_famiwel_no; ?>">
		<input type="hidden" name="io_famiwel_use[]" value="<?php echo $io_famiwel_use; ?>">
		<input type="checkbox" name="opt_chk[]" value="1">
	</td>
	<td class="opt1-cell"><?php echo $tmp_opt; ?></td>
    <td class="tac">
        <input type="text" name="opt_supply_price[]" value="<?php echo $opt_supply_price; ?>" class="frm_input wfull">
    </td>
    <td class="tac">
		<input type="text" name="opt_price[]" value="<?php echo $opt_price; ?>" class="frm_input wfull">
	</td>
	<td class="tac">
		<input type="text" name="opt_stock_qty[]" value="<?php echo $opt_stock_qty; ?>" class="frm_input wfull">
	</td>
	<td class="tac">
		<input type="text" name="opt_noti_qty[]" value="<?php echo $opt_noti_qty; ?>" class="frm_input wfull">
	</td>
	<td class="tac">
		<select name="opt_use[]">
			<option value="1"<?php echo get_selected('1', $opt_use); ?>>사용함</option>
			<option value="0"<?php echo get_selected('0', $opt_use); ?>>미사용</option>
		</select>
	</td>
</tr>
<?php
				$k++;
			} while($k < $opt3_count);

			$j++;
		} while($j < $opt2_count);
	} // for
}
?>
</tbody>
</table>
<div class="mart5">
	<button type="button" id="sel_option_delete" class="btn_small bx-white">선택삭제</button>
</div>
<div class="mart15">
	<b>옵션 일괄 적용</b> <span class="fs11 fc_197">전체 옵션의 추가금액, 재고/통보수량 및 사용여부를 일괄 적용할 수 있습니다. 단, 체크된 항목만 적용됨!</span>
</div>

<table class="mart5">
<colgroup>
	<col class="w100">
	<col>
	<col class="w100">
	<col>
</colgroup>
<tbody>
<tr>
	<th scope="row">
		<label for="opt_com_price">추가금액</label>
		<label for="opt_com_price_chk" class="sound_only">추가금액일괄수정</label>
	</th>
	<td>
        <label for="opt_com_price_chk">추가판매가격</label>
		<input type="checkbox" name="opt_com_price_chk" value="1" id="opt_com_price_chk" class="opt_com_chk">
		<input type="text" name="opt_com_price" value="0" id="opt_com_price" class="frm_input w80">

        <label for="opt_com_supply_price_chk">추가공급가격</label>
        <input type="checkbox" name="opt_com_supply_price_chk" value="1" id="opt_com_supply_price_chk" class="opt_com_chk">
        <input type="text" name="opt_com_supply_price" value="0" id="opt_com_supply_price" class="frm_input w80">
	</td>
	<th scope="row">
		<label for="opt_com_stock">재고수량</label>
		<label for="opt_com_stock_chk" class="sound_only">재고수량일괄수정</label>
	</th>
	<td>
		<input type="checkbox" name="opt_com_stock_chk" value="1" id="opt_com_stock_chk" class="opt_com_chk">
		<input type="text" name="opt_com_stock" value="0" id="opt_com_stock" class="frm_input w80">
		<a href="javascript:chk_obj('opt_com_stock', '999999999');" class="btn_small bx-white">무제한</a>
	</td>
</tr>
<tr>
	<th scope="row">
		<label for="opt_com_noti">통보수량</label>
		<label for="opt_com_noti_chk" class="sound_only">통보수량일괄수정</label>
	</th>
	<td>
		<input type="checkbox" name="opt_com_noti_chk" value="1" id="opt_com_noti_chk" class="opt_com_chk">
		<input type="text" name="opt_com_noti" value="0" id="opt_com_noti" class="frm_input w80">
		<a href="javascript:chk_obj('opt_com_noti', '999999999');" class="btn_small bx-white">무제한</a>
	</td>
	<th scope="row">
		<label for="opt_com_use">사용여부</label>
		<label for="opt_com_use_chk" class="sound_only">사용여부일괄수정</label>
	</th>
	<td>
		<input type="checkbox" name="opt_com_use_chk" value="1" id="opt_com_use_chk" class="opt_com_chk">
		<select name="opt_com_use" id="opt_com_use">
			<option value="1">사용함</option>
			<option value="0">미사용</option>
		</select>
	</td>
</tr>
</tbody>
</table>
<div class="btn_confirm02">
	<button type="button" id="opt_value_apply" class="btn_small grey">옵션일괄적용</button>
</div>
<?php } ?>

<script language="javascript">
function chk_obj(fld, num){
	document.getElementById(fld).value = num;
}
</script>