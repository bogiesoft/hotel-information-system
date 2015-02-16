<?php

Yii::import('application.models._base.BaseMCustomer');

class MCustomer extends BaseMCustomer
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}