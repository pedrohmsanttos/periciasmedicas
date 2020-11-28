<?php
class RotinasShell extends Shell {
	
	public $uses = array('Usuario');
	
    function main() {
    	$user = $this->Usuario->findById(1);
    	$this->out(print_r($user, true));
    }
}
?>