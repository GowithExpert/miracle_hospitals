<?php 
	
	function getRandom($arr){
		 shuffle($arr);
		return end($arr);
	}


	function randomString(){
		return substr(str_shuffle( DEV_AUTHOR ), 10,10);
	}

?>