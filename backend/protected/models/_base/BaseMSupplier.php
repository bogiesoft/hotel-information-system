<?php

/**
 * This is the model base class for the table "m_supplier".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "MSupplier".
 *
 * Columns in table "m_supplier" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $contact_person
 * @property string $address
 * @property string $city
 * @property string $province
 * @property string $post_code
 * @property string $nation
 * @property string $description
 * @property string $phone_1
 * @property string $phone_2
 * @property string $fax
 *
 */
abstract class BaseMSupplier extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'm_supplier';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'MSupplier|MSuppliers', $n);
	}

	public static function representingColumn() {
		return 'code';
	}

	public function rules() {
		return array(
			array('code, name, contact_person, address, city, province, post_code, nation, description, phone_1, phone_2, fax', 'required'),
			array('code, post_code', 'length', 'max'=>5),
			array('name, contact_person, nation', 'length', 'max'=>50),
			array('address', 'length', 'max'=>200),
			array('city, province, phone_1, phone_2, fax', 'length', 'max'=>20),
			array('id, code, name, contact_person, address, city, province, post_code, nation, description, phone_1, phone_2, fax', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('app', 'Name'),
			'contact_person' => Yii::t('app', 'Contact Person'),
			'address' => Yii::t('app', 'Address'),
			'city' => Yii::t('app', 'City'),
			'province' => Yii::t('app', 'Province'),
			'post_code' => Yii::t('app', 'Post Code'),
			'nation' => Yii::t('app', 'Nation'),
			'description' => Yii::t('app', 'Description'),
			'phone_1' => Yii::t('app', 'Phone 1'),
			'phone_2' => Yii::t('app', 'Phone 2'),
			'fax' => Yii::t('app', 'Fax'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('code', $this->code, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('contact_person', $this->contact_person, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('city', $this->city, true);
		$criteria->compare('province', $this->province, true);
		$criteria->compare('post_code', $this->post_code, true);
		$criteria->compare('nation', $this->nation, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('phone_1', $this->phone_1, true);
		$criteria->compare('phone_2', $this->phone_2, true);
		$criteria->compare('fax', $this->fax, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}