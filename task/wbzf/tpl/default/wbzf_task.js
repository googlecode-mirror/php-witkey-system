/**
 * 任务事件处理
 */
$(function(){
	var loading = parseInt($(".process li.selected").index()) + 1;
	$(".progress_bar").css("width", loading * 33.3 + "%");
	if(task_status==9){
		$(".progress_bar").css({width:"100%",background:"grey"}); 
	}

})
/** 稿件提交 */
function workHand() {
	if (check_user_login()) {
		if (uid == guid) {
			showDialog('操作无效，用户对自己发布的任务交稿!', 'alert', '操作失败提示', '', 0);
			return false;
		} else {
			$.get(
					basic_url + "&op=work_hand&step=1",
					{check_work:"check_work"},
					function(result){
						if(result=="false"){
							showDialog('您已经提交过稿件,无需重复提交!', 'alert', '操作失败提示', '', 0);
							return false;
						}
						showWindow("work_hand", basic_url + '&op=work_hand&step=1', "get", '0');
					}
			);
//			showWindow("work_hand", basic_url + '&op=work_hand&step=1', "get", '0');
			return false;
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
					$("#work_6_"+work_id).remove(); 
					$("#work_7_"+work_id).remove(); 
					$("#show_status_"+work_id).attr("class","work_status_big work_"+to_status+"_big qualified_big1 po_ab"); 
					showDialog(json.data,"right",json.msg);
				}else{
					showDialog(json.data,"alert",json.msg);
				}
				return false;
			},'json');
	}
}