/**
 * 任务事件处理

 */

$(function(){
	var loading = parseInt($(".process li.selected").index()) + 1;
	$(".progress_bar").css("width", loading * 20 + "%");
	if(task_status==9){
		$(".progress_bar").css({width:"100%",background:"grey"}); 
	}

})

/**任务发起投票*/
function taskVote(){
	if(check_user_login()){
		var url = basic_url+'&op=start_vote';
		$.getJSON(basic_url+'&op=start_vote',function(json){
			if(json.status==1){
				showDialog(json.data,'notice',json.msg,"location.href='"+basic_url+"&view=work'");return false;
			}else{
				showDialog(json.data,'alert',json.msg);return false;
			}
		})
	}
}

/** 稿件提交 */
function workHand(url) {
	if (check_user_login()) {
		if (uid == guid) {
			showDialog('操作无效，用户对自己发布的任务交稿!', 'alert', '操作失败提示', '', 0);
			return false;
		} else {
			showWindow("work_hand",url,"get",'0');return false;
		}
	}
}
/**
 * 稿件进行投票
 * @param int work_id 稿件编号
 * @param int vote_uid 被投票人
 */
function workVote(work_id,vote_uid){
	if(check_user_login()){
		 if(vote_uid==uid){
			 showDialog("无法对自己进行投票",'alert','操作提示');return false;}
		var url = basic_url+'&op=work_vote';
		$.post(url,{work_id:work_id},function(json){
			if(json.status==1){
				$("#work_vote_"+work_id).remove();
				var vote_num = $("#vote_num_"+work_id).html();
				num = parseInt(vote_num)+1;
				$("#vote_num_"+work_id).html(num);
				showDialog(json.data,'notice',json.msg);return false;
			}else
				showDialog(json.data,'alert',json.msg);return false;
		},'json')
	}
}





//完工
function work_over(op){
	var task_status = task_status;
	var op = op;
	var url = basic_url+'&op='+op;
	if (check_user_login()) { 
			showWindow("work_hand",url,"get",'0');
			return false; 
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
					$("#work_4_"+work_id).remove();
					$("#work_7_"+work_id).remove(); 
					$("#show_status_"+work_id).attr("class","work_status_big work_"+to_status+"_big qualified_big1 po_ab"); 
					showDialog(json.data,"right",json.msg);
					return false;
				}else{
					showDialog(json.data,"alert",json.msg);
					return false;
				}
			},'json')
	}
}



