<?php
$this->sideNav=array(
	array('label'=>'真题推荐', 'url'=>array('/admin/paperRecommendation/index', 'subject_id'=>$subjectModel->subject_id)),
	array('label'=>'考点树设置', 'url'=>array('/admin/examPoint/index', 'subject_id'=>$subjectModel->subject_id)),
	array('label'=>'试卷管理', 'url'=>array('/admin/examPaper/index', 'subject_id'=>$subjectModel->subject_id)),
	array('label'=>'试题管理', 'url'=>array('/admin/question/index', 'subject_id'=>$subjectModel->subject_id))
);
