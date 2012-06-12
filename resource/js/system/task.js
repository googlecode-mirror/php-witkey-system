/**
 * 任务操作公共js
 */

/**
 * 加载分享的显示菜单
 */


$(function() { 
	/*$(".state_detail").click(function() {
		$(this).prev().slideUp();
		$(".state_detail").slideUp();
		$(this).slideDown('slow');
	})*/

	//任务状态描述
	$(".state_title").click(function() {
		$(".state_title").slideDown(200);
		$(this).slideUp(200);
		$(".state_detail").slideUp(200);
		$(this).next().slideDown(200);
	})
	//稿件显示用户详细信息事件
	$(".user_info").mouseDelay().hover(function(){
			hoverDetail(this);
		},function(){
			leaveDetail(this);
		}
	)
	$(".user_info").live("hover",function(){
		hoverDetail(this);
	});
	$(".user_info").live("mouseleave",function(){
		leaveDetail(this);
	});
	
	$(".arrow-bottom-left,.arrow-top-right").click(function(){
		$("#left_nav").toggleClass("hidden");
		$("#top_nav").toggleClass("hidden");
		setcookie('nav-arrow-'+task_id,$(this).attr("id"),3600);
	})
	var nav_arrow = getcookie('nav-arrow-'+task_id);
	if(nav_arrow){
		if(nav_arrow=='arrow-bottom-left'){
			$("#top_nav").addClass("hidden");
			$("#left_nav").removeClass("hidden");
		}else if(nav_arrow=='arrow-top-right'){
				$("#left_nav").addClass("hidden");
				$("#top_nav").removeClass("hidden");
			}	
	}
})
var detailUID = new Array();
function hoverDetail(obj){
	var user_id = $(obj).attr("uid");
	var wid    = $(obj).attr("wid");
	$(obj).children('.user_detail').removeClass('hidden');
	if($(obj).children('.user_detail').text().length==0&&!detailUID[wid]){
		$(obj).children('.user_detail').load("index.php?do=ajax&view=menu&ajax=user_detail&user_id="+user_id);
		detailUID[wid]=1;
	}
}
function leaveDetail(obj){
	$(obj).children('.user_detail').addClass('hidden');
}
/**
 * 稿件附件加载
 */
function loadFile(work_id){
	if(!$("#work_"+work_id+"_file").html()){
		$("#work_"+work_id+"_file").load("index.php?do=ajax&view=file&ajax=load&work_id="+work_id);
	}else{
		$("#work_"+work_id+"_file").toggle();
	}
}
/**
 * 稿件留言加载
 */
function loadComment(obj,work_id,work_uid){
	if($("#work_"+work_id+"_comment").has("div.old_comment").length==0){
		$("#work_"+work_id+"_comment").load("index.php?do=ajax&view=task&ajax=work_comment&task_id="+task_id+"&work_id="+work_id+"&work_uid="+work_uid);
	}else{
		$("#work_"+work_id+"_comment").toggle();
	}
}
/**
 * 内容检测
 * @param obj
 * @param event
 */
function checkCommentInner(obj,e){
	var  num   = obj.value.length;
		e.keyCode==8?num-=1:num+=1;
		num<0?num=0:'';
	var Remain = Math.abs(100-num);
		if(num<=100){
			$(obj).next().find(".answer_word").text("你还能输入"+Remain+"个字!");
		}else{
			var nt = $(obj).val().toString().substr(0,100);
			$(obj).val(nt);	
		}
}
/** 需求补充 */
function taskReqedit() {
	if (check_user_login()) {
		showWindow('reqedit',basic_url+'&op=reqedit', 'get', 0);return false;
	}
}
/**延期加价*/
function taskDelay(){
	if(check_user_login()){
		if(delay_count>=delay_total){
			showDialog("延期次数超过"+delay_count+"次,无法继续延期","alert","操作提示");return false;
		}
		var url = basic_url+'&op=taskdelay';
		showWindow('taskdelay',url,'get',0);return false;
	}
}
/** 
 * 稿件评论
 * @param string obj 当前对象
 * @param int obj_id  对象编号
 */
function work_comment(obj,obj_id) {
	if (check_user_login()) {
		if(guid!=uid){
			showDialog("只有雇主才能评论稿件","alert","操作提示");return false;
		}
		var url = basic_url+'&op=comment&obj_type=work&obj_id=' + obj_id;
	
		var tar_content = $(obj).parent().prev().val();
			if(tar_content.length>100){
				showDialog("您的回复超过字数限制",'alert','操作提示');return false;
			}else if(tar_content.length>0){
				$.post(url,{tar_content:tar_content},function(json){
					if(json.status==1){ 
             			 var str=$('<div class="comment_item"><a href="index.php?do=space&member_id='+uid+'">'+username+'</a>于'+datePrv+' '+(new Date().toLocaleTimeString())+'评论:'
						 +'<span class="db">'+json.data+'</span></div>');
             			  str.appendTo($("#work_"+obj_id+"_comment"));
             			 $(obj).next().text("你还能输入100个字!").end().parent().hide().prev().css({height:"23px"}).val("我要说几句...");
					}else{
						showDialog(json.data,'alert',json.msg);return false;
					}
				},'json')
			}
	}
}
/**
 * 稿件删除*
 * @param work_id 稿件编号
 */
function workDel(work_id){
	if (check_user_login()) {
		showDialog("确认删除此稿件吗?","confirm","操作提示","delConfirm('"+work_id+"')");
	}
}
/**
 * 删除稿件
 * @param work_id 稿件编号
 */
function delConfirm(work_id){
	$.post(basic_url+'&op=work_del&work_id='+work_id,function(json){
		if(json.status=='1'){
			$("#work_"+work_id+",.work_"+work_id).slideUp(600).remove();
			showDialog(json.data,'notice',json.msg);return false;
		}else{
			showDialog(json.data,'alert',json.msg);return false;
		}
	},'json')
}
c_time();
function c_time() {
	$(".d_time").each(
			function() {
			
				var ed = $(this).attr('ed');
				
				if (ed) {
					var djs = d_time(ed);
					var str = "还剩：" + djs[0] + "天" + djs[1] + "小时" + djs[2]
							+ "分" + djs[3] + "秒";
				} else {
					var str = $(this).attr("title");
				}
				$(this).html(str);
			})
	setTimeout('c_time()', 1000);
}



//对话框提示
function comment_tips(obj_id,content){ 
	var obj_id = obj_id;
	var content = content;  
	var html = $("#"+obj_id).val();  
	
	if(html==content){
		$("#"+obj_id).val(""); 
	} 
	$("#"+obj_id).blur(function (){
		var html = $(this).val();  
		if(html==''){ 
			$(this).val(content); 
		}  
	}); 
}

function loadMarkAid(obj){
	ajaxmenu(obj, 250,'1','2','43');
	return false;
}