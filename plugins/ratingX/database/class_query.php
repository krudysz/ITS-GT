<?php
class Query{
	var $db;
	var $dbreturn;
	var $dbreturnget;
	
	function Query($server,$username,$password,$dbname)
	{
		$this->db=mysql_connect($server,$username,$password);
		if((mysql_select_db($dbname,$this->db)==false))
		{
			echo "Database Connection Error:  ".mysql_error();
		}else{
			//echo "DB works";
		}
	}
	
	function execute($arg)
	{
		$val=mysql_query($arg);
		$this->dbreturn=$val;
		return $val;
	}
	
	function get()
	{
		$val=mysql_fetch_array($this->dbreturn);
		return $val;
		
	}
	
	function update_db($table,$var1,$var2,$var3,$var4,$var5,$var6,$var7,$var8,$id)
	{
		$this->execute(("UPDATE ".$table." SET one=$var1,
									two=$var2,
									three=$var3,
									four=$var4,
									five=$var5,
									initial_rating=$var6,
									total_rates=$var7,
									final_rating=$var8 WHERE id=$id"));	
	}
	
}	
?>
