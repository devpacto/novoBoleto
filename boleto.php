<?php
    ob_start();
    include_once "conexaoPhp.php";
    
    $link = base64_decode($_GET['q']);
    $arrayLink = explode('&', $link);
    $arrayLinkLimpo = array_filter($arrayLink);
    
    $numApa        = $arrayLinkLimpo[1];
    $seq           = $arrayLinkLimpo[2];
    $IdN           = $arrayLinkLimpo[3];
    $identificador = $arrayLinkLimpo[4];
    $nomepagador   = $arrayLinkLimpo[5];
    $chave         = $arrayLinkLimpo[6];
    $tipo          = $arrayLinkLimpo[7];
    
    //Arquivo com chave
    $arquivoChave = 'tempBO/'.$identificador.'.txt';

    //verifica se existe o arquivo com o identificador
    if(file_exists($arquivoChave))
    {   
        // Abre o Arquvio no Modo r (para leitura)
        $arquivo = fopen ($arquivoChave, 'r');
        
        //Lê o conteúdo do arquivo
        while(!feof($arquivo))
        {
            //Mostra uma linha do arquivo
            $linha = fgets($arquivo,1024);

            //Verifica se a chave que está no arquivo é igual a chave enviada via Get. 
            if($linha == $chave)
            {
                $boleto = 'Validado';
            }
            else
            {
                if($tipo == 'C'){
                   // envia para pagina de boleto, pois se não houver sessao terá que informar os dados novamente
                   header("location: https://www.pactonet.com.br/condViaAviso.asp"); die('Não ignore meu cabeçalho...');  
                }
                else if($tipo == 'S') {
                   // envia para pagina de boleto, pois se não houver sessao terá que informar os dados novamente
                   header("location: https://www.pactonet.com.br/sindViaAviso.asp"); die('Não ignore meu cabeçalho...');  
                }
                else{
                    header("location: https://www.pactonet.com.br"); die('Não ignore meu cabeçalho...'); 
                }
                
            }
        }
        //Fecha arquivo aberto
        fclose($arquivo);
    }
    else{
        if($tipo == 'C'){
            // envia para pagina de boleto, pois se não houver sessao terá que informar os dados novamente
            header("location: https://www.pactonet.com.br/condViaAviso.asp"); die('Não ignore meu cabeçalho...');  
        }
         else if($tipo == 'S') {
            // envia para pagina de boleto, pois se não houver sessao terá que informar os dados novamente
            header("location: https://www.pactonet.com.br/sindViaAviso.asp"); die('Não ignore meu cabeçalho...');  
         }
         else{
             header("location: https://www.pactonet.com.br"); die('Não ignore meu cabeçalho...'); 
         }
    }

    //------------------------------- FIM VERIFICAÇÃO DE CHAVE PARA GERAR BOLETO -------------------------------
       
    if(isset($boleto) && $boleto == 'Validado')
    {    

        $sql     = '';
        $sqlDb   = '';

        function Mask($mask,$str)
        {
            $str = str_replace(" ","",$str);

            for($i=0;$i<strlen($str);$i++){
                $mask[strpos($mask,"#")] = $str[$i];
            }

            return $mask;
        }

        $obj    = new conexao();
        $sql    = $obj->conectar();
        $query = 
        "
            SELECT Asac0002.NUMAPA
                 , Asac0002.PERIOD
                 , Asac0002.NUMSEQ
                 , Asac0002.TOTGUIA
                 , Asac0002.NUMPRE
                 , Asac0002.DESCR1
                 , Asac0002.DESCR2
                 , Asac0002.DESCR3
                 , Asac0002.DESCR4
                 , Asac0002.DESCR5
                 , Asac0002.DESCR6
                 , Asac0002.DESCR7
                 , Asac0002.DESCR8
                 , Asac0002.DESCR9
                 , Asac0002.DESCR10
                 , Asac0002.VALOR1
                 , Asac0002.VALOR2
                 , Asac0002.VALOR3
                 , Asac0002.VALOR4
                 , Asac0002.VALOR5
                 , Asac0002.VALOR6
                 , Asac0002.VALOR7
                 , Asac0002.VALOR8
                 , Asac0002.VALOR9
                 , Asac0002.VALOR10
                 , Asac0002.TIPPRE
                 , Asac0002.DATVEN
                 , Asac0002.SITUAC
                 , Asac0002.CODSIT
                 , ASAC0002.REGNOM 
             FROM  Asac0002
            WHERE  NUMCON = $IdN
              AND  NUMAPA = $numApa
              AND  NUMSEQ = $seq
        ";      

        $stmt = sqlsrv_query($sql, $query);
        if( $stmt === false) 
        {   
            echo"<pre>";
                print_r( sqlsrv_errors());
            echo"</pre>";
        }
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $numapa  = $row["NUMAPA"];
            $periodo  = $row["PERIOD"]; 
            $numseq  = $row["NUMSEQ"]; 
            $totguia = $row["TOTGUIA"];
            $numpre  = $row["NUMPRE"]; 
            $descr1  = (isset($row["DESCR1"])  ? $row["DESCR1"] : ""); 
            $descr2  = (isset($row["DESCR2"])  ? $row["DESCR2"] : ""); 
            $descr3  = (isset($row["DESCR3"])  ? $row["DESCR3"] : ""); 
            $descr4  = (isset($row["DESCR4"])  ? $row["DESCR4"] : ""); 
            $descr5  = (isset($row["DESCR5"])  ? $row["DESCR5"] : "");
            $descr6  = (isset($row["DESCR6"])  ? $row["DESCR6"] : ""); 
            $descr7  = (isset($row["DESCR7"])  ? $row["DESCR7"] : ""); 
            $descr8  = (isset($row["DESCR8"])  ? $row["DESCR8"] : ""); 
            $descr9  = (isset($row["DESCR9"])  ? $row["DESCR9"] : ""); 
            $descr10 = (isset($row["DESCR10"]) ? $row["DESCR10"] : "");
            $valor1  = (isset($row["VALOR1"]) ?  $row["VALOR1"] : 0);
            $valor2  = (isset($row["VALOR2"]) ?  $row["VALOR2"] : 0); 
            $valor3  = (isset($row["VALOR3"]) ?  $row["VALOR3"] : 0);
            $valor4  = (isset($row["VALOR4"]) ?  $row["VALOR4"] : 0);
            $valor5  = (isset($row["VALOR5"]) ?  $row["VALOR5"] : 0); 
            $valor6  = (isset($row["VALOR6"]) ?  $row["VALOR6"] : 0);
            $valor7  = (isset($row["VALOR7"]) ?  $row["VALOR7"] : 0);
            $valor8  = (isset($row["VALOR8"]) ?  $row["VALOR8"] : 0);
            $valor9  = (isset($row["VALOR9"]) ?  $row["VALOR9"] : 0); 
            $valor10 = (isset($row["VALOR10"]) ? $row["VALOR10"] : 0);
            $tippre  = $row["TIPPRE"]; 
            $datven  = $row["DATVEN"]; 
            $situac  = $row["SITUAC"];
            $codsit  = $row["CODSIT"]; 
            $regnom  = $row["REGNOM"];

            // total das somas de todos os valores de taxas
            $totsim = ($valor1 + $valor2 + $valor3 + $valor4 + $valor5 + $valor6 + $valor7 + $valor8 + $valor9 + $valor10);
        }

        $totalPagar = number_format($totsim, 2, ',', '.');

        //-------------------------------------------------------------------------------------------------------------------------
        // manipulação das datas
        //-------------------------------------------------------------------------------------------------------------------------

        $dataVenc = explode('/', $datven);
        $datvenorig = (checkdate($dataVenc[1], $dataVenc[0], $dataVenc[2]) ? $datven : null);
        // data vencimento formato americano
        $dtven   = $dataVenc[2]."-".$dataVenc[1]."-".$dataVenc[0];
        // calculo para gerar desconto, apenas algumas unidades possui
        $datAtual =  date('d/m/Y');
        // data atual convertida padrão Y-m-d
        $dtAtual = date('Y-m-d');

        //-------------------------------------------------------------------------------------------------------------------------
        // calculo desconto
        //-------------------------------------------------------------------------------------------------------------------------

        $desconto = number_format(0, 2);

        // verifica os predios que fossui desconto
        if(strtotime($dtven) >=  strtotime($dtAtual)){
            $query1 = 
            "
                SELECT Asac0068.NUMCON
                     , Asac0068.NUMAPA
                     , Asac0068.NUMPRE
                     , Asac0068.TIPPRE
                     , Asac0068.VALOR
                  FROM Asac0068 
                 WHERE NUMCON = $IdN
                   AND NUMAPA = $numApa
                   AND NUMPRE = $numpre
                   AND TIPPRE = $tippre
            ";
            $stmt1 = sqlsrv_query($sql, $query1);
            $rowDes = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);

            $desconto = $rowDes["VALOR"];

            // calcula novamente o valor total
            if($desconto > 0){
                $totalPagar = ($totsim - $desconto);
            }
        }

        //-------------------------------------------------------------------------------------------------------------------------
        // calculos para elaboração do juros, somente para boletos vencidos   
        //-------------------------------------------------------------------------------------------------------------------------

        if(strtotime($dtven) < strtotime($dtAtual)){
            $datven = $datAtual;

            // pega os valores para efetuar os calculos
            $query2 = "SELECT PERMUL, PERMUL3, MULTADIA FROM Asac0003 WHERE NUMCON = $IdN ";
            $stmt2 = sqlsrv_query($sql, $query2);
            $rowJuros = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);

            //JUROS COBRADO UMA VEZ APOS VENCIMENTO
            $partes = explode("/", $datAtual);
            $anoVen = $partes[2] ;
            //echo"Ano vencimento ".$anoVen."<br>";

            if($anoVen < 2003){
                $permul = $rowJuros["PERMUL"];
            }
            else{
                //PERMUL JUROS COBRADO APÓS ANO 2003
                $permul = $rowJuros["PERMUL3"];
            }

            // total juros
            $Juros    = ($permul/100);
            $totJuros = $totsim * $Juros;

            //MULTA COBRADA POR DIA APOS VENCIMENTO
            $multaDia = $rowJuros["MULTADIA"];

            // no php é necessario formatar a data no padrao americano, para poder ver o dia certo
            $dtOriVenc = $dataVenc[2]."-".$dataVenc[1]."-".$dataVenc[0];

            //DIA SEMANA VENCIMENTO
            $diaSemana = date('w', strtotime($dtOriVenc));
            //echo"diaSemana venc original ".$diaSemana."<br>";

            //diaSemana: 0 = domingo / 6 = sabado
            if($diaSemana == 0){ 
                echo"entrou 1<br>";
                //echo date('d/m/Y',strtotime("-1 day", strtotime($datAtual)));

                //$diasVenc = ((strtotime($datAtual) - 1) - $datvenorig);

            } elseif($diaSemana == 6) {
                echo"entrou 2<br>";
                $diasVenc = (($datAtual - 2) - $datvenorig);
            }else{
                $datetime1 = new DateTime($dtAtual);

                // data original de vencimento convertida padrão Y-m-d
                $datetime2 = new DateTime($dtOriVenc);
                $interval = $datetime1->diff($datetime2);

                // quantidade de dias vencidos
                $diasVenc = $interval->days;
            }

            $tot = ($totsim * $multaDia);
            $totMultaDia = round($tot / 100,2);
            $totMulta = $totMultaDia * $diasVenc;

            // a soma dos juros + juros diarios
            $totMulJur = $totJuros + $totMulta;

            //Total a pagar
            $totalPagar = ($totsim + $totMulJur);
        }

        // caso não possua juros 
        if($totMulJur == 0) {
            $totMulJur = number_format(0, 2);
        } else{
            $totMulJur = number_format($totMulJur, 2);
        }

        //-------------------------------------------------------------------------------------------------------------------------
        // dados responsavel colocar acordo com registro morador/ proprietario
        //-------------------------------------------------------------------------------------------------------------------------

        $query3 = 
        "
           SELECT Asac0001.NUMAPA
                , Asac0001.NOMMOR
                , Asac0001.CPFRES
                , Asac0001.ENDMOR
                , Asac0001.PROPRI
                , Asac0001.CPFPRO
                , Asac0003.BAICON
                , Asac0003.CIDADE
                , Asac0003.NUMCEP 
                , Asac0003.NOMCON
                , Asac0003.NUMCGC
            FROM  Asac0001, Asac0003
           WHERE  Asac0001.NUMCON = $IdN
             AND  Asac0001.NUMAPA = $numApa 
             AND  Asac0001.NUMCON=Asac0003.NUMCON
        ";
        // chama a conexão com bdnet
        $stmt3 = sqlsrv_query($sql, $query3);
        $rowMorador = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);

        $endereco   = $rowMorador["ENDMOR"];
        $bairro     = $rowMorador["BAICON"];
        $cidade     = $rowMorador["CIDADE"];
        $nomeCond   = $rowMorador["NOMCON"];
        $n          = $rowMorador["NOMCON"];
        $cnpjCond   = $rowMorador["NUMCGC"];
        $cep        = (isset($rowMorador["NUMCEP"]) ? $rowMorador["NUMCEP"] : '' );

        // verifica se o cep é valido
        if(!empty($cep) || strlen($cep) == 8 ){
             $cep = preg_replace("/^(\d{5})(\d{3})$/", "\\1-\\2", $cep);
        }
        else{
            $cep = $cep;
        }

        // verifica o nome da cidade
        // transforma a variavel em string caso não seja
        $cidade = (string)$cidade;

         if($cidade == "BELO HORIZONTE" || $cidade == "BELO HTE" || $cidade == "BH"){
            $cidade = "BELO HORIZONTE/MG";
        } else {
            $cidade = $cidade;
        }

        if($regnom == "M"){
            $nomeresp = trim($rowMorador["NOMMOR"]);
            $docresp   = trim($rowMorador["CPFRES"]);
        }
        elseif ($regnom == "P"){
            $nomeresp = trim($rowMorador["PROPRI"]);
            $docresp   = trim($rowMorador["CPFPRO"]);
        }
        else{
            $nomeresp = trim($rowMorador["NOMMOR"]);
            $docresp   = trim($rowMorador["CPFRES"]);
        }

        $qtddoc = strlen($docresp);

        if($qtddoc == 11){
            // formatar cpf
            $formatdocresp = Mask("###.###.###-##",$docresp);
        }
        elseif ($qtddoc == 14) {
            // formatar cnpj
            $formatdocresp = Mask("##.###.###/####-##",$docresp);
        }

        //Transforma o nome do condomínio em array
        $arrayNomeCond = explode(" ", $nomeCond);

        //verifica se no array tem a palavra "CONDOMÍNIO" e retira a mesma
        if(stristr($arrayNomeCond[0], 'CONDOMINIO')){
            $nomeCondominio = $nomeCond;
        }
        else{
            $nomeCondominio = 'CONDOMÍNIO '.$nomeCond;
        }

        $tipoDespesa = array(
            $descr1 => $valor1,
            $descr2 => $valor2,
            $descr3 => $valor3,
            $descr4 => $valor4,
            $descr5 => $valor5,
            $descr6 => $valor6,
            $descr7 => $valor7,
            $descr8 => $valor8,
            $descr9 => $valor9,
            $descr10 => $valor10        
        );

        // lista as descrições se houver
        $queryDesc = 
        "
         SELECT Asac0036.DESCR1
              , Asac0036.DESCR2
              , Asac0036.DESCR3
              , Asac0036.DESCR4 
           FROM Asac0036 
          WHERE Asac0036.NUMCON = $IdN
            AND Asac0036.NUMAPA = $numApa
        ";

        $stmtDesc  = sqlsrv_query($sql, $queryDesc);
        $rowDescri = sqlsrv_fetch_array($stmtDesc, SQLSRV_FETCH_ASSOC);

        $Asac0036DESCR1 = $rowDescri["DESCR1"];
        $Asac0036DESCR2 = $rowDescri["DESCR2"];
        $Asac0036DESCR3 = $rowDescri["DESCR3"];
        $Asac0036DESCR4 = $rowDescri["DESCR4"];

        include_once "funcoesBoleto.php";
        $nossoN = "14".formata_numero($numseq,15,0);
        $mNOSNRODV = dgtVerificador_nossonumero($nossoN);
        $nossoNumero = $nossoN."-".$mNOSNRODV;
        $numeroDocumento = "$IdN"." "."$numApa"." "."$numpre"." "."$tippre"." "."$nossoNumero";
        $numeroDoc = "$IdN"." "."$numApa"." "."$numpre"." "."$tippre";
    ?>
    
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                table{
                    font-size: 11px;
                    font-family: Arial, Helvetica, sans-serif;
                    width:100%;
                    border:none;
                }
                td{
                    border:1px solid #000;
                    height: 40px;
                }
                .alto{
                    vertical-align: super;
                }
                .medio{
                    vertical-align: middle;
                }  
                .baixo{
                    vertical-align: bottom;
                }
                .campo{
                    font-size:9px;
                    padding:3px 0 5px 2px;
                }
                .campoInferior{
                    font-size:8px;
                    padding:3px 0 5px 2px;
                }
                .textoInferior{
                    font-size:10px;
                }
            </style>
        </head>
        <body>
            <table>
                <tr>
                    <td class="alto" style="width: 40%;height:20px;">
                        <div class="campo">BANCO ARRECADADOR</div>
                        <strong class="">
                            CAIXA ECONÔMICA FEDERAL
                        </strong>
                    </td>
                    <td style="width: 10%;height:20px; text-align: center; font-size: 16px;">
                        <strong>104-0</strong>  
                    </td>
                    <td style="width: 35%;height:20px; font-size: 10px; font-weight: bold; text-align: center;">
                        <span style="font-size: 9px;">
                            PACTO ADMINISTRADORA - 22.256.978/0001-51
                        </span><br>
                        <span style="font-size: 7px;">
                            RUA DOS TIMBIRAS,638 - FUNCIONÁRIOS - CEP: 30140-060 (31)3218-5000
                        </span>
                    </td>
                    <td class="alto" style="width: 15%;height:20px;background:#ddd;">
                        <div class="campo">IDENTIFICADOR</div>
                        <span class="" style="">
                            <?php echo $identificador;?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="alto" style="width:45%;height:90px;">
                        <div class="campo">NOME E ENDEREÇO DO IMÓVEL</div>
                        <strong style="margin-left:-3px;">
                            <?php echo $nomepagador;?><br>
                            <?php echo $endereco;?><br>
                            <?php echo $bairro;?><br>
                            <?php echo $cidade;?> - MG <?php echo $cep;?>
                        </strong>
                    </td>
                    <td colspan="2" class="alto" style="width:45%">
                        <div class="campo">OBSERVAÇÕES</div>
                        <?php echo $Asac0036DESCR1;?><br>
                        <?php echo $Asac0036DESCR2;?><br>
                        <?php echo $Asac0036DESCR3;?><br>
                        <?php echo $Asac0036DESCR4;?><br>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 6px 0 2px;">
                <tr>
                    <td class="alto" style="width:17%;background:#ddd;">
                        <div class="campo">VENCIMENTO ORIGINAL</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo $datvenorig;?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:33%;background:#ddd;">
                        <div class="campo">DESCRIÇÃO</div>
                        <strong>
                            <?php echo $periodo;?>
                        </strong>
                    </td>
                    <td class="alto" rowspan="4" style="width:25%">
                        <div style="border-bottom:1px solid #000; height:10px;text-align:center;font-size:9px;">
                            DISCRIMINAÇÃO
                        </div>
                        <!-- LOOP -->
                        <?php
                            foreach ($tipoDespesa as $chave => $valor) {
                                if($valor !=0){
                            ?>
                                    <div style="height:15px;padding-left: 2px;">
                                        <?php echo $chave;?>
                                    </div>
                            <?php
                                }
                            }
                        ?>
                        <!-- FIM LOOP -->
                    </td>
                    <td class="alto" rowspan="4" style="width:25%">
                        <div style="border-bottom:1px solid #000; height:10px;text-align:center;font-size:9px;">
                            VALOR
                        </div>
                        <!-- LOOP -->
                        <?php
                            foreach ($tipoDespesa as $chave => $valor) {
                                if($valor !=0){
                            ?>
                                    <div style="height:15px;text-align: right;padding-right: 2px;">
                                       <?php echo number_format($valor, 2, ',', '.');?>
                                    </div>
                            <?php
                                }
                            }
                        ?>
                        <!-- FIM LOOP -->
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="alto" style="width:45%">
                        <div class="campo">A CRÉDITO DE</div>
                        <strong>
                            <?php echo $nomeCondominio; ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="alto" style="width:45%; height:25px;">
                        <div class="campo">NÚMERO DO DOCUMENTO</div>
                        <strong>
                            <?php echo $numeroDocumento;?> 
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="" style="width:45%;padding:8px 0 -5px 0;background:#ddd;">
                        <div style="font-size:9px;text-align:center;">
                            <strong>
                            ESTE RECIBO NÃO QUITA DÉBITOS ANTERIORES POR VENTURA EXISTENTES
                            </strong>
                        </div>
                        <div style="font-size:8px;text-align:center;">
                            VÁLIDO COMO RECIBO APÓS COMPENSAÇÃO DO CHEQUE SEM EMENDAS OU RASURAS
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding:10px 2px 5px 2px; height: 5px !important;">
                        <strong>
                            REPRESENTAÇÃO CÓDIGO DE BARRAS: <span style="font-size:16px;"><?php echo $linhaDigitavel;?></span>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="height:130px;vertical-align:top;border-bottom:none;">
                        <div class="campo" style="">TEXTO INFORMATIVO DE RESPONSABILIDADE DO CONDOMÍNIO (USO EXCLUSIVO PELO SÍNDICO)</div>
                        <div style="position:absolute;top:490px;">
                            <strong>
                                <?php
                                    if(strtotime($dtven) < strtotime($dtAtual))
                                    { 
                                    ?>
                                        VÁLIDO PARA PAGAMENTO ATÉ <?php echo $datven;?>, RECEBER SOMENTE POR R$ <?php echo number_format($totalPagar, 2, ',', '.');?>
                                    <?php
                                    }
                                ?>
                            </strong>
                        </div>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="border-collapse:collapse;padding:0 4px 0 2px;">
                <tr style="background:#ddd;">
                    <td class="alto" style="width:20%;height:20px;">
                        <div class="campo">VENCIMENTO</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo $datvenorig;?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;">
                        <div class="campo">TOTAL SIMPELS</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo number_format($totsim, 2, ',', '.');?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;">
                        <div class="campo">MULTA/MOURA</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo number_format($totMulJur, 2, ',', '.');?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;">
                        <div class="campo">DESCONTO ATÉ O VENCIMENTO</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo ($desconto > 0 ?  "- ". number_format($desconto, 2, ',', '.') : ''); ?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;">
                        <div class="campo">TOTAL A PAGAR</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo number_format($totalPagar, 2, ',', '.');?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="alto" style="border-bottom: 1px dotted #000;border-left:none;border-right:none;height: 20px;">
                        <div class="campo" style="text-align:right;">AUTENTICAÇÃO MECÂNICA / RECIBO DO SACADO</div>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="widht:100%;border-collapse:collapse;padding-top:5px;">
                <tr>
                    <td style="width:25%;border-top:none; border-left:none;border-bottom:none; text-align:center;">
                        <img src="http://www.pactonet.com.br/imagens/logocaixa.jpg"/>
                    </td>
                    <td style="width:10%; text-align:center;border-top:none;border-bottom:none;font-size:16px;">
                        <strong>104-0</strong>
                    </td>
                    <td style="width:65%;border-top:none;border-right:none;border-bottom:none;text-align:right;font-size:16px;">
                        <strong>
                            <?php echo $linhaDigitavel;?>
                        </strong>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width:100%;" >
                <tr>
                    <td colspan="6" class="alto" style="width:80%;height:20px;border-right:none;border-bottom:none;">
                        <div class="campo">LOCAL DE PAGAMENTO</div>
                        <div style="">
                            <strong>
                                PAGÁVEL EM QUALQUER BANCO OU CASAS LOTÉRICAS
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;border-bottom:none;height:20px;background:#ddd;">
                        <div class="campo">VENCIMENTO</div>
                        <div style="text-align:right;">
                            <strong>
                                <?php echo $datvenorig;?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="alto" style="width:80%;border-right:none;height:20px;">
                        <div class="campoInferior">LOCAL DE PAGAMENTO</div>
                        <div style="font-size:10px;">
                            <strong>
                                PACTO ADMINISTRADORA & <?php echo $nomeCondominio.' - '.$cnpjCond;?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;">
                        <div class="campoInferior">AGÊNCIA/CÓDIGO DO CEDENTE</div>
                        <div style="text-align:right;">
                            <strong>
                                1667 / 852726
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="alto" style="width:14%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior">DATA DOCUMENTO</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo $datAtual;?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:17%;height:20px ;border-top:none;border-right:none;">
                        <div class="campoInferior">NÚMERO DOCUMENTO</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo $numeroDoc;?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:7%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior" style="text-align:center;">ESPÉCIE</div>
                        <div style="text-align:center;">
                            <strong>
                                RC
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:6%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior" style="text-align:center;">ACEITE</div>
                        <div style="text-align:center;">
                            <strong>
                                NÃO
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:15%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior">DATA PROCESSAMENTO</div>
                        <div style="text-align:center;">
                            <strong>
                                <br>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:18%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior">C.N.P.J BENEFICIÁRIO</div>
                        <div style="text-align:center;">
                            <strong>
                                22.256.978/0001-51
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;border-top:none;">
                        <div class="campoInferior">NOSSO NÚMERO</div>
                        <div style="text-align:right;">
                            <strong>
                                <?php echo $nossoNumero;?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="alto" style="width:31%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior">DESCRIÇÃO</div>
                        <div style="text-align:center;">
                            <strong>
                                <?php echo $periodo;?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:7%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior" style="text-align:center;">CARTEIRA</div>
                        <div style="text-align:center;">
                            <strong>
                                RG
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:6%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior" style="text-align:center;">MOEDA</div>
                        <div style="text-align:center;">
                            <strong>
                                R$
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:15%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior">QUANTIDADE</div>
                        <div style="text-align:center;">
                            <strong>
                                <br>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:18%;height:20px;border-top:none;border-right:none;">
                        <div class="campoInferior">VALOR</div>
                        <div style="text-align:center;">
                            <strong>
                                <br>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;border-top:none;background:#ddd;">
                        <div class="campoInferior">(=) VALOR DO DOCUMENTO</div>
                        <div style="text-align:right;">
                            <strong>
                                <?php echo number_format($totsim, 2, ',', '.');?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" rowspan="5" class="alto" style="width:80%;border-top:none;border-right:none;">
                        <div class="campoInferior">INSTRUÇÕES PARA PAGAMENTO FORA DO PRAZO</div>
                        <div style="">
                            <strong>
    <!--                            AO BANCO: APÓS O VENCIMENTO PAGÁVEL SOMENTE NA ADMINISTRADORA<BR>
                                COM MULTA / JUROS CONFORME DETERMINAÇÃO DE CADA CONDOMÍNIO OU<BR>
                                RETIRE GUIA&nbsp; &nbsp;ATUALIZADA PELA&nbsp; &nbsp;INTERNET &nbsp; :&nbsp; &nbsp; &nbsp;www.pactoadministradora.com.br

                                 Alexandre pediu para tirar dia 13/09/17 
                                 Leonardo pediu para tirar dia 14/09/17 -->
                                <?php 
                                if(strtotime($dtven) < strtotime($dtAtual))
                                {
                                ?>
                                    VÁLIDO PARA PAGAMENTO ATÉ <?php echo $datven;?>, RECEBER SOMENTE POR R$ <?php echo number_format($totalPagar, 2, ',', '.');?>
                                <?php
                                }
                                ?>
                            </strong>
                        </div>
                    </td>
                    <td class="alto" style="width:20%;height:20px;border-top:none;">
                        <div class="campoInferior">(-) DESCONTO /ABATIMENTO</div>
                        <div style="text-align:right;">
                            <strong>
                                <?php echo ($desconto > 0 ?   "- ". number_format($desconto, 2, ',', '.') : ''); ?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="alto" style="width:20%;height:20px;border-top:none;">
                        <div class="campoInferior">(-) OUTRAS DEDUÇÕES</div>
                        <div style="text-align:right;">
                            <strong>
                                <br>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="alto" style="width:20%;height:20px;border-top:none;">
                        <div class="campoInferior">(+) MORA/MULTA</div>
                        <div style="text-align:right;">
                            <strong>
                                <?php echo number_format($totMulJur, 2, ',', '.');?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="alto" style="width:20%;height:20px;border-top:none;">
                        <div class="campoInferior">(+) OUTROS ACRÉSCIMOS</div>
                        <div style="text-align:right;">
                            <strong>
                                <br>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="alto" style="width:20%;height:20px;border-top:none;">
                        <div class="campoInferior">(=) VALOR COBRADO</div>
                        <div style="text-align:right;">
                            <strong>
                                <?php echo number_format($totalPagar, 2, ',', '.');?>
                            </strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="alto" style="width:80%;border-top:none;border-right:none;height:60px;">
                        <div class="campoInferior">PAGADOR</div>
                        <div style="">
                            <strong style="margin-left:-3px;">
                                <?php echo $nomeresp;?> - CPF/CNPJ: <?php echo $formatdocresp;?><br>
                                <?php echo $endereco;?><br>
                                <?php echo $bairro;?> - <?php echo $cidade;?> - MG <?php echo $cep;?>
                            </strong>
                        </div>
                    </td>
                    <td class="baixo" style="width:20%;border-top:none;border-left:none;padding:0;height:60px;">
                        <div class="campoInferior">CÓDIGO DE BAIXA</div>
                    </td>
                </tr>
            </table>
            <table cellspacing=0 cellpadding=0 style="width:100%">
                <tr>
                    <td style="width:70%;border:none;text-align: center;">
                        <?php fbarcode($codBarras); ?>
                    </td>
                    <td class="alto" style="width:30%;height:60px;border:none;">
                        <div class="campoInferior" style="text-align:right;">
                            AUTENTICAÇÃO MECÂNICA /  FICHA DE COMPENSAÇÃO
                        </div>
                    </td>
                </tr>
            </table>
        </body>
    </html>
    <?php
        $content = ob_get_clean();
        $conteudo = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
        
        require_once(dirname(__FILE__) . './html2pdf/html2pdf.class.php');
        try
        {
            $html2pdf = new HTML2PDF('P','A4','pt');
            $html2pdf->pdf->setTitle('Pacto Administradora - 2 Via Boleto');
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->writeHTML($conteudo, isset($_GET['vuehtml']));
            ob_end_flush ();
            $html2pdf->Output($identificador.'.pdf');
            //Deleta arquivo com a chave
            unlink($arquivoChave);
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }