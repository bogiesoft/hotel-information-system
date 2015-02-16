<?php

/**
 * GxController class file.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 * @link http://giix.org/
 * @copyright Copyright &copy; 2010-2011 Rodrigo Coelho
 * @license http://giix.org/license/ New BSD License
 */

/**
 * GxController is the base class for the generated controllers.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 */
abstract class GxController extends Controller {

    /**
     * @var string The layout for the controller view.
     */
    public $layout = '//layouts/column2';

    /**
     * @var array Context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array The breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * Returns the data model based on the primary key or another attribute.
     * This method is designed to work with the values passed via GET.
     * If the data model is not found or there's a malformed key, an
     * HTTP exception will be raised.
     * #MethodTracker
     * This method is based on the gii generated method controller::loadModel, from version 1.1.7 (r3135). Changes:
     * <ul>
     * <li>Support to composite PK.</li>
     * <li>Support to use any attribute (column) name besides the PK.</li>
     * <li>Support to multiple attributes.</li>
     * <li>Automatically detects the PK column names.</li>
     * </ul>
     * @param mixed $key The key or keys of the model to be loaded.
     * If the key is a string or an integer, the method will use the tables' PK if
     * the PK has a single column. If the table has a composite PK and the key
     * has a separator (see below), the method will detect it and use it.
     * <pre>
     * $key = '12-27'; // PK values with separator for tables with composite PK.
     * </pre>
     * If $key is an array, it can be indexed by integers or by attribute (column)
     * names, as for {@link CActiveRecord::findByAttributes}.
     * If the array doesn't have attribute names, as below, the method will use
     * the table composite PK.
     * <pre>
     * $key = array(
     *   12,
     *   27,
     *   ...,
     * );
     * </pre>
     * If the array is indexed by attribute names, as below, the method will use
     * the attribute names to search for and load the model.
     * <pre>
     * $key = array(
     *   'model_id' => 44,
     * 	 ...,
     * );
     * </pre>
     * Warning: each attribute used should have an index on the database and the set of
     * attributes used should identify only one item on the database (the attributes being
     * ideally part of or multiple unique keys).
     * @param string $modelClass The model class name.
     * @return GxActiveRecord The loaded model.
     * @see GxActiveRecord::pkSeparator
     * @throws CHttpException if there's an invalid request (with code 400) or if the model is not found (with code 404).
     */
    public function loadModel($key, $modelClass) {

        // Get the static model.
        $staticModel = GxActiveRecord::model($modelClass);

        if (is_array($key)) {
            // The key is an array.
            // Check if there are column names indexing the values in the array.
            reset($key);
            if (key($key) === 0) {
                // There are no attribute names.
                // Check if there are multiple PK values. If there's only one, start again using only the value.
                if (count($key) === 1)
                    return $this->loadModel($key[0], $modelClass);

                // Now we will use the composite PK.
                // Check if the table has composite PK.
                $tablePk = $staticModel->getTableSchema()->primaryKey;
                if (!is_array($tablePk))
                    throw new CHttpException(400, Yii::t('giix', 'Your request is invalid.'));

                // Check if there are the correct number of keys.
                if (count($key) !== count($tablePk))
                    throw new CHttpException(400, Yii::t('giix', 'Your request is invalid.'));

                // Get an array of PK values indexed by the column names.
                $pk = $staticModel->fillPkColumnNames($key);

                // Then load the model.
                $model = $staticModel->findByPk($pk);
            } else {
                // There are attribute names.
                // Then we load the model now.
                $model = $staticModel->findByAttributes($key);
            }
        } else {
            // The key is not an array.
            // Check if the table has composite PK.
            $tablePk = $staticModel->getTableSchema()->primaryKey;
            if (is_array($tablePk)) {
                // The table has a composite PK.
                // The key must be a string to have a PK separator.
                if (!is_string($key))
                    throw new CHttpException(400, Yii::t('giix', 'Your request is invalid.'));

                // There must be a PK separator in the key.
                if (stripos($key, GxActiveRecord::$pkSeparator) === false)
                    throw new CHttpException(400, Yii::t('giix', 'Your request is invalid.'));

                // Generate an array, splitting by the separator.
                $keyValues = explode(GxActiveRecord::$pkSeparator, $key);

                // Start again using the array.
                return $this->loadModel($keyValues, $modelClass);
            } else {
                // The table has a single PK.
                // Then we load the model now.
                $model = $staticModel->findByPk($key);
            }
        }

        // Check if we have a model.
        if ($model === null)
            throw new CHttpException(404, Yii::t('giix', 'The requested page does not exist.'));

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * #MethodTracker
     * This method is based on the gii generated method controller::performAjaxValidation, from version 1.1.7 (r3135). Changes:
     * <ul>
     * <li>Supports multiple models.</li>
     * </ul>
     * @param CModel|array $model The model or array of models to be validated.
     * @param string $form The name of the form. Optional.
     */
    protected function performAjaxValidation($model, $form = null) {
        if (Yii::app()->getRequest()->getIsAjaxRequest() && (($form === null) || ($_POST['ajax'] == $form))) {
            echo GxActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Finds the related primary keys specified in the form POST.
     * Only for HAS_MANY and MANY_MANY relations.
     * @param array $form The post data.
     * @param array $relations A list of model relations in the format returned by {@link CActiveRecord::relations}.
     * @param string $uncheckValue Since Yii 1.1.7, htmlOptions (in {@link CHtml::activeCheckBoxList})
     * has an option named 'uncheckValue'. If you set it to different values than the default value (''), you will
     * need to set the appropriate value to this argument. This method can't be used when 'uncheckValue' is null.
     * @return array An array where the keys are the relation names (string) and the values are arrays with the related model primary keys (int|string) or composite primary keys (array with pk name (string) => pk value (int|string)).
     * Example of returned data:
     * <pre>
     * array(
     *   'categories' => array(1, 4),
     *   'tags' => array(array('id1' => 3, 'id2' => 7), array('id1' => 2, 'id2' => 0)) // composite pks
     * )
     * </pre>
     * An empty array is returned in case there is no related pk data from the post.
     * This data comes directly from the form POST data.
     * @see GxHtml::activeCheckBoxList
     * @throws InvalidArgumentException If uncheckValue is null.
     */
    protected function getRelatedData($form, $relations, $uncheckValue = '') {
        if ($uncheckValue === null)
            throw new InvalidArgumentException(Yii::t('giix', 'giix cannot handle automatically the POST data if "uncheckValue" is null.'));

        $relatedPk = array();
        foreach ($relations as $relationName => $relationData) {
            if (isset($form[$relationName]) && (($relationData[0] == GxActiveRecord::HAS_MANY) || ($relationData[0] == GxActiveRecord::MANY_MANY)))
                $relatedPk[$relationName] = $form[$relationName] === $uncheckValue ? null : $form[$relationName];
        }
        return $relatedPk;
    }

    protected function beforeAction($action) {
        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;

        if ($controller == "site" && $action == "login") {
            return TRUE;
        }
        
        if ($controller == "notFound") {
            return TRUE;
        }
        
        /*if(Yii::app()->user->level != NULL){
            $module = Module::model()->findByAttributes(array("controller"=>$controller));
            $hakAkses = HakAkses::model()->findByAttributes(array("module_id"=>$module->id, "user_level_id"=>Yii::app()->user->level));
            if($hakAkses == NULL){
                //throw new CHttpException(404, 'Halaman Tidak Ditemukan.');
                $this->redirect(Yii::app()->request->baseUrl . "/notFound/");
                return FALSE;
            }
            
        }*/

        if (Yii::app()->user == NULL) {
            $this->redirect(Yii::app()->request->baseUrl . "/site/login");
        } else {
            if (Yii::app()->user->getId() != NULL) {
                $log = new UserLog();
                $log->time = date("Y-m-d H:i:s");
                $log->user_id = Yii::app()->user->id;
                $log->user_level_id = Yii::app()->user->level_id;
                $log->path = $_SERVER["REQUEST_URI"];
                $log->data = json_encode($_POST);
                $log->is_ajax = 0;
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $log->is_ajax = 1;
                }
                $log->save();
                return TRUE;
            } else {
                $this->redirect(Yii::app()->request->baseUrl . "/site/login");
            }
        }
    }

    protected function dataTables($aColumns, $modelClass, $customQuery=NULL) {

        $staticModel = GxActiveRecord::model($modelClass);
        $sIndexColumn = $staticModel->getTableSchema()->primaryKey;
        $namaTabel = $staticModel->tableName();

        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                            $_GET['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }


        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }
        
        if($customQuery != NULL){
            if(strlen(trim($sWhere))==0){
                $sWhere = "WHERE ".$customQuery;
            }else{
                $sWhere .= " AND ".$customQuery;
            }
        }


        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
		   SELECT `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
		   FROM   $namaTabel
		   $sWhere
		   $sOrder
		   $sLimit
		   ";
        //echo $sQuery;
        $rResult = Yii::app()->db->createCommand($sQuery)->queryAll();

        /* Data set length after filtering */
        $sQuery = "SELECT COUNT(*) AS FR
		   FROM   $namaTabel
		   $sWhere
		   $sOrder";
        $rResultFilterTotal = Yii::app()->db->createCommand($sQuery)->queryAll();
        foreach ($rResultFilterTotal as $v) {
            $aResultFilterTotal = $v;
        }
        $iFilteredTotal = $aResultFilterTotal["FR"];

        /* Total data set length */
        $sQuery = "
		   SELECT COUNT(`" . $sIndexColumn . "`) as total
		   FROM   $namaTabel";
        if($customQuery != NULL){
            $sQuery .= " WHERE ".$customQuery;
        }
        
        $rResultTotal = Yii::app()->db->createCommand($sQuery)->queryAll();
        $i = 0;
        $iTotal = 0;
        foreach ($rResultTotal as $v) {
            $iTotal = $v["total"];
        }

        return array(
            "result" => $rResult,
            "total" => $iTotal,
            "filtertotal" => $iFilteredTotal,
        );
    }

    
    public function reverseDate($date) {
        $arr = explode("-", $date);
        $arr = array_reverse($arr);
        return implode("-", $arr);
    }
    
    public function getIcon(){
        $controller = Yii::app()->controller->id;
        $data = Module::model()->findByAttributes(array("controller"=>$controller));
        return "<i class='".$data->icon."'></i>";
    }
    
    public function actionDeleteAntrian($id = NULL){
        $ta = TransaksiAntrian::model()->findByPk($id);
        $ta->status = 0;
        $ta->save();
    }
    
    public function printReport($view, $option, $isPDF=FALSE){
        if($isPDF){
            include_once(YiiBase::getPathOfAlias("application.vendors.mpdf").'/mpdf.php');
            $mpdf=new mPDF( '',                          // mode (default '')
                            'A4-L',  0, '',               // format ('A4', '' or...), font size(default 0), font family
                            2, 2, 2, 2,     9,  9,   //15, 15, 16, 16,     9,  9,  //(margins) left, right, top, bottom, HEADER, FOOTER
                            'L');

            ob_start();
            $this->renderPartial($view, $option);
            $output = ob_get_contents();
            ob_end_clean();
            $mpdf->WriteHTML($output);
            $mpdf->Output('Disposisi', 'I');
        }else{
            $this->renderPartial($view, $option);
        }
    }
}
