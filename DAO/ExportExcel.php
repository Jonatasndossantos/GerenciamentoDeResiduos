<?php
namespace PHP\Modelo\DAO;

require 'vendor/autoload.php'; // You'll need PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel {
    public function exportTrimesterData($trimester, $year) {
        $conexao = new Conexao();
        $conn = $conexao->conectar();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'Data');
        $sheet->setCellValue('B1', 'Categoria');
        $sheet->setCellValue('C1', 'Peso (kg)');
        
        // Get data
        $startDate = date("Y-m-d", mktime(0, 0, 0, ($trimester * 3) - 2, 1, $year));
        $endDate = date("Y-m-d", mktime(0, 0, 0, ($trimester * 3) + 1, 0, $year));
        
        $sql = "SELECT dt, categoria, peso 
                FROM residuos 
                WHERE dt BETWEEN '$startDate' AND '$endDate'
                ORDER BY dt";
                
        $result = mysqli_query($conn, $sql);
        
        $row = 2;
        while($data = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A'.$row, $data['dt']);
            $sheet->setCellValue('B'.$row, $data['categoria']);
            $sheet->setCellValue('C'.$row, $data['peso']);
            $row++;
        }
        
        // Create Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = "residuos_${trimester}trim_$year.xlsx";
        $writer->save($filename);
        
        return $filename;
    }
}