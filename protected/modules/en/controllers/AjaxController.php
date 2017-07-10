<?php
class AjaxController extends Controller
{
	public function actionUpfile()
	{
		$jpgtype = array('jpg', 'gif', 'png', 'jpeg', 'tif');
		$filetype = array('xls','xlsx', 'jpg', 'rar', 'zip', 'pdf','txt','doc','docx');
		if (Yii::app()->request->getIsPostRequest())
		{
			$file = CUploadedFile::getInstanceByName('file');
			$type = Yii::app()->request->getPost('type', '');
			if ($file)
			{
				$ext = strtolower($file->getExtensionName());
		
				if (in_array($ext, $jpgtype) || in_array($ext, $filetype))
				{
					$filename = time();
					$filedir = $filename % 10;
					$uploaddir = Yii::getPathOfAlias('webroot.upload.' . $filedir) . DIRECTORY_SEPARATOR;
					$filename = md5($file->getName()) . '_' . $filename;
					
					if ($file->saveAs($uploaddir . $filename. '.' . $ext))
					{
						$document = new Document();
						$document->type = $type;
						$document->name = $file->getName();
						$document->path = $filedir . '/' . $filename . '.' . $ext;
						$document->save();
						
						echo CJSON::encode($document);
					}
				}
			}
		}
	}
}
