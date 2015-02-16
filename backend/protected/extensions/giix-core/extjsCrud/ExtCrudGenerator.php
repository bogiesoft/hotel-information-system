<?php

class ExtCrudGenerator extends CCodeGenerator {

	public $codeModel = 'ext.giix-core.extjsCrud.ExtCrudCode';

	
	protected function getModels() {
		$models = array();
		$files = scandir(Yii::getPathOfAlias('application.models'));
		foreach ($files as $file) {
			if ($file[0] !== '.' && CFileHelper::getExtension($file) === 'php') {
				$fileClassName = substr($file, 0, strpos($file, '.'));
				if (class_exists($fileClassName) && is_subclass_of($fileClassName, 'GxActiveRecord')) {
					$fileClass = new ReflectionClass($fileClassName);
					if (!$fileClass->isAbstract())
						$models[] = $fileClassName;
				}
			}
		}
		return $models;
	}

}