<?php
class WPaymentYandexmoneyCancel extends UWidgetWorklet
{
	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	
	public function taskConfig()
	{
		parent::taskConfig();
	}
	
	public function taskRenderOutput()
	{	
		$order_id = Yii::app()->session['order_for_payment']['order_id'];
		// списали счет
		wm()->get('payment.order')->void($order_id);
		
		unset(Yii::app()->session['order_for_payment']);
		
		$this->render('cancel_bill');
	}
}