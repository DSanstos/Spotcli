<?php

class Query 
{
    public function __construct()
    {

    }
    public static function helpText()
    {
        return 'monta as query para o datatables';
    }
    public  function table()
    {

    }
    public function detail()
    {
        return [
            '--table' => 'Qual a tabela que deve montar a query',
            '--join' => "Quais tabelas realizar join com a tabela principal \n \t\t separadas por '-'.",
            '--memory' => 'Especifica se deve armazenar na memoria para aprimoramento'
        ];
    }
}
