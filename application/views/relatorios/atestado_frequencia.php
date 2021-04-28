<?php
require_once("dompdf/autoload.inc.php");
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');


//referenciar o DomPDF com namespace
use Dompdf\Dompdf;



//===================================================================
// CSS para o formulario
$css = '
<style>
#customers {
  border-collapse: collapse;
  width: 100%;
}
#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 3px;
}
#customers tr:nth-child(even){background-color: #f2f2f2;}
#customers tr:hover {background-color: #bcdae9;}
#customers th {
  padding-top: 3px;
  padding-bottom: 3px;
  text-align: center;
  background-color: #0073ae;
  color: white;
}
</style>
';
//===================================================================


//Monta o cabecalho
$imgCabecalhoPath = $_SERVER["DOCUMENT_ROOT"] ."/imagens/relatorios/cabecalho.jpg"; //recupera o caminho da imagem no servidor
//$imgCabecalhoPath = base_url( "/imagens/relatorios/cabecalho.jpg"); //recupera o caminho da imagem no servidor

$htmlStr = '<img style="width: 19cm;" src="'.$imgCabecalhoPath .'" >';
$htmlStr .= '<h3><b><center>Anexo III - Atestado de Frequência Mensal</center></b></h3>';


//Monta as informações basicas do relatorio
$htmlStr .= '

<p><strong>Unidade Acadêmica:</strong> '.$disciplina->unidade_academica.'</p>
<p><strong>Monitor:</strong> '.$monitores->nome.'</p>
<p><strong>Processo Seletivo(Edital/Ano):</strong> '.$monitores->numero_edital.'</p>
<p><strong>Docente Supervisor:</strong> '.$monitoria->nome.'</p>
<p><strong>Unidade Curricular:</strong> '.$disciplina->nome .'</p>
<p><strong>Monitoria renumerada:</strong> '.$monitores->monitoria_remunerada.'</p>
';

//Monta a tabela do relatorio
$htmlStr .= '
<table id="customers">
  <tr>
    <th>Data da Monitoria</th>
    <th>Tipo</th>
    <th>Descrição</th>
    <th>Duração</th>
    <th>Quant. Alunos</th>
  </tr>
';

foreach ($frequencia as $aluno) {
    $htmlStr .= '
      <tr>
        <td>'.date("d/m/Y", strtotime($aluno->data )).'</td>
        <td>'.$aluno->tipo.'</td>
        <td>'.$aluno->descrição.'</td>
        <td>'.$aluno->duracao.'</td>
        <td>'.$aluno->quant_alunos.'</td>
      </tr>
    ';
}

$htmlStr .= '
</table>
';


$htmlStr .= '
<br/> <br/>
<p>
    Declaro que o monitor acima citado cumpriu
    <b>'. str_replace (' ','',substr( $somatorioReuniaoAtestadoFrequencia, 0, -3)) .'</b> hora(s) demais atividades
    e <b>'. str_replace (' ','', substr( $somatorioAulaAtestadoFrequencia, 0, -3)) .'</b> hora(s) de atividade monitoria,
    totalizando <b>'. str_replace (' ','',substr( $cargaHorariaAtestadoFrequencia, 0, -3)) .'</b>
    hora(s) de atividade de monitoria no périodo de '. date("d/m/Y", strtotime($data->data_inicio)) .' a '. date("d/m/Y", strtotime($data->data_fim)) .'
</p>
<br/>
<p style="text-align: center;">

'. date("d/m/Y", strtotime('today')).'
<br><br>
___________________________________________________<br/>
'. $monitoria->nome .' / '. $monitoria->matricula .' <br/>
Professor(a) Supervisor(a) da Monitoria /Nº SIAPE
</p>
';

//Criando a Instancia
$dompdf = new DOMPDF();

//Envia o HTML para o DomPDF
ob_start();

echo $css;
echo $htmlStr;
//die;

$dompdf->loadHtml(ob_get_clean());

$dompdf->setPaper('a4', 'portrait');

//Renderizar o html
$dompdf->render();

//Exibibir a página
$dompdf->stream(
    "atestado_frequência_mensal.pdf",
    array(
        "Attachment" => false //Para realizar o download somente alterar para true
    )
);

?>
