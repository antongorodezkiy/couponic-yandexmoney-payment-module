<?php
/*
	**************************************************
	yandex money payment module for Couponic
	
	(c) antongorodezkiy@gmail.com © 2012
	Version 1.0
	**************************************************
*/

include_once('yamoney/ym.php');

class YM
{
	
	private $ClientId = '';
	private $ClientSecret = '';
	private $RedirectUrl = '';
	private $ReceiverWallet = '';
	private $ShopName = '';
	private $Certificate;
	private $token;
	
	public $error;
	
	function __construct($config)
	{
		$this->ClientId = isset($config['ClientId']) && $config['ClientId'] != '' ? $config['ClientId'] : '';
		$this->ClientSecret = isset($config['ClientSecret']) && $config['ClientSecret'] != ''  ? $config['ClientSecret'] : '';
		$this->RedirectUrl = isset($config['RedirectUrl']) && $config['RedirectUrl'] != ''  ? $config['RedirectUrl'] : '';
		$this->ReceiverWallet = isset($config['ReceiverWallet']) && $config['ReceiverWallet'] != ''  ? $config['ReceiverWallet'] : '';
		
		$this->Certificate = dirname(__FILE__).'/yamoney/ym.crt';
	}
	
	function getAuthorizeLink()
	{
		
		$scopes = array(
			'account-info',
			'operation-history',
			'operation-details',
			'payment-p2p',
			'money-source("wallet","card")'
		);

		return YandexMoney::authorizeUri($this->ClientId, implode(' ',$scopes), $this->RedirectUrl);
	}
	

	function authorize($code)
	{
		$ym = new YandexMoney($this->ClientId, $this->Certificate, $this->ClientSecret);
		return $this->token = $ym->receiveOAuthToken($code, $this->RedirectUrl);
	}
	
	
	function checkBalance($neededBalance)
	{
		$ym = new YandexMoney($this->ClientId, $this->Certificate);
		$accountInfoResponse = $ym->accountInfo($this->token);	
	
		//echo 'Номер счета: ' . $accountInfoResponse->getAccount() . "\n";
		return $accountInfoResponse->getBalance() >= $neededBalance;
		//echo 'Код валюты: ' . $accountInfoResponse->getCurrency() . "\n";	
	}

	
	
	
	function doPay($order)
	{
		$ym = new YandexMoney($this->ClientId, $this->Certificate);
		$request = $ym->requestPaymentP2P($this->token, $this->ReceiverWallet, $order['sum'], Yii::app()->t('Заказ').' №'.$order['order_id'].' на сумму '.$order['sum'].Yii::app()->t(' в магазине ').Yii::app()->name, $order['description']);
		
		if ($request->getStatus() != 'success')
		{
			$this->error = $request->getError();
			return false;
		}
		else
		{
			$process = $ym->processPayment($this->token, $request->getRequestId());
			
			if ($process->getStatus() != 'success')
			{
				$this->error = $process->getError();
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	
	
	
}
