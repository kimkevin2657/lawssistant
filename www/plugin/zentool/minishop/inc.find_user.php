<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-24
 * Time: 20:19
 */
?>
<style>

    input[type=radio].css-checkbox {display:none;}
    input[type=radio].css-checkbox + label.css-label {padding-left:23px;height:20px;display:inline-block;line-height:20px;background-repeat:no-repeat;background-position:0 0;font-size:12px;vertical-align:middle;cursor:pointer;}
    input[type=radio].css-checkbox:checked + label.css-label {background-position:0 -20px;}
    input[type=checkbox].css-checkbox {display:none;}
    input[type=checkbox].css-checkbox + label.css-label {padding-left:23px;height:20px;display:inline-block;line-height:20px;background-repeat:no-repeat;background-position:0 0;font-size:12px;vertical-align:middle;cursor:pointer;}
    input[type=checkbox].css-checkbox:checked + label.css-label {background-position:0 -20px;}
    label.css-label {
        background-image:url(/m/img/csscheckbox.png);
        -webkit-touch-callout:none;
        -webkit-user-select:none;
        -khtml-user-select:none;
        -moz-user-select:none;
        -ms-user-select:none;
        user-select:none;
    }

    table .spr,dd .spr,.total_price .spr {display:inline-block;}
    .helper {
        padding: 10px 5px 0px;
        color: #369;
    }
    h1.newp_tit {     font-size: 1.5em;
        text-align: center;
        margin: .5em 0 1em;
        padding: 0.5em;
        border-radius: 0.25em; }
    .new_win_body {padding: 10px;
        background: #efefef;
        border-radius: 0.25em;}
    .guidebox label { font-weight: bold;}
    .tbl_head01 { margin-top:20px; border-radius:0.25em; border:none;}
    .tbl_head01 table { width: 100%; border-top:1px solid #666; margin-top:10px; }
    .tbl_head01 table th,
    .tbl_head01 table td { padding: 10px 2px;}
    .tbl_head01 table tbody tr:nth-of-type(even) td {background: #efefef; }
    .holder--selector,
    .holder--col-no { color: #369; }
    .holder--selector.disabled,
    .holder--col-no.disabled { color: #f00; }
    .css-checkbox:disabled+.css-label{ color:#f00;}

    .btn_group li { float:left; margin-right:10px; padding:10px 0;}

    .price-info { position: absolute; left: 84px; margin-top:25px; font-weight:bold; color:#ff383e;}
    .btn_detail_view { position: absolute; left: 164px; margin-top:25px;}


    .overlay { position: fixed; top:0; left:0; z-index: 9998; width:100%; height: 100%; background-color: rgba(30, 30, 30, 0.7);}

    .overlay-closer { position: fixed; top: 10px; left:50%; margin-left: 492px; z-index: 9999; font-size:2.25em; color:#fff;}
    .overlay-closer.thin { margin-left: 312px;}
    .overlay-content {position: fixed; top: 40px; left:50%; width: 1000px; height: auto; margin: 0 0 0 -500px; z-index:9999; overflow: auto; background: #fff; padding:10px;}
    .overlay-content.thin {width: 640px; margin-left:-320px}
    body.lock { overflow: hidden; }


    body.mobile .overlay { position: fixed; top:0; left:0; z-index: 9990; width:100%; height: 100%; background-color: rgba(30, 30, 30, 0.7);}
    body.mobile .overlay-content.thin,
    body.mobile .overlay-content {position: fixed; top: 40px; left:0; width: 100%; height:100%; z-index:9991; overflow: auto; background: #fff; padding:10px; margin:0;}
    body.mobile .overlay-closer.thin,
    body.mobile .overlay-closer  {position: fixed; top: 6px; right:6px; left:auto; z-index: 9994; font-size:24px; color: #1e1e1e;}
    body.mobile .overlay::before { content:attr(data-content); position: fixed; top:0; left:0; width:100%; background: #fff; height:40px; z-index:9993; box-sizing: border-box; border: 1px solid rgba(152, 137, 154, 0.37); border-left-width:0; border-right-width:0;overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding:10px 40px 10px 10px; font-size:16px; font-weight: bold; }
    body.lock { overflow: hidden; }
    body.mobile .sp_wrap .sp_sub_wrap { border-top: none;}
    /*
    body.lock #wrapper #container,
    body.lock #wrapper { overflow: hidden; max-width:100%; max-height: 100%;}
    */
    .for_gb_no dd { display: none; }
    .for_gb_no.selected dd { display: block; }

</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>
<script type="text/template" id="tmpl" class="dpn">
    <table>
        <colgroup>
            <col class="w50">
            <col class="w120">
            <col>
            <col class="w120">
            <col class="w120">
        </colgroup>
        <thead>
        <tr>
            <th scope="col">번호</th>
            <th scope="col">아이디</th>
            <th scope="col">회원명</th>
            <th scope="col">가입일</th>
            <th scope="col">선택</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot class="dpn">
        <tr>
            <td class="tac">{$num}</td>
            <td class="tal">{$id}</td>
            <td class="tal">{$name}</td>
            <td class="tac">{$reg_time}</td>
            <td class="tac"><button type="button" class="btn_small grey holder--selector">선택</button></td>
        </tr>
        </tfoot>
    </table>
</script>
<script>

    (function($){
        $(document).on('ready',function(){
            var $findUserButtons = $('button.holder--find-user');
            var activeFindUserType = 'pt';
            var $body            = $('body');

            var findUserSuccess = function(user){

                $('#'+activeFindUserType+'_id').val(user.id);
                $('#'+activeFindUserType+'_nm').val(user.plain_name);
                $('#'+activeFindUserType+'_grade').val(user.grade);
                $('#'+activeFindUserType+'_grade_name').val(user.grade_name);

                $('#pt_id,#up_id').trigger('blur');
            };

            $findUserButtons.on('click', function(){
                activeFindUserType = $(this).data('for');

                var $overlay   = $('<div class="overlay"></div>');
                var $content   = $('<div class="overlay-content thin"></div>');
                var $closer    = $('<button type="button" class="overlay-closer thin fa fa-window-close"></button>');

                $overlay.attr('data-content', activeFindUserType == 'pt' ? '추천인찾기' : '추천인찾기');

                $body.append( $overlay );

                var $resultTable = $('<div class="tbl_head01"/>').append($('#tmpl').html());
                var $resultBody  = $resultTable.find('tbody');
                var $resultTmpl  = $resultTable.find('tfoot');

                $resultTmpl.find('.holder--col-no').remove();

                var $header = $('<h1 class="newp_tit" />').text( $overlay.data('content') );
                $content.append($header);
                var $search = $('<form autocomplete="off" />');
                $search.append($('<label for="sfx">회원명 : </label>'));
                $search.append($('<input type="text" name="stx" id="stx" class="frm_input w100 marr10">'));
                $search.append($('<label for="sfl">아이디 : </label>'));
                $search.append($('<input type="text" name="sfl" id="sfl" class="frm_input w100 marr10">'));
                $search.append($('<input type="submit" value="검색" class="btn_small">'));
                $search.append($('<div class="helper"><i class="fa fa-info-circle"></i> 아이디는 전체를 입력후 검색하세요.</div>'));

                $search.on('submit', function(e){
                    e.preventDefault();
                    if( $(this).find('[name=sfl]').val() == '' && $(this).find('[name=stx]').val() == '' )
                    {
                        alert('검색어를 입력하세요.');
                        $(this).find('[name=stx]').focus();
                        return;
                    }
                    if( ($(this).find('[name=sfl]').val()+$(this).find('[name=stx]').val()).length < 2 ){
                        alert('검색어를 두자리 이상 입력하세요');
                        return;
                    }

                    $.ajax({
                        url : '/plugin/zentool/minishop/ajax.find_user.php',
                        data: $(this).serializeJSON(),
                        type: 'POST',
                        dataType:'json',
                        success:function(data){
                            if( data && data.length > 0 ){
                                $resultBody.empty();
                                for( var i = 0; i < data.length ; i++ ) {
                                    var tmpl = $resultTmpl.html();
                                    var user  = data[i];

                                    tmpl = tmpl.replace(/\{\$num\}/g, i+1);
                                    tmpl = tmpl.replace(/\{\$id\}/g, user.id);
                                    tmpl = tmpl.replace(/\{\$name\}/g, user.name);
                                    tmpl = tmpl.replace(/\{\$reg_time\}/g, user.reg_time);

                                    var $tmpl = $(tmpl);

                                    $tmpl.data('user', user);

                                    $resultBody.append( $tmpl );
                                }

                                $resultBody.find('.holder--selector').not(':disabled').on('click', function(){
                                    var $tr = $(this).closest('tr');
                                    var user= $tr.data('user');

                                    findUserSuccess(user);

                                    $closer.trigger('click');

                                });

                            } else {
                                alert('검색된 회원이 없습니다.');
                            }
                        }
                    });
                });

                $content.append( $('<div class="new_win_body"/>').append( $('<div class="guidebox tac"/>').append($search) ) );

                $content.append( $resultTable )

                $body.append( $closer ).append( $content ).addClass('lock');

                if( $body.is('.mobile') ) {
                    $content.width( $overlay.width() - 20 );
                } else {
                    $content.height( $overlay.height() - 90);
                }

                $closer.on('click', function(){
                    $overlay.remove();
                    $content.remove();
                    $closer.remove();
                    $('body').removeClass('lock');
                });
            });

        });
    }(jQuery));
</script>
