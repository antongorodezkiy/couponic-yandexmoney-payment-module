<?php
Yii::import('application.modules.payment.modules.yandexmoney.worklets.WPaymentYandexmoneyAuthorize',true);
class WPaymentYandexmoneyPay extends WPaymentYandexmoneyAuthorize
{
	public $paymentAction = 'sale';
}