<?php

Yii::import('application.models._base.BaseUserLevel');

class UserLevel extends BaseUserLevel
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}