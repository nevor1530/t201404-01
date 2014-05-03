<?php
$this->breadcrumbs=array(
	$subjectModel->examBank->name=>array('/admin'),
	$subjectModel->name=>array('/admin/subject/view', 'id'=>$subjectModel->subject_id),
	'考点树'
);

$this->menu=array(
	array('label'=>'创建顶级考点', 'url'=>array('/admin/examPoint/ajaxCreate', 'subject_id'=>$subjectModel->subject_id),
										'linkOptions'=>array(
															'id'=>'create-top-point')
									),
	array('label'=>'Manage ExamPointModel','url'=>array('admin')),
);

$baseUrl=$this->module->assetsUrl;
Yii::app()->getClientScript()->registerScriptFile($baseUrl.'/js/admin.js');
?>

<h1>考点树管理</h1>

<?php $this->widget('CTreeView',array(
	'data'=>$data,
)); ?>

<div id="topPoint" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>增加顶级节点</h3>
  </div>
  <div class="modal-body">
  </div>
</div>

<script type="text/javascript">
		
	$(function(){
		var $modal = $('#topPoint');
 
		$('#create-top-point').on('click', function(e){
			e.preventDefault();
			var $this = $(this);
			$modal.find('.modal-body').load($this.attr('href'), function(){
				$modal.modal();
			});
		});
	});
</script>