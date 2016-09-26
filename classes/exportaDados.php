<?php
require_once 'consultas.php';

session_start();
$datainicial = $_SESSION['datainicial'];
$datafinal = $_SESSION['datafinal'];

$consulta = new Consulta;
$inventario = $consulta->exportaInventario($datainicial, $datafinal);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

/** Include PHPExcel */
require_once dirname(__FILE__) . './PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Dashboard DTI")
            ->setLastModifiedBy("Dashboard DTI")
            ->setTitle("Movimentações de Inventário")
            ->setSubject("Movimentações de Inventário")
            ->setDescription("Movimentações de Inventário");
            //->setKeywords("office 2007 openxml php")
            //->setCategory("Test result file");

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Patrimônio')
        ->setCellValue('B1', 'Número da Movimentação')
        ->setCellValue('C1', 'Tipo')
        ->setCellValue('D1', 'Instituição Origem')
        ->setCellValue('E1', 'Unidade Origem')
        ->setCellValue('F1', 'Local Origem')
        ->setCellValue('G1', 'Status Origem')
        ->setCellValue('H1', 'Instituição Destino')
        ->setCellValue('I1', 'Unidade Destino')
        ->setCellValue('J1', 'Local Destino')
        ->setCellValue('K1', 'Status Destino')
        ->setCellValue('L1', 'Data da Movimentação');

$index = 2;
// Add some data
while ($linha = mysql_fetch_array($inventario)) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$index."", utf8_encode($linha[0]))
            ->setCellValue('B'.$index."", utf8_encode($linha[1]))
            ->setCellValue('C'.$index."", utf8_encode($linha[2]))
            ->setCellValue('D'.$index."", utf8_encode($linha[3]))
            ->setCellValue('E'.$index."", utf8_encode($linha[4]))
            ->setCellValue('F'.$index."", utf8_encode($linha[5]))
            ->setCellValue('G'.$index."", utf8_encode($linha[6]))
            ->setCellValue('H'.$index."", utf8_encode($linha[7]))
            ->setCellValue('I'.$index."", utf8_encode($linha[8]))
            ->setCellValue('J'.$index."", utf8_encode($linha[9]))
            ->setCellValue('K'.$index."", utf8_encode($linha[10]))
            ->setCellValue('L'.$index."", utf8_encode($linha[11]));
    $index++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(45);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(21);


//$objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', 'Hello')
//            ->setCellValue('B2', 'world!')
//            ->setCellValue('C1', 'Hello')
//            ->setCellValue('D2', 'world!');

//// Miscellaneous glyphs, UTF-8
//$objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A4', 'Miscellaneous glyphs')
//            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Movimentações Inventário');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Movimentações Inventário.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

//unset($_SESSION['datainicial']);
//unset($_SESSION['datafinal']);

exit;



