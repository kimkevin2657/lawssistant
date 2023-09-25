// filnd();
if (!Array.prototype.find) {
  Object.defineProperty(Array.prototype, 'find', {
    value: function(predicate) {
      // 1. Let O be ? ToObject(this value).
      if (this == null) {
        throw new TypeError('"this" is null or not defined');
      }

      var o = Object(this);

      // 2. Let len be ? ToLength(? Get(O, "length")).
      var len = o.length >>> 0;

      // 3. If IsCallable(predicate) is false, throw a TypeError exception.
      if (typeof predicate !== 'function') {
        throw new TypeError('predicate must be a function');
      }

      // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
      var thisArg = arguments[1];

      // 5. Let k be 0.
      var k = 0;

      // 6. Repeat, while k < len
      while (k < len) {
        // a. Let Pk be ! ToString(k).
        // b. Let kValue be ? Get(O, Pk).
        // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
        // d. If testResult is true, return kValue.
        var kValue = o[k];
        if (predicate.call(thisArg, kValue, k, o)) {
          return kValue;
        }
        // e. Increase k by 1.
        k++;
      }

      // 7. Return undefined.
      return undefined;
    },
    configurable: true,
    writable: true
  });
}
  
/**
 * 테이블(table) TR의 마우스오버 색상.
 * @param {Object} tr tr의 객체
 * @param {String} eventMode 마우스이벤트모드
 */
function select_tr(tr,eventMode) {
  var tds = tr.getElementsByTagName("td");
	if(eventMode=="over") {
		for(i=0;i<tds.length;i++) {
			tds[i].style.backgroundColor="#DCEFEF";
		}
	} else if(eventMode=="out"){
		for(i=0;i<tds.length;i++) {
			tds[i].style.backgroundColor="";
		}
	}
}

/**
 * 셀렉트 박스 직접 입력 선택시... 
 * @param {String} mb_kind 셀렉트박스 value 값
 */
function select_in(mb_kind) {
	if(mb_kind == 'input_text') {
		$("#mb_kind_zone").empty();
		$("#mb_kind_zone").append("<input type='text' name='mb_kind' class='mb_kind' value='' required='required' />");
	}
}

/**
 * 직원추가 및 수정 팝업
 * @param {Number} id_no 자료고유번호
 */
function memberadd(id_no) {

    if(id_no == undefined || !id_no) {
        id_no = "";
    }
    //alert(cfg.board_skin_url);

    rumiPopup.popup({
        width : 900,
        height : 550,
        fadeIn : true,
        fadeinTime : 200,
        iframe : true,
        url : "https://blingbeauty.shop/bbs/skin/basic_doc/member/mem_edit.php?bo_table="+cfg.bo_table+"&pg=1&id_no="+id_no,
        title : "직원 등록 및 수정",
        print : true,
        reloadBtn : true,
        button : {
            "저장":function(){
                $("#rumiIframe").contents().find("#save").trigger("click");
            },
            "삭제":function(){
                $("#rumiIframe").contents().find("#delete").trigger("click");
            },
            "닫기" : function(){
                rumiPopup.close();
            },
        },
        open : function(){
            $("div.rumiButton button:contains('닫기')").css({"background":"#555"});
        },
        close : function() {
            //parent.document.location.reload();
            //$("#refresh_list").trigger("click");
            jqGridReload();
        }
    });    
}

/**
 * 결재의견 작성하기
 * @param {Number} id_no 자료고유번호
 * @param {Number} z 결재순번 
 */
function app_pop(current, z) {
  
  //alert(cfg.board_skin_url+"/app_popup.php?bo_table="+cfg.bo_table+"&wr_id="+cfg.wr_id+"&current="+current+"&z="+z);

  rumiPopup.popup({
    width : 800,
    height : 400,
    fadeIn : true,
    fadeinTime : 200,
    iframe : true,
    url : cfg.board_skin_url+"/app_popup.php?bo_table="+cfg.bo_table+"&wr_id="+cfg.wr_id+"&current="+current+"&z="+z,
    title : "결재 의견 작성하기",
    print : true,
    reloadBtn : true,
    button : {
        "확인":function(){
            $("#rumiIframe").contents().find("#save").trigger("click");
        },
        "닫기" : function(){
            rumiPopup.close();
        },
    },
    open : function(){
        $("div.rumiButton button:contains('닫기')").css({"background":"#555"});
    },
    close : function() {
        parent.document.location.reload();
    }
  });    
}

