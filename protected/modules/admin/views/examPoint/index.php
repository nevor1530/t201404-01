<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'考点树管理'
);

$this->menu=array(
	array('label'=>'创建顶级考点', 'url'=>array('/admin/examPoint/ajaxCreate', 'subject_id'=>$subjectModel->subject_id),
										'linkOptions'=>array(
															'id'=>'create-top-point')
									),
);

$baseUrl=$this->module->assetsUrl;
Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/admin.js');
?>

<h1>考点树管理</h1>

<?php if (empty($data)):?>
	<p>当前没有考点数据</p>
<?php else:?>
	<?php $this->widget('ExamPointTreeView',array(
		'data'=>$data,
	)); ?>
<?php endif; ?>

<div id="topPoint" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="modal-title">增加顶级节点</h3>
  </div>
  <div class="modal-body">
  	<?php $this->renderPartial('ajax_form', array('model'=>$examPointModel))?>
  </div>
</div>

<script type="text/javascript">
	$(function(){
		var createUrl = "<?php echo Yii::app()->createUrl('/admin/examPoint/ajaxCreate'); ?>";
		var updateUrl = "<?php echo Yii::app()->createUrl('/admin/examPoint/ajaxUpdate', array('id'=>'_id_')); ?>";
		
		var $modal = $('#topPoint');
		var $form = $('#exam-point-model-form');
		
		$modal.modal({show: false});
 
		$('#create-top-point').on('click', function(e){
			e.preventDefault();
			$("#modal-title").html("增加顶级节点");
			$("#ExamPointModel_pid").val(0);
			$form.attr("action", createUrl);
			$modal.modal('show');
		});
		
		$(".add_sub_exam_point").live("click", function(e){
			e.preventDefault();
			$this = $(this);
			$("#modal-title").html("增加"+$this.attr('data-name')+"的节点");
			$("#ExamPointModel_pid").val($this.attr('data-id'));
			$form.attr("action", createUrl);
			$modal.modal('show');
		})
		
		$(".update_exam_point").live("click", function(e){
			e.preventDefault();
			$.get("<?php echo Yii::app()->createUrl('/admin/examPoint/ajaxModel');?>", 
					{id: $(this).attr('data-id')}, 
					function(data){
						if (data.status === 0){
							$("#modal-title").html("编辑节点 "+data.data.name);
							$("#ExamPointModel_name").val(data.data.name);
							$("#ExamPointModel_pid").val(data.data.pid);
							$("ExamPointModel_description").val(data.data.description);
							var url = updateUrl.replace(/_id_/, data.data.exam_point_id);
							$form.attr("action", url);
							$modal.modal('show');
						} else {
							alert(data.errMsg);
						}
			}, 'json');
			$("#ExamPointModel_pid").val($(this).attr('data-id'));
			$form.attr("action", createUrl);
			$modal.modal('show');
		});
		
		$(".delete_exam_point").live("click", function(e){
			e.preventDefault();
			$this = $(this);
			if (confirm("确定删除考点 "+$this.attr('data-name')+" 及其子考点吗？")){
				$.post($(this).attr('href'), function(){
					location.reload();
				})
			}
		});
		
		$(".exam_point_move_up").live("click", function(e){
			e.preventDefault();
			$this = $(this);
			$.post($this.attr("href"), function(data){
				if (data.status === 0){
					location.reload();
				} else {
					alert(data.errMsg);
				}
			}, "json");
		});
		
		$(".exam_point_move_down").live("click", function(e){
			e.preventDefault();
			$this = $(this);
			$.post($this.attr("href"), function(data){
				if (data.status === 0){
					location.reload();
				} else {
					alert(data.errMsg);
				}
			}, "json");
		});
		
		$(".exam_point_visible input").live("change", function(){
			$this = $(this);
			var url = "<?php echo Yii::app()->createUrl('/admin/examPoint/visible')?>";
			var data = {id: $this.attr('data-id')};
			data.value = $this.attr("checked") ? 1 : 0;
			$.post(url, data);
		});
		
		$("#topPoint").on("hidden", function(){
			$("#exam-point-model-form")[0].reset();
		})
		
		$("#topPoint").on("shown", function(){
			$("#ExamPointModel_name").focus();
		})
	});
</script>