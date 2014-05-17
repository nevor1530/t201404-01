<style type="text/css">
.exam-bank-section {
	padding:10px 90px 10px 10px;
	background-color:#f4f9ff;
	border-style: solid;
	border-width: 1px;
	border-color: #e4e9ff;
}

.title {
	font-family: "Microsoft YaHei" !important;
	font-weight: 500;
	font-size: 16px;
	letter-spacing: 1px;
}

.paper-qty {
	font-family: "Microsoft YaHei" !important;
	font-weight: 500;
	font-size: 14px;
	letter-spacing: 1px;
	margin-top: 4px;
}

.question-qty {
	font-family: "Microsoft YaHei" !important;
	font-weight: 500;
	font-size: 14px;
	letter-spacing: 1px;
}

.btn {
	padding-bottom: 4px;
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 4px;
    box-shadow: 0 -4px 0 #2A6496 inset;
    border-radius: 6px;
    background-color: #4391e3;
    color: #FFFFFF;
    font-size: 12px;
    letter-spacing: 1px;
    border-width: 0;
    margin-top: 6px;
    font-weight: 700;
}
</style>

<?php 
for ($i = 0; $i < count($examBanks); $i++) {
	$examBank = $examBanks[$i];
	$sectionStyle = ($i % 2 == 0 ? "float:right" : "float:left");
	if ($i % 2 == 0) { 
?>
<div class="row" style="margin-top: 50px;">
<?php } ?>
	<div class="span6">
		<div class="exam-bank-section" style="<?php echo $sectionStyle ?>">
			<img src="<?php echo Yii::app()->baseUrl . "/" . $examBank['icon'];?>" style="height:110px"/>
			<div style="float:right;margin-top:10px;margin-left:15px;">
				<div class="title"><?php echo $examBank['name'] ?></div>
				<div class="paper-qty">共<?php echo $examBank['real_exam_paper_count'] ?>套真卷</div>
				<div class="question-qty"><?php echo $examBank['question_count'] ?>道题</div>
				<button class="btn btn-primary" name="yt0" type="submit">马上去答题</button>
			</div>
		</div>
	</div>
<?php if ($i % 2 ==1) { ?>
</div>
<?php }} ?>
