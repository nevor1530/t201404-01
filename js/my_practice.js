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
 *		$jQuery.mydialog.show(options);
 *
 */
$(function(){
	var rawhtml = '<div class="dialog-backdrop" style="z-index: 999;"></div>\
				<div class="dialog-wrap need-payment-dialog-wrap">\
					<div class="dialog">\
					    <div class="dialog-hd">\
					        <span class="title bold">{title}</span>\
					        <button class="close">×</button>\
					    </div>\
					    <div class="dialog-bd">\
					    	<div class="download-confirm-wrap">\
								<div class="download-confirm">\
								    <div class="shenlun-text">\
								    	{content}\
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
			var html = rawhtml.replace("{title}", options.title);
			html = html.replace("{content}", options.content);
			html = html.replace("{cancelBtnLabel}", options.cancelBtnLabel);
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

;(function($) {
  jQuery.fn.scrollFix = function(height, dir) {
    height = height || 0;
    height = height == "top" ? 0 : height;
    return this.each(function() {
      if (height == "bottom") {
        height = document.documentElement.clientHeight - this.scrollHeight;
      } else if (height < 0) {
        height = document.documentElement.clientHeight - this.scrollHeight + height;
      }
      var that = $(this),
        oldHeight = false,
		oldPosition = that.css('position'),
		oldTop = that.css('top'),
		oldLeft = that.css('left'),
        p, r, l = that.offset().left;
      dir = dir == "bottom" ? dir : "top"; //默认滚动方向向下
      if (window.XMLHttpRequest) { //非ie6用fixed


        function getHeight() { //>=0表示上面的滚动高度大于等于目标高度
          return (document.documentElement.scrollTop || document.body.scrollTop) + height - that.offset().top;
        }
        $(window).scroll(function() {
          if (oldHeight === false) {
            if ((getHeight() >= 0 && dir == "top") || (getHeight() <= 0 && dir == "bottom")) {
              oldHeight = that.offset().top - height;
              that.css({
                position: "fixed",
                top: height,
                left: l
              });
            }
          } else {
            if (dir == "top" && (document.documentElement.scrollTop || document.body.scrollTop) < oldHeight) {
              that.css({
                position: oldPosition,
				top: oldTop,
				left: oldLeft
              });
              oldHeight = false;
            } else if (dir == "bottom" && (document.documentElement.scrollTop || document.body.scrollTop) > oldHeight) {
              that.css({
                position: oldPosition,
				top: oldTop,
				left: oldLeft
              });
              oldHeight = false;
            }
          }
        });
      } else { //for ie6
        $(window).scroll(function() {
          if (oldHeight === false) { //恢复前只执行一次，减少reflow
            if ((getHeight() >= 0 && dir == "top") || (getHeight() <= 0 && dir == "bottom")) {
              oldHeight = that.offset().top - height;
              r = document.createElement("span");
              p = that[0].parentNode;
              p.replaceChild(r, that[0]);
              document.body.appendChild(that[0]);
              that[0].style.position = "absolute";
            }
          } else if ((dir == "top" && (document.documentElement.scrollTop || document.body.scrollTop) < oldHeight) || (dir == "bottom" && (document.documentElement.scrollTop || document.body.scrollTop) > oldHeight)) { //结束
            that[0].style.position = oldPosition;
            p.replaceChild(that[0], r);
            r = null;
            oldHeight = false;
          } else { //滚动
            that.css({
              left: l,
              top: height + document.documentElement.scrollTop
            })
          }
        });
      }
    });
  };
})(jQuery);