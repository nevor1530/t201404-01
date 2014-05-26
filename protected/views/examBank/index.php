<div class="exam-bank-page">
	<?php foreach($examBanks as $examBank):?>
		<div class="item">
			<img src="<?php echo Yii::app()->baseUrl . "/" . $examBank['icon'];?>" class="exam-bank-icon"/>
			<div class="exam-bank-text">
				<div class="title"><?php echo $examBank['name'] ?></div>
				<div class="paper-qty">共<?php echo $examBank['real_exam_paper_count'] ?>套真卷</div>
				<div class="question-qty"><?php echo $examBank['question_count'] ?>道题</div>
				<button class="btn btn-primary disabled" onclick="{location.href='<?php echo Yii::app()->createUrl("/examPoint/index", array("exam_bank_id"=> $examBank['id']))?>'}">马上去答题</button>
			</div>
		</div>
	<?php endforeach;?>
</div>