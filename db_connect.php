<?php
  function connect() {
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "GreenfieldSportsCentre";
      $port = "3306";

      // Create a connection to MySQL
      $conn = new mysqli($servername, $username, $password, $dbname, $port);

      // Check that the connection works
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      return $conn;
  }
?>
