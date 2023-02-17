<?php 
    namespace becwork\utils;

    class MysqlUtils {

        /* 
          * FUNCTION: database connection (use PDO)
          * RETURN: database connection
        */
        public function connect() {
            
            global $pageConfig;
            global $siteController;

            // get mysql connection data form app config
            $address = $pageConfig->getValueByName("mysql-address");
            $database = $pageConfig->getValueByName("mysql-database");
            $username = $pageConfig->getValueByName("mysql-username");
            $password = $pageConfig->getValueByName("mysql-password");

            // get default database charset
            $encoding = $pageConfig->getValueByName("encoding");
            
            // try connect to database
            try {

                // build connction string
                $conn = new \PDO("mysql:host=$address;dbname=$database;charset=$encoding", $username, $password);
               
                // set the PDO error mode to exception
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                   
            // catch connection error
            } catch(\PDOException $e) {
                
                // check if dev-mode is enabled
                if ($pageConfig->getValueByName("dev-mode") == true) {
                    
                    // print error to page
                    die('Database connection error: '.$e->getMessage());
                } else {
                    
                    // redirect to error page
                    $siteController->redirectError("400");
                }
            }

            // return connection
            return $conn;
        }

        /*
          * FUNCTION:  database insert sql query function (Use database name from config.php)
          * USAGE: like insertQuery("INSERT INTO logs(name, value, date, remote_addr) VALUES('log name', 'log value', 'log date', 'log remote_addr')")
          * INPUT: sql command like string
        */
        public function insertQuery($query) {

            global $pageConfig;
            global $siteController;

            // get PDO connection
            $connection = $this->connect();

            // use prepare statement for query
            $statement = $connection->prepare($query);

            try {
                
                // execute prepered query
                $statement->execute();

            // catch insert error
            } catch(\PDOException $e) {

                // check if dev-mode is enabled
                if ($pageConfig->getValueByName("dev-mode") == true) {
                    
                    // print error to page
                    die('SQL query insert error: '.$e->getMessage());
                } else {
                    
                    // redirect to error page
                    $siteController->redirectError("400");
                }
            }
        }

        /*
         * FUNCTION: mysql log function (Muste instaled logs table form sql)
         * INPUT: log name and value
        */
        public function logToMysql($name, $value) {

            global $escapeUtils;
            global $mainUtils;

            // check if name is null
            if (empty($name)) {
                $name = null;
            }

            // check if value is null
            if (empty($value)) {
                $value = null;
            }

            // get data & escape
            $name = $escapeUtils->specialCharshStrip($name);
            $value = $escapeUtils->specialCharshStrip($value);

            // get current log date
            $date = date('d.m.Y H:i:s');
            
            // get remote address
            $remote_addr = $mainUtils->getRemoteAdress();

            // insert log to mysql
            $this->insertQuery("INSERT INTO logs(name, value, date, remote_addr) VALUES('$name', '$value', '$date', '$remote_addr')");
        }

        /*
          * FUNCTION: mysql data query fetch
          * INPUT: query like "SELECT * FROM logs"
          * RETURN: database output
        */
        public function fetch($query) {

            // get database connection
            $connection = $this->connect();

            // use prepare statement for query
            $statement = $connection->prepare($query);

            // execute query
            $statement->execute();
            
            // fetch data
            $data = $statement->fetchAll();

            // return data
            return $data;
        }

        /*
          * FUNCTION: fetch single value form database
          * INPUT: sql query & specific value
          * RETURN: selected value
        */
        public function fetchValue($query, $value) {

            global $pageConfig;
            global $siteController;

            // get database connection
            $connection = $this->connect();

            // use prepare statement for query
            $statement = $connection->prepare($query);

            // execute query
            $statement->execute();

            // fetch data query
            $fetch = $statement->fetchAll();

            // check if select exist
            if (array_key_exists(0, $fetch)) {
                
                // check if selected value exist in array
                if (array_key_exists($value, $fetch[0])) {

                    // get value from retrun
                    $valueOutput = $fetch[0][$value];
                
                } else {
                
                    // print not found error (only for developer mode)
                    if ($pageConfig->getValueByName("dev-mode")) {
                        die("Database select error: '$value' not exist in selected data");
                    } else {
                        $siteController->redirectError(404);
                    }
                }

            } else {

                // print not found error (only for developer mode)
                if ($pageConfig->getValueByName("dev-mode")) {
                    die("Database select error: please check if query valid, query:'$query'");
                } else {
                    $siteController->redirectError(404);
                }
            }

            // return value
            return $valueOutput;
        }

        /*
          * FUNCTION: get version function
          * USAGE: $ver = getMySQLVersion();
          * RETURN: mysql version in system
        */
        public function getMySQLVersion() {
            $output = shell_exec('mysql -V');
            preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
            return $version[0];
        }
    }
?>
