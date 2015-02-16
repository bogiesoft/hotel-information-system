<?php

Yii::import('application.models._base.BaseMUnitConversion');

class MUnitConversion extends BaseMUnitConversion
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}