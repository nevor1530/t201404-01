<div class="container" style="margin-top: 50px;">
	<div>请选择在线支付方式（支付<span class="theme-origen"><?php echo $price;?></span>元）</div>
	<form>
		<div style="margin-top: 25px;">
			<div>支付宝支付</div>
			<div style="padding: 10px 0;">
				<div class="pay-item">
					<input type="radio" checked name="org" value="alipay" id="alipay"></input>
					<label for="alipay">
						<img class="pay-image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/alipay-icon.png"></img>
					</label>
				</div>
			</div>
		<div>
		<!-- 
		<div style="margin-top: 15px;">
			<div>网银支付</div>
			<div style="padding: 10px 0;">
				<div class="pay-item">
					<input type="radio"></input>
					<img class="pay-image" src="#"></img>
				</div>
				<div class="pay-item">
					<input type="radio"></input>
					<img class="pay-image" src="#"></img>
				</div>
				<div class="pay-item">
					<input type="radio"></input>
					<img class="pay-image" src="#"></img>
				</div>
				<div class="pay-item">
					<input type="radio"></input>
					<img class="pay-image" src="#"></img>
				</div>
				<div class="pay-item">
					<input type="radio"></input>
					<img class="pay-image" src="#"></img>
				</div>
				<div class="pay-item">
					<input type="radio"></input>
					<img class="pay-image" src="#"></img>
				</div>
			</div>
		</div>
		-->
		<input type="submit" class="btn1" value="确认支付" />
	</form>