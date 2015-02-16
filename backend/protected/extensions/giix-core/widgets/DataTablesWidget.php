<?php

class DataTablesWidget extends CWidget
{
    /**
     * @var CFormModel
     */
    public $dataProvider;

    public function run()
    {
        $this->render('dataTables', array( 'dataProvider' => $this->dataProvider ));
    }
}

?>