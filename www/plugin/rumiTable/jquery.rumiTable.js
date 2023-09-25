/**
 * 북모아 동적테이블 플러그인.
 * 제작일 : 2019년 2월 03일
 * 제작자 : 조정영 (루미집사)
 * E-mail : cjy7627@naver.com
 * 동적테이블 만들고, 지우고, 엔터키로 다음 항목으로 이동
 * 
 * 아래의 코드는 맨위에서 로드되어야 합니다.
 * rumiTable.rumiLoad({
 *      autoTabId:"#absc",
 *      selector:".rtable", // 동적테이블을 생성할 <tbody> 의 class 또는 ID : <tbody class='rtable'>
 *      delBtn  :".delButton", // 동적테이블의 Row를 삭제시 사용되는 버튼이 들어갈 td의 class
 *      unique  : ".id_no", // 자료 고유번호 class 
 *      delUrl  : "sample.php", // 자료삭제 파일명 및 경로 (삭제시 파라미터는 POST 전송, 'oper' and 'id' )
 *      autoTabclass : "input.insTb" // 엔터키 자동이동 가능한 Class
 * });
 * 
 * 테이블추가 : n개만큼 추가한다.
 * rumiTable.add_item(n);
 * 
 * 테이블삭제 : n번째 row를 삭제한다.
 * rumiTable.delRow(n);
 * 
 * @param { string } autoTabId 엔터키 입력시 커서(focus) 자동 이동 적용될 영역의 className 또는 IdNmae
 * @param { string } selector 동적테이블의 <tbody>의 className 또는 IdNmae
 * @param { string } delBtn 테이블 ROW 삭제버튼의 ClassName
 * @param { string } unique 자료의 고유번호 선택자(id_no, wr_id, no, ids... 등등..)
 * @param { string } delUrl 자료삭제 URL (행 자료삭제)
 * @param { string } autoTabclass 엔터키입력시 자동으로 포커스 이동 가능 ClassName
 * @param { Object } dbData (배열값) DB 데이타 배열.
 * @param { Array } items (배열값) 동적테이블을 구성하고 있는 요소들의 정보. 
 * @param { function } delSucess 행 삭제시 추가로 실행될 사용자 함수
 */
