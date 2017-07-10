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
		$sql = "select var_name, var_value from config ";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		
		$result = CJSON::encode($data);
		echo $result;
	}

	
	public function actionError()
	{
		
	}


}