<?php
class YandexmoneyModule extends UWebModule
{	
	public function getTitle()
	{
		return 'YandexMoney';
	}
	
	public function getRequirements()
	{
		return array('payment' => self::getVersion());
	}
}
