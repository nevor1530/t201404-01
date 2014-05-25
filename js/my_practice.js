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
});