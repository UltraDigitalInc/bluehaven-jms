<?php
# connect to the database

ini_set('display_errors','On');
error_reporting(E_ALL);

  try {
    $host='CORP-DB01.bhcorp.local';
    $dbname='jest';
    $user='sa';
    $pass='date1995';
    $DBH = new PDO("mssql:host=$host;dbname=$dbname", $user, $pass);
    $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  
    # UH-OH! Typed DELECT instead of SELECT!
    $STH=$DBH->query('SELECT lname FROM security');
    
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    
    echo $STH->fetchColumn().'<br>';
    
    while($row = $STH->fetch()) {
        echo $row['lname'] . '<br>';
    }

    
    //echo 'OUT';
  }
  catch(PDOException $e)
  {
    echo "I'm sorry, Dave. I'm afraid I can't do that.";
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
  }