<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-27
 * Time: 21:10
 */


class Shop {

    public static function shopInfo()
    {
        global $pt, $member;


        if( !empty($pt) )
        {
           return $pt;
        } else {
           return $member;
        }
    }

    public static function dpLabel($shop_id, $filters = array())
    {
        $wheres = array();
        foreach($filters as $fkey=>$fval){
            array_push( $wheres, ' and '.$fkey.' = \''. $fval. '\'' );
        }
        $where = join( ' ', $wheres);

        $isset = sql_fetch("select count(1) isset from shop_dp_label where shop_id= '{$shop_id}'");

        $sql ="select * from shop_dp_label a where a.shop_id = '{$shop_id}' {$where} order by type_no ";
        $rslt=sql_query($sql);

        if( sql_num_rows($rslt) == 0 && $isset['isset'] == 0 ){

            $sql ="select * from shop_dp_label a where a.shop_id = '".encrypted_admin()."'  {$where} order by type_no ";
            $rslt=sql_query($sql);

            if( sql_num_rows($rslt) == 0 ) {

                $sql = "select * from shop_dp_label a where a.shop_id = '_default_' {$where} order by type_no";
                $rslt = sql_query($sql);
            }
        }

        $rows = array();
        while($row = sql_fetch_array($rslt)) {
            $rows[$row['type_no']] = $row;
        }

        return $rows;

    }

    public static function dpLabelCheckbox($shop_id, $filters = array(), $checked = array())
    {
        $dpLabels = self::dpLabel($shop_id, $filters);

        foreach($dpLabels as $dpLabel) {
            $key = 'q_type'.$dpLabel['type_no'];
            echo check_checked($key, $checked[$key], 1, $dpLabel['type_label']);
        }

        return $dpLabels;
    }

