<?php
	//Using user tables
	session_start();
	require_once('dbparam.php');
	$context=isset($_POST['context'])?$_POST['context']:'field';
?>
<html>
	<head>
		<title>DBSearch</title>
		<link rel="stylesheet" href="css/style.css" type="text/css"></style>
		<script src="js/jquery.min.js" type="text/javascript"></script>
		
	</head>
	<body>
		<h1>Autostrade <?php echo $_SESSION['which']; ?></h1>
		<form id="form" method="post">
			<?php if($context=="field") { ?> 
				   <input type="text" size="150" id="searchString" name="searchString" placeholder="nome campo (min. 3 car.)" autocomplete="off" list="campi"> 
				   <datalist id="campi">
						<?php
							for ($i=0;$i<sizeof($_SESSION['fields']);$i++) {	
						?>
								<option value="<?php echo $_SESSION['fields'][$i]; ?>"><?php echo $_SESSION['fields'][$i]; ?> </option>
								<?php
								}
								?>
					</datalist>
				   
				   
				   <?php } 
				   
			
			
				  if($context=="table") { ?>  
					<input type="text" size="150" name="searchString" placeholder="nome campo (min. 3 car.)" autocomplete="off" list="tabelle" > 
					<datalist id="tabelle">
						<?php
							for ($i=0;$i<sizeof($_SESSION['tables']);$i++) {	
						?>
								<option value="<?php echo $_SESSION['tables'][$i]; ?>"><?php echo $_SESSION['tables'][$i]; ?> </option>
								<?php
								}
								?>
					</datalist>
			<?php
				}
			?>
			<fieldset id="radio">
				<input type="radio" name="context" id="field" value="field" <?php if($context=="field") echo"checked"; ?>><label for="field">Campo</label>
				<input type="radio" name="context" id="table" value="table" <?php if($context=="table") echo"checked"; ?>><label for="table">Tabella</label>
			</fieldset>
			<input type="submit">
		</form>
		<?php
				$search_field=isset($_POST['searchString'])?(strtoupper($_POST['searchString'])):"nil";
				
				if(strlen($search_field)>3){
					echo "<table class=\"blueTable\">\n";
					switch($context){
						case ("field"): { 
							$stid = oci_parse($db, "SELECT column_name, table_name, data_type, data_length FROM user_tab_cols WHERE column_name LIKE '%$search_field%' ORDER by table_name,column_name"); 
							oci_execute($stid);
							echo "<thead><tr align=\"center\"><th>NOME CAMPO</th><th>TABELLA</th><th>TIPO</th><th>LUNGHEZZA</th></tr></thead>\n";
							while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
								$i=0;
								echo "<tr class=\"result\">\n";
								foreach ($row as $item) {
										if($i!=1) echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
										else echo "<td><a class=\"popup\" href=\"viewTable.php?table=".$item."\">".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</a></td>\n";
										$i++;
									}
								echo "</tr>\n";
								}
							oci_free_statement($stid);
							break; 
							}
						case ("table"): { 
							$stid = oci_parse($db, "SELECT table_name FROM user_tables WHERE table_name LIKE '%$search_field%' ORDER by table_name"); 
							oci_execute($stid);
							echo"<thead><tr align=\"center\"><th>TABELLA</th></tr></thead>\n";
							while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
								echo "<tr class=\"result\">\n";
								foreach ($row as $item) {
											echo "<td><a class=\"popup\" href=\"viewTable.php?table=".$item."\">".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</a></td>\n";
										}
								 
								}
								echo "</tr>\n";
							}
							oci_free_statement($stid);
							break;
						}
					echo "</table>\n";
				}
		?>	
		<script type="text/javascript">
			$(document).ready(function() {
					$('.popup').click(function() {
						var newwindow = window.open($(this).prop('href'), '', 'height=800,width=800');
						if (window.focus) {
							newwindow.focus();
						}
						return false;
					});
					
					$(".blueTable tr").click(function() {
						var selected = $(this).hasClass("highlight");
						$(".blueTable tr").removeClass("highlight");
						if(!selected){
								$(this).removeClass("result");
								$(this).addClass("highlight");
								}
						else
							$(this).addClass("result");
					});
					
					 $('#radio input[type=radio]').change(function(){
						$("#form").submit();
					});
		});
		</script>
		
	</body>
</html>