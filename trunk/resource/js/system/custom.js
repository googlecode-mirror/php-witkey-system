
//jQuery

    $(function(){
    	
    	//�������۽�
    	$(".togg").focus(function(){
    		$(this).removeClass("c999");
    		if(this.value=='��������/��Ʒ'){
    			this.value='';
			}
    		;
    	}).blur(function(){
    		$(this).addClass("c999");
    			this.value==''?this.value=$(this).attr(this.title?'title':'original-title'):'';
    	})
			$('.operate a,a.prev,a.next,a.small_nav,.border_n a ').not('.nav .operate a').hover(function(){
				$(this).children('.icon16').addClass("reverse");
				}, function(){
				$(this).children('.icon16').removeClass("reverse");
			});
			//��������ƶ��¼���ʾ������
			$(".top1,.comment_item").hover(function(){
				$(this).children('.operate').removeClass('hidden');
				
			},function(){
				$(this).children('.operate').addClass('hidden');
			});
		
        //Ϊ����������������ż�в�ͬɫ
        $("table tbody tr:odd").not('table.jqTransformTextarea tr').addClass("odd");
        //Ϊ�б������ż�в�ͬɫ
        $(".list dd:odd").not('dd.tags').addClass("odd");
        //Ϊ�б����ع�����
        $(".list dd").children('.operate').addClass('hidden');
        
        //Ϊ����������������¼�
        $('table tbody tr,.list dd,.category_list .item,.case_con').not('.list dd.tags,table.jqTransformTextarea tr').hover(function(){
            $(this).addClass("hover").children('.operate').removeClass('hidden');
        }, function(){
            $(this).removeClass("hover").children('.operate').addClass('hidden');
        });
        
		$(".tar_comment").click(function(event){
			tarClick($(this),event);event.stopPropagation();
		})
		$(".tar_comment").blur(function(event){
			tarBlur($(this),event);event.stopPropagation();
		})
        
    	$(".tar_comment").live("click",function(event){
    		tarClick($(this),event);event.stopPropagation();
    	})
    	$(".tar_comment").live("blur",function(event){
    		tarBlur($(this),event);event.stopPropagation();
    	})
        var tarClick = function(obj,event){
        	if($(obj).val()=='��Ҫ˵����...'){
        		$(obj).val('').css({height:"50px"}).next().show();
        	}
        	event.stopPropagation();
        }
        var tarBlur = function(obj,event){
        	$("html,body").click(function(event){
        		if(!$(event.target).hasClass("answer-zone")){
        			$(obj).val('��Ҫ˵����...').css({height:"23px"}).next().hide().find(".answer_word").text("�㻹������100����!");
        		}
        	})
        }
        
        
        var s = $('.messages');
        //msgshow(s);

        // ��Ϣ
        $('.messages .close').click(function() {
        	var s = $(this).parent('.messages');
        	msghide(s);
        });

        // ��ʾ��Ϣ
        /*function msgshow(ele) {
        	var t = setTimeout(function() {
        		ele.slideDown(200);
        		clearTimeout(t);
        	}, 400);
        };*/
        // �ر���Ϣ
        function msghide(ele) {
        	ele.animate({
        		opacity : .01
        	}, 200, function() {
        		ele.slideUp(200, function() {
        			ele.remove();
        		});
        	});
        };
	
  
   //���ض���
        $('.top').addClass('hidden');
        $.waypoints.settings.scrollThrottle = 30;
        $('#wrapper').waypoint(function(event, direction){
            $('.top').toggleClass('hidden', direction === "up");
        }, {
            offset: '-1%'
        });
        
      
        $(".box.model .shop .box_detail .small_list li.item,.case_con").mouseover(function(){
    		$(this).css('z-index','2');
    	});
    	$(".box.model .shop .box_detail .small_list li.item,.case_con").mouseout(function(){
    		$(this).css('z-index','1');
    	});

    });
    
  //�˵��̶�����
    if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style && location.href.indexOf('do=browser') < 0) {
	}
	else {
    
        if ( $(".second_menu").length > 0 ) { 
        	
        	$('.section').waypoint(function(event, direction) {
    			$(this).children('.second_menu').toggleClass('fixed-top', direction === "down");
    			event.stopPropagation();
    		});
        } 
	}
    
var checkall = function(){
    if ($('#checkbox').attr('value') == 0) {
    	$("#checkbox").attr("value",1);
    	$('input[type=checkbox]').attr('checked', true);
    }  else {
    	$("#checkbox").attr("value",0);
        $('input[type=checkbox]').attr('checked', false);
    }

}
     //���select ��Ⱦ
	function jq_select(){
	$("#reload_indus div.jqTransformSelectWrapper ul li a").click(function(){
			 $("#indus_id").removeClass("jqTransformHidden").css('display:none');
			 $("select").jqTransSelect().addClass("jqTransformHidden");
		});
	}
	
	/**
	 * ��ȡ������ҵ
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
					$("#reload_indus div.jqTransformSelectWrapper ul li a").triggerHandler("click");
				}
			},'text');
		}
	}


/**
 * �����������
 * 
 * @param obj
 *            �������
 * @param ��󳤶�
 */
function checkInner(obj,maxLength,e){
	var  len   = obj.value.length;
		e.keyCode==8?len-=1:len+=1;
		len<0?len=0:'';
	
	var Remain = Math.abs(maxLength-len);
//	obj.value = obj.value.substring(0,maxLength);
	$(obj).blur(function() {
		obj.value = obj.value.substring(0,maxLength);
	});
	if(maxLength>=len){
       
        $("#length_show").text("�����볤��:"+len+",����������:"+Remain+"��");
	}else{
		$("#length_show").text("������:"+maxLength+"��,�ѳ�������:"+Remain+"��");
	}
}