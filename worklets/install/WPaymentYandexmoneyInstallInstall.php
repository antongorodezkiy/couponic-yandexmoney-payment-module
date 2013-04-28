<?php
class WPaymentYandexmoneyInstallInstall extends UInstallWorklet
{
	public function taskModuleParams()
	{
		return CMap::mergeArray(parent::taskModuleParams(),array(
			'name' => 'Yandexmoney',
			'ClientId' => '',
			'ClientSecret' => '',
			'RedirectUrl' => '',
			'ReceiverWallet' => ''
		));
	}
}