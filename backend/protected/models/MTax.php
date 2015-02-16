<?php

Yii::import('application.models._base.BaseMTax');

class MTax extends BaseMTax
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}