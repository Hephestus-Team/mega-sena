<?php
    include 'AutoLoad.php';
    
    class DBManager 
    {   
        public $database;
        public const INSERT = 'INSERT';
        public const UPDATE = 'UPDATE';
        public const DELETE = 'DELETE';
        private const LIMIT = '';

        ##CONSTRUCT##
        public function __construct(string $dbname)
        {   
            try
            {   
                global $CONFIG;
                $this->database = new PDO("mysql:host=localhost;dbname=$dbname", ServerSettings::DBCONFIG['USER'], ServerSettings::DBCONFIG['PASSWORD'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
            }
            catch(PDOException $e){
                $message = $e->getMessage();
                    print "ERROR>>> NA CONEXÃO DO BD ({$message})";
                die();
            }
        }

        private function stringifyValues(array $values){
            for($i = 0; $i <= array_key_last($values); ++$i){
                $array[$i] = implode(',', $values[$i]);
            }
            return $array;
        }

        ##SELECT SEM WHERE##
        private function DQLAll(array $column, array $table) : ?array
        {
            $placeholder = self::stringifyValues([$column, $table]);
            $model = "SELECT {$placeholder[0]} FROM {$placeholder[1]} " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute();
                $row = $query->fetchAll();
                    return $row;
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "ERROR>>> NA FUNÇÃO DQLAll ({$message}) SQL MODEL => $model // <br>";
                die();
            }
        }

        ##SELECT COM WHERE##
        private function DQLWhere(array $column, array $table, ?string $where = '1', ?array $params = []) : ?array
        {
            $placeholder = self::stringifyValues([$column, $table]);
            $model = "SELECT {$placeholder[0]} FROM {$placeholder[1]} WHERE $where " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
                $row = $query->fetchAll();
                    return $row;
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "ERROR>>> NA FUNÇÃO DQLWhere ({$message}) SQL MODEL => $model // <br>";
                die();
            }
        }
    
        ##INSERIR##
        private function DMLInsert(array $table, array $column, string $values, array $params)
        {
            $placeholder = self::stringifyValues([$table, $column]);
            $model = "INSERT INTO {$placeholder[0]} ({$placeholder[1]}) VALUES ($values) " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "ERROR>>> NA FUNÇÃO DMLInsert ({$message}) SQL MODEL => $model // <br>";
                die();
            }        
        }

        ##UPDATE##
        private function DMLUpdate(array $table, array $valores_novos, array $valores_antigos)
        {
            $placeholder = self::stringifyValues([$table]);
            $model = "UPDATE {$placeholder[0]} SET {$valores_novos['where_novo']} WHERE {$valores_antigos['where_antigo']} " . self::LIMIT;
            $params = array_merge($valores_antigos['params_antigo'], $valores_novos['params_novo']);

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
            }
            catch(PDOException $e)
            {
                $message = $e->getMessage();
                    print "ERROR>>> NA FUNÇÃO DMLUpdate ({$message}) SQL MODEL => $model // <br>";
                    die();
            }        
        }

        ##DELETE##
        private function DMLDelete(array $table, string $where, array $params)
        {
            $placeholder = self::stringifyValues([$table]);
            $model = "DELETE FROM {$placeholder[0]} WHERE {$where} " . self::LIMIT;

            try
            {
                $query = $this->database->prepare($model);
                $query->execute($params);
            }
            catch(PDOException $e){
                $message = $e->getMessage();
                    print "ERROR>>> NA FUNÇÃO DMLDelete ({$message}) SQL MODEL => $model // <br>";
                die();
            }        
        }

        public function DQL()
        {
            if(func_num_args() == 2){
                return $this->DQLAll(func_get_arg(0), func_get_arg(1));
            }elseif(func_num_args() == 4){
                return $this->DQLWhere(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
            }else{
                print '(FUNCTION DQL) ERROR>>> INVALID NUMBER OF ARGUMENTS<br>';
                die();
            }
        }

        public function DML()
        {
            if(func_num_args() == 5){
                if(func_get_arg(4) == 'INSERT'){
                    return $this->DMLInsert(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
                }elseif(func_get_arg(4) == 'DELETE' || func_get_arg(4) == 'UPDATE'){
                    print '(FUNCTION DML) ERROR>>> INVALID ARGUMENT, FOURTH ARGUMENT MUST BE DBManager::INSERT OR DBManager::UPDATE<br>';
                    die();
                }else{                    
                    print '(FUNCTION DML) ERROR>>> INVALID NUMBER OF ARGUMENTS<br>';
                    die();
                }
            }elseif(func_num_args() == 4){
                if(func_get_arg(3) == 'DELETE'){
                    return $this->DMLDelete(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                }elseif(func_get_arg(3) == 'UPDATE'){
                    return $this->DMLUpdate(func_get_arg(0), func_get_arg(1), func_get_arg(2));
                }elseif(func_get_arg(3) == 'INSERT'){
                    print '(FUNCTION DML) ERROR>>> INVALID ARGUMENT, THIRD ARGUMENT MUST BE DBManager::DELETE OR DBManager::UPDATE<br>';
                    die();
                }else{
                    print '(FUNCTION DML) ERROR>>> INVALID NUMBER OF ARGUMENTS<br>';
                    die();
                }
            }else{
                print '(FUNCTION DML) ERROR>>> INVALID NUMBER OF ARGUMENTS<br>';
                die();
            }
    }
}