/**
 * 자료수정시  계산에 사용된 배열에 값 넣기.
 * @param {Array} arr db Data 배열
 */
function array_sum(arr) {
	for(var idx in arr) {
		calCulate(idx);
	}
}

/**
 * 합계 산출
 * @param {Array} arr 특정 요소의 금액이 들어 있는 배열
 */
function supply_sum(arr){
    return arr.reduce(function(a, b) {
        return a + b;
    }, 0); // 공급가액의 합계
}

/**
 * 결재상태를 변경
 * @param {Number} current 변경할 결재상태
 * @param {Number} z 결재순번
 */
function app_me(cfg) {

  $.ajax({
		url : cfg.board_skin_url+"/_approval_update.php",
		type : "POST",
		data : {
			"bo_table" : cfg.bo_table,
			"wr_id" : cfg.wr_id,
      "seq" : cfg.z, 
      "current" : cfg.current,
      "memo" : cfg.memo
		},
		dataType : "json",
		async : false,
	  cache : false,
		success : function(data){
      parent.rumiPopup.close();
    }
	});
}

/**
 * 자료수정시
 */
function docEdit() {
  if(cfg.s1 == '1' || cfg.s2 == '1' || cfg.s3 == '1' || cfg.s4 == '1' ) {
    alert("현재 결재가 진행중이므로 자료를 수정할 수 없습니다.");
    return false;
  }

  //alert(cfg.update_url);

  location.href =  cfg.update_url;
};


/**
 * 문서양식불러오기
 * @param {String} doc 문서종류
 * @param {String} mode 페이지 종류 ( view 또는 write )
 */
function get_document(doc, mode) {
  
  var doc_list = cfg.doc_list.split("|"); // 문서종류(카테고리)
  var doc_file = cfg.doc_file.split("|"); // 문서파일명

  var s = 0;
  $.each(doc_list, function(idx, data) {
    if(doc == data) {
      var file = (mode=='view') ? doc_file[idx]+"_view" : doc_file[idx];
      $("#doc_zone").load(cfg.board_skin_url+"/doc/"+file+".php?bo_table="+g5_bo_table+"&wr_id="+cfg.wr_id, function(response, status, xhr) {
        if (status == "error") {
          // 파일이 존재 하지 않으면 _no_page.php  파일을 가져온다.
          $("#doc_zone").load(cfg.board_skin_url+"/doc/_no_page.php?bo_table="+g5_bo_table+"&wr_id="+cfg.wr_id+"&doc="+doc);
          console.log(cfg.board_skin_url+"/doc/_no_page.php?bo_table="+g5_bo_table+"&wr_id="+cfg.wr_id+"&doc="+doc);
          //$(".doc_title").text("서식이 없습니다.").css("color","red");
        }

        // 타이틀은 문서종류의 텍스트에서 숫자를 제외하고 순수 문자만 타이틀 보낸다.
        doc = doc.replace(/[0-9]/g, "");
        $(".doc_title").text(doc).css("color","#000");

      });
      s++;
    }
  });

  if(s==0) {
    $("#doc_zone").load(cfg.board_skin_url+"/doc/_no_page.php?bo_table="+g5_bo_table+"&wr_id="+cfg.wr_id);
  }

}

/**
 * 참조자 추가시 배열 생성
 * @param {Number} val 참조 : 추가한 인덱스 번호
 */
