<?php
if(!defined('_MALLSET_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_minishop_center a left join shop_member b on a.pc_cc_no = b.index_no left join (select pc_no, count(1) mb_cnt from shop_member c group by pc_no ) c on a.pc_no = c.pc_no ";
$sql_search = " where 1 ";

if($sfl && $stx) {
    if( $sfl == 'id') $sql_search .= " and $sfl like '%".$stx."%' ";
    else $sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
    $sql_search .= " and grade = '$sst' ";

if(!$orderby) {
    $filed = "a.pc_nm";
    $sod = "asc";
} else {
    $sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*, b.name pc_cc_nm, b.id pc_cc_id  $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">검색어</th>
                <td>
                    <select name="sfl">
                        <?php echo option_selected('a.pc_nm', $sfl, '지점명'); ?>
                        <?php echo option_selected('b.name', $sfl, '지점장명'); ?>
                    </select>
                    <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
                    <input type="submit" value="검색" class="btn_lsmall">
                    <input type="button" value="초기화" id="frmRest" class="btn_lsmall grey">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</form>

<div class="local_frm01"></div>
<div class="holder--form-center">
    <form name="frm_center" id="frm_center" action="./minishop/mini_pcenterupdate.php">
        <div class="tbl_frm01">
            <table>
                <colgroup>
                    <col class="w100">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th><label for="pc_nm">지점명</label></th>
                    <td><input type="hidden" name="pc_no"><input type="text" class="frm_input required" id="pc_nm" name="pc_nm" required="required"></td>
                </tr>
                <tr >
                    <th><label for="pc_cc_no">지점장</label></th>
                    <td>
                        <input type="hidden" name="pc_cc_no" id="pc_cc_no">
                        <input type="text" readonly placeholder="이름" class="frm_input" id="pc_cc_nm" name="pc_cc_nm">
                        <input type="text" readonly placeholder="ID" class="frm_input" required="required" id="pc_cc_id" name="pc_cc_id">
                        <a href="./seller/seller_reglist.php" onclick="win_open(this,'seller_reglist','550','500','1'); return false" class="btn_small grey">선택</a>
                    </td>
                </tr>
                <tr >
                    <th><label for="pc_state">지점운영상태</label></th>
                    <td><select name="pc_state" id="pc_state">
                            <option value="1">운영중</option>
                            <option value="0">운영종료</option>
                        </select></td>
                </tr>
                <tr >
                    <th></th>
                    <td>
                        <button class="btn_lsmall" type="submit">추가</button>
                        <button class="btn_lsmall" type="reset">초기화</button>
                        <button class="btn_lsmall" type="button">취소</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<style>
    .holder--modify { height:165px; display: none;}
    .holder--form-center{ display: none; background: #fff;}
    .holder--form-center.modify{ position:absolute;}
    .holder--btn-modify{}
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>
<script>


    (function($){

        document.setUser = function(user){

            $('#pc_cc_id').val(user.plain_id);
            $('#pc_cc_no').val(user.no);
            $('#pc_cc_nm').val(user.nm);

        };

        $(document).on('ready', function(){
            var $form = $('.holder--form-center');
            var $addButton = $('.button--add-center');
            var $reset = $form.find('button[type=reset]');
            var $close = $form.find('button[type=button]');
            var $modifies= $('.holder--modify');
            $form.find('form').on('submit', function(e){
                e.preventDefault();

                data = $(this).serializeJSON();
                url  = $(this).attr('action');

                if( 1 == $(this).attr('submitted') ) {
                    return;
                }

                $.ajax({
                    url : $(this).attr('action'),
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function(){
                        $(this).attr('submitted', 1);
                    }.bind(this),
                    success: function(data){
                        if( data.result == 'success') {
                            document.location.reload();
                        } else {
                            alert(data.message);
                        }
                    },
                    complete: function(){
                        $(this).attr('submitted', 0);
                    }.bind(this)
                })
            });
            $close.on('click', function(){
                $reset.trigger('click');
                $form.removeClass('modify');
                $form.css({'top' : '0','left':'0','width' : '100%'});
                $form.find('[type=submit]').text('추가');
                $form.find('[type=reset]').show();
                $form.find('[name=pc_no]').val('');
                $modifies.hide();
                $form.hide();
            });
            $addButton.on('click', function(){
                $close.trigger('click');
                $form.show();
            });
            $('.holder--btn-modify').on('click', function(){
                $close.trigger('click');

                var data = $(this).data();

                $form.find('[name=pc_no]').val( data.pc_no);
                $form.find('[name=pc_nm]').val( data.pc_nm);
                $form.find('[name=pc_cc_no]').val( data.pc_cc_no);
                $form.find('[name=pc_cc_id]').val( data.pc_cc_id);
                $form.find('[name=pc_cc_nm]').val( data.pc_cc_nm);
                $form.find('[name=pc_state]').val( data.pc_state );
                $form.find('[type=submit]').text('수정');
                $form.find('[type=reset]').hide();

                $modify = $('#holder--modify-'+data.pc_no);
                $modify.show();

                pos    = $modify.position();
                width  = $modify.width();
                height = $modify.height();

                $form.addClass('modify');
                $form.show();
                $form.css({'top' : pos.top + 'px', 'left' : pos.left +'px', 'width' : width + 'px'});

            });
        });
    }(jQuery))
</script>
<form name="fpcenter" id="fpcenter" method="post" action="./minishop/mini_pcenterupdate.php" onsubmit="return fpcenter_submit(this);">
    <input type="hidden" name="q1" value="<?php echo $q1; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <div class="local_frm01">
        <?php if( false ) : ?>
            선택된 지점를 <input type="submit" name="act_button" value="운영종료" class="btn_small bx-white" onclick="document.pressed=this.value">
            <input type="submit" name="act_button" value="운영중" class="btn_small bx-white" onclick="document.pressed=this.value">
            로(으로) 변경
        <?php endif; ?>
        <button type="button" class="fr btn_lsmall button--add-center"><i class="fa fa-plus"></i> 지점추가</button>
    </div>
    <div class="tbl_head02">
        <table id="minishop_list">
            <colgroup>
                <?php if( false ) : ?>
                    <col class="w50">
                <?php endif; ?>
                <col class="w50">
                <col>
                <col class="w130">
                <col class="w130">
                <col class="w100">
                <col class="w100">
                <col class="w130">
            </colgroup>
            <thead>
            <tr>
                <?php if( false ) : ?>
                    <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
                <?php endif; ?>
                <th scope="col">관리</th>
                <th scope="col"><?php echo subject_sort_link('a.pc_nm',$q2); ?>지점명</a></th>
                <th scope="col"><?php echo subject_sort_link('b.name',$q2); ?>지점장명</a></th>
                <th scope="col"><?php echo subject_sort_link('b.id',$q2); ?>지점장아이디</a></th>
                <th scope="col"><?php echo subject_sort_link('c.mn_cnt',$q2); ?>지점회원수</a></th>
                <th scope="col"><?php echo subject_sort_link('a.pc_state',$q2); ?>지점상태</a></th>
                <th scope="col"><?php echo subject_sort_link('a.reg_at',$q2); ?>등록일시</a></th>
            </tr>
            </thead>
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {

                if($i==0)
                    echo '<tbody class="list">'.PHP_EOL;

                $bg = 'list'.($i%2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <?php if( false ) : ?>
                        <td>
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['id']; ?> 님</label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
                        </td>
                    <?php endif; ?>
                    <td class="tac">
                        <input type="hidden" name="pc_no[<?php echo $i; ?>]" value="<?php echo $row['pc_no']; ?>">
                        <input type="button" id="btn_modify_<?php echo $row['pc_no']?>"
                               data-pc_no="<?php echo $row['pc_no']; ?>"
                               data-pc_nm="<?php echo $row['pc_nm']; ?>"
                               data-pc_cc_no="<?php echo $row['pc_cc_no']; ?>"
                               data-pc_cc_nm="<?php echo $row['pc_cc_nm']; ?>"
                               data-pc_cc_id="<?php echo $row['pc_cc_id']; ?>"
                               data-pc_state="<?php echo $row['pc_state']; ?>"
                               class="btn_lsmall white holder--btn-modify" value="수정">
                    </td>
                    <td class="tal">
                        <?php echo $row['pc_nm']; ?>
                    </td>
                    <td class="tac"><?php echo get_sideview($row['pc_cc_id'], $row['pc_cc_nm']); ?></td>
                    <td class="tac"><?php echo $row['pc_cc_id']; ?></td>
                    <td class="tar"><?php echo number_format($row['mb_cnt']); ?></td>
                    <td class="tac"><?php echo $row['pc_state'] ? '운영중' : '운영종료'; ?></td>
                    <td class="tac"><?php echo $row['reg_at']; ?></td>
                </tr>
                <tr class="holder--modify" id="holder--modify-<?php echo $row['pc_no'] ?>"></tr>
                <?php
            }
            if($i==0)
                echo '<tbody><tr><td colspan="14" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
    </div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>


<script>
    function fpcenter_submit(f)
    {
        if(!is_checked("chk[]")) {
            alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        return true;
    }
</script>
