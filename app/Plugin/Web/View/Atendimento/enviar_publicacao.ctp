<?php

App::import( 'Vendor', 'PHPWord', array('file'=>'PhpWord' . DS . 'Autoloader.php') );
\PhpOffice\PhpWord\Autoloader::register();
$phpWord = new \PhpOffice\PhpWord\PhpWord();

error_reporting(0);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(6);

// Adding Text element with font customized using explicitly created font style object...
$fontStyle = new \PhpOffice\PhpWord\Style\Font();
$fontStyle->setBold(true);
$fontStyle->setName('Arial');
$fontStyle->setSize(8);

$section = $phpWord->addSection();
// $section->getStyle()->setBreakType('continuous');

$paragraphOptions = array('spaceBefore' => 0, 'spaceAfter' => 0);

$data_hora = Util::toBrDataHora($this->data['Publicacao']['data_publicacao']);

$myTextElement = $section->addText(
    // htmlspecialchars('DESPACHO DA DIRETORIA DE RECURSOS HUMANOS/ GERÊNCIA ADMINISTRATIVA DE PERÍCIAS MÉDICAS - I.R.H. EM: '.$data_hora)
    htmlspecialchars('DESPACHO DO SERVIÇO DE PERÍCIAS MÉDICAS E SEGURANÇA DO TRABALHO EM: '.$data_hora)
);
$myTextElement->setFontStyle($fontStyle);
$section->addText('');


$phpWord->addFontStyle('fontBold', array('bold' => true));



function trasnformSituacao($situacao){
    if(strtoupper($situacao) == 'DEFERIDO'){
        return 'DEFIRO';
    }
    if(strtoupper($situacao) == 'INDEFERIDO'){
        return 'INDEFIRO';
    }
}

function linhaPublicacao($publicacao){
    if(strtoupper($publicacao['ParecerSituacao']['nome']) == 'DEFERIDO'){
        return (isset($publicacao['Atendimento']['idOrig'])?$publicacao['Atendimento']['idOrig']:$publicacao['Atendimento']['id'])
            . ' - '. $publicacao['Usuario']['nome']
            .', mat.'.$publicacao['Vinculo']['matricula'] . ' concedo '.$publicacao['Atendimento']['duracao']
            . ' dias a partir de '.Util::inverteData($publicacao['AgendamentoServidor']['data_a_partir']);
    }
    if(strtoupper($publicacao['ParecerSituacao']['nome']) == 'INDEFERIDO'){
        return $publicacao['Atendimento']['id']. ' - '. $publicacao['Usuario']['nome'].', mat.'
            . $publicacao['Vinculo']['matricula'].', Indeferido';
    }
}

function isNewHeader($publicacao, $lastPublicacao){
    if($lastPublicacao == null){
        return true;
    }else{
        if($publicacao['OrgaoOrigem']['orgao_origem'] != $lastPublicacao['OrgaoOrigem']['orgao_origem']){
            return true;
        }
        if($publicacao['ParecerSituacao']['nome'] != $lastPublicacao['ParecerSituacao']['nome']){
            return true;
        }
        if($publicacao['Tipologia']['nome'] != $lastPublicacao['Tipologia']['nome']){
            return true;
        }
        if($publicacao['Atendimento']['modo'] != $lastPublicacao['Atendimento']['modo']){
            return true;
        }
        return false;
    }
}



$newHeader = true;
$lastPublicacao = null;


foreach($publicacoes as $publicacao){
    if(isNewHeader($publicacao, $lastPublicacao)) {
        // $section->addText(strtoupper($publicacao['OrgaoOrigem']['orgao_origem']), 'fontBold');
        $section->addText(strtoupper($publicacao['OrgaoOrigem']['orgao_origem']), array('bold' => true));
        // $section->addText(
        //     trasnformSituacao($publicacao['ParecerSituacao']['nome']) . ' os pedidos de ' . $publicacao['Tipologia']['nome']
        //         .( isset($publicacao['TipologiaRecurso']['nome'])?" ({$publicacao['TipologiaRecurso']['nome']})":"")
        //         .(!empty($publicacao['Atendimento']['modo'])?' - '. $publicacao['Atendimento']['modo']:'') ,
        //     'fontBold'
        // );
        $section->addText(
            trasnformSituacao($publicacao['ParecerSituacao']['nome']) . ' os pedidos de ' . $publicacao['Tipologia']['nome']
                .( isset($publicacao['TipologiaRecurso']['nome'])?" ({$publicacao['TipologiaRecurso']['nome']})":"")
                .(!empty($publicacao['Atendimento']['modo'])?' - '. $publicacao['Atendimento']['modo']:'') ,
            array('bold' => true)
        );
    }
    $section->addText(linhaPublicacao($publicacao));
    $lastPublicacao = $publicacao;
}

$fontStyle = array('name' => 'Arial', 'size' => 6);
$phpWord->addFontStyle('fDefault', $fontStyle);

$phpWord->addParagraphStyle('pCenter',array('align' => 'center'));
$section->addTextBreak();
$section->addTextBreak();
$section->addText('Helena Carneiro Leão', 'fDefault', 'pCenter');
$section->addText("Gerente Administrativa de Perícias Médicas", 'fDefault', 'pCenter');

$phpWord->addParagraphStyle('pCenter',array('align' => 'center'));
$section->addTextBreak();
$section->addTextBreak();
$section->addText($this->data['Publicacao']['diretor_presidente'], 'fDefault', 'pCenter');
$section->addText("Diretor - Presidente", 'fDefault', 'pCenter');


$filepathPart = WWW_ROOT. '/' .  'publicacoes';
if(!is_dir($filepathPart)) {
    mkdir($filepathPart);
}


// Saving the document as OOXML file...
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save($filepathPart.'/publicacao_'.$publicacaoId.'.docx');

// Saving the document as HTML file...
// echo "<br>LINHA :: ".  __LINE__;
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
// echo "<br>LINHA :: ".  __LINE__;
// $objWriter->save($filepathPart.'/publicacao_'.$publicacaoId.'.html');
// echo "<br>LINHA :: ".  __LINE__;
// die;


// Saving the document as ODF file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
$objWriter->save($filepathPart.'/publicacao_'.$publicacaoId.'.odt');

$url = Router::url('/', true).'publicacoes/publicacao_'.$publicacaoId.'.odt';
?>

<?php
App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail('default');
$email->from(array(
    'spm@irh.pe.gov.br' => 'SPM'
));
$email->subject('Publicação de Perícia .:SPM:.');
$email->emailFormat('html');

$email->to($email_publicacao);

$htmlMsg="<html>
    <head></head>
    <body>
        <h1>Publicação realizada,</h1>
        <br/>
        <p class=\"nrml-txt textoInfo\">Download da publicação <a href='$url'>aqui</a></p>
    </body>
</html>";

 if($email->send($htmlMsg)){ ?>
    <script type="text/javascript">console.log('EMAIL - SUCESSO');</script>
 <?php }else{ ?>
    <script type="text/javascript">console.log('EMAIL - ERRO')</script>
   <?php }
?>


<div class="error-head divHead">
    <div class="error-desk divMensagem">
        <p class="nrml-txt texto">Publicação Realizada com Sucesso!</p>
        <p class="nrml-txt textoInfo">Download publicação <a href="<?= $url ?>">aqui</a></p>
    </div>
</div>