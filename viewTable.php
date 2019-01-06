<?php   //Table details
		session_start();
		require('dbparam.php');
		$table=$_GET['table']; //Which table (passed from search)
		
		function detailTable ($nomeTabella, $stringaRicerca) { //filtering tables (show rows only for logic/parametric tables - naming convention needed e.g. all tables starts with LTAB..,PTAB..)
				return substr($nomeTabella, 0 ,strlen($stringaRicerca)) == $stringaRicerca;
			}
?>
<html>
<head>
	<title>Table detail - <?php echo $table; ?></title>
	<link rel="stylesheet" href="css/style.css" type="text/css"></style>
	<script src="js/jquery.min.js" type="text/javascript"></script>
</head>
<body>
	<h1><?php echo $table; ?></h1>
	<?php
		$stid = oci_parse($db, "SELECT COLUMN_NAME, DATA_TYPE, DATA_LENGTH FROM USER_TAB_COLS WHERE table_name='$table'");
		oci_execute($stid);
		echo "<table class=\"blueTable\">\n";
		echo "<thead><tr align=\"center\"><th>NOME CAMPO</th><th>TIPO</th><th>LUNGHEZZA</th><th>NOTE</th></tr></thead>\n";
		$fields=array();
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$first=0;
			echo "<tr class=\"result\">\n";
			foreach ($row as $item) {
						if($first==0) array_push($fields,($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;"));
						echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
						$first++;
					}
			echo "<td><input type\"text\" size=\"100\"> </td>\n";
			echo "</tr>\n";
			}
		oci_free_statement($stid);
	?>
	
<script type="text/javascript">
			$(document).ready(function() {
				
			$(".blueTable tr").click(function() {
				var selected = $(this).hasClass("highlight");
				//$(".blueTable tr").removeClass("highlight");
				if(!selected){
						$(this).removeClass("result");
						$(this).addClass("highlight");
						}
				else {
					$(this).addClass("result");
					$(this).removeClass("highlight");
					}
			});
		});
</script>
</table>

<?php 
	$detailTab=false; 
	for ($i=0;$i<sizeof($_SESSION['ltTables']);$i++) //from config file (all tables that start with a defined naming convention)
		$detailTab = $detailTab || detailTable($table,$_SESSION['ltTables'][$i]); //parametric table?
	
	if(($table{0}=="L")||($table{0}=="T")||($detailTab)) { //if yes, show relative rows
		echo "<h2>Dettagli tabella</h2>";
		$stid = oci_parse($db, "SELECT * FROM $table"); 
		oci_execute($stid);
		echo "<table class=\"blueTable\">\n";
		echo "<thead><tr align=\"center\">";
		for ($i=0;$i<sizeof($fields);$i++)
			echo "<th>".$fields[$i]."</th>";
		echo "\n";
		echo "</tr></thead>\n";
	
		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			echo "<tr class=\"result\">\n";
			foreach ($row as $item) {
						echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
					}
			echo "</tr>\n";
			}
		oci_free_statement($stid);
	}
?>
</body>
</html>