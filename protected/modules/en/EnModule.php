<?php

class EnModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
				'en.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}