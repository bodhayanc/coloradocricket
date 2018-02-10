<?php
/*
This is a PHP class definition for mysql database server connections.
Last Modified on 4/24/01 5:38PM Justin Koivisto [Koivi Media]
*/

$MYSQL_CLASS_INC=true;

class mysql_class{
	# Constructor
	function mysql_class($user="phpuser",$pass="php",$host="localhost"){
		$this->user=$user;
		$this->pass=$pass;
		$this->host=$host;
		$this->id=@mysql_connect($this->host,$this->user,$this->pass) or
			mysql_ErrorMsg("mysql_connect: Unable to connect to mysql server: $this->host");
	}

	# Get a list of the available databases on the current server
	function GetDatabases(){
		$this->result=@mysql_list_dbs() or
			mysql_ErrorMsg("Unable to find a database on server: $this->host");
		$i=0;
		while($i < mysql_num_rows($this->result)){
			$db_names[$i]=mysql_tablename($this->result,$i);
			$i++;
		}
		return($db_names);
	}

	# Create a database on the server
	function CreateDB($database){
		$this->result= @mysql_create_db($database) or
			mysql_ErrorMsg("Unable to create database: $database");
		$this->a_rows=@mysql_affected_rows($this->result);
	}

	# Drop a database
	function DropDB($database){
		$this->result=@mysql_drop_db($database) or
			mysql_ErrorMsg("Unable to drop database: $database");
		$this->a_rows=@mysql_affected_rows($this->result);
	}

	# Copy a database from this server (even to another)
	# Returns TRUE or FALSE for success or failure
	function CopyDB($database,$dest_db,$dest_host="localhost",$dest_user="phpuser",$dest_pass="php"){
		# This function is only useful if the machine that the php script is running on
		# has the mysqldump and mysql commands installed on it. You will need to edit the
		# variables below to reflect the path where they are installed.
		# 4/7/01 11:57AM

		# MYSQLPATH is only used in these 3 lines
		$MYSQLPATH="/usr/local/bin/";
		$MYSQLDUMP= $MYSQLPATH . "mysqldump";
		$MYSQL= $MYSQLPATH . "mysql";

		$dest = new MySQL_class($dest_user, $dest_pass, $dest_host);

		# if the database doesn't exist on destination (can't select it), create it
		if(!$dest->SelectDB($dest_db))
			$dest->CreateDB($dest_db);

		# Now that connection has been established, we can do the copying
		$system_command="$MYSQLDUMP -h$this->host -u$this->user -p$this->pass --opt $database | $MYSQL -h$dest_host -u$dest_user -p$dest_pass -C $dest_db";
		system($system_command,$system_result);

		# return true for success else return false
		if($system_result)
			return FALSE;
		else
			return TRUE;
	}

	# Select or change databases
	function SelectDB($db){
		$this->db=$db;
		@mysql_select_db($db,$this->id) or
			mysql_ErrorMsg("Unable to select database: $db");
	}
	

	# Get a list of the available tables in this database
	function GetTableList(){
		$this->result=@mysql_list_tables($this->db,$this->id) or
			mysql_ErrorMsg("Unable to find any tables in database: $this->db");
		$i=0;
		while($i < mysql_num_rows($this->result)){
			$tb_names[$i]=mysql_tablename($this->result,$i);
			$i++;
		}
		return($tb_names);
	}

	# Get a list of the fields names in the given table of the current database
	function GetFieldList($tbl_name){
		$this->result=@mysql_list_fields($this->db,$tbl_name,$this->id);
		$i=0;
		while($i < mysql_num_fields($this->result)){
			$fd_names[$i]=mysql_field_name($this->result,$i);
			$i++;
		}
		return($fd_names);
	}

	# Delete rows from a table
	function Delete($query){
		$this->result=@mysql_query($query,$this->id) or
			mysql_ErrorMsg("Unable to perform Delete: $query");
		$this->a_rows=@mysql_affected_rows($this->result);
	}

	# Update elements in database
	function Update($query){
		$this->result=@mysql_query($query,$this->id) or
			mysql_ErrorMsg("Unable to perform update: $query");
		$this->a_rows=@mysql_affected_rows($this->result);
	}

