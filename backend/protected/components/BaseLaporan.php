<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseLaporan
 *
 * @author feb
 */
class BaseLaporan {

    public function getHuruf($num) {
        $arr = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N"
            , "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        return $arr[$num];
    }

    public function loadPHPExcelLib($reportName) {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes.PHPExcel');
        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'IOFactory.php');
        spl_autoload_register(array('YiiBase', 'autoload'));

        $reportPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . "report" . DIRECTORY_SEPARATOR;

        return PHPExcel_IOFactory::load($reportPath . $reportName . ".xlsx");
    }

    protected function previewFile($data) {
        $objPHPExcel = $data["phpexcel"];
        $report = $data["name"];
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
        ?>
        <div class="mws-panel grid_8">
            <div class="mws-panel-header">
                <span><i class="icon-search"></i> Preview</span>
            </div>
            <div class="mws-panel-body no-padding">
                <?php
                ob_start();
                $objWriter->save('php://output');
                $output = ob_get_contents();
				ob_end_clean();
				$output = str_replace("</body>", "</body >", $output);
				echo $output;
                ?>
            </div>
        </div>   
        <?php
    }

    public function downloadFile($data, $saveFirst=FALSE) {
        $objPHPExcel = $data["phpexcel"];
        $report = $data["name"];
        $outputPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . "output" . DIRECTORY_SEPARATOR;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $report . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        if($saveFirst == FALSE){
            $objWriter->save('php://output');
        }else{
            $objWriter->save($outputPath . $report . ".xls");
            header("Location: " . Yii::app()->request->baseUrl . "/output/" . $report . ".xls");
        }
    }

    public function generateReport($objPHPExcel, $report, $type, $isRedirect = TRUE) {
        $outputPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . "output" . DIRECTORY_SEPARATOR;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($outputPath . $report . ".xls");

        if ($isRedirect) {
            header("Location: " . Yii::app()->request->baseUrl . "/output/" . $report . ".xls");
        }
        return "/output/" . $report . ".xls";
    }

    public function getHeaderStyle() {
        $headerBorder = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
            //'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
            //'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
            //'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
            //'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
            ),
        );

        return $headerBorder;
    }

    public function getNormalStyle() {
        $normalBorder = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'FFFFFFFF')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font' => array(
                'bold' => false,
                'size' => 12,
            )
        );
        return $normalBorder;
    }
    
    public function fillBorder($activeSheet, $loc){
        $activeSheet->getStyle($loc)->applyFromArray(array(
            'borders' => array(
              'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              )
            )
        ));
    }

    public function reverseDate($date) {
        return implode("-", array_reverse(explode("-", $date)));
    }

}
