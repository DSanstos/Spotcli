<?php
class AutodataTables
{
    /**
     * @param string $table = nome da tabela
     * @param object $resultSet = resultado da query no nome da tabela
     * @return string
     */
    public function createQuery($table, $resultSet, $join = false)
    {
        $queryResult = "Editor::inst( @DB, '@TABLE', '@KEYTABLE' ) \n ->fields('\n @FIELDS\n )\n ->process( @POST )\n ->json();'";
        $fields = '';
        $start = count($resultSet);
        foreach ($resultSet as $key => $column) {
            if($join){
                if($start == 1){
                    $fields .= "Field::inst( '".$table.'.'.$column['COLUMN_NAME']."' )";
                    $start--;
                } else {
                    $fields .= "Field::inst( '".$table.'.'.$column['COLUMN_NAME']."' ), \n";
                    $start--;
                }
                } else {
                    if($start == 1){
                        $fields .= "Field::inst( '".$column['COLUMN_NAME']."' )";
                        $start--;
                    } else {
                        $fields .= "Field::inst( '".$column['COLUMN_NAME']."' ),\n";
                        $start--;
                    }
                }
        };
        return $fields;
    }
    public function joins($tableCrud, $tables)
    {
        $joinsText = "\n";
        $tables = $this->toArray($tables);
        foreach ($tables as $table) {
            $joinsText .= "->leftjoin('$table', '$table.COD_$table', '=', '$tableCrud.COD_$table') \n";
        }
        return $joinsText;
    }
    public function optionSelect($tables)
    {
        $optionText = '';
        $tables = $this->toArray($tables);
        foreach ($tables as $table) {
            $validation = "->validator( Validate::notEmpty( ValidateOptions::inst()->message( 'Campo obrigatório!' )"
            .  ") )->options( Options::inst()->table( '$table' )->value( 'COD_$table' )->label( 'DES_$table' )->where( function(".'$q'.") {"
            .'$q '. '->where( function($r) { $r ->where(\'STA_'.$table.'\', 1); }); } ) ),';
            $optionText .= $validation; 
        }
        return $optionText;
    }
    public function toArray($argv)
    {
        return explode('-', strtoupper($argv));
    }
}