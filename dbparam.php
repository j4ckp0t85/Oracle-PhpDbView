<?php
	
	$_SESSION['which']=(@$_GET['db'] !== null ? @$_GET['db']: $_SESSION['which']); //passed from index or called from session var (when viewing a table)
	
	
	/**********************************
	*        CONNECTION SETTINGS      *
	***********************************/
	
	switch($_SESSION['which']) 
	{
		case('SAMPLEDB1'): { //
			$username='user';
			$password='pass';
			$connection_string='localhost/xe';
			$character_set='UTF8';
			$_SESSION['ltTables']= array("LTAB","STAB","PTAB"); //filter to show rows of static/logic/parametric tables based on table convention used (begins with..) 
			//$schemaChange="alter session set current_schema=SCHEMA"; //set to specific schema
			break;
		}
	}
	
	
	
	$db=@oci_pconnect($username, $password , $connection_string, $character_set='UTF8');
	if(isset($schemaChange)) { 
		$stid = oci_parse($db, "'".$schemaChange."'"); 
		oci_commit($db);
	}
	
	if(!$db){ //die immediately if connection to db fail and show details
		echo "Connection to DB failed\n";
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		exit();
	}
	
	
	if(!isset($_SESSION['fields'])) { //if the config file is called from a table view, don't refresh the list of fields
		$_SESSION['fields']=array();
		$stid = oci_parse($db, "select distinct(column_name) from user_tab_columns order by column_name"); 
		oci_execute($stid);
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) {
							array_push($_SESSION['fields'],($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;"));
						}
				}
		oci_free_statement($stid);
		}
	
	if(!isset($_SESSION['tables'])) { //if the config file is called from a table view, don't refresh the list of tables
		$_SESSION['tables']=array();
		$stid = oci_parse($db, "SELECT table_name FROM user_tables"); 
		oci_execute($stid);
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) {
							array_push($_SESSION['tables'],($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;"));
						}
				}
		oci_free_statement($stid);
		}
?>