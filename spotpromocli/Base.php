<?php
 namespace Spotpromo\Spotcli;
/**
 * Class Base
 * Description: class with the basic commands of the Spotpromo cli tool
 * Autor: Daniel Hogans
 * email: daniel.santos.ap@gmail.com
 * Create date: 06/04/2024
 * last alter date: 06/04/2024
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
                if(self::checkMethods($command, $arguments)){
                    unset($arguments[1]);
                    print_r($arguments);
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
        return "Comandos Base\n";
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
        self::execute(self::splitArgs($arguments));
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
        print_r($arguments);
        exit;
    }
 }