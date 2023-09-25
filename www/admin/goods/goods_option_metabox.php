
<section id="anc_sitfrm_option">
    <h2>옵션정보</h2>
    <?php echo $pg_anchor; ?>
    <div class="tbl_frm02">
        <table>
            <colgroup>
                <col class="w180">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <?php
                $opt_subject = explode(',', $gs['opt_subject']);
                ?>
            <tr>
                <th scope="row">상품 주문옵션</th>
                <td>
                    <p>
                        <span class="mart7 fl">옵션항목은 콤마 ( , ) 로 구분하여 여러개를 입력할 수 있습니다. 예시) 빨강, 노랑, 파랑 / 옵션1 등록시 기입한 텍스트 순서대로 옵션2가 적용됩니다. 예시) 블랙O(중복), 블랙펄O(중복), 블루블랙X(아님)  (텍스트중복시 등록순)</span>
                        <button type="button" id="add_option_row" class="btn_small blue marb5 fr">옵션추가</button>
                    </p>
                    <style>
                        .holder--option>tr:first-of-type>*:last-child>button { visibility: hidden; }
                    </style>
                    <table class="mart7">
                        <colgroup>
                            <col width="60px">
                            <col width="150px">
                            <col width="85px">
                            <col>
                            <col width="76px">
                        </colgroup>
                        <tbody class="holder--option" data-min-row="1" data-max-row="10">
                        <?php foreach($opt_subject as $i=>$opt) : ?>
                            <tr data-for-option="<?php echo $i+1; ?>">
                                <th scope="row">옵션<?php echo $i+1; ?></th>
                                <td><input type="text" name="opt<?php echo $i+1; ?>_subject" value="<?php echo $opt; ?>" id="opt<?php echo $i+1; ?>_subject" class="frm_input wfull"></td>
                                <th scope="row">옵션<?php echo $i+1; ?> 항목</th>
                                <td><input type="text" name="opt<?php echo $i+1; ?>" id="opt<?php echo $i+1; ?>" value="" class="frm_input wfull"></td>
                                <td><button type="button" class="btn_small bx-white"><i class="fa fa-close"> 삭제</i></button></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="btn_confirm02"><button type="button" id="option_table_create" class="btn_lsmall red">옵션목록생성</button></p>
                    <div id="sit_option_frm">
                        <?php include_once(MS_ADMIN_PATH.'/goods/goods_option.php'); ?>
                    </div>
                    <script>
                        (function($){

                            $(document).ready(function(){
                                var $holder_option = $('.holder--option');
                                $holder_option.find('button').on('click', function(){
                                    if( $holder_option.data('minRow') == $holder_option.find('tr').size() ) {
                                        alert($holder_option.data('minRow')+ ' 이상 필요 합니다.');
                                        return;
                                    }
                                    $(this).closest('tr').remove();
                                    init_holder_option_index();
                                });
                                $('#add_option_row').on('click', function(){

                                    if( $holder_option.data('maxRow') == $holder_option.find('tr').size() ) {
                                        alert($holder_option.data('maxRow')+ ' 개 까지 만드실 수 있습니다.');
                                        return;
                                    }

                                    var $cloned = $holder_option.find('tr:nth-of-type(1)').clone(true);
                                    $cloned.find('input').val('');
                                    $holder_option.append( $cloned );
                                    init_holder_option_index();
                                });
                                var init_holder_option_index = function(){
                                    var $trs = $holder_option.find('>tr');

                                    for(var i = 1; i <= $trs.size(); i++){
                                        var $tr = $($trs.get(i-1));
                                        $tr.attr('data-for-option', i);
                                        $tr.find('input').each(function(){
                                            $(this).attr('id', $(this).attr('id').replace(/opt(\d+)/g, 'opt'+i));
                                            $(this).attr('name', $(this).attr('name').replace(/opt(\d+)/g, 'opt'+i));
                                        });
                                        $tr.find('th').each(function(){
                                            $(this).text($(this).text().replace(/옵션(\d+)/g, '옵션'+i));
                                        });
                                    }
                                };

                                <?php if($gs['index_no'] && $po_run) { ?>
                                //옵션항목설정
                                var arr_opt1 = [];
                                var arr_opt2 = [];
                                var arr_opt3 = [];
                                var opt1 = opt2 = opt3 = '';
                                var opt_val;
                                var opt     = [];
                                var arr_opt = [];

                                $(".opt-cell").each(function() {
                                    opt_val = $(this).text().split(" > ");
                                    for(var i = 0, imax = opt_val.length; i < imax ; i++ ) {
                                        opt[i] = $.trim(opt_val[i]);
                                        if( ! arr_opt[i] ) arr_opt[i] = [];
                                        if( opt[i] && $.inArray(opt[i], arr_opt[i]) == -1 ){
                                            arr_opt[i].push(opt[i]);
                                        }
                                    }

                                    opt1 = $.trim(opt_val[0]);
                                    opt2 = $.trim(opt_val[1]);
                                    opt3 = $.trim(opt_val[2]);

                                    if(opt1 && $.inArray(opt1, arr_opt1) == -1)
                                        arr_opt1.push(opt1);

                                    if(opt2 && $.inArray(opt2, arr_opt2) == -1)
                                        arr_opt2.push(opt2);

                                    if(opt3 && $.inArray(opt3, arr_opt3) == -1)
                                        arr_opt3.push(opt3);
                                });

                                for( var i = 0, imax = arr_opt.length; i < imax ; i++ ) {
                                    $('input[name=opt'+(i+1)+']').val( arr_opt[i].join())
                                }

                                console.log( arr_opt );
                                console.log( opt );

                                $("input[name=opt1]").val(arr_opt1.join());
                                $("input[name=opt2]").val(arr_opt2.join());
                                $("input[name=opt3]").val(arr_opt3.join());
                                <?php } ?>

                                // 옵션목록생성
                                $("#option_table_create").click(function() {
                                    var gs_id = $.trim($("input[name=gs_id]").val());
                                    var opt1_subject = $.trim($("#opt1_subject").val());
                                    var opt1 = $.trim($("#opt1").val());
                                    var $option_table = $("#sit_option_frm");

                                    var param = { gs_id: gs_id, w: "<?php echo $w; ?>"
                                        , opt1_subject: opt1_subject
                                        , opt1: opt1
                                    };

                                    if(!opt1_subject || !opt1) {
                                        alert("옵션명과 옵션항목을 입력해 주십시오.");
                                        return false;
                                    }

                                    var $trs = $holder_option.find('tr');
                                    if( $trs.size() > 1 ) {
                                        for( var i = 1; i < $trs.size(); i++ ){
                                            var $tr = $($trs.get(i));
                                            var opt_no = $tr.attr('data-for-option');
                                            var opt_key= 'opt'+opt_no;
                                            var sub_key= opt_key+'_subject';
                                            if( $.trim($('#'+opt_key).val()) == '' ){
                                                break;
                                            }
                                            param[opt_key] = $.trim($('#'+opt_key).val());
                                            param[sub_key] = $.trim($('#'+sub_key).val());
                                        }
                                    }

                                    $.post(
                                        tb_admin_url+"/goods/goods_option.php",
                                        param,
                                        function(data) {
                                            $option_table.empty().html(data);
                                        }
                                    );
                                });

                                // 모두선택
                                $(document).on("click", "input[name=opt_chk_all]", function() {
                                    if($(this).is(":checked")) {
                                        $("input[name='opt_chk[]']").attr("checked", true);
                                    } else {
                                        $("input[name='opt_chk[]']").attr("checked", false);
                                    }
                                });

                                // 선택삭제
                                $(document).on("click", "#sel_option_delete", function() {
                                    var $el = $("input[name='opt_chk[]']:checked");
                                    if($el.size() < 1) {
                                        alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                                        return false;
                                    }

                                    $el.closest("tr").remove();
                                });

                                // 일괄적용
                                $(document).on("click", "#opt_value_apply", function() {
                                    if($(".opt_com_chk:checked").size() < 1) {
                                        alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                                        return false;
                                    }

                                    var opt_supply_price = $.trim($("#opt_com_supply_price").val());
                                    var opt_price = $.trim($("#opt_com_price").val());
                                    var opt_stock = $.trim($("#opt_com_stock").val());
                                    var opt_noti = $.trim($("#opt_com_noti").val());
                                    var opt_use = $("#opt_com_use").val();
                                    var $el = $("input[name='opt_chk[]']:checked");

                                    // 체크된 옵션이 있으면 체크된 것만 적용
                                    if($el.size() > 0) {
                                        var $tr;
                                        $el.each(function() {
                                            $tr = $(this).closest("tr");

                                            if($("#opt_com_price_chk").is(":checked"))
                                                $tr.find("input[name='opt_price[]']").val(opt_price);

                                            if($("#opt_com_supply_price_chk").is(":checked"))
                                                $tr.find("input[name='opt_supply_price[]']").val(opt_supply_price);

                                            if($("#opt_com_stock_chk").is(":checked"))
                                                $tr.find("input[name='opt_stock_qty[]']").val(opt_stock);

                                            if($("#opt_com_noti_chk").is(":checked"))
                                                $tr.find("input[name='opt_noti_qty[]']").val(opt_noti);

                                            if($("#opt_com_use_chk").is(":checked"))
                                                $tr.find("select[name='opt_use[]']").val(opt_use);
                                        });
                                    } else {
                                        if($("#opt_com_price_chk").is(":checked"))
                                            $("input[name='opt_price[]']").val(opt_price);

                                        if($("#opt_com_supply_price_chk").is(":checked"))
                                            $("input[name='opt_supply_price[]']").val(opt_supply_price);

                                        if($("#opt_com_stock_chk").is(":checked"))
                                            $("input[name='opt_stock_qty[]']").val(opt_stock);

                                        if($("#opt_com_noti_chk").is(":checked"))
                                            $("input[name='opt_noti_qty[]']").val(opt_noti);

                                        if($("#opt_com_use_chk").is(":checked"))
                                            $("select[name='opt_use[]']").val(opt_use);
                                    }
                                });


                            });

                        }(jQuery));
                    </script>
                </td>
            </tr>
            <?php
            $spl_subject = explode(',', $gs['spl_subject']);
            $spl_count = count($spl_subject);
            ?>
            <tr>
                <th scope="row">상품 추가옵션</th>
                <td>
                    <p>
                        <span class="mart7 fl">옵션항목은 콤마 ( , ) 로 구분하여 여러개를 입력할 수 있습니다. 예시) 빨강, 노랑, 파랑</span>
                        <button type="button" id="add_supply_row" class="btn_small blue marb5 fr">옵션추가</button>
                    </p>
                    <div id="sit_supply_frm">
                        <table>
                            <colgroup>
                                <col width="60px">
                                <col width="150px">
                                <col width="85px">
                                <col>
                                <col width="76px">
                            </colgroup>
                            <?php
                            $i = 0;
                            do {
                                $seq = $i + 1;
                                ?>
                                <tr>
                                    <th scope="row">추가<?php echo $seq; ?></th>
                                    <td><input type="text" name="spl_subject[]" value="<?php echo $spl_subject[$i]; ?>" id="spl_subject_<?php echo $seq; ?>" class="frm_input wfull"></td>
                                    <th scope="row">추가<?php echo $seq; ?> 항목</th>
                                    <td><input type="text" name="spl[]" id="spl_item_<?php echo $seq; ?>" value="" class="frm_input wfull"></td>
                                    <td class="tac">
                                        <?php
                                        if($i > 0)
                                            echo '<button type="button" id="del_supply_row" class="btn_small bx-white"><i class="fa fa-close"></i> 삭제</button>';
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            } while($i < $spl_count);
                            ?>
                        </table>
                        <p class="mart5 tac"><button type="button" id="supply_table_create" class="btn_lsmall red">옵션목록생성</button></p>
                    </div>
                    <div id="sit_option_addfrm">
                        <?php include_once(MS_ADMIN_PATH.'/goods/goods_spl.php'); ?>
                    </div>

                    <script>
                        $(function() {
                            <?php if($gs['index_no'] && $ps_run) { ?>
                            // 추가옵션의 항목 설정
                            var arr_subj = new Array();
                            var subj, spl;

                            $("input[name='spl_subject[]']").each(function() {
                                subj = $.trim($(this).val());
                                if(subj && $.inArray(subj, arr_subj) == -1)
                                    arr_subj.push(subj);
                            });

                            for(i=0; i<arr_subj.length; i++) {
                                var arr_spl = new Array();
                                $(".spl-subject-cell").each(function(index) {
                                    subj = $.trim($(this).text());
                                    if(subj == arr_subj[i]) {
                                        spl = $.trim($(".spl-cell:eq("+index+")").text());
                                        arr_spl.push(spl);
                                    }
                                });

                                $("input[name='spl[]']:eq("+i+")").val(arr_spl.join());
                            }
                            <?php } ?>
                            // 입력필드추가
                            $("#add_supply_row").click(function() {
                                var $el = $("#sit_supply_frm tr:last");
                                var fld = "<tr>\n";
                                fld += "<th scope=\"row\"><label for=\"\">추가</label></th>\n";
                                fld += "<td><input type=\"text\" name=\"spl_subject[]\" value=\"\" class=\"frm_input wfull\"></td>\n";
                                fld += "<th scope=\"row\" class=\"ssupply_type\"><label for=\"\">추가 항목</label></th>\n";
                                fld += "<td><input type=\"text\" name=\"spl[]\" value=\"\" class=\"frm_input wfull\"></td>\n";
                                fld += "<td class=\"tac\"><button type=\"button\" id=\"del_supply_row\" class=\"btn_small bx-white\"><i class=\"fa fa-close\"></i> 삭제</button></td>\n";
                                fld += "</tr>";

                                $el.after(fld);

                                supply_sequence();
                            });

                            // 입력필드삭제
                            $(document).on("click", "#del_supply_row", function() {
                                $(this).closest("tr").remove();

                                supply_sequence();
                            });

                            // 옵션목록생성
                            $("#supply_table_create").click(function() {
                                var gs_id = $.trim($("input[name=gs_id]").val());
                                var subject = new Array();
                                var supply = new Array();
                                var subj, spl;
                                var count = 0;
                                var $el_subj = $("input[name='spl_subject[]']");
                                var $el_spl = $("input[name='spl[]']");
                                var $supply_table = $("#sit_option_addfrm");

                                $el_subj.each(function(index) {
                                    subj = $.trim($(this).val());
                                    spl = $.trim($el_spl.eq(index).val());

                                    if(subj && spl) {
                                        subject.push(subj);
                                        supply.push(spl);
                                        count++;
                                    }
                                });

                                if(!count) {
                                    alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
                                    return false;
                                }

                                $.post(
                                    tb_admin_url+"/goods/goods_spl.php",
                                    { gs_id: gs_id, w: "<?php echo $w; ?>", 'subject[]': subject, 'supply[]': supply },
                                    function(data) {
                                        $supply_table.empty().html(data);
                                    }
                                );
                            });

                            // 모두선택
                            $(document).on("click", "input[name=spl_chk_all]", function() {
                                if($(this).is(":checked")) {
                                    $("input[name='spl_chk[]']").attr("checked", true);
                                } else {
                                    $("input[name='spl_chk[]']").attr("checked", false);
                                }
                            });

                            // 선택삭제
                            $(document).on("click", "#sel_supply_delete", function() {
                                var $el = $("input[name='spl_chk[]']:checked");
                                if($el.size() < 1) {
                                    alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                                    return false;
                                }

                                $el.closest("tr").remove();
                            });

                            // 일괄적용
                            $(document).on("click", "#spl_value_apply", function() {
                                if($(".spl_com_chk:checked").size() < 1) {
                                    alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                                    return false;
                                }

                                var spl_supply_price = $.trim($("#spl_com_supply_price").val());
                                var spl_price = $.trim($("#spl_com_price").val());
                                var spl_stock = $.trim($("#spl_com_stock").val());
                                var spl_noti = $.trim($("#spl_com_noti").val());
                                var spl_use = $("#spl_com_use").val();
                                var $el = $("input[name='spl_chk[]']:checked");

                                // 체크된 옵션이 있으면 체크된 것만 적용
                                if($el.size() > 0) {
                                    var $tr;
                                    $el.each(function() {
                                        $tr = $(this).closest("tr");

                                        if($("#spl_com_price_chk").is(":checked"))
                                            $tr.find("input[name='spl_price[]']").val(spl_price);
                                            
                                        if($("#spl_com_supply_price_chk").is(":checked"))
                                            $tr.find("input[name='spl_supply_price[]']").val(spl_supply_price);

                                        if($("#spl_com_stock_chk").is(":checked"))
                                            $tr.find("input[name='spl_stock_qty[]']").val(spl_stock);

                                        if($("#spl_com_noti_chk").is(":checked"))
                                            $tr.find("input[name='spl_noti_qty[]']").val(spl_noti);

                                        if($("#spl_com_use_chk").is(":checked"))
                                            $tr.find("select[name='spl_use[]']").val(spl_use);
                                    });
                                } else {
                                    if($("#spl_com_price_chk").is(":checked"))
                                        $("input[name='spl_price[]']").val(spl_price);

                                    if($("#spl_com_supply_price_chk").is(":checked"))
                                        $("input[name='spl_supply_price[]']").val(spl_supply_price);

                                    if($("#spl_com_stock_chk").is(":checked"))
                                        $("input[name='spl_stock_qty[]']").val(spl_stock);

                                    if($("#spl_com_noti_chk").is(":checked"))
                                        $("input[name='spl_noti_qty[]']").val(spl_noti);

                                    if($("#spl_com_use_chk").is(":checked"))
                                        $("select[name='spl_use[]']").val(spl_use);
                                }
                            });
                        });

                        function supply_sequence()
                        {
                            var $tr = $("#sit_supply_frm tr");
                            var seq;
                            var th_label, td_label;

                            $tr.each(function(index) {
                                seq = index + 1;
                                $(this).find("th label").attr("for", "spl_subject_"+seq).text("추가"+seq);
                                $(this).find("td input").attr("id", "spl_subject_"+seq);
                                $(this).find("th.ssupply_type label").attr("for", "spl_item_"+seq);
                                $(this).find("th.ssupply_type label").text("추가"+seq+" 항목");
                                $(this).find("td input").attr("id", "spl_item_"+seq);
                            });
                        }
                    </script>
                </td>
            </tr>
            <tr>
                <th scope="row">상품 색상<br><span class="fc_137">(리스트에 보여질 색상)</span></th>
                <td>
                    <div class="local_desc03">
                        <?php
                        $sql = " select * from shop_goods_color ";
                        $res = sql_query($sql);
                        for($i=0; $row=sql_fetch_array($res); $i++) {
                            $arr = explode(",", $gs['info_color']);
                            if(in_array($row['gd_color'], $arr))
                                $checked = ' checked="checked"';
                            else
                                $checked = '';
                            ?>
                            <div class="dib padl10 padr10">
                                <label><input type="checkbox" name="info_color[]" value="<?php echo $row['gd_color']; ?>"<?php echo $checked; ?>><div class="dib vam" style="width:21px;height:21px;background-color:<?php echo $row['gd_color']; ?>;border:1px solid #efefef;"></div></label>
                            </div>
                            <?php
                        }
                        if($i==0) echo '<p class="empty_list">등록 된 색상이 없습니다.</p>';
                        ?>
                    </div>
                    <p class="mart5 tac"><a href="<?php echo MS_ADMIN_URL; ?>/goods/goods_color.php" onclick="win_open(this,'pop_color','500','640','yes');return false;" class="btn_lsmall red itemicon">상품색상관리</a></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</section>
