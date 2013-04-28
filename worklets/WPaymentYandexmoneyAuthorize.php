<?php
class WPaymentYandexmoneyAuthorize extends USystemWorklet
{

	public function accessRules()
	{
		return array(
			array('deny', 'users'=>array('?'))
		);
	}
	

	
	public function run($items,$orderId=null,$options=array())
	{
		include_once(dirname(__FILE__).'/../components/ym.php');
	
		$config = array(
			'ShopName' => Yii::app()->name,
			'ReceiverWallet' => $this->module->param('ReceiverWallet'),
			'ClientId' => $this->module->param('ClientId'),
			'ClientSecret' => $this->module->param('ClientSecret'),
			'RedirectUrl' => $this->module->param('RedirectUrl'),
		);
		
		// создаем эксземпляр
			$ym = new YM($config);
		
		// заполняем сессию оплаты
			$orderItems = array();
			$amount = 0;
			foreach($items as $key=>$val)
			{
				if(is_array($val))
				{
					$names[] = $val['name'];
					//$ids[] = $val['id'];
					
					$item = array(
						'l_name' => urlencode($val['name']),
						'l_amt' => $val['price'],
						'l_qty' => $val['quantity'],
					);
					$orderItems[] = $item;
					$amount+= $val['price']*$val['quantity'];
				}
			}

			Yii::app()->session['order_for_payment'] = array(
				'sum' => $amount,
				'order_id' => $orderId,
				'description' => implode(', ',$names),
			);
		
		// получаем ссылку и авторизуемся
			$redirectUrl = $ym->getAuthorizeLink();
		
		if (app()->request->isAjaxRequest)
		{
			wm()->get('base.init')->addToJson(array(
				'redirect' => $redirectUrl,
			));
		}
		else
			app()->request->redirect($redirectUrl);

	}
	
}