    public static function dpLabelMetaBox($shop_id)
    {
        ?>
        <form name="fmainform" id="fmainform"  method="post" action="<?php echo MS_ADMIN_URL . '/design/dp_update.php'; ?>">
            <input type="hidden" name="token" value="">
            <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
            <h2>메인진열 설정</h2>
            <div class="tbl_head01" id="shop_dp_label_controller">
                <table id="table_sh">
                    <colgroup>
                        <col width="80px">
                        <col>
                        <col width="180px">
                        <col width="180px">
                        <col width="180px">
                        <col width="180px">
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">TYPE</th>
                        <th scope="col">구분</th>
                        <th scope="col">사용여부</th>
                        <th scope="col">메뉴노출</th>
                        <th scope="col">모바일메뉴노출</th>
                        <th scope="col">메인노출</th>
                        <th scope="col">모바일메인노출</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $dpLabels = Shop::dpLabel($shop_id);
//                    $tmp = sizeof($dpLabels);
//                    array_push($dpLabels, ['type_no'=>++$tmp]);
//                    array_push($dpLabels, ['type_no'=>++$tmp]);
//                    array_push($dpLabels, ['type_no'=>++$tmp]);

                    $i = 0;
                    foreach($dpLabels as $row ) :
                        ?>
                        <tr>
                            <td><?php echo $row['type_no']; ?></td>
                            <td class="list1">
                                <input type="hidden" name="type_no[<?php echo $i; ?>]" id="type_no_<?php echo $row['type_no']; ?>" value="<?php echo $row['type_no']; ?>">
                                <input type="text" class="frm_input" size="20" name="type_label[<?php echo $i; ?>]" id="type_label_<?php echo $row['type_no']?>" value="<?php echo $row['type_label']; ?>">
                            </td>
                            <td>
                                <input type="hidden" name="use_yn[<?php echo $i; ?>]" id="use_yn_<?php echo $row['type_no']; ?>" value="<?php echo $row['use_yn']; ?>">
                                <label for="use_yn_<?php echo $row['type_no']; ?>">사용</label>
                                <input type="checkbox" name="use_yn_chk[<?php echo $i; ?>]" id="use_yn_<?php echo $row['type_no']; ?>_chk" value="1"
                                    <?php if( $row['use_yn'] == 'Y') echo ' checked="checked" '; ?>>
                            </td>
                            <td>
                                <input type="hidden" name="shop_main_menu[<?php echo $i; ?>]" id="shop_main_menu_<?php echo $row['type_no']; ?>" value="<?php echo $row['shop_main_menu']; ?>">
                                <label for="shop_main_menu_<?php echo $row['type_no']; ?>">메뉴사용</label>
                                <input type="checkbox" name="shop_main_menu_chk[<?php echo $i; ?>]" id="shop_main_menu_<?php echo $row['type_no']; ?>_chk" value="1"
                                    <?php if( $row['shop_main_menu'] == 'Y') echo ' checked="checked" '; ?>>
                            </td>
                            <td>
                                <input type="hidden" name="mshop_main_menu[<?php echo $i; ?>]" id="mshop_main_menu_<?php echo $row['type_no']; ?>" value="<?php echo $row['mshop_main_menu']; ?>">
                                <label for="mshop_main_menu_<?php echo $row['type_no']; ?>">모바일메뉴사용</label>
                                <input type="checkbox" name="mshop_main_menu_chk[<?php echo $i; ?>]" id="mshop_main_menu_<?php echo $row['type_no']; ?>_chk" value="1"
                                    <?php if( $row['mshop_main_menu'] == 'Y') echo ' checked="checked" '; ?>>
                            </td>
                            <td>
                                <input type="hidden" name="use_shop_main[<?php echo $i; ?>]" id="use_shop_main_<?php echo $row['type_no']; ?>" value="<?php echo $row['use_shop_main']; ?>">
                                <label for="use_shop_main_<?php echo $row['type_no']; ?>">메인노출</label>
                                <input type="checkbox" name="use_shop_main_chk[<?php echo $i; ?>]" id="use_shop_main_<?php echo $row['type_no']; ?>_chk" value="1"
                                    <?php if( $row['use_shop_main'] == 'Y') echo ' checked="checked" '; ?>>
                            </td>
                            <td>
                                <input type="hidden" name="use_mshop_main[<?php echo $i; ?>]" id="use_mshop_main_<?php echo $row['type_no']; ?>" value="<?php echo $row['use_mshop_main']; ?>">
                                <label for="use_mshop_main_<?php echo $row['type_no']; ?>">모바일노출</label>
                                <input type="checkbox" name="use_mshop_main_chk[<?php echo $i; ?>]" id="use_mshop_main_<?php echo $row['type_no']; ?>_chk" value="1"
                                    <?php if( $row['use_mshop_main'] == 'Y') echo ' checked="checked" '; ?>>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="btn_confirm">
				<input type="button" value="행추가" class="btn_large" onclick="addRow();">
				<input type="button" value="행삭제" class="btn_large" onclick="deleteRow(-1);">
                <input type="submit" value="저장" class="btn_large" accesskey="s"><br><br>(행추가후 구분값을 입력한뒤 먼저 저장버튼을 눌러주세요)
            </div>
        </form>
<script>
function addRow() {
  // table element 찾기
  const table = document.getElementById('table_sh');
  const tbody = table.tBodies[0].rows.length;
  const tbodyta = table.tBodies[0].rows.length+1;

if(tbody == 20){
	alert("20개까지만 추가 가능 합니다. ");  
	return false;
}
  
  // 새 행(Row) 추가
  const newRow = table.insertRow();
  
  // 새 행(Row)에 Cell 추가
  const newCell1 = newRow.insertCell(0);
  const newCell2 = newRow.insertCell(1);
  const newCell3 = newRow.insertCell(2);
  const newCell4 = newRow.insertCell(3);
  const newCell5 = newRow.insertCell(4);
  const newCell6 = newRow.insertCell(5);
  const newCell7 = newRow.insertCell(6);
  
  // Cell에 텍스트 추가
  newCell1.innerHTML = tbodyta;
  newCell2.innerHTML = '<input type="hidden" name="type_no['+tbody+']" id="type_no_'+tbodyta+'" value="'+tbodyta+'">\n <input type="text" class="frm_input" size="20" name="type_label['+tbody+']" id="type_label_'+tbodyta+'" value="">';
  newCell3.innerHTML = '<input type="hidden" name="use_yn['+tbody+']" id="use_yn_'+tbodyta+'" value="">\n <label for="use_yn_'+tbodyta+'">사용</label>\n <input type="checkbox" name="use_yn_chk['+tbody+']" id="use_yn_'+tbodyta+'_chk" value="1" \n >';
  newCell4.innerHTML = '<input type="hidden" name="shop_main_menu['+tbody+']" id="shop_main_menu_'+tbodyta+'" value="">\n <label for="shop_main_menu_'+tbodyta+'">메뉴사용</label>\n <input type="checkbox" name="shop_main_menu_chk['+tbody+']" id="shop_main_menu_'+tbodyta+'_chk" value="1">';
  newCell5.innerHTML = '<input type="hidden" name="mshop_main_menu['+tbody+']" id="mshop_main_menu_'+tbodyta+'" value="">\n <label for="mshop_main_menu_'+tbodyta+'">모바일메뉴사용</label>\n <input type="checkbox" name="mshop_main_menu_chk['+tbody+']" id="mshop_main_menu_'+tbodyta+'_chk" value="1">';
  newCell6.innerHTML = '<input type="hidden" name="use_shop_main['+tbody+']" id="use_shop_main_'+tbodyta+'" value="">\n <label for="use_shop_main_'+tbodyta+'">메인노출</label>\n <input type="checkbox" name="use_shop_main_chk['+tbody+']" id="use_shop_main_'+tbodyta+'_chk" value="1">';
  newCell7.innerHTML = '<input type="hidden" name="use_mshop_main['+tbody+']" id="use_mshop_main_'+tbodyta+'" value="">\n <label for="use_mshop_main_'+tbodyta+'">모바일노출</label>\n <input type="checkbox" name="use_mshop_main_chk['+tbody+']" id="use_mshop_main_'+tbodyta+'_chk" value="1">';
}
function deleteRow(rownum) {
  // table element 찾기
  const table = document.getElementById('table_sh');
  
  // 행(Row) 삭제
  const newRow = table.deleteRow(rownum);
}
</script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>
        <script>
            (function($){
                $(document).on('ready', function(){
                    $('#shop_dp_label_controller').find('input[type=checkbox]').on('click', function(){
                        var strId = $(this).attr('id').replace('_chk', '');
                        $('#'+strId).val( $(this).is(':checked') ? 'Y' : 'N' )
                    });

                    $('#fmainform').on('submit', function(e){
                        e.preventDefault();
                        var data = $(this).serializeJSON();
                        $.ajax({
                            url : $(this).attr('action'),
                            data: data,
                            type: 'POST',
                            dataType:'json',
                            success: function(data){
                                if( data.result == 'success' ) {
                                    alert('저장되었습니다.');
                                } else {
                                    alert(data.message);
                                }
                            }
                        });
                    });

                });
            }(jQuery));
        </script>
<?php
    }

    public static function dpLabelUpdate(array $param)
    {
        $shop_id  = $param['shop_id'];

        sql_query("DELETE FROM shop_dp_label where shop_id = '{$shop_id}'");

        for($i=0; $i<count($param['type_no']); $i++) {
            if(!trim($param['type_no'][$i]))
                continue;

            if( !empty($param['type_label'][$i]) ) {
                insert('shop_dp_label', array(
                    'shop_id'=>$shop_id,
                    'type_no'=>$param['type_no'][$i],
                    'type_label'=>$param['type_label'][$i],
                    'use_yn'=>$param['use_yn'][$i],
                    'use_shop_main'=>$param['use_shop_main'][$i],
                    'use_mshop_main'=>$param['use_mshop_main'][$i],
                    'shop_main_menu'=>$param['shop_main_menu'][$i],
                    'mshop_main_menu'=>$param['mshop_main_menu'][$i]
                ));
            }

        }
    }
}
