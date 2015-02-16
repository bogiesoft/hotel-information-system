<?php

/**
 * This is the model base class for the table "m_asset_aging".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "MAssetAging".
 *
 * Columns in table "m_asset_aging" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property integer $month
 * @property integer $year
 * @property integer $status
 *
 */
abstract class BaseMAssetAging extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'm_asset_aging';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'MAssetAging|MAssetAgings', $n);
	}

	public static function representingColumn() {
		return 'code';
	}

	public function rules() {
		return array(
			array('code, description, month, year, status', 'required'),
			array('month, year, status', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>5),
			array('description', 'length', 'max'=>50),
			array('id, code, description, month, year, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'code' => Yii::t('app', 'Code'),
			'description' => Yii::t('app', 'Description'),
			'month' => Yii::t('app', 'Month'),
			'year' => Yii::t('app', 'Year'),
			'status' => Yii::t('app', 'Status'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('code', $this->code, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('month', $this->month);
		$criteria->compare('year', $this->year);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}