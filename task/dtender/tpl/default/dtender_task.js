$(function(){
	var loading = parseInt($(".process li.selected").index()) + 1;
	$(".progress_bar").css("width", loading * 20 + "%");
})

/** 稿件提交 */
function workHand() {
	if (check_user_login()) {
		if (uid == guid) {
			showDialog('操作无效，用户对自己发布的任务交稿!', 'alert', '操作失败提示', '', 0);
			return false;
		} else {
			var is_bided = parseInt($("#is_bided").html());
			if(is_bided==0){
				showDialog('操作无效，您已经对任务投过标了','alert','操作失败提示','',0);
			}else{
				showWindow("work_hand",basic_url+'&op=work_hand',"get",'0');return false;
			}			
		}
	}
}
/**
 * 选择稿件
 * @param work_id 稿件编号
 * @param to_status 变更状态
 * @returns {Boolean}
 */
function workBid(work_id,to_status){
	if(guid!=uid){
		showDialog("只有雇主才能操作稿件","alert","操作提示");return false;
	}else{
		var url=basic_url+'&op=work_choose&work_id='+work_id;
			$.post(url,{to_status:to_status},function(json){
				if(json.status==1){					
					$(".work_pass").remove();$("#work_7_"+work_id).remove();					
					var divStatus=$('<div class="work_status_big work_'+to_status+'_big qualified_big1 po_ab"></div>');
					$("#"+work_id).find(".work_status_big").remove();
					divStatus.appendTo($("#"+work_id));
					showDialog(json.data,"right",json.msg);return false;
				}else{
					showDialog(json.data,"error",json.msg);return false;
				}
			},'json')
	}
}

/**
 * 稿件编辑
 * @param work_id
 */
function workEdit(work_id) {
	if (check_user_login()) {		
		showWindow("work_edit",basic_url+'&op=work_edit&bid_id='+work_id,"get",'0');return false;
	}
}
/**
 * 赏金托管
 * @returns {Boolean}
 */
function task_pay(){
	if(check_user_login()){
		if(uid!=guid){
			showDialog('只有雇主才能进行赏金托管','error','操作提示');return false;
		}else{
			var url = basic_url + "&op=hosted_amount";
			showWindow('hosted_amount',url,'get',0);return false;
		}
	}
}

/**
 * 确认计划完成(威客)
 * @returns {Boolean}
 */
function plan_complete(plan_id,plan_step){
	if(check_user_login()){
		var url = basic_url +"&op=plan_complete";
		$.post(url,{plan_id:plan_id,plan_step:plan_step},function(json){
			if(json.status==1){
				$("#complate_"+plan_id).remove();
				$("#plan_status_"+plan_id).html('待付款');
				showDialog(json.data,"right",json.msg);return false;
			}else{
				showDialog(json.data,"alert",json.msg);return false;
			}
		},'json')
	}
}

/**
 * 确认付款
 * @returns {Boolean}
 */
function plan_confirm(plan_id,plan_step){
	if(check_user_login()){
		var url = basic_url +"&op=plan_confirm";
		$.post(url,{plan_id:plan_id,plan_step:plan_step},function(json){
			if(json.status==1){
				$("#confirm_"+plan_id).remove();
				$("#plan_status_"+plan_id).html('完成');
				showDialog(json.data,'right',json.msg);return false;
			}else{
				showDialog(json.data,'error',json.msg);return false;
			}
		},'json')
	}
}

//添加任务计划
function add_task_plan(){	
	var i = parseInt($("#plan i:last").html());	
	var k = i+1;	
	if(k>5){
		showDialog('工作计划最多不得超过5步','error','消息提示');return false;
	}else{		
		var append_html = "<div id=\"plan_step_"+k+"\" name=\"plan_step_"+k+"\" class=\"pb_10 pl_10\">"+
		"<div class=\"rowElem clearfix\">"+
		"<label>计划金额：</label>"+
		"<input type=\"text\" size=\"3\" name=\"plan_amount[]\" class=\"txt_input\" id=\"plan_amount_"+k+"\" value=\"\" onkeyup=\"clearstr(this)\" limit=\"required:true;type:float\" maxlength=\"5\" msg=\"计划金额填写有误！\" tilte=\"填写计划金额\" msgArea=\"span_plan_cash_"+k+"\">"+
		"<span class=\"ml_5\">元</span>"+
		"<label>,开始时间：</label>"+
		"<input type=\"text\" size=\"9\" name=\"start_time[]\" class=\"txt_input\" id=\"start_time_"+k+"\" onkeyup=\"clearstr(this)\" limit=\"required:true;type:date;than:end_time_"+i+"\" maxlength=\"12\"  onclick=\"showcalendar(event, this, 0)\" msg=\"开始时间填写有误！\" tilte=\"填写开始时间\" msgArea=\"span_plan_cash_"+k+"\">"+
		"<label>,结束时间：</label>"+
		"<input type=\"text\" size=\"9\" name=\"end_time[]\" class=\"txt_input\" id=\"end_time_"+k+"\" onkeyup=\"clearstr(this)\" limit=\"required:true;type:date;than:start_time_"+k+"\" maxlength=\"12\"  onclick=\"showcalendar(event, this, 0)\" msg=\"结束时间填写有误！\" tilte=\"填写结束时间\" msgArea=\"span_plan_cash_"+k+"\">"+
		"<label>,工作目标：</label>"+
		"<input type=\"text\" size=\"11\" name=\"plan_title[]\" class=\"txt_input\" id=\"plan_target_"+k+"\" value=\"\" limit=\"required:true\" maxlength=\"20\" msg=\"工作目标填写有误！\" tilte=\"填写工作目标\" msgArea=\"span_plan_cash_"+k+"\">"+
		"<button type=\"button\"  class=\"mt_5\" value=\"删除\" id=\"del_plan\" name=\"del_plan\" onclick=\"del_task_plan("+k+");\" >删除</button>"+
	"</div><span id=\"span_plan_cash_"+k+"\"></span><i style=\"display:none;\">"+k+"</i></div>"		
		$("#plan_add").append(append_html);
	}
	form_valid(); 
}

//删除融资规则
function del_task_plan(k){
	$("div #plan_step_"+k).remove();
}
//检验计划金额与总金额是否相符
function check_cash(){
	var totle_cash = parseFloat($("#quote").val());
	var i = parseInt($("#plan i:last").html());
	var rule_cash=0;
	for(var j=1;j<=i;j++){		
		var cash = parseFloat($("#plan_amount_"+j).val());		
		rule_cash +=cash;
	}	
	if(rule_cash!=totle_cash){
		showDialog('所填计划总金额与报价不符，请重新设置','error','错误提示','$("#plan_amount_1").focus()',0);		 
		return false;
	}else{
		return true;
	}
	
}