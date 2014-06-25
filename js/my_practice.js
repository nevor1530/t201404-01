function gotoTop(min_height){
    //预定义返回顶部的html代码，它的css样式默认为不显示
    var gotoTop_html = '<div id="gotoTop">返回顶部</div>';
    //将返回顶部的html代码插入页面上id为page的元素的末尾 
    $("#container").append(gotoTop_html);
    $("#gotoTop").click(//定义返回顶部点击向上滚动的动画
        function(){$('html,body').animate({scrollTop:0},700);
    }).hover(//为返回顶部增加鼠标进入的反馈效果，用添加删除css类实现
        function(){$(this).addClass("hover");},
        function(){$(this).removeClass("hover");
    });
    //获取页面的最小高度，无传入值则默认为600像素
    min_height ? min_height = min_height : min_height = 600;
    //为窗口的scroll事件绑定处理函数
    $(window).scroll(function(){
        //获取窗口的滚动条的垂直位置
        var s = $(window).scrollTop();
        //当窗口的滚动条的垂直位置大于页面的最小高度时，让返回顶部元素渐现，否则渐隐
        if( s > min_height){
            $("#gotoTop").fadeIn(100);
        }else{
            $("#gotoTop").fadeOut(200);
        };
    });
};

$(function(){
	$(".exam-point-tree .item").on("click", function(){
		$this = $(this);
		$parent = $this.parent();
		
		// 判断是否有子级
		$sublevel = $parent.find(".sublevel");
		if (!$sublevel) {
			return ;
		}
		
		if ($parent.hasClass("expand")){
			$parent.removeClass("expand");			
			$parent.children(".sublevel").slideUp();
		} else {
			$parent.addClass("expand");			
			$parent.children(".sublevel").slideDown();
		}
	});
	
	$(".exam-point-tree a").on("click", function(e){
		stopBubble(e);
	});
	
	//阻止事件冒泡函数
	function stopBubble(e)
	{
	    if (e && e.stopPropagation)
	        e.stopPropagation()
	    else
	        window.event.cancelBubble=true
	}
});

/*
 * demo:
 *		var options = {
 *			title: '',
 *			content: '',
 *			confirmBtnLabel: '确定',
 *			cancelBtnLabel: '取消',
 *			confirmCallback: function(){}
 *		};
 *		$.myplugin.show(options);
 *
 */
$(function(){
	var rawhtml = '<div class="dialog-backdrop" style="z-index: 999;"></div>\
				<div class="dialog-wrap need-payment-dialog-wrap" style="z-index: 1000; visibility: visible; top: 261px; left: 408px;">\
					<div class="dialog">\
					    <div class="dialog-hd">\
					        <span class="title bold">申论练习</span>\
					        <button class="close">×</button>\
					    </div>\
					    <div class="dialog-bd">\
					    	<div class="download-confirm-wrap">\
								<div class="download-confirm">\
								    <div class="shenlun-text">\
								    	你的批改次数不足，先去练习?\
								   	</div>\
								    <div class="text-right">\
								        <span class="b-btn btn-cancel">\
											{cancelBtnLabel}\
								        </span>\
								        <span class="b-btn b-btn-primary btn-confirm">\
								        	{confirmBtnLabel}\
								        </span>\
								    </div>\
								</div>\
							</div>\
						</div>\
					</div>\
				</div>';
	var $dom;
	function close(){
		if ($dom != null){
			$dom.remove();
			$dom = null;
		}
	}
	
	var defaultOptions = {
		title: '',
		content: '',
		confirmBtnLabel: '确定',
		cancelBtnLabel: '取消',
		confirmCallback: null
	};
	
	jQuery.mydialog = {
		show: function(options){
			if ($dom != null){
				return;
			}
			options = $.extend(defaultOptions, options); 
			var html = rawhtml.replace("{cancelBtnLabel}", options.cancelBtnLabel);
			html = html.replace("{confirmBtnLabel}", options.confirmBtnLabel);
			$dom = $(html);
			$('body').append($dom);
			$('.dialog-wrap .close').on('click', function(){
				close();
			});
			$('.dialog-wrap .btn-confirm').on('click', function(){
				if (typeof(options.confirmCallback) == 'function'){
					options.confirmCallback();
				}
			});
			$('.dialog-wrap .btn-cancel').on('click', function(){
				close();
			});
		},
		dismiss: function(){
			close();
		}
	};
});