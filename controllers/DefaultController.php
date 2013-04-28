<?php
// в этом файле происходят основные действия
class DefaultController extends UController
{
	function actionPayout()
	{
		include_once(dirname(__FILE__).'/../components/ym.php');
	
		$config = array(
			'ShopName' => Yii::app()->name,
			'ReceiverWallet' => $this->module->param('ReceiverWallet'),
			'ClientId' => $this->module->param('ClientId'),
			'ClientSecret' => $this->module->param('ClientSecret'),
			'RedirectUrl' => $this->module->param('RedirectUrl'),
		);
		
		$ym = new YM($config);
		
		if (isset($_GET['code']))
		{
			$code = $_GET['code'];
			
			$payed = true;
			
			// validation
				if (!$ym->authorize($code))
				{
					app()->request->redirect(
						$this->createUrl('payment/yandexmoney/cancel',
						array('error' => $this->t('Ошибка авторизации')))
					);
				}
				elseif (!$ym->checkBalance(Yii::app()->session['order_for_payment']['sum']))
				{
					app()->request->redirect(
						$this->createUrl('payment/yandexmoney/cancel',
						array('error' => $this->t('Не достаточно денег для оплаты')))
					);
				}
			
			if ( $ym->doPay( Yii::app()->session->get('order_for_payment') ) )
			{
				// пополнили счет
				wm()->get('payment.order')->charge(Yii::app()->session['order_for_payment']['order_id'],true);
				
				app()->request->redirect(
					$this->createUrl('payment/yandexmoney/success')
				);
			}
			else
				app()->request->redirect(
					$this->createUrl('payment/yandexmoney/cancel',
					array('error' => $this->t($ym->error)))
				);
		}
		else
			app()->request->redirect(
				$this->createUrl('payment/yandexmoney/cancel',
				array('error' => $_GET['error']))
			);
		
		
	}

	

	
}