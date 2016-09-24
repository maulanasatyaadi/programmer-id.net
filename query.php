<?php
class query
{
	var $connection;

	function __construct()
	{
		require('connection.php');
		$this->connection = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);
	}

	function insert_data($table_name, $data = array())
	{
		$data_1 = '';
		$data_2 = '';
		$counter = 1;
		foreach($data as $key => $var){
			$delimiter = ', ';
			if(count($data) == $counter){
				$delimiter = '';
			}
			$data_1 .= '`'.$key.'`'.$delimiter;
			$data_2 .= "'".$var."'".$delimiter;
			$counter++;
		}
		mysqli_query($this->connection, "INSERT INTO `$table_name` ($data_1) VALUES ($data_2)");
	}

	function read_data($table_name, $specification = NULL, $ordering = NULL)
	{
		$data = '';
		$data_spec = '';
		if(is_array($specification)){
			$counter = 1;
			foreach($specification as $key => $val){
				$delimiter = ' AND ';
				if(count($specification) == $counter){
					$delimiter = '';
				}
				$data .= "`$key`='$val'".$delimiter;
				$counter++;
			}
		} else {
			$data = $specification;
		}
		if(!is_null($specification)){
			$data_spec = "WHERE ".$data;
		}
		$order = '';
		if(is_array($ordering)){
			$order = "ORDER BY ".$ordering['order_by'];
			if(isset($ordering['direction'])){
				$order .= ' '.$ordering['direction'];
			}
			if(isset($ordering['start'])){
				$order .= ' LIMIT '.$ordering['start'];
			}
			if(isset($ordering['limit'])){
				$order .= ', '.$ordering['limit'];
			}
		}
		$query = mysqli_query($this->connection, "SELECT * FROM `$table_name` $data_spec $order");
		$data_output = array();
		$data_start = 0;
		while($fetch = mysqli_fetch_object($query)){
			foreach($fetch as $key => $val){
				$data_output[$data_start][$key] = $val;
			}
			$data_start++;
		}
		return $data_output;
	}

	function update_data($table_name, $data = array(), $specification = NULL)
	{
		$data_1 = '';
		$data_2 = '';
		$counter = 1;
		foreach($data as $key => $var){
			$delimiter = ', ';
			if(count($data) == $counter){
				$delimiter = '';
			}
			$data_1 .= "`$key`='$var'".$delimiter;
			$counter++;
		}
		if(is_array($specification)){
			$counter = 1;
			foreach($specification as $key => $var){
				$delimiter = ' AND ';
				if(count($specification) == $counter){
					$delimiter = '';
				}
				$data_2 .= "`$key`='$var'".$delimiter;
				$counter++;
			}
			$data_2 = "WHERE $data_2";
		}
		if(!is_array($specification)){
			$data_2 = "WHERE $specification";
		}
		mysqli_query($this->connection, "UPDATE `$table_name` SET $data_1 $data_2");
	}

	function delete_data($table_name, $specification = NULL)
	{
		$data = '';
		if(is_array($specification)){
			$counter = 1;
			foreach($specification as $key => $val){
				$delimiter = ' AND ';
				if(count($specification) == $counter){
					$delimiter = '';
				}
				$data .= "`$key`='$val'".$delimiter;
				$counter++;
			}
		} else {
			$data = $specification;
		}
		if(!is_array($specification)){
			$data = " WHERE ".$specification;
		}
		mysqli_query($this->connection, "DELETE FROM `$table_name` $data");
	}
}