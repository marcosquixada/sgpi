<?php
//define('FPDF_FONTPATH','/sgpi/libs/fpdf/font');
include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/fpdf/fpdf.php");
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

class PDF extends FPDF
{
protected $B = 0;
protected $I = 0;
protected $U = 0;
protected $HREF = '';

function WriteHTML($html)
{
    // HTML parser
    $html = str_replace("\n",' ',$html);
    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            // Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            // Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                // Extract attributes
                $a2 = explode(' ',$e);
                $tag = strtoupper(array_shift($a2));
                $attr = array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])] = $a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    // Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF = $attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    // Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF = '';
}

function SetStyle($tag, $enable)
{
    // Modify style and select corresponding font
    $this->$tag += ($enable ? 1 : -1);
    $style = '';
    foreach(array('B', 'I', 'U') as $s)
    {
        if($this->$s>0)
            $style .= $s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    // Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

$html = '<table>
		     <tr><td>1</td><td></td><td>11</td><td></td><td rowspan=10>imagem</td></tr>
			 <tr><td>2</td><td></td><td>12</td><td></td></tr>
			 <tr><td>3</td><td></td><td>13</td><td></td></tr>
			 <tr><td>4</td><td></td><td>14</td><td></td></tr>
			 <tr><td>5</td><td></td><td>15</td><td></td></tr>
			 <tr><td>6</td><td></td><td>16</td><td></td></tr>
			 <tr><td>7</td><td></td><td>17</td><td></td></tr>
			 <tr><td>8</td><td></td><td>18</td><td></td></tr>
			 <tr><td>9</td><td></td><td>19</td><td></td></tr>
			 <tr><td>10</td><td></td><td>20</td><td></td></tr>
		 </table>';

$pdf = new PDF();
// First page
$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$pdf->SetFont('','U');
$link = $pdf->AddLink();
$pdf->SetFont('');
// Second page
//$pdf->AddPage();
$pdf->SetLink($link);
$pdf->SetLeftMargin(10);
$pdf->SetFontSize(14);
$pdf->WriteHTML($html);
//$pdf->Image($_SERVER['DOCUMENT_ROOT']."/sgpi/_img/logo.png",10,12,30,0,'','http://www.fpdf.org');
$pdf->Output();

/*
$idProcesso = $_GET['idProcesso'];
$html = 'You can now easily print text mixing different styles: <b>bold</b>, <i>italic</i>,
<u>underlined</u>, or <b><i><u>all at once</u></i></b>!<br><br>You can also insert links on
text, such as <a href="http://www.fpdf.org">www.fpdf.org</a>, or on an image: click on the logo.';

class PDF extends FPDF
{
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	
	function WriteHTML($html)
	{
		// HTML parser
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				// Tag
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					// Extract attributes
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag, $attr)
	{
		// Opening tag
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		// Closing tag
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
		// Modify style and select corresponding font
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
		// Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	// Load data
	function LoadData($file)
	{
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line)
			$data[] = explode(';',trim($line));
		return $data;
	}

	function dadosImovel($header, $data)
	{
		for($i=0;$i<count($header);$i++){
			$this->Cell(40,7,$col[i],1);
			$this->Cell(40,7,$data[i],1);
			if ($i % 3 === 0)
				$this->Ln();
		}
	}

	function dadosCliente($header, $data)
	{
		for($i=0;$i<count($header);$i++){
			$this->Cell(40,7,$col[i],1);
			$this->Cell(40,7,$data[i],1);
			if ($i % 2 === 0)
				$this->Ln();
		}
	}
	
	function documentosEntregues($header, $data)
	{
		$this->Cell(20,7,'',1,0);
		$this->Cell(50,7,'',1,0);
		$this->Cell(20,7,'',1,0);
		$this->Cell(50,7,'',1,1);
		$this->Ln();
	}
	
	function historico($header, $data)
	{
		// Column widths
		$w = array(40, 35, 40, 45);
		// Header
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}

	// Colored table
	function FancyTable($header, $data)
	{
		// Colors, line width and bold font
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		// Header
		$w = array(40, 35, 40, 45);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = false;
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
			$this->Ln();
			$fill = !$fill;
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
}

$pdf = new PDF();

// Column headings
/*$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
// Data loading
$data = $pdf->LoadData('countries.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();

$pdf->FancyTable($header,$data);
$pdf->Output();*/



/*$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 11);

$pdf->SetXY(85, 15);
$pdf->Cell(50, 8, 'FICHA DE ACOMPANHAMENTO DE PROCESSO', 0, 1, 'C');
$pdf->Ln(5);
$pdf->setXY(10, 25);
$pdf->SetFont('Arial', 'B', 5);
$pdf->SetFillColor(192,192,192);
$pdf->Cell(190, 7, 'DADOS DO IMOVEL', 1, 1, 'C', true);
$pdf->Ln(5);
//CONSULTA
$cliente = mysql_query("select u.nome, 
                    u.rg, 
                    u.cpf, 
                    u.estado, 
                    u.cidade, 
                    u.bairro,
                    u.logradouro, 
                    u.numero, 
                    u.cep,
                    o.descricao
			from usuario u, 
                 origem o, 
                 processo p,
				 orcamento orc
			where p.id = ".$idProcesso."
			  and orc.idCliente = u.id
			  and p.idOrcamento = orc.id
			  and u.idOrigem = o.id
            order by p.id ") or die(mysql_error());

while($row = mysql_fetch_array($cliente)){
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'NOME', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, utf8_decode($row['nome']), 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'RG', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['rg'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CPF', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['cpf'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'ESTADO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, utf8_decode($row['estado']), 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'CIDADE', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, utf8_decode($row['cidade']), 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'BAIRRO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['bairro'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'LOGRADOURO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, utf8_decode($row['logradouro']), 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'NUMERO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['numero'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CEP', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 8, $row['cep'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'ORIGEM', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, utf8_decode($row['descricao']), 0);
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);

$pdf->Cell(50, 8, utf8_decode('SERVIÇO'), 1, 0, 'C');
$pdf->Ln(15);
//CONSULTA
$servico = mysql_query("select s.descricao
             from servico s,
                  processo p, 
				  orcamento o, 
				  orcamento_servico os
			 where p.id = ".$idProcesso."
			   and os.idServico = s.id
			   and o.id = os.idOrcamento
			   and o.id = p.idOrcamento
			 order by p.id ");

while($row = mysql_fetch_array($servico)){
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, '', 0);
	$pdf->Cell(70, 8, $row['descricao'], 0, 1);
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);

$pdf->Cell(50, 8, utf8_decode('HISTÓRICO'), 1, 0, 'C');
$pdf->Ln(15);
//CONSULTA
$historico = mysql_query("select hp.status, 
                               u.nome, 
                               hp.dtAlteracao, 
                               hp.observacao, 
                               hp.dtPrev							   
             from historico_processo hp,
			      usuario u
			 where hp.idProcesso = ".$idProcesso."
			   and hp.idUsuAlteracao = u.id
			 order by hp.id ");

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 8, 'STATUS', 1);
$pdf->Cell(70, 8, 'ATENDENTE', 1);
$pdf->Cell(30, 8, 'DATA', 1);
$pdf->Cell(30, 8, 'OBS', 1);
$pdf->Cell(30, 8, utf8_decode('PREVISÃO RETORNO'), 1, 1);	
while($row = mysql_fetch_array($historico)){
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(25, 8, $row['status'], 0);
	$pdf->Cell(70, 8, utf8_decode($row['nome']), 0);
	$pdf->Cell(30, 8, $row['dtAlteracao'], 0);
	$pdf->Cell(30, 1, utf8_decode($row['observacao'])."\n", 0);
	$pdf->Cell(30, 8, $row['dtPrev'], 0, 1);
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);
$pdf->Output();
*/
?>