var rumiTable = (function(option) {
    'use static';
    var arr = {
        autoTabId : "#bm_price",
        selector : ".work_item",
        delBtn  : ".delItemBtn",
        unique  : ".id_no",
        delUrl  : "sample.php",
        autoTabclass : "input.insTb",
        dbData : [],
        items : [],
        delSuccess : function(){}
    }

    var _this = this;
    var tId, delBtn, unique, delUrl;
    var fields = {};
    var numberClass = ""; // 숫자에 콤마를 적용할 ClassNames
    var rumiLoad = function(option) {

        if(option && typeof option == "object") {
            arr = $.extend(arr, option);
        };

        tId    = arr.selector;
        delBtn = arr.delBtn;
        unique = arr.unique;
        delUrl = arr.delUrl;
        autoTabclass = arr.autoTabclass;
        autoTabId = arr.autoTabId;
        items = arr.items;

        // items에서 selector 추출
        numberClass = "";
        for(i = 0; i < items.length; i++) {
            if(items[i].number === true) {
                numberClass += ", ."+items[i].selector;
            }
        }
        numberClass = numberClass.substr(2);


        if(arr.dbData.length > 0) {
		
            // 자료의 갯수에 3개를 더 추가로 불러온다.
            //this.add_item((arr.dbData.length + 3));
            this.add_item((arr.dbData.length));
            $.each(arr.dbData, function(index, data) {
                
                for (var key in data) {
                    var type = items.find(c => c.selector == key );
                    if(type || typeof type !== 'undefined') { 
                        switch(type.type) {
                            case "text" :
                            case "textarea" :
                            case "select" :
                                $("."+key).eq(index).attr("value", data[key]);
                                break;
                            case "radio" : // 보류중
                                //$('.'+key+':input[value="'+data[key]+'"]').attr("checked", true);
                                break;
                            case "checkbox" : // 보류중
                                break;
                        }
                    }
                }

                $(tId+" > tr:eq("+index+") > td:eq(0)").text((index+1));
                $(delBtn).eq(index).attr("onclick","rumiTable.delRow("+index+")").html("<i class='fa fa-trash-o' aria-hidden='true'></i>");
            });

        } else {
            // 데이타가 없으면 입력칸을 추가로 10개 로드한다.
            this.add_item(10);
        }

        // 엔터키 입력시 다음칸으로 자동이동 (페이지 로딩시 최초1회만 실행.)
        auto_cursor();

        // input 클릭시 내용을 셀렉트한다.     
        $(tId).on("click", autoTabclass, function(e) {
            $(this).select().focus();
        });       

    };


    var add_item = function(rows) {

       	//마지막번호 구하기 (마지막줄 td의 텍스트)
        var idx = parseInt($(tId+" > tr:last > td:eq(0)").text());
        if(!idx) {
            idx=0;
            var del = "Y"; // 자료가 없을 경우 마지막 줄을 삭제하기 위함.
        }
        
        for(i=0; i < rows; i++) {
		    $(tId).append("<tr>" + $(tId+" > tr:last").html() + "</tr>"); //마지막 tr 을 복사하여 마지막에 추가
            $(tId+" > tr:last input, "+tId+" > tr:last select").not("input[type=checkbox]").val(""); // 추가한 줄의 input, select 초기화
		    $(tId+" > tr:eq("+idx+") > td:eq(0)").text((idx + 1)); // 줄번호 추가.
            $(delBtn).eq(idx).attr("onclick","rumiTable.delRow("+idx+")").html("<i class='fa fa-trash-o' aria-hidden='true'></i>");            
            idx++;
        };
        
        if(del=="Y") {
           $(tId+" > tr:last").remove();  // 자료불러올때 마지막 추가로 복사된줄 삭제. 
        };

        // 엔터키로 이동해야되는 요소 배열 생성.(input, select...)
        enterTab();

        if(numberClass) {
            $(numberClass).number(true, 0);
        }

    };

    this.rowDelete = function(ids) {
        var cnt;
        //console.log(ids, delUrl);
        $.ajax({
            type : 'POST',
            url : "https://blingbeauty.shop/bbs/skin/basic_doc/popup.approval_list.php?bo_table=approval",
            data : {
                "oper":"del",
                "id":ids
            },
            dataType : "json",
            async : false,
            cache : false,
            error : function(error) {
            },
            success : function(data) {
               cnt = data;
            },
            complete : function() {
            }
        });
        return cnt;
    };

    var delRow = function(idx) {

        var totalRows = $(tId+" > tr").length;   
        if(totalRows==1) {
            if(!confirm("마지막 자료까지 삭제하면 페이지가 새로고침됩니다.\n삭제 하시겠습니까?")) {
                return false; // 자료삭제 취소.
            };
        };

        // 고유번호가 있으면 자료도 삭제한다.
        var ids = $(unique).eq(idx).val();
        if(ids) {
            if(!confirm("한번 삭제한 자료는 복구 불가합니다.\n정말로 삭제 하시겠습니까?")) {
                return false; // 자료삭제 취소.
            };

            //alert(ids);
            var del_cnt = rowDelete(ids);  // 단일 자료 삭제.
            
            console.log("del_cnt", del_cnt);

            if(del_cnt > 0) {
                alert("[ "+del_cnt+" ]개의 자료가 삭제 되었습니다.");
            } else {
                alert("해당 자료가 존재하지 않거나 삭제에 실패하였습니다.");
                return false;
            };

        };

        $(tId+" > tr:eq("+idx+")").remove();
        var end_no = $(tId+" > tr").length; // 마지막번호(전체 tr의 갯수)
		for(i=idx; i < end_no; i++) {
		    // tr(index번째)의 첫번째 td에 index+1의 값을 넣어줌
			$(tId+" > tr:eq("+i+") > td:eq(0)").text((i+1));
			$(delBtn).eq(i).attr("onclick","rumiTable.delRow("+i+")");
			$(delBtn).eq(i).html("<i class='fa fa-trash-o' aria-hidden='true'></i>");  // 버튼에 추가...
        };

        // 선택한 행이 삭제된후 호출되는 사용자 함수.
        arr.delSuccess(idx);
       
        // 마지막 남은 Row까지 삭제하면 더이상 동적테이블을 생성할 수 없으므로 페이지를 새로고침한다.
        // totalRow는 Row 삭제하기전값. (모든 row 삭제후 새로고침하면서 10개의 row를 생성한다.)
        if(totalRows==1) {
            document.location.reload(true);
        };

    };

    // 동적테이블이 생성된 이후 
    this.enterTab = function() {
        fields = $(autoTabId).find(autoTabclass);
    }


    var auto_cursor = function() {
        $(document).on("keypress", autoTabclass, function(evt) {
            if (evt.keyCode == 13) {

                var index = fields.index(this);
                var is_field = fields.eq(index+1);
                
                try{
                    console.log(is_field[0].className); // 마지막 TD에 에러 호출.
                } catch(err) {
                    add_item(3);
                    $('html, body').animate({
                        scrollTop : (document.body.scrollHeight)
                    },1500);
                } // end try

                if ( index > -1 && ( index + 1 ) < fields.length ) {
                    fields.eq( index + 1 ).select().focus();
			    }
			    return false;
		    }
	    }); //엔터키로 INPUT 자동이동
    }

    return {
        add_item : add_item,
        rumiLoad : rumiLoad,
        delRow : delRow
    }

}());
