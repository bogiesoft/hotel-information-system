<?php

Yii::import('application.models._base.BaseMCashBank');

class MCashBank extends BaseMCashBank
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}