function reperrerList(val) {

  if(val) {
      var txt = $("#referrer option:selected").text();
      var name = "<li data-id='"+val+"|"+txt+"'>"+txt+"<span class='re_del'><i class='fa fa-remove' aria-hidden='true'></i></span></li>";
      $(".refer").append(name);
  }
  
  var idx_re = $(".refer li");
  for(i=0; i < idx_re.length; i++) {
      var id = idx_re.eq(i).attr("data-id");
      id = id.split("|");
      mb_reperrer[i] = {
          id : id[0],
          name : id[1]
      }
  }

  var str = JSON.stringify(mb_reperrer);
  $("#wr_1").val(str);

  // 이미 등록되어 있는지 체크
  if(val != '') {
    var xi = Object.keys(mb_reperrer).length; // Object 개수 구하기
    var chk = 0;
    $.each(mb_reperrer, function(idx, data) {
      if(data.id == val) {
          chk++;
      }
    });
    
    if(chk > 1) {
      alert("이미 선택되었습니다.");
      $(".refer li").eq(xi - 1).remove();
      reperrerList('');
    }
  }

}

/**
 * 참조부서 추가시 배열 생성
 * @param {Number} val 참조 : 추가한 인덱스 번호
 */
function reperrerList2(val) {

  if(val) {
      var name = "<li data-id='"+val+"'>"+val+"<span class='re_del2'><i class='fa fa-remove' aria-hidden='true'></i></span></li>";
      $(".refer2").append(name);
  }
  
  var idx_re = $(".refer2 li");
  for(i=0; i < idx_re.length; i++) {
      var id = idx_re.eq(i).attr("data-id");
      mb_section[i] = id;
  }

  var str = JSON.stringify(mb_section);
  $("#wr_11").val(str);

  // 이미 등록되어 있는지 체크
  if(val != '') {
    var xi = Object.keys(mb_section).length; // Object 개수 구하기
    var chk = 0;
    $.each(mb_section, function(idx, data) {
      if(data == val) {
          chk++;
      }
    });
    
    if(chk > 1) {
      alert("이미 선택되었습니다.");
      $(".refer2 li").eq(xi - 1).remove();
      reperrerList2('');
    }
  }

}


//날짜 형식 만들기 (0000-00-00)
function formatDate(date) {
  var mymonth = date.getMonth() + 1;
  var myweekday = date.getDate();
  return (date.getFullYear() + "-" + ((mymonth < 10) ? "0" : "") + mymonth + "-" + ((myweekday < 10) ? "0" : "") + myweekday);
}

// 이전달구하기 (한달전) ( 1일 ~ 말일)
function SetPrevMonthDays(begin, end) {
  
  var d2, d22;
    d2 = new Date();
    d22 = new Date(d2.getFullYear(), d2.getMonth() -1);

  var d3, d33;
    d3 = new Date();
    d33 = new Date(d3.getFullYear(), d3.getMonth(), "");
    
  var fd = formatDate(d22);
    var td = formatDate(d33);

  $(begin).val(fd);
  $(end).val(td);
}

// 이번달 (당월) (1일 ~ 말일)
function SetCurrentMonthDays(begin, end) {
  var d2, d22;
    d2 = new Date();
    d22 = new Date(d2.getFullYear(), d2.getMonth());

    var d3, d33;
    d3 = new Date();
    d33 = new Date(d3.getFullYear(), d3.getMonth() + 1, "");

  var fd = formatDate(d22);
    var td = formatDate(d33);

  $(begin).val(fd);
  $(end).val(td);
}

// 오늘
function SetToDays(begin, end) {
  var obj1 = $(begin).val();
  var obj2 = $(end).val();
  var now = new Date();
  var nowDayOfWeek = now.getDay();
  var nowDay = now.getDate();
  var nowMonth = now.getMonth();
  var nowYear = now.getYear();
  nowYear += (nowYear < 2000) ? 1900 : 0;
  var toDay = new Date(nowYear, nowMonth, nowDay);
  $(begin).val(formatDate(toDay));
  $(end).val(formatDate(toDay));
}


