<?php
    //From https://www.cloudways.com/blog/connect-mysql-with-php/#newphp

    function OpenCon() {
        $host = "localhost";
        $dbname = "houseledger";
        $username = "root";
        $password = "";
        $conn = new mysqli($host, $username, $password,$dbname) or die("Connect failed: %s\n". $conn -> error);

        return $conn;
    }

    function CloseCon($conn) {
        $conn -> close();
    }

?>