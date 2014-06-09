<form method="GET" action="<?php echo Yii::app()->createUrl('charge/pay')?>">
	<?php echo CHtml::hiddenField('exam_bank_id', $examBankModel->exam_bank_id);?>
	<table class="charge-table">
		<tr>
			<td class="title">续费对象：</td>
			<td><span class="exam-bank"><?php echo $examBankModel->name;?></span></td>
		</tr>
		<tr>
			<td class="title">当前状态：</td>
			<td>账号有效，过期时间为<span class="theme-origen"><?php echo $expiry;?></span></td>
		</tr>
		<tr>
			<td class="title">续费方案：</td>
			<td>
				<span class="theme-origen"><?php echo $examBankModel->price;?></span>
				元/月  X 
				<input class="theme-origen" style="width: 60px; text-align: center;" id="chargeMonth" name="chargeMonth" value="1" data-price="<?php echo $examBankModel->price;?>"/>月</td>
		</tr>
		<tr>
			<td class="title">续费金额：</td>
			<td><span class="theme-origen" id="price"><?php echo $examBankModel->price;?></span>元</td>
		</tr>
		<tr>
			<td colspan="2" class="text-center"><input class="btn1" type="submit" value="立即续费"/></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	$(function(){
		$("#chargeMonth").on("input", function(){
			$this = $(this);
			var price = $this.data("price");
			$("#price").text(price * $this.val());
		});
	});
</script>