	# Insert row into a table
	function Insert($query){
		$this->result=@mysql_query($query,$this->id) or
			mysql_ErrorMsg("Unable to perform insert: $query");
		$this->a_rows=@mysql_affected_rows($this->result);
	}

	# Get last insert id from an auto_incremented field
	function InsertID(){
		$this->result=@mysql_insert_id($this->id) or
			mysql_ErrorMsg("Cannot retrieve auto_increment value: $this->id");
		return($this->result);
	}

	# Multiple row return query - Use GetRow function to loop through
	function Query($query){
		$this->result=@mysql_query($query,$this->id) or
			mysql_ErrorMsg("Unable to perform query: $query");
		$this->rows=@mysql_num_rows($this->result);
		$this->StripRowSlashes();
	}

	# Get a row of data from a multiple row query
	function GetRow($row){
		@mysql_data_seek($this->result,$row) or
			mysql_ErrorMsg("Unable to seek data row: $row");
		$this->data=@mysql_fetch_array($this->result) or
			mysql_ErrorMsg("Unable to fetch row: $row");
		$this->StripRowSlashes();
	}

	# Single row return query
	function QueryRow($query){
		$this->result=@mysql_query($query,$this->id) or
			mysql_ErrorMsg("Unable to perform query: $query");
		$this->rows=@mysql_num_rows($this->result);
		$this->data=@mysql_fetch_array($this->result) or
			mysql_ErrorMsg("Unable to fetch data from query: $query");
		$this->StripRowSlashes();
		return($this->data);
	}

	# Single element return query
	function QueryItem($query){
		$this->result=@mysql_query($query,$this->id) or
			mysql_ErrorMsg("Unable to perform query: $query");
		$this->rows=@mysql_num_rows($this->result);
		$this->data=@mysql_fetch_array($this->result) or
			mysql_ErrorMsg("Unable to fetch data from query: $query");
		$this->StripRowSlashes();
		return($this->data[0]);
	}

	# Use if checking for empty query result returns 0 if empty,
	# and 1 if there is at least one result
	function Exists($query){
		$this->result=@mysql_query($query,$this->id);
		if(@mysql_num_rows($this->result)) return 1;
		else return 0;
	}

	# This gets rid of the escape characters in the returned strings.
	# This function was submitted by Andrew Collington, and I implemented
	# it into all the query functions so you don't have to remember to
	# strip them yourself. Handy if you use page titles as primary keys
	# or for other types of matches.
	function StripRowSlashes(){
		if (isset($this->data)) {
			foreach($this->data as $key => $val){
				$this->data[$key] = stripslashes($val);
			}
		}
	}


	# extensions by Andrew Collington


	function GetSetList($table="",$field="") {
		if ($table=="" || $field=="") {
			$this->set = array();
			return;
		} else {
			$query = "SHOW COLUMNS FROM $table LIKE '$field'";
			$myResult=@mysql_query($query,$this->id) or
							mysql_ErrorMsg("Unable to perform set fetch: $query");
			$myData=@mysql_fetch_array($myResult) or
			mysql_ErrorMsg("Unable to fetch data set form : $query");
			foreach($myData as $key => $val){
				$myData[$key] = stripslashes($val);
			}
			$mySet = split("','", substr($myData[1],5,-2));
			$this->set = $mySet;
		}
	}

	function GetEnumList($table="",$field="") {
		if ($table=="" || $field=="") {
			$this->enum = array();
			return;
		} else {
			$query = "SHOW COLUMNS FROM $table LIKE '$field'";
			$myResult=@mysql_query($query,$this->id) or
							mysql_ErrorMsg("Unable to perform enum fetch: $query");
			$myData=@mysql_fetch_array($myResult) or
			mysql_ErrorMsg("Unable to fetch data enum form : $query");
			foreach($myData as $key => $val){
				$myData[$key] = stripslashes($val);
			}
			$myEnum = split("','", substr($myData[1],5,-2));
			$this->enum = $myEnum;
		}
	}
	
