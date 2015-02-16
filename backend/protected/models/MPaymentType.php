<?php

Yii::import('application.models._base.BaseMPaymentType');

class MPaymentType extends BaseMPaymentType
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}