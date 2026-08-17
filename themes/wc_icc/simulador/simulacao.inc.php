<?php
    $simulacao = "
<style type='text/css'> .master {border-collapse: collapse;border-spacing: 0;border: 1px solid #bbb;width:100%;max-width:800px;margin:0 auto;background-color:#fff;}.master td,th {border: 1px solid #bbb;border-collapse: collapse;padding: 4px 8px;} .master legend {color: #781f1c;}.master fieldset {border: 2px solid #781f1c;border-radius: 6px;-moz-border-radius: 6px;-webkit-border-radius: 06px;-ms-border-radius: 6px;-o-border-radius: 6px; padding: 6px; clear:both;} .master fieldset table{ border-collapse: collapse; border-spacing: 0; border: 1px solid #bbb !important; width:100%; max-width:800px; margin:0 auto; background-color:#F5F5F5 !important; color:#333;}.master fieldset table th{background-color:#781f1c; color:#ffffff; border:1px solid #781f1c;}.img { float:left; margin: 2%; width: 25%; border:0;}.clear {clear:both !important;}.center { width: 100%; text-align:center;} page { background: white; display: block; margin: 0 auto; margin-bottom: 0.5cm; box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5); } page[size= 'A4'] { width: 21cm; height: 29.7cm; } page[size='A4][layout='portrait'] { width: 29.7cm; height: 21cm;' } @media print { body, page { margin: 0; } .header { padding-top: 10px; text-align: center; border: 2px solid #ddd; } table { border-collapse: collapse; width: 100%; font-size: 80%; } table th { background-color: #4caf50; color: white; text-align: center; } th, td { border: 1px solid #ddd; text-align: left; } tr:nth-child(even) { background-color: #f2f2f2 } .no-print{display: none; }}
</style>
<div id='result_simulator'>
<table class='master' cellpadding='7px' align='center' border='0' style='border-collapse: collapse;border-spacing: 0;border: 2px solid #bbb;width:100%;max-width:800px;margin:0 auto;background-color:#fff;'>
  <tr>
    <td align=\"center\" width=\"30%\" height=\"150\" border=\"1\">
        <img class=\"logo\" style=\" width: 80%; margin: 5px auto; \" src=\"https://iccserra.org.br/themes/wc_icc/images/iccserra-logo-box.png\">
    </td>
    <td width=\"70%\" align=\"center\" colspan=\"2\">
        <p style='line-height: 25px;'>". SITE_ADDR_ADDR ."  <br>
            ". SITE_ADDR_DISTRICT ." - CEP: ". SITE_ADDR_ZIP ." <br>
            ". SITE_ADDR_CITY ." - ". SITE_ADDR_UF." <br>
            Fone: ". SITE_ADDR_PHONE_A." <br>
            E-mail: <a href=\"mailto:". SITE_ADDR_EMAIL ."\">". SITE_ADDR_EMAIL ."</a>
        </p>
    </td>
  </tr>
  <!--DADOS PESSOAIS-->
  <tr>
    <td align=\"center\" colspan=\"3\">$date</td>
  </tr>
  <tr>
    <td colspan=\"3\">
        <fieldset style=\"border: 2px solid #781f1c; border-radius: 0.6em; clear:both;\">
            <legend style=\" color: #781f1c; \"><b>DADOS PESSOAIS</b></legend>
            <table width=\"100%\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\" style='border-collapse: collapse;border-spacing: 0;border: 1px solid #bbb;width:100%;max-width:800px;margin:0 auto;background-color:#fff;'>
                <tr>
                  <td colspan=\"2\"><strong>Nome:</strong> $nome</td>
                </tr>
                <tr>
                  <td><strong>E-mail:</strong> $email</td>
                  <td><strong>Telefone:</strong> $telefone</td>
                </tr>
                <tr>
                  <td><strong>Cidade:</strong> $cidade</td>
                  <td><strong>Estado:</strong> $estado</td>
                </tr>
            </table>
        </fieldset>
     </td>
  </tr>
  ";
    $simulacao .= "<tr>
    <td colspan=\"3\">
        <fieldset style=\"border: 2px solid #781f1c; border-radius: 0.6em; clear:both;\">
	<legend style=\" color: #781f1c; \"> Resultado da simulação: <b>VALORES</b></legend>
	
	<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\" style='border-collapse: collapse;border-spacing: 0;border: 1px solid #bbb;width:100%;max-width:800px;margin:0 auto;background-color:#fff;'>
      <tr>
       <td colspan=\"2\">Tipo de capital: $modalidade </td>
	  <tr>
       <td colspan=\"2\">Modalidade: $tipo_credito</td>
				
	  <tr><td>Valor solicitado:</td>
        ";

    $simulacao .= "<td>";
    $simulacao .= "<strong style=\"font-size: 20px; \"> R\$   {$Simulacao['valor']} </strong>";
    $simulacao .= "</td>";
    $simulacao .= "</tr>";
    $simulacao .= "<tr>";
    $simulacao .= "<td>Valor *TAC:</td><td>";
    $simulacao .= "R\$ " . $Simulacao['tac'];
    $simulacao .= "</td>
		</tr>
		<tr><td>Data da solicitação: </td>
                            <td>";

    $simulacao .= $dia . "/" . $mes . "/" . $ano;
    $simulacao .= "</td>
                            </tr>
                            <tr>
                                <td>Quantidade de parcelas: </td>
                                <td>";
    $simulacao .= $parcelas . " parcelas";
    $simulacao .= "</td>
                            </tr>
                                <tr>
                                    <td colspan=\"2\"><font style=\"text-shadow: 1px 1px 1px #fff; font-weight:bold;\" color=\"#D12421\">* Crédito Sujeito à Análise.<br>* Parcelas Sujeitas a alterações conforme Contratação.</font>
                                    </td>
                                    </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
  
  <!--RETORNO PARCELAS-->
  <tr>
    <td colspan=\"3\">
    	<fieldset style=\"border: 2px solid #781f1c; border-radius: 0.6em; clear:both;\">
	<legend style=\" color: #781f1c; \"> Resultado da simulação:<b> PARCELAS </b> </legend>
	<table width=\"100%\" border=\"1\" cellspacing=\"2\" cellpadding=\"3\" style='border-collapse: collapse;border-spacing: 0;border: 1px solid #bbb;width:100%;max-width:800px;margin:0 auto;background-color:#fff; text-align: center !important;'>
    
    <tr style=\"background-color:#781f1c; color:#ffffff; border:1px solid #781f1c;\">
        <th scope=\"col\" style=\"text-align:center\">PARCELA</th>
        <th scope=\"col\" style=\"text-align:center\">VALOR</th>
        <th scope=\"col\" style=\"text-align:center\">DATA DE VENCIMENTO</th>
    </tr>";

    $meses[$mes] = ($meses[$mes] == '12' ? 'Dezembro' : $mes);

    for ($i = 0; $i < $parcelas; $i++) {

        $simulacao .= "<tr>";
        $simulacao .= "<td>";
        $simulacao .= $i + 1 . "&deg; Parcela";
        $simulacao .= "</td>";
        $simulacao .= "<td>";
        $simulacao .= " R\$ " . $Simulacao['valor_parcela'];
        $simulacao .= "</td>";
        $simulacao .= "<td>";

        //Atualiza ANO em Dezembro
        if ($meses[$mes] == 12 || $meses[$mes] == "Dezembro") {
            $ano++;
            $mes = 1;
        } else {
            $mes++;
        }

        $simulacao .= $dia . " de " . $meses [$mes] . " de " . $ano;
        $simulacao .= "</td>";
        $simulacao .= "</tr>";

    }

    $simulacao .= "</table>";
    $simulacao .= "</fieldset>";
    $simulacao .= '<a href="'.BASE.'/simulador#form_simulador" class="wc_goto no-print" title="Fazer nova Simulação"><img src="https://iccserra.org.br/themes/wc_icc/images/nova_simulacao.jpg" alt="Nova simulação"/></a>';

    $simulacao .= '<a href="" class="no-print" onclick="cont();" title="Imprimir"><img src="https://iccserra.org.br/themes/wc_icc/images/print.gif" alt="Imprimir"/></a>';
    $simulacao .= "</td>";
    $simulacao .= "</tr>";
    $simulacao .= "</table>";
    $simulacao .= "</div><br><br>";
