<?php
$codigobanco = "104";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";

// calcula o fator vencimento
$fator_vencimento = fator_vencimento($datven);

//valor tem 10 digitos, sem virgula
//$valor = "1262,79";//formata_numero(str_replace(".","",$totalPagar),10,0,"valor");
$valorF = number_format($totalPagar, 2, ',', '.');
$valor = formata_numero(str_replace(".","",$valorF),10,0,"valor");

//conta cedente (sem dv) com 6 digitos
$conta_cedente = formata_numero(852726,6,0);

//dv da conta cedente  ------------------------------- VERIFICAR ISSO
$conta_cedente_dv = 1;

//nosso NUMERO (sem dv)  17 digitos

$nossonumero = "14".formata_numero($numseq,15,0);
//$dvnossonumero = dgtVerificador_nossonumero($nossonumero);
$sequenciaNossoNumero = sequenciaNossoNumero($nossonumero);

// campos livre para poder calcular o dv 
$campolivre = $conta_cedente.$conta_cedente_dv.$sequenciaNossoNumero;

// dv do campo livre
$mDVCL = dgVerificador_barra($campolivre);

$mCB = "$codigobanco$nummoeda$fator_vencimento$valor$conta_cedente$conta_cedente_dv$sequenciaNossoNumero$mDVCL";

// dv do codigo de barras
$mCBDv = digitoVerificador_barra($mCB);

$codBarras = substr($mCB,0, 4).$mCBDv.substr($mCB, 4 , 39);
$linhaDigitavel = monta_linha_digitavel($codBarras);

//---------------------------------------------------------------------------------------------------------------------------------------------------------
// SEQUENCIA DO NOSSO NUMERO
//-----------------------------------------------------------------------------------------------------------------------------------------------------------

function sequenciaNossoNumero($nossoNumero) {

    $sequencia1 = substr($nossoNumero, 3, 3);
    $sequencia2 = substr($nossoNumero, 0, 1);
    $sequencia3 = substr($nossoNumero, 6, 3);
    $sequencia4 = substr($nossoNumero, 1, 1);
    $sequencia5 = substr($nossoNumero, 8, 9);

    return $sequencia1.$sequencia2.$sequencia3.$sequencia4.$sequencia5;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITO VERIFICADOR DO NOSSO NUMERO
//-----------------------------------------------------------------------------------------------------------------------------------------------------------

function digitoVerificador_nossonumero($numero) {
     $resto2 = modulo_11($numero, 9, 0);
     $digito = 11 - $resto2;
     if ($digito == 10 || $digito == 11) {
        $dv = 0;
     } else {
        $dv = $digito;
     }
	 return $dv;
}


function dgtVerificador_nossonumero($numero){
    $soma = 0;
    $peso = 1;
    for($i=16; $i>-1; $i--)
    {
        if( ($peso + 1) >9){
            $peso = 2;
        }
        else{
            $peso = $peso + 1;
        }
        $soma += ( (int)($numero[$i]) * $peso );
        $dv--;
    }
    
    $resto = ($soma % 11);
    $vSoma = 11 - $resto;
    
    if ( $vSoma == 0 || $vSoma == 10 ){
        $vSoma = 0; // Se o resto for 0 ou 10 então retorna 0
    }
    return $result = $vSoma;
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITO VERIFICADOR DA BARRA
//-----------------------------------------------------------------------------------------------------------------------------------------------------------

function digitoVerificador_barra($numero) {
    $resto2 = modulo_11($numero, 9, 1);
    if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
        $dv = 1;
    } else {
        $dv = 11 - $resto2;
    }
    return $dv;
}

function dgVerificador_barra($mCAMLIV) {
    $DV    = 23;
    $MULTI = 2;
    $mSOMA = 0;
    
    while ($DV > -1){
        $mSOMA += $MULTI * intval(substr($mCAMLIV,$DV,1));
        $MULTI = ($MULTI +1);
        if ($MULTI > 9){
            $MULTI = 2;
        }
        $DV = $DV-1;
    }
    $mRESTO = $mSOMA % 11;
    $mDVCL = 11 - $mRESTO;
    if ($mDVCL > 9){
        $mDVCL = 0;
    }
    return substr($mDVCL, 0, 1);
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITO VERIFICADOR DA BARRA
//-----------------------------------------------------------------------------------------------------------------------------------------------------------

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITO VERIFICADOR DA BARRA
//-----------------------------------------------------------------------------------------------------------------------------------------------------------

function fbarcode($valor){

$fino = 1 ;
$largo = 3 ;
$altura = 50 ;

  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){
    for($f2=9;$f2>=0;$f2--){
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }


//Desenho da barra


//Guarda inicial
?><img src=imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=imagens/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
<?php
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src=imagens/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img
<?php
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src=imagens/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img
<?php
  }
}

// Draw guarda final
?>
src=imagens/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img
src=imagens/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=imagens/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0>
  <?php
} //Fim da funï¿½ï¿½o

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITO VERIFICADOR DA BARRA
//----------------------------------------------------------------------------------------------------------------------------------------------------------- 

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITO VERIFICADOR DA BARRA
//----------------------------------------------------------------------------------------------------------------------------------------------------------- 

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

