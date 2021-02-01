
<form action="" method="post">
	<label> Ação </label>
	<input type="text" name="acao" />
	<br><br>
	<label> Valor </label>
	<input type="text" name="valor" />
	<br><br>
	<input type="submit" name="salvar" value="salvar" />
</form>
<?php 
	
	$con = mysqli_connect('localhost', "root", '');
	mysqli_select_db($con, 'acoes');
	
	ini_set('default_charset','UTF-8');
	mysqli_set_charset($con, "utf8");
	
	$sql_saldo = "SELECT * FROM saldo ORDER BY id DESC LIMIT 1";
	$exec_saldo = mysqli_query($con,$sql_saldo);
	$row = mysqli_fetch_array($exec_saldo);
	$meu_saldo = $row["saldo"];
	$troco = $row["troco"];
	//$tantos = $row["quantidade"];
	
	if(isset($_POST["salvar"])){
		$sql = "INSERT INTO variacao VALUES('".strtoupper($_POST["acao"])."',".str_replace(",",'.',$_POST["valor"]).",NOW())";
		mysqli_query($con,$sql);
		
		$sql_carteira = "SELECT * FROM carteira WHERE acao='".strtoupper($_POST["acao"])."'";
		$exec = mysqli_query($con,$sql_carteira);
		if(mysqli_num_rows($exec)>0){
			while($col = mysqli_fetch_array($exec)){
				//$tantos = $col["quantidade"];
				$meu_saldo -= ($col["valor"])*$col["quantidade"];
				$meu_saldo += (str_replace(",",'.',$_POST["valor"]))*$col["quantidade"];
				$up = "INSERT INTO saldo VALUES(NULL,$meu_saldo,$troco,NOW())";
				mysqli_query($con,$up);
			}	
		}
	}
	
	if(isset($_POST["sub"])){
		if($_POST["exist"] == "")
		$sql = "INSERT INTO carteira VALUES('".strtoupper($_POST["acao"])."',".str_replace(",",'.',$_POST["valor"]).",".str_replace(",",'.',$_POST["qtd"])." ,NOW())";
		else
		$sql = "UPDATE carteira SET quantidade=quantidade+".str_replace(",",'.',$_POST["qtd"])." WHERE acao='".strtoupper($_POST["acao"])."'";	
		
		mysqli_query($con,$sql);
		
		$sql = "INSERT INTO variacao VALUES('".strtoupper($_POST["acao"])."',".str_replace(",",'.',$_POST["valor"]).",NOW())";
		mysqli_query($con,$sql);
		
		$meu_saldo += str_replace(",",'.',$_POST["valor"])*str_replace(",",'.',$_POST["qtd"]);
		$troco -= str_replace(",",'.',$_POST["valor"])*str_replace(",",'.',$_POST["qtd"]);
				$up = "INSERT INTO saldo VALUES(NULL,$meu_saldo,$troco,NOW())";
				mysqli_query($con,$up);
	}
	
	if(isset($_POST["atlz"])){
		
		$sql = "INSERT INTO variacao VALUES('".strtoupper($_POST["acao"])."',".str_replace(",",'.',$_POST["valor"]).",NOW())";
		mysqli_query($con,$sql);
		
		$sql_carteira = "SELECT * FROM carteira WHERE acao='".strtoupper($_POST["acao"])."'";
		$exec = mysqli_query($con,$sql_carteira);
		if(mysqli_num_rows($exec)>0){
			while($col = mysqli_fetch_array($exec)){
				//$tantos = $col["quantidade"];
				$meu_saldo -= ($col["valor"])*$col["quantidade"];
				$meu_saldo += (str_replace(",",'.',$_POST["valor"]))*$col["quantidade"];
				$up = "INSERT INTO saldo VALUES(NULL,$meu_saldo,$troco,NOW())";
				mysqli_query($con,$up);
			}	
		}
	}
	
	if(isset($_POST["vend"])){
		if($_POST["qtd"] >= $_POST["quant"])
		$sql = "DELETE FROM carteira WHERE acao='".strtoupper($_POST["acao"])."'";
		else
		$sql = "UPDATE carteira SET quantidade=quantidade-".$_POST["qtd"]." WHERE acao='".strtoupper($_POST["acao"])."'";	
		
		mysqli_query($con,$sql);
		
		$sql = "INSERT INTO variacao VALUES('".strtoupper($_POST["acao"])."',".str_replace(",",'.',$_POST["valor"]).",NOW())";
		mysqli_query($con,$sql);
		
		$meu_saldo -= str_replace(",",'.',$_POST["valor"])*str_replace(",",'.',$_POST["qtd"]);
		$troco += str_replace(",",'.',$_POST["valor"])*str_replace(",",'.',$_POST["qtd"]);
				$up = "INSERT INTO saldo VALUES(NULL,$meu_saldo,$troco,NOW())";
				mysqli_query($con,$up);
	}
	
	echo "<h2>Situação Atual</h2>";
	echo "<table border=1>";
	echo "<tr>";
	echo "<th> Carteira </th>";
	echo "<th> Disponível </th>";
	echo "<th> Total </th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td> $meu_saldo </td>";
	echo "<td> $troco </td>";
	echo "<td> ".($meu_saldo+$troco)." </td>";
	echo "</tr>";
	echo "</table>";
	
	echo "<br><br>";
	
	echo "<table border=1>";
	echo "<tr>";
	echo "<th> Ação </th>";
	echo "<th> Valor </th>";
	echo "<th> Quantidade </th>";
	echo "<th colspan=2> Vender </th>";
	echo "</tr>";
	$list = "SELECT * FROM carteira";
	$total = 0;
	$exec = mysqli_query($con,$list);
	while($col = mysqli_fetch_array($exec)){
		echo "<tr>";
		$total += $col["valor"]*$col["quantidade"];
		echo "<td> <a href='acoes.php?a=".$col["acao"]."'>".$col["acao"]."</a> </td>";
		echo "<td> ".$col["valor"]." </td>";
		
		echo "<form action='' method='post'>";
		echo "<td> <input type='number' name='qtd' max=".$col["quantidade"]." value=".$col["quantidade"]."> <input type='hidden' name='acao' value='".$col["acao"]."' /> <input type='hidden' name='quant' value='".$col["quantidade"]."' /></td>";
		echo "<td><input type='text' name='valor' />  <input type='submit' name='vend' value='Vender' /></td>";
		echo "</form>";
		
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td colspan=5> <b> R$ $total </b> <a href='acoes3.php?t=".base64_encode($total)."'>Previsão</a> </td>";
	echo "</tr>";
	echo "</table>";
	
	echo "<br><br>";
	
	echo '<table id="example2" class="table table-bordered table-hover">';
	echo "<tr>";
	echo "<th> Ação </th>";
	echo "<th> Ultimo Valor </th>";
	echo "<th> Mínimo </th>";
	echo "<th> Máximo </th>";
	echo "<th colspan=2> Comprar </th>";
	echo "</tr>";
	$list = "SELECT a.data,a.acao,a.valor,b.acao as 'exist', max(a.valor) as 'max', min(a.valor) as 'min' FROM variacao a left join carteira b on b.acao = a.acao GROUP BY a.acao order by a.acao,a.data desc";
	$exec = mysqli_query($con,$list);
	while($col = mysqli_fetch_array($exec)){
		echo "<tr>";
		
		echo "<td> <a href='acoes.php?a=".$col["acao"]."'>".$col["acao"]."</a> </td>";
		$sql_valor = "SELECT valor FROM variacao WHERE acao='".$col["acao"]."' AND data=(SELECT MAX(data) FROM variacao WHERE acao='".$col["acao"]."')";
		$exec_valor = mysqli_query($con,$sql_valor);
		$row = mysqli_fetch_array($exec_valor);
		echo "<td> ".$row["valor"]." </td>";
		
		echo "<td> ".$col["min"]." </td>";
		echo "<td> ".$col["max"]." </td>";
		
		$disabled = null;
		if($row["valor"]>$troco)
			$disabled = "disabled";
		echo "<form action='' method='post'>";
		echo "<td> <input type='text' name='valor' value='".$row["valor"]."' /> <input type='hidden' name='acao' value='".$col["acao"]."' /> <input type='hidden' name='exist' value='".$col["exist"]."' /></td>";
		echo "<td> <input type='number' name='qtd' value=1 > <input type='submit' name='sub' $disabled value='Comprar' /> <input type='submit' name='atlz' value='Atualizar' /></td>";
		echo "</form>";
		
		echo "</tr>";
	}
	echo "</table>";
?>
