<?php
class ChargeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'users'=>array('@'),
			),
			array('deny')
		);
	}
	
	/**
	 * 充值页面
	 */
	public function actionIndex($exam_bank_id) {
		// exam bank model
		$examBankModel = ExamBankModel::model()->findByPk($exam_bank_id);
		// 获取到期时间
		$uid = Yii::app()->user->id;
		$paymentModel = PaymentModel::model()->find('user_id=:uid and exam_bank_id=:ebid', array(':uid'=>$uid, 'ebid'=>$exam_bank_id));
		$res = array();
		$res['expiry'] = $paymentModel === null ? '已过期' : $paymentModel->expiry;
		$res['examBankModel'] = $examBankModel;
		$res['paymentModel'] = $paymentModel;
		$this->render('charge', $res);
	}
	
	public function actionPay($exam_bank_id, $chargeMonth){
		if (!is_int(intval($chargeMonth)) || $chargeMonth <= 0){
			throw new CHttpException(400, '请检查要充值的月数');
		}
		// exam bank model
		$examBankModel = ExamBankModel::model()->findByPk($exam_bank_id);
		// 获取到期时间
		$uid = Yii::app()->user->id;
		$price = $examBankModel->price * $chargeMonth;
		$res = array();
		$res['price'] = $price;
		$this->render('pay', $res);
	}
}
