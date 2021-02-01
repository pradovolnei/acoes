<?php 

	$con = mysqli_connect('localhost', "root", '');
	mysqli_select_db($con, 'acoes');
	
	ini_set('default_charset','UTF-8');
	mysqli_set_charset($con, "utf8");
	
	echo "<table border=1>";
	echo "<tr>";
	echo "<th> Ação </th>";
	echo "<th> Valor </th>";
	echo "<th> Data </th>";
	echo "<th> Variação </th>";
	echo "</tr>";
	$list = "SELECT * FROM variacao WHERE acao='".$_GET["a"]."'";
	$exec = mysqli_query($con,$list);
	$x=1;
	while($col = mysqli_fetch_array($exec)){
		echo "<tr>";
		
		echo "<td> ".$col["acao"]." </td>";
		echo "<td> ".number_format($col["valor"], 2, ',', '.')." </td>";
		echo "<td> ".$col["data"]." </td>";
		
		echo "<td>";
		$valor[$x] = $col["valor"];
		if($x == 1){
			echo "--";
		}else{
			$varia = $valor[$x]-$valor[$x-1];
			if($varia>0)
				echo "<font color='green'> ".number_format($varia, 2, ',', '.')." </font>";
			elseif($varia<0)
				echo "<font color='red'> ".number_format($varia, 2, ',', '.')." </font>";
			else
				echo "<font color='black'> ".number_format($varia, 2, ',', '.')." </font>";
		}
		echo "</td>";
		
		echo "</tr>";
		$x++;
	}
	echo "</table>";

?>