function fator_vencimento($data) {
  if ($data != "") {
	$data = explode("/",$data);
	$ano = $data[2];
	$mes = $data[1];
	$dia = $data[0];
    return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
  } else {
    return "0000";
  }
}

function _dateToDays($year,$month,$day) {
    $century = substr($year, 0, 2);
    $year = substr($year, 2, 2);
    if ($month > 2) {
        $month -= 3;
    } else {
        $month += 9;
        if ($year) {
            $year--;
        } else {
            $year = 99;
            $century --;
        }
    }
    return ( floor((  146097 * $century)    /  4 ) +
            floor(( 1461 * $year)        /  4 ) +
            floor(( 153 * $month +  2) /  5 ) +
                $day +  1721119);
}

function modulo_10($num) {
		$numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            $temp = $numeros[$i] * $fator;
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }

        // vï¿½rias linhas removidas, vide funï¿½ï¿½o original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;

}

function modulo_11($num, $base=9, $r=0)  {
    /**
     *   Autor:
     *           Pablo Costa <pablo@users.sourceforge.net>
     *
     *   Funï¿½ï¿½o:
     *    Calculo do Modulo 11 para geracao do digito verificador
     *    de boletos bancarios conforme documentos obtidos
     *    da Febraban - www.febraban.org.br
     *
     *   Entrada:
     *     $num: string numï¿½rica para a qual se deseja calcularo digito verificador;
     *     $base: valor maximo de multiplicacao [2-$base]
     *     $r: quando especificado um devolve somente o resto
     *
     *   Saï¿½da:
     *     Retorna o Digito verificador.
     *
     *   Observaï¿½ï¿½es:
     *     - Script desenvolvido sem nenhum reaproveitamento de cï¿½digo prï¿½ existente.
     *     - Assume-se que a verificaï¿½ï¿½o do formato das variï¿½veis de entrada ï¿½ feita antes da execuï¿½ï¿½o deste script.
     */

    $soma = 0;
    $fator = 2;

    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) {
            // restaura fator de multiplicacao para 2
            $fator = 1;
        }
        $fator++;
    }

    /* Calculo do modulo 11 */
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }
        return $digito;
    } elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}

function monta_linha_digitavel($codigo) {

		// Posiï¿½ï¿½o 	Conteï¿½do
        // 1 a 3    Nï¿½mero do banco
        // 4        Cï¿½digo da Moeda - 9 para Real
        // 5        Digito verificador do Cï¿½digo de Barras
        // 6 a 9   Fator de Vencimento
		// 10 a 19 Valor (8 inteiros e 2 decimais)
        // 20 a 44 Campo Livre definido por cada banco (25 caracteres)

        // 1. Campo - composto pelo cï¿½digo do banco, cï¿½digo da moï¿½da, as cinco primeiras posiï¿½ï¿½es
        // do campo livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 0, 4);
        $p2 = substr($codigo, 19, 5);
        $p3 = modulo_10("$p1$p2");
        $p4 = "$p1$p2$p3";
        $p5 = substr($p4, 0, 5);
        $p6 = substr($p4, 5);
        $campo1 = "$p5.$p6";

        // 2. Campo - composto pelas posiï¿½oes 6 a 15 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 24, 10);
        $p2 = modulo_10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo2 = "$p4.$p5";

        // 3. Campo composto pelas posicoes 16 a 25 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 34, 10);
        $p2 = modulo_10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo3 = "$p4.$p5";

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = substr($codigo, 4, 1);

        // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
		$p1 = substr($codigo, 5, 4);
		$p2 = substr($codigo, 9, 10);
		$campo5 = "$p1$p2";

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
}

function geraCodigoBanco($numero) {
    $parte1 = substr($numero, 0, 3);
    $parte2 = modulo_11($parte1);
    return $parte1 . "-" . $parte2;
}
