<?php

namespace model;

/**
 * Class Repository
 * @package model
 * This code is taken from https://github.com/gingerswede/1dv408-HT14/blob/master/Portfolio/src/model/Repository.php
 */
abstract class Repository{
    protected  $dbUsername =  '131860-jd64493';
    protected  $dbPassword = 'KalleAnka1';
    protected  $dbConnectionString = 'mysql:host=mysql14.citynetwork.se;dbname=131860-ovsriy410012';
    protected  $dbConnection;
    protected  $dbTable;

    protected function connection(){
        if($this->dbConnection == null){
            $this->dbConnection = new \PDO($this->dbConnectionString, $this->dbUsername, $this->dbPassword);

            $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $this->dbConnection;
        }
    }

}