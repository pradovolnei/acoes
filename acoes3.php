<?php 
	
	$aomes = 1.01;
	
	for($a=1;$a<=20;$a++){
		$aomes *= 1.01;
	}
	
	$aoano = 1.01;
	
	for($a=1;$a<=240;$a++){
		$aoano *= 1.01;
	}

	echo "AO MÊS: $aomes <br><br>";
	echo "AO ANO: $aoano <br><br>";
	$dia = 0;
	//$valor = 34.5;
	$variacao = 1.01;
	//$data = "2019-11-18";
	$feriados = ["01/01/2020","24/02/2020","25/02/2020","26/02/2020","10/04/2020","21/04/2020","01/05/2020","11/06/2020","07/09/2020","12/10/2020","02/11/2020","15/11/2020","25/12/2020", "31/12/2020" , "24/12/2020", "20/11/2020"];
	
	$valor = 885;
	$data_f = "2020-05-25";
	
	//$valor = 1002;
	//$data_f = "2020-06-05";
	
	if(isset($_GET["t"])){
		$valor = base64_decode($_GET["t"]);
		$data_f = date("Y-m-d");
	}
		
	
	while($valor <= 1000000){
		$situacao = "Dia Util! ";
		$data = date('d/m/Y', strtotime("+$dia days",strtotime($data_f)));
		$diames = date('d', strtotime("+$dia days",strtotime($data_f)));
		$diasemana = date('l', strtotime("+$dia days",strtotime($data_f)));
		
		if( $diasemana != "Saturday"  && $diasemana != "Sunday"){
			//if( $diasemana != "Saturday" && $diasemana != "Sunday" && $diasemana != "Friday"){
			if(in_array($data, $feriados))
				$situacao = "Feriado! ";
			else
				$valor *= $variacao;
		}else{
			$situacao = "Final de Semana! ";
		}
		
		echo number_format($valor, 2, ',', '.')." :: ".$data." :: $situacao <br>";
		
		$dia++;
		
		//if($data == "25/04/2020")
			//$valor += 1500;
		
		//if($diames == 1)
			//$valor += 50;
		//if( $diasemana != "Saturday" && $diasemana != "Sunday")
		//$valor *= $variacao;
	
		//if($diames == 1)
		//	$valor += 50;
		
		//$variacao *= 1.001;
	}
	
?>