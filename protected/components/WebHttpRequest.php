<?php
class WebHttpRequest extends CHttpRequest
{
	public $csrfUrl;
	
	public function validateCsrfToken($event)
	{
		if($this->getIsPostRequest() && !in_array($this->getUrl(), $this->csrfUrl))
		{
			$cookies=$this->getCookies();
			if($cookies->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName]))
			{
				$tokenFromCookie=$cookies->itemAt($this->csrfTokenName)->value;
				$tokenFromPost=$_POST[$this->csrfTokenName];
				$valid=$tokenFromCookie===$tokenFromPost;
			}
			else
				$valid=false;
			if(!$valid)
				throw new CHttpException(400,Yii::t('yii','The CSRF token could not be verified.'));
		}
	}
}