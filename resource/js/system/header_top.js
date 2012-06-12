/**
 * 页头js
 */

$(function(){
	
	
	//显示登录层
	$("#login").click(function(){
			$("#login_box").toggleClass("hidden");
			$(this).toggleClass("selected");
			$("#txt_account").focus();
			return false;
	});
	
	//方法-隐藏登录弹出层
	var hideLoginPopup = function(){
		if (!$("#login_box").is(".hidden")) {
			$("#login").removeClass("selected");
			$("#login_box").addClass("hidden");
			
		}
	}; 
	//body点击触发隐藏方法
	$("body").click(function(){
		hideLoginPopup();
		$("#user_menu").addClass("hidden");
		$("#search_select a").not(".selected").addClass("hidden");
	});
	
	//登录后用户导航菜单
	$("#avatar").mouseDelay().hover(function(){
		$("#user_menu").removeClass("hidden");
	},
	function(){
		$("#user_menu").addClass("hidden");
	});
	
	
	//阻止点击隐藏方法
	$("#login_box,#user_menu,#search_select").click(function (e) {
		e.stopPropagation();
	});
		
	//搜索选项
	$("#search_select a.selected").click(function(){
		$(this).nextAll("a").removeClass("hidden");
	});

	$("#search_select a").not(".selected").click(function(){
		$("#search_select .selected").attr("rel",$(this).attr("rel")).children("span").html($(this).html()).end().nextAll("a").addClass("hidden");
	})
	
	
	//语言选项
	$("#lan_menu").mouseDelay().hover(function(){
		$(this).addClass("hover").children("a").removeClass("hidden");
	},
	function(){
		$(this).removeClass("hover").children("a").not("a.selected").addClass("hidden");
	}).click(function(){
		$(this).toggleClass("hover").children("a").not("a.selected").toggleClass("hidden");
	}); 
	$("#lan_menu a").click(function(){
		setLang($(this).attr("rel"));
	})

});

function search_keydown(event){
    if ($.browser.msie) {
        if (window.event.keyCode == 13) {
        	topSearch();
        }
    }
    else {
        if (event.keyCode == 13) {
        	topSearch();
        }
    }
}

function login_keydown(event){
    if ($.browser.msie) {
        if (window.event.keyCode == 13) {
        	ajaxLogin(INDEX);
        }
    }
    else {
        if (event.keyCode == 13) {
        	ajaxLogin(INDEX);
        }

   }
}
 
$("#search_btn").click(function(){topSearch();})
function topSearch(){
	var searchKey = $.trim($("#search_key").val());
	if(searchKey&&searchKey!='输入任务/商品'){
		var type      = $("#search_select .selected").attr("rel");
		var link    = "index.php?do="+type+"&path=H2&search_key="+searchKey;
			$("#frm_search").attr("action",link);
		location.href=link;
	}
}
function setLang(lang){
	if(lang==LANG){
		return false;
	}else{
		setcookie("_lang",lang,24*3600);
		document.location.reload();
	}
}
