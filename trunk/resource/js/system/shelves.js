/**
 * ��Ʒ����js
 */
$(function(){
 
	
	var submit_method = $(":radio[name='submit_method']:checked");
	if(submit_method.val()=='outside'){
		$("#submit_method").hide();
	}
	$(":radio[name='submit_method']").click(function(){
		if($(this).val()=='outside'){
			$("#submit_method").hide();
		}else{
			$("#submit_method").show();
		}
	})
})
/**
 * ��ȡ��Ʒ��ҵ
 * @param indus_pid
 */
function showIndus(indus_pid){
	if(indus_pid){
		$.post("index.php?do=ajax&view=indus",{indus_pid: indus_pid}, function(html){
			var str_data = html;
			if (trim(str_data) == '') {
				$("#indus_id").html('<option value="-1"> ��ѡ������ҵ </option>');
			}
			else {
				$("#indus_id").html(str_data);
			}
		},'text');
	}
}
function checkAgreement(){
	if($("#agreement").attr("checked")==false){
		showDialog("����ͬ����Ʒ����Э��","alert","������ʾ");return false;
	}else return true;
}
function stepCheck(){
	var i 	 = checkForm(document.getElementById('frm_'+r_step));
	var pass = false;
	switch(r_step){
		case "step1":
					pass=true;
			break;
		case "step2":
			if(i){
				if(contentCheck('tar_content',"��������",5,1000)&&checkAgreement()){
					pass=true;
				}else{
					pass=false;
				}
			}
			break;
		case "step3":
			if($("#item_map").attr("checked")==true&&$.trim($("#point").val())==''){
				set_map();pass=false;
			}else{
				pass=true;
			}
			
			break;
		case "step4":
			
			break;
	}
	if(pass==true){
		
		$("#frm_"+r_step).submit();
	}
}

uploadBlur1=function(){	
	if(ifOut('upfile','1')&&$("#upload").val()){	
	 
		upload("upload",'service','front','','','service');
	}else
		return false;
}



/**
 * ��ֵ�����
 * @param obj ��ǰ����
 * @param action��ǰ����  add����/delɾ��
 */
function add_payitem(obj,action,item_num){
	var item_id = parseInt($(obj).attr('item_id'))+0;
	var item_cash = parseFloat($(obj).attr('item_cash')*Number(item_num));
	if(!item_cash){
		item_cash = 0;
		} 
	var item_name = $.trim($(obj).val());
	var item_code = $.trim($(obj).attr("item_code"));
	var item_num = item_num;

	switch(action){
		case "add":
			$.post(basic_url,{ajax:"save_payitem",item_id:item_id,item_name:item_name,item_cash:item_cash,item_code:item_code,item_num:item_num},function(json){
			 
				$("#total").text(json.msg);
			},'json')
			break;
		case "del":
			$.post(basic_url,{ajax:"rm_payitem",item_id:item_id},function(json){
				
					$("#total").text(json.msg);
			},'json')
			break;
	}
}
/**
 * �ϴ���ɺ��ҳ����Ӧ
 * @param json json����
 */
function uploadResponse(json){
	//alert($("#"+json.filename).val().length);
	//if($("#"+json.filename).length<1){//�ж��Ƿ�����ͬ����
		if(json.msg){
			att_uploadResponse(json);
			return false;
		}
		var file_path = json.path;	 
		var file=$('<li class="items" id="'+json.fid+'" style="display:none">'
                 +'<span>'+json.localname+'</span>'
                 +'<a href="javascript:;" class="close" onclick="del_file(\''+file_path+'\','+json.fid+');">&times;</a></li>');
	  
			file.appendTo("#upfile").fadeIn(1000);
			loadingControl("#upfile li","#loading_"+json.fid,2000);
			var file_ids = $("#file_ids").val();
			if(file_ids){
				$("#file_ids").val(file_ids+','+file_path);
			}else{
				$("#file_ids").val(file_path);
				$("#more_pic").val(json.size);
				$("#upload").val('');
			}	
			 
}
//����json��Ӧ
function att_uploadResponse(json){
	var file_path = json.msg.url;	 
	var file=$('<li class="items" id="'+json.fid+'" style="display:none">'
             +'<span>'+json.msg.localname+'</span>'
             +'<a href="javascript:;" class="close" onclick="del_file(\''+file_path+'\','+json.fid+');">&times;</a></li>');
  
	file.appendTo("#file_upfile").fadeIn(1000);
	loadingControl("#file_upfile li","#loading_"+json.fid,2000);
	var file_ids = $("#file_ids_2").val(); 
	$("#file_path_2").val(file_path);
  
}



//��ʾ����ʹ�������������
function show_payitem_num(obj,item_code){
	
	var item_code = item_code;
	var checked = $(obj).attr("checked");  
	if(checked ==true){ 
		if(item_code=='map'){
			$("#set_map").show(); 
			add_payitem($("#item_map"),'add',1);  
		}else{
			$("#span_"+item_code).show();  
		}
	}else{ 	
		if(item_code=='map'){
			add_payitem($("#item_map"),'del',1);  
			$("#set_map").hide(); 
		}else{
			del_payitem(item_code);//ɾ����ֵ����
			$("#span_"+item_code).hide(); 
			$("#span_"+item_code).val(""); 
		} 
	} 
}


//�༭��ֵ����
function edit_payitem(item_code){

	var item_code = item_code;
	var payitem_num = parseInt($("#payitem_"+item_code).val());
	var item_cash = Number($("#checkbox_"+item_code).attr("item_cash"));
	var total_cash = Number( $("#ago_total").val()); 
 
	add_payitem($("#checkbox_"+item_code),'add',payitem_num); 
}

//ɾ����ֵ����
function del_payitem(item_code){
	var item_code = item_code;
	var payitem_num = parseInt($("#payitem_"+item_code).val());
	add_payitem($("#checkbox_"+item_code),'del',payitem_num);  
}

/**
 * �ϴ�����ɾ��
	* @param file_id �������
	*/
function del_file(file_path,fid){

	var file_ids = $("#file_ids").val().toString();
	var more_size = $("#more_pic").val().toString();
	$.post("index.php?do=ajax&view=file&ajax=del",
			{"fid":fid,"filepath":file_path,"size":more_size},
			function(json){
	//$.getJSON("index.php?do=ajax&view=file&ajax=del&filepath="+file_path,function(json){
		if(json.status=='1'){	
			file_ids = file_ids.replace(file_path,'');
			var len = file_ids.length;
			var firstchar = file_ids.substring(0,1);			
			if(firstchar==','){
				file_ids = file_ids.substring(1);
			}
			var lastchar = file_ids.substring(len-1);
			if(lastchar==','){
				file_ids = file_ids.substring(0,len-1);				
			}			
			$("#file_ids").val(file_ids);
			$("#"+fid).remove();
		}
	},'json');	
}