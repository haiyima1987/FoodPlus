<?php

class DbcHelper
{
    private $host_name;
    private $username;
    private $password;
    private $db_name;

    public function __construct($name, $user, $pwd, $dbn)
    {
        $this->host_name = $name;
        $this->username = $user;
        $this->password = $pwd;
        $this->db_name = $dbn;
    }

    public function selectUpdateData($query)
    {
        try {
            $dbc = $this->connect();
            $stmt = $dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $exception) {
            return null;
        } finally {
            $this->disconnect();
        }
    }

    public function insertDeleteData($query) {
        try {
            $dbc = $this->connect();
            $result = $dbc->exec($query);
            return $result;
        } catch (PDOException $exception) {
            return null;
        } finally {
            $this->disconnect();
        }
    }

    public function connect()
    {
        $dbc = new PDO("mysql:host=" . $this->host_name . ";dbname=" . $this->db_name, $this->username, $this->password);
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbc;
    }

    public function disconnect()
    {
        $dbc = null;
    }
}

?>