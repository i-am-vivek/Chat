<?php
session_start();
include "class.query.builder.php";
include "common_functions.php";
Class Process{
	var $user_id,$db,$json;
	function __Construct($user_id=FALSE){
		if(!$user_id){
			$this->_set_("status",0);
			$this->_set_("message","user is not valid");
		}
		else{
			$this->user_id=$user_id;
			$this->db=new query_builder();
		}
	}
	function get_chat(){
		$result=$this->db->raw_query("
			SELECT ch.id,asps.id as agid,ch.message,us.name,us1.name as sname FROM 
			users us 
			INNER JOIN  `activesupporters` asps ON us.id=asps.userid
			INNER JOIN  `users` us1 ON us1.id=asps.supporterid
			LEFT JOIN  chat ch ON asps.id=ch.agid
			WHERE (asps.`supporterid`='$this->user_id' OR asps.`userid`='$this->user_id') 
			AND if(ch.receivedstatus IS NOT NULL,ch.receivedstatus=0,true)
			AND asps.activestatus='1'
		")->result();
		$this->_set_("status",1);
		if(!count($result)){
			$this->_set_("message","No Unread Messages");
		}
		else{
			$this->_set_("chat",$result);
			$this->_set_("message","Messages are available");
		}
	}
	function insert_chat(){
		extract(post());
		if(!isset($message)){
			$this->_set_("status",0);
			$this->_set_("message","Message is not valid");
		}
		else if(isset($gid)){
			$ch_array['agid']= $gid;
			$ch_array['userid']=$this->user_id;
			$ch_array['message']=$message;
			$this->db->insert_data("chat",$ch_array);
			$this->_set_("status",1);
			$this->_set_("message","Successfully Inserted");
		}
		else{
			$this->_set_("status",1);
			$this->_set_("message","Our Support Team is busy. Kindly wait for sometime");
		}
	}
	function _set_($key,$value){
		$this->json[$key]=$value;
	}
	function generate_output(){
		return json_encode($this->json);
	}
}
extract($_REQUEST);
if(isset($function)){
	$obj=new Process(get_session("user_id"));
	if(method_exists($obj,$function))
	$obj->$function();
	else{
		$obj->_set_("status",0);
		$obj->_set_("message","function is not valid");
	}
	echo $obj->generate_output();
}
