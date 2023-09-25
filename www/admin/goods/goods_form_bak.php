<?php
if(!defined('_MALLSET_')) exit;

if($w == "") {
	$gs['mb_id']		= encrypted_admin();
	$gs['gcode']		= time();
	$gs['sc_type']		= 0; // ��ۺ� ����	0:���뼳��, 1:������, 2:���Ǻ� ������, 3:������
	$gs['sc_method']	= 0; // ��ۺ� ����	0:����, 1:����, 2:����ڼ���
	$gs['stock_mod']	= 0;
	$gs['noti_qty']		= 999;
	$gs['simg_type']	= 0;
	$gs['isopen']		= 1;
	$gs['notax']		= 1;
	$gs['use_aff']		= 0;
	$gs['ppay_type']	= 0;
	$gs['ppay_rate']	= 0;
	$gs['zone']			= '����';

} else if($w == "u") {
	$gs = get_goods($gs_id);
    if(!$gs)
        alert("�������� ���� ��ǰ �Դϴ�.");

	$gs_id_attr = " readonly style='background-color:#ddd;'";

	if(is_null_time($gs['sb_date'])) {
		$gs['sb_date'] = '';
	}
	if(is_null_time($gs['eb_date'])) {
		$gs['eb_date'] = '';
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


if($gs['use_aff']) // ������ ��ǰ�ΰ�?
	$target_table = 'shop_cate_'.$gs['mb_id'];
else // ���� ��ǰ
	$target_table = 'shop_cate';

include_once(MS_LIB_PATH."/categoryinfo.lib.php");
include_once(MS_LIB_PATH.'/goodsinfo.lib.php');
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$frm_submit = '<div class="btn_confirm">
    <input type="submit" value="����" class="btn_large" accesskey="s">';
if($w == "u" && $bak) {
    $frm_submit .= PHP_EOL.'<a href="./goods.php?code='.$bak.$qstr.'&page='.$page.'" class="btn_large bx-white">���</a>';
	$frm_submit .= '<a href="./goods.php?code=form" class="btn_large bx-red">�߰�</a>'.PHP_EOL;
}
$frm_submit .= '</div>';

$pg_anchor ='<ul class="anchor">
<li><a href="#anc_sitfrm_cate">ī�װ�</a></li>
<li><a href="#anc_sitfrm_ini">�⺻����</a></li>
<li><a href="#anc_sitfrm_option">�ɼ�����</a></li>
<li><a href="#anc_sitfrm_cost">���� �� ���</a></li>
<li><a href="#anc_sitfrm_pay">������������</a></li>
<li><a href="#anc_sitfrm_sendcost">��ۺ�</a></li>
<li><a href="#anc_sitfrm_compact">�������</a></li>'.PHP_EOL;
if(!$gs['use_aff'])
	$pg_anchor .='<li><a href="#anc_sitfrm_relation">���û�ǰ</a></li>'.PHP_EOL;
$pg_anchor .='<li><a href="#anc_sitfrm_img">��ǰ�̹���</a></li>'.PHP_EOL;
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
<h2>ī�װ�</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>���õ� ī�װ��� <span class="fc_084">�ֻ��� ī�װ��� ��ǥ ī�װ��� �ڵ�����</span>�Ǹ�, �ּ� 1���� ī�װ��� ����ϼž� �մϴ�.</p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row" rowspan="2">ī�װ�</th>
		<td>
			<div class="sub_frm01">
				<table>
				<tr>
					<th scope="col" class="tac">1�� �з�</th>
					<th scope="col" class="tac">2�� �з�</th>
					<th scope="col" class="tac">3�� �з�</th>
					<th scope="col" class="tac">4�� �з�</th>
					<th scope="col" class="tac">5�� �з�</th>
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
				<button type="button" class="btn_lsmall blue" onclick="category_add();">�з��߰�</button>
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
				<button type="button" class="btn_lsmall bx-white" onclick="category_move('sel_ca_id', 'prev');">�� ����</button>
				<button type="button" class="btn_lsmall bx-white" onclick="category_move('sel_ca_id', 'next');">�� �Ʒ���</button>
				<button type="button" class="btn_lsmall frm_option_del red fr">�з�����</button>
			</div>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_ini">
<h2>�⺻����</h2>
<?php echo $pg_anchor; ?>
<?php if($w == 'u') { ?>
<div class="local_desc02 local_desc">
	<p>��ǰ ����Ͻ� : <b><?php echo $gs['reg_time']; ?></b>, �ֱ� �����Ͻ� : <b><?php echo $gs['update_time']; ?></b></p>
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
		<th scope="row">��ü�ڵ�</th>
		<td>
			<input type="text" name="mb_id" value="<?php echo $gs['mb_id']; ?>" required itemname="��ü�ڵ�" class="required frm_input">
			<a href="./supply.php" onclick="win_open(this,'pop_supply','550','500','no');return false" class="btn_small">��ü����</a>
		</td>
	</tr>
	<tr>
		<th scope="row">��ǰ�ڵ�</th>
		<td>
			<input type="text" name="gcode" value="<?php echo $gs['gcode']; ?>" required itemname="��ǰ�ڵ�" class="required frm_input"<?php echo $gs_id_attr; ?>>
			<?php if($w == "u") { ?><a href="<?php echo MS_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank" class="btn_small">�̸�����</a><?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">��ǰ��</th>
		<td><input type="text" name="gname" value="<?php echo $gs['gname']; ?>" required itemname="��ǰ��" class="required frm_input" size="80"></td>
	</tr>

	<tr>
		<th scope="row">ª������</th>
		<td><input type="text" name="explan" value="<?php echo $gs['explan']; ?>" class="frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">�˻�Ű����</th>
		<td>
			<input type="text" name="keywords" value="<?php echo $gs['keywords']; ?>" class="frm_input wfull">
			<?php echo help('�ܾ�� �ܾ� ���̴� �޸� ( , ) �� �����Ͽ� �������� �Է��� �� �ֽ��ϴ�. ����) ����, ���, �Ķ�'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">A/S ���ɿ���</th>
		<td><input type="text" name="repair" value="<?php echo $gs['repair']; ?>" class="frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">�귣��</th>
		<td>
			<select name="brand_uid">
				<option value="">����</option>
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
		<th scope="row">�𵨸�</th>
		<td><input type="text" name="model" value="<?php echo $gs['model']; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">���걹(������)</th>
		<td><input type="text" name="origin" value="<?php echo $gs['origin']; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">������</th>
		<td><input type="text" name="maker" value="<?php echo $gs['maker']; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">��������<?=$gs['notax']?></th>
		<td class="td_label">
			<?php echo radio_checked('notax', $gs['notax'], '1', '����'); ?>
			<?php echo radio_checked('notax', $gs['notax'], '0', '�鼼'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">�Ǹſ���</th>
		<td class="td_label">
			<?php echo radio_checked('isopen', $gs['isopen'], '1', '����'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '2', 'ǰ��'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '3', '����'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '4', '����'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">���̹����� ��ǰID</th>
		<td>
			<input type="text" name="ec_mall_pid" value="<?php echo $gs['ec_mall_pid']; ?>" id="ec_mall_pid" class="frm_input">
			<?php echo help("���̹����ο� ������ ��� ���̹����� ��ǰID�� �Է��Ͻø� ���̹����̿� �����˴ϴ�.<br>�Ϻ� ���θ��� ��� ���̹����� ��ǰID ��� ���θ� ��ǰID�� �Է��ؾ� �ϴ� ��찡 �ֽ��ϴ�.<br>���̹����� ������������ �� �κп� ���� �ȳ��� �̷����� �ȳ����� ��� ���� �Է��Ͻø� �˴ϴ�."); ?>
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
<h2>���� �� ���</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">���߰���</th>
		<td colspan="3">
			<input type="text" name="normal_price" value="<?php echo number_format($gs['normal_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> ��
			<span class="fc_197 marl5">���߿� �ǸŵǴ� ���� (�ǸŰ����� ũ�������� ���߰� ǥ�þ���)</span>
		</td>
	</tr>
	<tr>
		<th scope="row">���ް���</th>
		<td colspan="3">
			<input type="text" name="supply_price" value="<?php echo number_format($gs['supply_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> ��
			<span class="fc_197 marl5">����ó���� ���޹��� ����</span>
		</td>
	</tr>
    <tr>
		<th scope="row">�ǸŰ���</th>
		<td colspan="3">
            <?php if( defined('USE_BUY_PARTNER_GRADE') && USE_BUY_PARTNER_GRADE ) : ?><?php echo minishop::minishopLevelSelect('buy_minishop_grade', $gs['buy_minishop_grade'], "���ͻ�ǰ�ƴ�", array( "onchange"=>"setGoodsPrice(this)") ); ?><script>
                function setGoodsPrice(el) {
                    var $opt = $(el).find("option:selected");
                    if( $opt.data('anewPrice') > 0 ) {
                        $("[name=goods_price]").val( $opt.data('anewPriceFormat'));
                    }
                }
            </script><?php endif; ?>
            <input type="text" name="goods_price" value="<?php echo number_format($gs['goods_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> ��
			<span class="fc_197 marl5">���� �ǸŰ� �Է� (��ǥ�������� ���)</span>
		</td>
	</tr>
    <tr>
        <th scope="row">���ϸ��� ����</th>
        <td colspan="3">
            <input type="text" name="goods_kv" value="<?php echo number_format($gs['goods_kv']); ?>" class="frm_input w80" onkeyup="addComma(this);"> ��
			<input type="text" name="goods_kv_per" class="frm_input w50" value="<?php echo number_format($gs['goods_kv_per']); ?>"> %&nbsp;&nbsp;<label><input type="checkbox" name="goods_kv_basic" value="1" <?if($gs['goods_kv_basic']=="1")		echo " checked";?>>���ϸ��� ���� ����</label>
			<span class="fc_197 marl5">���� % �Ѵ� �ԷµǾ��ִ� ��� %�� ���������մϴ�.</span>


            <!--<span class="fc_197 marl5">���ް���, �ǸŰ��� ������ �ڵ����� ���� �˴ϴ�.</span>-->
            <script>
                function calcGoodsKv(){
                    var supply_price = parseInt($('[name=supply_price]').val().replace(/[^0-9]/g, ''));
                    var goods_price  = parseInt($('[name=goods_price]').val().replace(/[^0-9]/g, ''));
                    if( goods_price > supply_price ) $('[name=goods_kv]').val( commaStr( new String(Math.round((goods_price - supply_price)*0.4))) ); // 40%
                }
            </script>
        </td>
    </tr>
	<tr>
		<th scope="row">��������Ʈ ����</th>
		<td colspan="3">
			<input type="text" name="gpoint" value="<?php echo number_format($gs['gpoint']); ?>" class="frm_input w80" onkeyup="addComma(this);"> P
			<input type="text" name="gpoint_per" class="frm_input w50" value="<?php echo number_format($gs['gpoint_per']); ?>"> %&nbsp;&nbsp;<label><input type="checkbox" name="gpoint_basic" value="1" <?if($gs['gpoint_basic']=="1")		echo " checked";?>>��������Ʈ ���� ����</label>
			<span class="fc_197 marl5">P�� % �Ѵ� �ԷµǾ��ִ� ��� %�� ���������մϴ�.</span>
		</td>
	</tr>
    <tr>
        <th scope="row">��������Ʈ���� ���</th>
        <td colspan="3">
            <input type="hidden" name="point_pay_allow" id="point_pay_allow" value="<?php echo $gs['point_pay_allow']?>">
            <input type="checkbox" id="point_pay_allow_checker" name="point_pay_allow_checker" <?php if( $gs['point_pay_allow'] == '1' ) echo ' checked="checked" '; ?> value="1">
            <label for="point_pay_allow_checker">��������Ʈ���� ���</label>

            <label for="point_pay_point" class="marl30">����������Ʈ</label>
            <input type="number" name="point_pay_point" id="point_pay_point" class="frm_input w80" size="10" value="<?php echo $gs['point_pay_point']; ?>">P
            <input type="number" name="point_pay_per" id="point_pay_per" class="frm_input w50" size="2" value="<?php echo $gs['point_pay_per']; ?>">%
			<span class="fc_197 marl5">P�� % �Ѵ� �ԷµǾ��ִ� ��� %�� ���������մϴ�.</span>
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
        <th scope="row"><?php echo('��õID'); ?></th>
        <td><input type="text" _readonly placeholder="ID" class="frm_input" _required="required" id="up_id" name="up_id" value="<?php echo $gs['up_id']; ?>">
            <a href="./seller/seller_reglist.php?target=up_id" onclick="win_open(this,'seller_reglist','550','500','1'); return false" class="btn_small grey">ȸ���˻�</a><script>
                var setUser = function(mb){
                    $('#up_id').val(mb.plain_id);
                }
            </script></td>
        <th scope="row"><?php echo('��õ�Ǹż�����'); ?></th>
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
            (�Ǹż����Ḧ ���������� �߰� �����Ͻ� �� �ֽ��ϴ�.)
        </td>
    </tr>
	<tr>
		<th scope="row">���� ��ü����</th>
		<td colspan="3">
			<input type="text" name="price_msg" value="<?php echo $gs['price_msg']; ?>" class="frm_input">
			<span class="fc_197 marl5">���ݴ�� ������ ������ ������ �� �Է�, �ֹ��Ұ�</span>
		</td>
	</tr>
	<tr>
		<th scope="row">����</th>
		<td colspan="3">
			<input type="radio" name="stock_mod" value="0" id="ids_stock_mode1"<?php echo get_checked('0', $gs['stock_mod']); ?> onclick="chk_stock(0);">
			<label for="ids_stock_mode1" class="marr10">������</label>
			<input type="radio" name="stock_mod" value="1" id="ids_stock_mode2"<?php echo get_checked('1', $gs['stock_mod']); ?> onclick="chk_stock(1);">
			<label for="ids_stock_mode2">����</label>
			<input type="text" name="stock_qty" value="<?php echo number_format($gs['stock_qty']); ?>" class="frm_input w80" onkeyup="addComma(this);"> ��,
			<b class="marl10">��� �뺸����</b> <input type="text" name="noti_qty" value="<?php echo number_format($gs['noti_qty']); ?>" class="frm_input w80" onkeyup="addComma(this);"> ��
			<p class="fc_197 mart7">��ǰ�� ��� �뺸�������� ���� �� ��ǰ �������� ǥ�õ˴ϴ�.<br>�ɼ��� �ִ� ��ǰ�� ���� �ɼ��� �뺸������ ����˴ϴ�. ������ �������̸� �������� ǥ�õ��� �ʽ��ϴ�.</p>
		</td>
	</tr>
	<tr>
		<th scope="row">�ֹ��ѵ�</th>
		<td colspan="3">
			�ּ� <input type="text" name="odr_min" value="<?php echo $gs['odr_min']; ?>" class="frm_input w80"> ~
			�ִ� <input type="text" name="odr_max" value="<?php echo $gs['odr_max']; ?>" class="frm_input w80">
			<span class="fc_197 marl5">���Է½� ������</span>
		</td>
	</tr>
	<tr>
		<th scope="row">�ǸűⰣ ����</th>
		<td colspan="3">
			<label for="sb_date" class="sound_only">������</label>
			<input type="text" name="sb_date" value="<?php echo $gs['sb_date']; ?>" id="sb_date" class="frm_input w80" maxlength="10"> ~
			<label for="eb_date" class="sound_only">������</label>
			<input type="text" name="eb_date" value="<?php echo $gs['eb_date']; ?>" id="eb_date" class="frm_input w80" maxlength="10">
			<a href="javascript:void(0);" class="btn_small is_reset">�Ⱓ�ʱ�ȭ</a>
			<div class="fc_197 mart7">
				������ �Ⱓ ���ȸ� �Ǹ� �����ϸ�, ������ ������ ���Ŀ��� �Ǹŵ��� �ʽ��ϴ�.<br>
				�Ͻ� �Ǹ����� ó���Ͻ� ���, �������� ���糯¥ ������ ���� ��¥�� �־��ֽø� �˴ϴ�.
			</div>
			<script>
			$(function(){
				// ��¥ �˻� : TODAY MAX������ �ν� (maxDate: "+0d")�� �����ϸ� MAX�� ����
				$("#sb_date,#eb_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});

				// �Ⱓ�ʱ�ȭ
				$(document).on("click", ".is_reset", function() {
					$("#sb_date, #eb_date").val("");
				});
			});
			</script>
		</td>
	</tr>
	<tr>
		<th scope="row">���Ű��� ����</th>
		<td colspan="3">
			<?php echo get_goods_level_select('buy_level', $gs['buy_level']); ?>
			<label class="marl5"><input type="checkbox" name="buy_only" value="1"<?php echo get_checked('1', $gs['buy_only']); ?>> ���� �����̻� ���ݰ���</label>
		</td>
	</tr>
	<tr>
		<th scope="row">�˻��� ���� ����</th>
		<td colspan="3">
			<?php echo get_goods_level_select('display_level', $gs['display_level']); ?>
			<!--<label class="marl5"><input type="checkbox" name="display_only" value="1"<?php echo get_checked('1', $gs['display_only']); ?>> ���� �����̻� ��ǰ����</label>-->
		</td>
	</tr>

	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_pay">
<h2>������������</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">������ ����Ÿ��</th>
		<td>
			<select name="ppay_type" onChange="chk_ppay_type(this.value);">
				<?php echo option_selected('0', $gs['ppay_type'], '���뼳��'); ?>
				<?php echo option_selected('1', $gs['ppay_type'], '��������'); ?>
			</select>
			<a href="./minishop.php?code=pbasic" target="_blank" class="btn_small grey">����</a>
		</td>
	</tr>
	<tr>
		<th scope="row">������ �����ܰ�</th>
		<td>
			<select name="ppay_rate">
				<?php echo option_selected('0', $gs['ppay_rate'], '�ۼ�Ʈ�� ����'); ?>
				<?php echo option_selected('1', $gs['ppay_rate'], '�ݾ����� ����'); ?>
			</select>
			<input type="text" name="ppay_dan" value="<?php echo $gs['ppay_dan']; ?>" onkeyup="chk_ppay_dan(this.value,'<?php echo $gs_id; ?>')" class="frm_input w50"> <span>�ܰ�</span>
		</td>
	</tr>
	<tr>
		<th scope="row">�������Է�</th>
		<td><span id="chk_ppay_auto"><span></td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_sendcost">
<h2>��ۺ�</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>�� <span>�������) : ���� ���� �Ǹ����� ��ǰ�� ���� ���Ž� ��ۺ�� �� �ѹ��� �ΰ� �˴ϴ�. ��! ��ۺ�� ���� ū���� �����Ͽ� ���� �˴ϴ�.</span></p>
	<p>�� <span>���Ǻι�����) : ���� ���� �Ǹ����� ��ǰ�� ���� ���Ž� ���� ū ���� (���� ��ۺ�) �ݾ��� �����Ͽ� ������ۺ� �ڵ� ���� �˴ϴ�.</span></p>
	<p>�� <span>������) : ���� ���� �Ǹ����� ��ǰ�� ���� ���Ž� ���� ū ���� (�⺻ ��ۺ�) �ݾ��� �����Ͽ� ������ۺ� �ڵ� ���� �˴ϴ�.</span></p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">�������</th>
		<td>
			<select name="sc_type" onChange="chk_sc_type(this.value);">
				<?php echo option_selected('0', $gs['sc_type'], '���뼳��'); ?>
				<?php echo option_selected('1', $gs['sc_type'], '������'); ?>
				<?php echo option_selected('2', $gs['sc_type'], '���Ǻι�����'); ?>
				<?php echo option_selected('3', $gs['sc_type'], '������'); ?>
			</select>
			<a href="./config.php?code=baesong" target="_blank" class="btn_small grey">����</a>
			<div id="sc_method" class="mart7">
				��ۺ����
				<select name="sc_method" class="marl10">
				<?php echo option_selected('0', $gs['sc_method'], '����'); ?>
				<?php echo option_selected('1', $gs['sc_method'], '����'); ?>
				<?php echo option_selected('2', $gs['sc_method'], '����ڼ���'); ?>
				</select>
			</div>
			<div id="sc_amt" class="padt5">
				�⺻��ۺ� <input type="text" name="sc_amt" value="<?php echo number_format($gs['sc_amt']); ?>" class="frm_input w80 marl10" onkeyup="addComma(this);"> ��
				<label class="marl10"><input type="checkbox" name="sc_each_use" value="1"<?php echo get_checked('1', $gs['sc_each_use']); ?>> ������ۺҰ�</label>
			</div>
			<div id="sc_minimum" class="padt5">
				���ǹ�ۺ� <input type="text" name="sc_minimum" value="<?php echo number_format($gs['sc_minimum']); ?>" class="frm_input w80 marl10" onkeyup="addComma(this);"> �� �̻��̸� ������
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row">��۰��� ����</th>
		<td>
			<select name="zone">
				<?php echo option_selected('����', $gs['zone'], '����'); ?>
				<?php echo option_selected('������', $gs['zone'], '������'); ?>
				<?php echo option_selected('��⵵', $gs['zone'], '��⵵'); ?>
				<?php echo option_selected('���', $gs['zone'], '���'); ?>
				<?php echo option_selected('����/��⵵', $gs['zone'], '����/��⵵'); ?>
				<?php echo option_selected('����Ư����', $gs['zone'], '����Ư����'); ?>
				<?php echo option_selected('����', $gs['zone'], '����'); ?>
				<?php echo option_selected('���ֵ�', $gs['zone'], '���ֵ�'); ?>
				<?php echo option_selected('��û��', $gs['zone'], '��û��'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">�߰�����</th>
		<td><input type="text" name="zone_msg" value="<?php echo $gs['zone_msg']; ?>" class="frm_input" size="50" placeholder="�� : ���� (�������� ����)"></td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_compact">
<h2>�������</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p><strong>���ڻ�ŷ� ����� ��ǰ ���� ���������� ���� ���</strong>�� ���� �� 35�� ��ǰ���� ���� ��ǰ Ư�� ���� ��Ŀ� ���� �Է��� �� �ֽ��ϴ�.</p>
</div>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">��ǰ�� ����</th>
		<td>
			<select name="info_gubun" id="info_gubun">
				<option value="">��ǰ�� ī�װ� ����</option>
				<?php
				if(!$gs['info_gubun']) $gs['info_gubun'] = 'wear';
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
	// ��ǰ�������� ��ǰ������
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
<h2>���û�ǰ</h2>
<?php echo $pg_anchor; ?>
<div class="local_desc02 local_desc">
	<p>
		<span class="fc_red">���û�ǰ�� ���� ��ǰ�� ��ϰ����ϸ�, ������ ��ǰ�� ����Ͻ� �� �����ϴ�.</span><br>
		��ϵ� ��ü��ǰ ��Ͽ��� ī�װ��� �����ϸ� �ش� ��ǰ ����Ʈ�� ���̾� ��Ÿ���ϴ�.<br>
		��ǰ����Ʈ���� ���� ��ǰ���� �߰��Ͻø� ���õ� ���û�ǰ ��Ͽ� <strong>�Բ�</strong> �߰��˴ϴ�.<br>
		���� ���, A ��ǰ�� B ��ǰ�� ���û�ǰ���� ����ϸ� B ��ǰ���� A ��ǰ�� ���û�ǰ���� �ڵ� �߰��Ǹ�, <strong>���� ��ư�� �����ž� ���� �ݿ��˴ϴ�.</strong>
	</p>
</div>
<div class="srel">
	<div class="compare_wrap">
		<section class="compare_left">
			<h3>��ϵ� ��ü��ǰ ���</h3>
			<label for="sch_relation" class="sound_only">ī�װ�</label>
			<span class="srel_pad">
				<?php echo get_goods_sca_select('sch_relation'); ?>
				<label for="sch_name" class="sound_only">��ǰ��</label>
				<input type="text" name="sch_name" id="sch_name" class="frm_input" size="15">
				<button type="button" id="btn_search_item" class="btn_small">�˻�</button>
			</span>
			<div id="relation" class="srel_list">
				<p>ī�װ��� �����Ͻðų� ��ǰ���� �Է��Ͻ� �� �˻��Ͽ� �ֽʽÿ�.</p>
			</div>
			<script>
			$(function() {
				$("#btn_search_item").click(function() {
					var gcate = $("#sch_relation").val();
					var gname = $.trim($("#sch_name").val());
					var $relation = $("#relation");

					if(gcate == "" && gname == "") {
						$relation.html("<p>ī�װ��� �����Ͻðų� ��ǰ���� �Է��Ͻ� �� �˻��Ͽ� �ֽʽÿ�.</p>");
						return false;
					}

					$("#relation").load(
						tb_admin_url+"/goods/goods_form_relation.php",
						{ gs_id: "<?php echo $gs_id; ?>", gcate: gcate, gname: gname }
					);
				});

				$(document).on("click", "#relation .add_item", function() {
					// �̹� ��ϵ� ��ǰ���� üũ
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
						alert("�̹� ���õ� ��ǰ�Դϴ�.");
						return false;
					}

					var cont = "<li>"+$li.html().replace("add_item", "del_item").replace("�߰�", "����")+"</li>";
					var count = $("#reg_relation li").size();

					if(count > 0) {
						$("#reg_relation li:last").after(cont);
					} else {
						$("#reg_relation").html("<ul>"+cont+"</ul>");
					}

					$li.remove();
				});

				$(document).on("click", "#reg_relation .del_item", function() {
					// if(!confirm("��ǰ�� �����Ͻðڽ��ϱ�?"))
					//    return false;

					$(this).closest("li").remove();

					var count = $("#reg_relation li").size();
					if(count < 1)
						$("#reg_relation").html("<p>���õ� ��ǰ�� �����ϴ�.</p>");
				});
			});
			</script>
		</section>

		<section class="compare_right">
			<h3>���õ� ���û�ǰ ���</h3>
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
						<div class="list_item_btn"><button type="button" class="del_item btn_small">����</button></div>
					</li>
				<?php
					$str[] = $row['index_no'];
				}
				$str = implode(",", $str);

				if($g > 0)
					echo '</ul>';
				else
					echo '<p>���õ� ��ǰ�� �����ϴ�.</p>';
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
<h2>��ǰ�̹��� �� ������</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">�̹��� ��Ϲ��</th>
		<td class="td_label">
			<input type="radio" name="simg_type" id="simg_type_1" value="0"<?php echo get_checked('0', $gs['simg_type']); ?> onclick="chk_simg_type(0);">
			<label for="simg_type_1">���� ���ε�</label>
			<input type="radio" name="simg_type" id="simg_type_2" value="1"<?php echo get_checked('1', $gs['simg_type']); ?> onclick="chk_simg_type(1);">
			<label for="simg_type_2">URL �Է�</label>
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
		<th scope="row">�̹���<?php echo $i; ?> <span class="fc_197">(<?php echo $item_wpx; ?> * <?php echo $item_hpx; ?>)</span></th>
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
		<th scope="row">�󼼼���</th>
		<td>
			<?php echo editor_html('memo', get_text(stripcslashes($gs['memo']), 0)); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">�����ڸ޸�</th>
		<td><textarea name="admin_memo" class="frm_textbox"><?php echo $gs['admin_memo']; ?></textarea></td>
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

	// ���ߺз�ó��
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
        alert("ī�װ��� �ϳ��̻� �����ϼ���.");
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

// ��ۺ� ����
function chk_sc_type(ergFun) {
	var f = document.fregform;
	switch (ergFun) {
		case "0" : // ���뼳��
			eval('sc_amt').style.display = 'none';
			eval('sc_minimum').style.display = 'none';
			eval('sc_method').style.display = 'block';
			f.sc_amt.disabled = true;
			f.sc_minimum.disabled = true;
			f.sc_method.disabled = false;
			break;
		case "1" : // ������
			eval('sc_amt').style.display = 'none';
			eval('sc_minimum').style.display = 'none';
			eval('sc_method').style.display = 'none';
			f.sc_amt.disabled = true;
			f.sc_minimum.disabled = true;
			f.sc_method.disabled = true;
			break;
		case "2" : // ���Ǻι�����
			eval('sc_amt').style.display = 'block';
			eval('sc_minimum').style.display = 'block';
			eval('sc_method').style.display = 'block';
			f.sc_amt.disabled = false;
			f.sc_minimum.disabled = false;
			f.sc_method.disabled = false;
			break;
		case "3" : // ������
			eval('sc_amt').style.display = 'block';
			eval('sc_minimum').style.display = 'none';
			eval('sc_method').style.display = 'block';
			f.sc_amt.disabled = false;
			f.sc_minimum.disabled = true;
			f.sc_method.disabled = false;
			break;
	}
}

//������ ����
function chk_ppay_type(argFun) {
	var f = document.fregform;
	switch (argFun) {
		case "0" :
			f.ppay_dan.disabled = true;
			f.ppay_dan.style.backgroundColor = "dddddd";
			f.ppay_rate.disabled = true;
			eval("chk_ppay_auto").innerHTML = "���������� > ������ ��������å (�⺻���� �����)";
			break;
		case "1" :
			f.ppay_dan.disabled = false;
			f.ppay_dan.style.backgroundColor = "";
			f.ppay_rate.disabled = false;
			eval("chk_ppay_auto").innerHTML = "�����Ḧ ������ �ܰ踦 �Է��ϼ���!";
			break;
	}
}

// �̹��� ��Ϲ��
function chk_simg_type(n) {
	if(n == 0) { // �������ε�
		$(".item_file_fld").show();
		$(".item_url_fld").hide();
	} else { // URL �Է�
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

// ������ üũ
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
