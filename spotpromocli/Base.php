<?php
 namespace Spotpromo\Spotcli;
/**
 * Class Base
 * Description: class with the basic commands of the Spotpromo cli tool
 * Autor: Daniel Hogans
 * email: daniel.santos.ap@gmail.com
 * Create date: 06/04/2024
 * last alter date: 08/04/2024
 */
 class Base 
 {
    public function __construct()
    {
        
    }
    /**
     * @param array $argv os argumentos passados ao comando; 
     * @return string
     */
    public static function startCli($argv)
    {
        $argv = self::getArgs($argv);
        $class = ucfirst($argv[1]);

        if(file_exists(__DIR__.'/'.$class.'.php') && $class <> 'Base'){
            include( __DIR__.'/'.$class.'.php' );
            $command = new $class;
            unset($argv[0]);
            unset($argv[1]);
            $arguments = $argv;
            if(count($arguments) < 1){
                echo "Precisa de Argumentos!\n";
                print_r($command->detail());
            } else {
                // caso o comando seja a classe base
                if(self::checkMethods($command, $arguments)){
                    unset($arguments[1]);
                    self::execute($arguments);
                    echo "aqui base";
                    exit;
                };
                echo "fora";
            }

        } else {
            if ($class == 'Base'){
                self::baseTratament($argv);
            } else {
                echo 'comando inexistente!';
            }
            
        }
    }
    public static function getHelp()
    {
        $diretory = __DIR__;
        $files = glob($diretory . '/*');
        $exclude = ['.php', $diretory.'/'];
        foreach ($files as $file){
            $commandClass = str_replace($exclude, '', $file);
            if ($commandClass == 'Base'){
                $help = $commandClass .':'.self::helpText();
                echo $help;
                next($files);
            } else {
                include($commandClass.'.php');
                $help = $commandClass .':'.$commandClass::helpText();
                // $help = new $commandClass;
                echo $help;
            }
            
        }
        // print_r($files);
        // foreach ($files as $arquivo) {
        //     echo basename($arquivo) . "\n";
        // }
    }
    public static function helpText()
    {
        return "\e[92m Comandos Base:\n\e[39m"
                ."Os comandos para tarefas basicas das aplicações da SpotPromo."."\n"
                . "\e[92m getinfo: \t\e[39m coleta informações do sistema corrente."."\n"
                . "\t\t Parametros: \e[95m system, database\e[39m"."\n"
                . "\t\t exemplo: \e[93m php spotcli\e[34m base getinfo \e[95m --system  --database\e[39m"."\n"
                . "\e[92m outro: \t\e[39m coleta informações do sistema corrente."."\n"
                ;
    }
    public static function getEnv()
    {
        $diretory = __DIR__;
        echo $diretory;
    }
    public static function getArgs($argv)
    {
        unset($argv[0]);
        return $argv;
    }
    public static function baseTratament($arguments)
    {
        unset($arguments[1]);
        // print_r($arguments);
        // exit;
        if (self::checkMethods(__CLASS__, $arguments[2])){
            self::execute($arguments);
        }
        exit;
        
    }
    public static function checkMethods($object, $arguments)
    {
        foreach (self::splitArgs($arguments) as $method) {
            // Verifica se o método existe na classe
            if (!method_exists($object, $method)) {
                // Se não existir, encerra o script
                die("Método '$method' não é um método válido da classe.\n");
            }
        }
        return true;
    }
    /**
     * Description: decomposes the arguments, returning only method names
     * @param array $arguments
     * @return array
     */
    public static function splitArgs($arguments)
    {
        $nodash = [];
        foreach($arguments as $argument){
             array_push($nodash, explode('=', str_replace('--', '', $argument))[0]);
        }
        return $nodash;
    }
    public static function create()
    {
        return '';
    }
    public static function execute($arguments)
    {
        $arguments = self::splitArgs($arguments);
        if(count($arguments) > 0){
                self::{$arguments[0]}($arguments);
                exit;
            } else {
                echo self::helpText();
                exit;
            }
    }
    public static function describe($arguments)
    {
        unset($arguments[0]);
        foreach($arguments as $key => $value){
                echo self::getInfo($value);
        }
        exit;
    }
    public static function getInfo($typeInfo)
    {
        $info = '';
        switch($typeInfo)
        {
            case 'system':
                $info .= "\e[31mSistema: \e[39m". NOME_PROJETO."\n";
                break;
            case 'database';
                $info .= "\e[31mBanco de dados: \e[39m". DB_NAME ."\n";
                break;
            case 'database';
                $info .= "\e[31mBanco de dados: \e[39m". DB_NAME ."\n";
                break;
            default :
                break;
        }
        return $info;
    }
 }