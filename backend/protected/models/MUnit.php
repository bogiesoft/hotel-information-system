<?php

Yii::import('application.models._base.BaseMUnit');

class MUnit extends BaseMUnit
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}