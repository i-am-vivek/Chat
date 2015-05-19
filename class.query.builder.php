<?php

/*
* Author : Vivek
* Date     : 02-12-2015
* Name   : Query Builder
* Email   :  vivek.aasaithambi@gmail.com
*
*
* */
class query_builder{
	private $hostname ="localhost"; 
	private $uname  = "root";
	private $pass = ""; 
	private $dbname = "messenger"; 
	private $link,$query,$source,$mode='development';
 
	function query_builder(){
	  $this->link = mysql_connect($this->hostname, $this->uname, $this->pass) or die(mysql_error());
	  mysql_select_db($this->dbname) or die(mysql_error());
	  return $this;
	}
	function insert_data($table,$data){
		if(!is_array($data))
		return false;
		$keys= "`".implode('`,`', array_keys($data))."`";
		$values= '"'.implode('", "', array_values($data)).'"';
		$this->query="INSERT INTO $table ( $keys ) VALUES ( $values )";
		return $this->go_query()!=0?false:$this;
	}
	function update_data($table,$data,$where){
		if(!is_array($data) || !$data || !is_array($where) || !$where)
		return false;
		$udata=$uwhere="";
		$search=array("<",">","!=","<=",">=","=");
		foreach($data as $key => $value)
		$udata[]="`".$key."` = '".$value."'";
		foreach($where as $key => $value){
			$array = explode(" ",$key);
			$p = array_intersect($search,$array);
			$errors = array_filter($p);
			$uwhere[]=$errors?"`".$key."` ".$value."'":"`".$key."` = '".$value."'";
		}
		$udata =implode(" , ",$udata);
		$uwhere =implode(" AND ",$uwhere);
		$this->query="UPDATE `$table` SET $udata WHERE $uwhere";
		return $this->go_query()!=0?false:$this;
	}
	function get_data($table,$fields=FALSE,$where=FALSE){
		$udata=$uwhere="";
		$search=array("<",">","!=","<=",">=","=");
		$fields = $fields?is_string($fields)? $fields:" ` ".implode('`,`', $fields)." ` " :"*";
		if($where && is_array($where)){
			foreach($where as $key => $value){
				$array = explode(" ",$key);
				$p = array_intersect($search,$array);
				$errors = array_filter($p);
				$uwhere[]=$errors?"`".$key."` ".$value."'":"`".$key."` = '".$value."'";
			}
			$uwhere =implode("  AND ",$uwhere);
		}
		else $uwhere =$where;
		$this->query="SELECT  $fields FROM `$table` ";
		if($uwhere)
		$this->query.="WHERE $uwhere";
		$this->go_query();
		return $this;
	}
	function delete($table,$where=FALSE){
		$udata=$uwhere="";
		$search=array("<",">","!=","<=",">=","=");
		if($where && is_array($where)){
			foreach($where as $key => $value){
				$array = explode(" ",$key);
				$p = array_intersect($search,$array);
				$errors = array_filter($p);
				$uwhere[]=$errors?"`".$key."` ".$value."'":"`".$key."` = '".$value."'";
			}
			$uwhere =implode("  AND ",$uwhere);
		}
		else $uwhere =$where;
		$this->query="DELETE FROM `$table` WHERE $uwhere";
		return $this->go_query()==0?TRUE:FALSE;
	}
	function raw_query($sql){
		$this->query=$sql;
		return $this->go_query()!=0?false:$this;
	}
	function go_query(){
		if(request('query_exit') && $this->mode='development'){var_dump($this->query);}
		$this->source=mysql_query($this->query,$this->link) or die(mysql_error());
		return mysql_errno();
	}
	function row(){
		return mysql_num_rows($this->source)?mysql_fetch_assoc($this->source):false;
	}
	function result(){
		$return_data;
		//return mysql_fetch_array($this->source);
		if(mysql_num_rows($this->source)){
			while($row=mysql_fetch_assoc($this->source)){
				$return_data[]=$row;
			}
			return $return_data;
		}
		else
		return false;
	}
	function inserted_id(){
		return mysql_insert_id();
	}
	function return_query(){
		return $this->query;
	}
}
?>
