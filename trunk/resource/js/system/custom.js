
//jQuery

    $(function(){
    	
    	//搜索栏聚焦
    	$(".togg").focus(function(){
    		$(this).removeClass("c999");
    		if(this.value=='输入任务/商品'){
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
			//评论鼠标移动事件显示工具栏
			$(".top1,.comment_item").hover(function(){
				$(this).children('.operate').removeClass('hidden');
				
			},function(){
				$(this).children('.operate').addClass('hidden');
			});
		
        //为表格内容区域添加奇偶行不同色
        $("table tbody tr:odd").not('table.jqTransformTextarea tr').addClass("odd");
        //为列表添加奇偶行不同色
        $(".list dd:odd").not('dd.tags').addClass("odd");
        //为列表隐藏工具栏
        $(".list dd").children('.operate').addClass('hidden');
        
        //为表格内容区添加鼠标事件
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
        	if($(obj).val()=='我要说几句...'){
        		$(obj).val('').css({height:"50px"}).next().show();
        	}
        	event.stopPropagation();
        }
        var tarBlur = function(obj,event){
        	$("html,body").click(function(event){
        		if(!$(event.target).hasClass("answer-zone")){
        			$(obj).val('我要说几句...').css({height:"23px"}).next().hide().find(".answer_word").text("你还能输入100个字!");
        		}
        	})
        }
        
        
        var s = $('.messages');
        //msgshow(s);

        // 消息
        $('.messages .close').click(function() {
        	var s = $(this).parent('.messages');
        	msghide(s);
        });

        // 显示消息
        /*function msgshow(ele) {
        	var t = setTimeout(function() {
        		ele.slideDown(200);
        		clearTimeout(t);
        	}, 400);
        };*/
        // 关闭消息
        function msghide(ele) {
        	ele.animate({
        		opacity : .01
        	}, 200, function() {
        		ele.slideUp(200, function() {
        			ele.remove();
        		});
        	});
        };
	
  
   //返回顶部
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
    
  //菜单固定浮动
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
     //解决select 宣染
	function jq_select(){
	$("#reload_indus div.jqTransformSelectWrapper ul li a").click(function(){
			 $("#indus_id").removeClass("jqTransformHidden").css('display:none');
			 $("select").jqTransSelect().addClass("jqTransformHidden");
		});
	}
	
	/**
	 * 获取任务行业
	 * @param indus_pid
	 */
	function showIndus(indus_pid){
		if(indus_pid){
			$.post("index.php?do=ajax&view=indus",{indus_pid: indus_pid}, function(html){
				var str_data = html;
				if (trim(str_data) == '') {
					$("#indus_id").html('<option value="-1"> 请选择子行业 </option>');
				}
				else {
					$("#indus_id").html(str_data);
					$("#reload_indus div.jqTransformSelectWrapper ul li a").triggerHandler("click");
				}
			},'text');
		}
	}


/**
 * 需求字数检查
 * 
 * @param obj
 *            需求对象
 * @param 最大长度
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
       
        $("#length_show").text("已输入长度:"+len+",还可以输入:"+Remain+"字");
	}else{
		$("#length_show").text("可输入:"+maxLength+"字,已超出长度:"+Remain+"字");
	}
}