	function BagAndTag($field="") {
		if ($this->data) {
			if ($field=="") {
				foreach($this->data as $key => $val) {
					$val = htmlentities($val);
					$val = preg_replace("~[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}~", "<a href=\"mailto:\\0\">\\0</a>",$val);
					$val = preg_replace("/\[link=(\S+?)\](.*?)\[\/link\]/is", "<a href=\"\\1\" class=\"news\">\\2</a>",$val);
					$val = preg_replace("/\[img=(\S+?)\]/is", "<img src=\"\\1\">",$val);					
					$val = preg_replace("/\[i\](.*?)\[\/i\]/is", "<i>\\1</i>",$val);
					$val = preg_replace("/\[b\](.*?)\[\/b\]/is", "<b>\\1</b>",$val);
					$val = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>\\1</u>",$val);
					$val = preg_replace("/\[table\](.*?)\[\/table\]/is", "<table width=\"100%\">\\1</table>",$val);
					$val = preg_replace("/\[tr\](.*?)\[\/tr\]/is", "<tr>\\1</tr>",$val);
					$val = preg_replace("/\[td\](.*?)\[\/td\]/is", "<td valign=\"top\">\\1</td>",$val);
					$val = preg_replace("/\[pre\](.*?)\[\/pre\]/is", "<pre>\\1</pre>",$val);
					$val = preg_replace("[list]","<li type=\"square\">",$val);
					$this->data[$key] = nl2br($val);
				}
			} else {
				$val = htmlentities($this->data[$field]);
				$val = preg_replace("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}", "<a href=\"mailto:\\0\">\\0</a>",$val);
				$val = preg_replace("/\[link=(\S+?)\](.*?)\[\/link\]/is", "<a href=\"\\1\" class=\"news\">\\2</a>",$val);
				$val = preg_replace("/\[img=(\S+?)\]/is", "<img src=\"\\1\">",$val);					
				$val = preg_replace("/\[i\](.*?)\[\/i\]/is", "<i>\\1</i>",$val);
				$val = preg_replace("/\[b\](.*?)\[\/b\]/is", "<b>\\1</b>",$val);
				$val = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>\\1</u>",$val);
				$val = preg_replace("/\[table\](.*?)\[\/table\]/is", "<table width=\"100%\">\\1</table>",$val);
				$val = preg_replace("/\[tr\](.*?)\[\/tr\]/is", "<tr>\\1</tr>",$val);
				$val = preg_replace("/\[td\](.*?)\[\/td\]/is", "<td valign=\"top\">\\1</td>",$val);				
				$val = preg_replace("/\[pre\](.*?)\[\/pre\]/is", "<pre>\\1</pre>",$val);
				$val = preg_replace("\[list\]","<li type=\"square\">",$val);
				$this->data[$field] = nl2br($val);
			}
		}	
	}

	function DeBagAndTag($field="") {
		if ($this->data) {
			if ($field=="") {
				foreach($this->data as $key => $val) {
					$val = htmlentities($val);
					$val = preg_replace("[link=([^\\[]*)]","",$val);
					$val = str_replace("[/link]","",$val);
					$val = preg_replace("[img=([^\\[]*)]","",$val);
					$val = str_replace("[/img]","",$val);					
					$val = str_replace("[i]","",$val);
					$val = str_replace("[/i]","",$val);
					$val = str_replace("[b]","",$val);
					$val = str_replace("[/b]","",$val);
					$val = str_replace("[list]","",$val);
					$this->data[$key] = $val;
				}
			} else {
				$val = htmlentities($this->data[$field]);
				$val = htmlentities($val);
				$val = preg_replace("[link=([^\\[]*)]","",$val);
				$val = str_replace("[/link]","",$val);
				$val = preg_replace("[img=([^\\[]*)]","",$val);
				$val = str_replace("[/img]","",$val);				
				$val = str_replace("[i]","",$val);
				$val = str_replace("[/i]","",$val);
				$val = str_replace("[b]","",$val);
				$val = str_replace("[/b]","",$val);
				$val = str_replace("[list]","",$val);
				$this->data[$field] = $val;
			}
		}	
	}
	

	function PrintR() {
		print "doing it...<br>";
		print_r($this->data);
	}


} # End of class

# MySQL error message function
function mysql_ErrorMsg($msg){
	# Get out of html constraints so we can see the message
	echo("</ul></ul></ul></dl></dl></dl></ol></ol></ol>\n");
	echo("</table></table></table></script></script></script>\n");

	# Display the error message
	$text ="<font color=\"#ff0000\" size=+2><p>Error: $msg :";
	$text .= mysql_error();
	$text .= "</font>\n";
	die($text);
}

?>
