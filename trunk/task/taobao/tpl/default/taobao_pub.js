/**
 * 
 */
	// �ύ
	function stepCheck_tao(model_id){
		var model_id = model_id;
		var pass     = true;
		// �ж��Ƿ�һ��ƽ̨��ûѡ��
		var sinaObj = $('#delivery_platform_sina');
		var tenObj = $('#delivery_platform_ten');
			if(sinaObj.attr('checked')==false&&tenObj.attr('checked')==false){
				tipsAppend('platform',"��ѡ��΢��ƽ̨",'warning','orange');
				pass = false;
			}else{
				con= contentCheck('tar_content','��΢��','50','140',1,'length_show');
				if(con==''){pass=false;}
			}
		pass==true&&stepCheck();
	}
	$(function(){
		var nAt  = parseInt('{$r_info[new_is_at]}');
			if(nAt){
				$('#new_at_demand').attr('checked',true);
				$('#span_new_at_num').show();
			}
			$('#new_at_demand').click(function(){
				$('#span_new_at_num').toggle();
			})
		 
	})
	$(function(){
			var taoUser= $('#taobao_user');
			if(wb_img!=''){
				$('#div_wb_img').show();
				$('#wb_img').attr('src',wb_img);
			}else{
				$('#div_wb_img').hide();
			}
			$('#zf_1').click(function(){
				if(taoUser.val()){
					taoButtonShow('tao_info','next');
				}
			});
			$('#zf_2').click(function(){
				if(taoUser.val()){
					taoButtonShow('tao_goods','prev');
				}	
			})
			taoUser.blur(function(){
				var zf_obj = $(":radio[name='zf_obj']:checked").val();
				if(taoUser.val()){
					if(typeof(zf_obj)=='undefined'||zf_obj=='shop'){
						taoButtonShow('tao_info','next');
					}else{
						taoButtonShow('tao_goods','prev');
					}
				}
			})
	})
	function taoButtonShow(obj,direction){
		var obj = $('#'+obj);
			obj.removeClass('hidden');
			direction=='next'?obj.next().addClass('hidden'):obj.prev().addClass('hidden');
						
	}
	function taoInfo(){
		var user = '';
			if(user = taoUser()){
				$.getJSON(basic_url,{api:'shop',nick:user},function(json){
					if(json.status){
						var info = json.data.shop;
						var purl = "http://shop"+info.sid+".taobao.com";
						var str  = '��������:'+info.title;
							assign==1?str  += "\n���̵�ַ:"+purl:'';
							str  +="\n���̼��:";
							info.desc!=''?str+=info.desc:str+='����';
							if(info.pic_path!=''){
								pic = 'http://logo.taobaocdn.com/shop-logo'+info.pic_path;
								$('#div_wb_img').show().children('strong').html('�Ա���꣺');
								$('#div_wb_img img#wb_img').attr('src',pic);
								$("input[name='wb_img']").val(pic);
							}
							$('#div_prom_url').show().find('stong').html('���̵�ַ��');
							$('#prom_url').val(purl);
							$('#tar_content').html(str);
					}else{
						showDialog('������Ϣ��ȡʧ��','alert','������ʾ');
					}
				})
			}
	}
	function taoGoods(){
			var user = '';
				if(user = taoUser()){
					showWindow('tao_goods',basic_url+'&api=items&nick='+user,'get','0');
			}
	}
	function taoUser(){
		var taoUser = $('#taobao_user').val();
			if(!taoUser){
				showDialog('����д�Ա��ƹ�����','alert','������ʾ',function(){
					$('#taobao_user').focus();
			});
		}
		return taoUser;
	}