<?php


$url = Router::url('/', true).'publicacoes/publicacao_'.$publicacaoId.'.odt';
?>

<div class="error-head divHead">
    <div class="error-desk divMensagem">
        <p class="nrml-txt texto">Reenvio de email da publicação realizada com sucesso!</p>
        <p class="nrml-txt textoInfo">Download publicação <a href="<?= $url ?>">aqui</a></p>
    </div>
</div>

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
        <h1>Reenvio da publicação,</h1>
        <br/>
        <p class=\"nrml-txt textoInfo\">Download da publicação <a href='$url'>aqui</a></p>
    </body>
</html>";
 ($email->send($htmlMsg));
?>
