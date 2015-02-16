<?php

Yii::import('application.models._base.BaseUserLog');

class UserLog extends BaseUserLog
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}