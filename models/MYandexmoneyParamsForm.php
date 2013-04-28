<?php
class MYandexmoneyParamsForm extends UFormModel
{
	public $name;
	public $ClientId;
	public $ClientSecret;
	public $RedirectUrl;
	public $ReceiverWallet;

	public static function module()
	{
		return 'payment.yandexmoney';
	}
	
	public function rules()
	{
		return array(
			array(implode(',',array_keys(get_object_vars($this))),'safe')
		);
	}
}