/**
 * �����¼�����

 */

$(function(){
	var loading = parseInt($(".process li.selected").index()) + 1;
	$(".progress_bar").css("width", loading * 20 + "%");
	if(task_status==9){
		$(".progress_bar").css({width:"100%",background:"grey"}); 
	}
	
	//ͼ��
	var colors = ['#005ba8','#ff9900',
					'#92d5ea','#ee8310',
					'#8d10ee','#5a3b16',
					'#26a4ed','#f45a90',
					'#e9e744']; 
	$('table.chart_line').addClass('hidden').visualize({type: 'line', height: '200px', width: '450px',colors: colors});
})

/** ����ύ */
function workHand() {
	if (check_user_login()) {
		if (uid == guid) {
			showDialog('������Ч���û����Լ����������񽻸�!', 'alert', '����ʧ����ʾ', '', 0);
			return false;
		} else {
			showWindow("work_hand", basic_url + '&op=work_hand&step=1', "get", '0');
			return false;
		}
	}
}
