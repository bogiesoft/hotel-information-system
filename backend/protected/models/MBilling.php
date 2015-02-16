<?php

Yii::import('application.models._base.BaseMBilling');

class MBilling extends BaseMBilling
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}