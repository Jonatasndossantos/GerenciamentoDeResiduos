<?php
namespace PHP\Modelo\DAO;

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportExcel {
    public function importData($file) {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        $conexao = new Conexao();
        $conn = $conexao->conectar();
        
        // Skip header row
        array_shift($rows);
        
        foreach($rows as $row) {
            $dt = mysqli_real_escape_string($conn, $row[0]);
            $categoria = mysqli_real_escape_string($conn, $row[1]);
            $peso = floatval($row[2]);
            
            $sql = "INSERT INTO residuos (dt, categoria, peso) 
                    VALUES ('$dt', '$categoria', $peso)";
            mysqli_query($conn, $sql);
        }
    }
}