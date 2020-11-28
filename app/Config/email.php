<?php

class EmailConfig {

    public $default = array(
        'transport' => 'Smtp',
        'from' => array('saci-helpdesk@banksystem.com.br' => 'Sigas'),
        'host' => 'mail.banksystem.com.br',
//        'port' => 110,
        'port' => 587,
        'timeout' => 30,
        'username' => 'saci-helpdesk@banksystem.com.br',
        'password' => 'sacihelpdesk123',
        'client' => null,
        'log' => false,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );

}
