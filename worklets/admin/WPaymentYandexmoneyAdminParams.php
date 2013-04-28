<?php
class WPaymentYandexmoneyAdminParams extends UParamsWorklet
{
	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users'=>array('*'))
		);
	}
	
	public function title()
	{
		return;
	}
	
	public function properties()
	{
		return array(
			'elements' => array(
				$this->render('apiInfo',null,true),
				'name' => array('type' => 'text', 'label' => $this->t('Name')),
				'<h4>'.$this->t('YandexMoney account info').'</h4>',
				'ReceiverWallet' => array('type' => 'text', 'label' => $this->t('ЯД.Кошелек магазина')),
				'ClientId' => array('type' => 'text', 'label' => $this->t('Client ID')),
				'ClientSecret' => array('type' => 'text', 'label' => $this->t('Client secret')),
				'RedirectUrl' => array('type' => 'text', 'label' => $this->t('Redirect URL')),
			),
			'buttons' => array(
				'submit' => array('type' => 'submit', 'label' => $this->t('Save'))
			),
			'model' => $this->model
		);
	}
}