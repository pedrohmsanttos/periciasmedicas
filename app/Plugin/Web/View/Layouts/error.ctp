<?php
ob_start();

App::uses('CakeEmail', 'Network/Email');
$email = new CakeEmail('default');
$email->from(array(
		'spm@irh.pe.gov.br' => 'SPM'  
));
$email->subject('Erros .:SPM:.');
$email->emailFormat('html');
 // $email->to("tpporto@gmail.com");
 // $email->bcc("rodrigorbv@gmail.com");
 // $email->addBcc("thymac@gmail.com");

// if(!defined(NO_EMAIL_ERROR) || NO_EMAIL_ERROR == 0)($email->send($this->fetch('content')));
// ob_end_clean();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
<?php echo $this->Html->charset(); ?>
	<title><?= $title_for_layout?></title>
	<?php
	if(SHOW_ERROR || (isset($_SESSION['show_all_error']) && $_SESSION['show_all_error'])){
		echo $this->fetch('content');
	}else{
		echo $this->Html->css ( BS_PLUGIN_CSS.'style-erro' );
	}
	?>
</head>
<body>


</body>
</html>
