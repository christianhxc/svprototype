<?php
require_once('ConfigurationCAUS.php');
require_once('libs/Connection.php');

class ConnectionCAUS extends Connection{

        private $conn;
	private $dsn;
	private $stmt;

	function __construct($db = null)
	{
            $this->db = ($db == null) ? ConfigurationCAUS::DB : $db;
            $this->dsn['username'] = ConfigurationCAUS::DBuser;
            $this->dsn['username'] = ConfigurationCAUS::DBuser;
	    $this->dsn['password'] = ConfigurationCAUS::DBpass;
	    $this->dsn['phptype'] = ConfigurationCAUS::DBHandler;
	    $this->dsn['hostspec'] = ConfigurationCAUS::host;
	}

	public function initConn(){
		$this->conn = new mysqli(
				$this->dsn['hostspec'],
				$this->dsn['username'],
				$this->dsn['password'],
				$this->db);

		if (mysqli_connect_errno()) {
		    die("Conexion Fallida: ".mysqli_connect_error());
		}
	}

	public function prepare($sql){
		$this->stmt = $this->conn->prepare($sql);
	}

	public function param($value, $type = "s"){
		$this->stmt->bind_param($type, $value);
	}

	public function params(&$params){
	  	$values = array();

		foreach ($params as $paramName => $paramType) $args .= "s";
	  	$values[] = $args;
		foreach ($params as $paramName => $paramType)
	  	{
	    	$values[] = &$params[$paramName];
	    	$params[$paramName] = $paramType;
	  	}

	  	call_user_func_array(array($this->stmt, 'bind_param'), $values);
	}

	public function execute(){
		return $this->stmt->execute();
	}

	public function getID(){
		return $this->conn->insert_id;
	}

	public function scapeString($string){
		return $this->conn->real_escape_string($string);
	}

	public function count(){
		return $this->conn->num_rows;
	}

	public function fetch(){
		$meta = $this->stmt->result_metadata();
	    while ($field = $meta->fetch_field())
	    {
	        $params[] = &$row[$field->name];
	    }

	    call_user_func_array(array($this->stmt, 'bind_result'), $params);

	    while ($this->stmt->fetch()) {
	    	foreach($row as $key => $val)
	        {
	            if (count($params) == 1){
	            	$c = $val;
	            }else{
	        		$c[$key] = $val;
	            }
	        }
	        $result[] = $c;
	    }

	    return $result;
	}

	public function fetchOne(){
		$meta = $this->stmt->result_metadata();
	    while ($field = $meta->fetch_field())
	    {
	        $params[] = &$row[$field->name];
	    }

	    call_user_func_array(array($this->stmt, 'bind_result'), $params);

	    $this->stmt->fetch();
		foreach($row as $key => $val){
			$c[$key] = $val;
	    }
	    $result = $c;

	    return $result;
	}

	public function closeStmt(){
		$this->stmt->close();
	}

	public function closeConn(){
		$this->conn->close();
	}

	public function begin(){
		$this->conn->autocommit(false);
	}

	public function rollback(){
		$this->conn->rollback();
	}

	public function commit(){
		$this->conn->commit();
	}
}
?>