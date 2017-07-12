<?php
class SiteController extends Controller
{
	
	public function actionIndex()
	{
		$order = 'wwwwwwwwwwwwwwwwwwwww!';
		//echo 333;exit;
		$this->render('index', array(
			'order' => $order,
		));
	}

	public function actionData()
	{
		$keyword = Yii::app()->wechat->getAccessToken();
		print_r($keyword);exit;
		$sql = "select var_name, var_value from config ";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		
		$result = CJSON::encode($data);
		echo $result;
	}

	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
				else
					$this->render('error', $error);
		}
	}


}