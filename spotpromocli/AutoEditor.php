<?php

class AutoEditor
{
    public function getColums($filename)
    {
        // Abre o arquivo para leitura
        $arquivo = fopen($filename, 'r');
        // Verifica se o arquivo foi aberto com sucesso
        if (!$arquivo) {
            die("Não foi possível abrir o arquivo.\n");
        }    
        // Array para armazenar as linhas correspondentes ao padrão
        $linhasEncontradas = [];
        // Padrões a serem procurados
        $padroes = array('/Field::inst(\s*\.\s*)/', '/Field::inst(\s*\s*)/');
        // Percorre o arquivo linha por linha
        while (!feof($arquivo)) {
            $linha = fgets($arquivo);
            // Verifica se a linha corresponde a algum dos padrões
            foreach ($padroes as $padrao) {
                if (preg_match($padrao, $linha)) {
                    $linhasEncontradas[] = $linha;
                    break;
                    }
                }
            }    
        // Fecha o arquivo
        fclose($arquivo);        
        // Retorna as linhas encontradas
        return $linhasEncontradas;
    }
    public function  CleanPatterms($patterns)
    {
        return str_replace(['Field::inst(', ')', ','], '', preg_replace(['/\t/', '/\n/', '/ /'], '', $patterns));
    }
    public function creatJsColuns($columns)
    {
        $start = count($columns);
        foreach ($columns as $key => $value) {
            if ($start == 1){
                echo '{ data: '.$value. ', visible: true }';
                echo "\n";
                } else {
                    echo '{ data: '.$value. ", visible: true }, \n";
                }
            $start--;
        }
        return '';
    }
    public function creatJsFields($columns)
    {
        $start = count($columns);
        foreach ($columns as $key => $value) {
            if ($start == 1){
                echo '{ label: "'.$value.'", name: "'.$value.'", type: "text" }';
                echo "\n";
                } else {
                    echo '{ label: "'.$value.'", name: "'.$value.'", type: "text" },'. "\n";
                }
            $start--;
        }
        return '';
    }
    public function createTableRow($columns)
    {
        foreach (str_replace("'", '', $columns) as $key => $value) {
                    echo '<th> '.$value.' </th>' ."\n";
        }
        return '';
    }
    public static function helpText()
    {
        return '';
    }

}