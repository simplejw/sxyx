<?php
class Controller extends CController
{

	public function init()
	{

	}

	public function render($view, $data = null, $return = false, $options = null)
	{
		$output = parent::renderPartial($view, $data, true);

		if (!YII_DEBUG)
		{
			$compactor = Yii::app()->contentCompactor;
			if ($compactor == null)
				throw new CHttpException(500, Yii::t('messages', 'Missing component ContentCompactor in configuration.'));

			$output = $compactor->compact($output, $options);
		}

		if ($return) return $output;
		else echo $output;
	}
	
}