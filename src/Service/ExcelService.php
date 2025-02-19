<?php
namespace App\Service;

use App\DAO\ResiduoDAO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Model\Residuo;

class ExcelService {
    private $residuoDAO;
    
    public function __construct(ResiduoDAO $residuoDAO) {
        $this->residuoDAO = $residuoDAO;
    }
    
    public function exportarTrimestre($trimestre, $ano) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Cabeçalhos
        $sheet->setCellValue('A1', 'Data');
        $sheet->setCellValue('B1', 'Categoria');
        $sheet->setCellValue('C1', 'Peso (kg)');
        
        // Dados do trimestre
        $dados = $this->residuoDAO->obterDadosTrimestre($trimestre, $ano);
        
        $row = 2;
        while ($data = mysqli_fetch_assoc($dados)) {
            $sheet->setCellValue('A'.$row, $data['dt']);
            $sheet->setCellValue('B'.$row, $data['categoria']);
            $sheet->setCellValue('C'.$row, $data['peso']);
            $row++;
        }
        
        $writer = new Xlsx($spreadsheet);
        $filename = "residuos_${trimestre}trim_$ano.xlsx";
        $writer->save($filename);
        
        return $filename;
    }
    
    public function importarDados($arquivo) {
        $spreadsheet = IOFactory::load($arquivo);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Remove cabeçalho
        array_shift($rows);
        
        foreach($rows as $row) {
            $residuo = new Residuo();
            $residuo->setData($row[0]);
            $residuo->setCategoria($row[1]);
            $residuo->setPeso(floatval($row[2]));
            
            $this->residuoDAO->inserir($residuo);
        }
    }
} 