// 이번주
function SetWeek(begin, end) {
  var obj1 = $(begin).val();
  var obj2 = $(end).val();
  var now = new Date();
  var nowDayOfWeek = now.getDay();
  var nowDay = now.getDate();
  var nowMonth = now.getMonth();
  var nowYear = now.getYear();
  nowYear += (nowYear < 2000) ? 1900 : 0;
  var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek);
  var weekEndDate = new Date(nowYear, nowMonth, nowDay + (6 - nowDayOfWeek));
  $(begin).val(formatDate(weekStartDate));
  $(end).val(formatDate(weekEndDate));
}

// 지난주
function SetWeek_befor(begin, end) {
  var obj1 = $(begin).val();
  var obj2 = $(end).val();
  var now = new Date();
  var nowDayOfWeek = (now.getDay() + 7);
  var nowDay = now.getDate();
  var nowMonth = now.getMonth();
  var nowYear = now.getYear();
  nowYear += (nowYear < 2000) ? 1900 : 0;
  var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek);
  var weekEndDate = new Date(nowYear, nowMonth, nowDay + (6 - nowDayOfWeek));
  $(begin).val(formatDate(weekStartDate));
  $(end).val(formatDate(weekEndDate));
}

// 7일전
function Set7Days(begin, end) {
  var obj1 = document.getElementById(begin);
  var obj2 = document.getElementById(end);
  var mydate = new Date();
  mydate.setDate(mydate.getDate() - 7);
  obj1.value = formatDate(mydate);
  obj1.focus();
  obj2.value = formatDate(new Date());
  obj2.focus();
}

/**
 * 문서작성하기 페이지에서 선택된 결재선 저장하기
 */
function approvalSave() {
  
  var app1 = $("#app_2").val();
  var app2 = $("#app_3").val();
  var app3 = $("#app_4").val();

  if(!app1 && !app2 && !app3) {
    alert("결재자를 선택해 주세요.");
    return false;
  }

  $.ajax({
		url : cfg.board_skin_url+"/ajax.appsave.php?bo_table="+cfg.bo_table,
		type : "POST",
		data : {
      "app1" : app1,
      "app2" : app2,
      "app3" : app3
		},
		dataType : "json",
		async : false,
	  cache : false,
		success : function(data){
      if(data==false) {
        alert("이미 등록된 결재선입니다.");
      } else if(data > 0) {        
        alert("저장되었습니다.");        
      }
    }
	});

}

/**
 * 결재선 관리 및 선택하기
 */
function approvalOpen() {

  rumiPopup.popup({
    width : 680,
    height : 500,
    fadeIn : true,
    fadeinTime : 200,
    iframe : true,
    url : "https://blingbeauty.shop/bbs/skin/basic_doc/popup.approval_list.php?bo_table=approval",
    title : "결재선 관리 및 선택",
    print : true,
    reloadBtn : true,
    button : {
       "입력항목 5개 추가":function(){
          $("#rumiIframe").contents().find(".add_five").trigger("click");
        },
        
        "저장":function(){
            $("#rumiIframe").contents().find("#submit").trigger("click");
        },
        "닫기" : function(){
            rumiPopup.close();
        },
    },
    open : function(){
        $("div.rumiButton button:contains('닫기')").css({"background":"#555"});
    },
    close : function() {
    }
  });    
}

/**
 * 문서작성하기 페이지에서 선택된 결재선 저장하기
 */
//function approvalOpenSave() {
  //var arr = $("#approvalform").serializeArray()
  //console.log(arr);
  //return false;

//   $.ajax({
// 		url : cfg.board_skin_url+"/ajax.appsave.php?bo_table="+cfg.bo_table,
// 		type : "POST",
// 		data : {
//       "app1" : app1,
//       "app2" : app2,
//       "app3" : app3
// 		},
// 		dataType : "json",
// 		async : false,
// 	  cache : false,
// 		success : function(data){
//       if(data==false) {
//         alert("이미 등록된 결재선입니다.");
//       } else if(data > 0) {        
//         alert("저장되었습니다.");        
//       }
//     }
// 	});

// }