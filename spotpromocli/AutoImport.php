<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AutoImport 
{
    public function getColsName($table, $cols )
    {
        $select = explode('-', $cols);
        $selectedCols = [];
            foreach ($select as $key => $value) {
                    array_push($selectedCols, $table[$value]['colunas']);
            }
        return $selectedCols;
    }
    public function gerarExcel($colsnames, $path, $excelName)
    {
        $spreadsheet = new Spreadsheet();
        // Define o título da planilha
        $spreadsheet->getProperties()->setTitle($excelName);
        // Cria uma nova planilha
        $sheet = $spreadsheet->getActiveSheet();
        //realiza a s iterações de acordo com as colunas
        $startCols = 1;
        $startlLetters = 0;
        $letters = range('A', 'Z');
        // Adiciona alguns dados à planilha
        foreach ($colsnames as $key => $value) {
            if($startlLetters == count($letters)){
                $startCols++;
            };
            $sheet->setCellValue($letters[$startlLetters].$startCols, $value); 
            $startlLetters++;
        }
        // Cria um objeto de escrita para o arquivo xlsx
        $writer = new Xlsx($spreadsheet);
        // Define o caminho do arquivo onde o arquivo xlsx será salvo
        $arquivoXlsx = $path.$excelName.'.xlsx';
        // Salva o arquivo xlsx
        $writer->save($arquivoXlsx);
        return true;
    }
    public function htmlTable($filename, $resultSetCols)
    {
        // definir os tipo para o texto
        $types=[
            'int'=>'númerico',
            'varchar'=> 'texto'
        ];
        // aparte que deve ser copiada
        $html = '<tr> <!-- comentario -->
        <td>
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="text-primary"> *** NOME DA IMPORTACAO *** </h4><br>'."\n";
        foreach ($resultSetCols as $key => $value) {
            $html .= '<b> '. $value['COLUMN_NAME']. ': </b> ['.$types[$value['DATA_TYPE']].'] Exportar <a href="home.php?module=ZZZ/YYY"target="_blank"><u><i> AAA | BBB | CCC </i></u></a> e usar a primeira coluna;<br>'."\n";
        };
        $html.='</div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    Baixar aquivo de exemplo <b><a href="<?= $full_url ?>/<?= PASTA_PRINCIPAL ?>/modules/importar_arquivos/archives/'.$filename.'.xlsx">aqui</a></b>
                    <br>
                </div>
            </div>
        </td>
    </tr>';
    return $html;
    }
    public static function helpText()
    {
        return '';
    }
}