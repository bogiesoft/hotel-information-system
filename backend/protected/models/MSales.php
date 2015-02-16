<?php

Yii::import('application.models._base.BaseMSales');

class MSales extends BaseMSales
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}