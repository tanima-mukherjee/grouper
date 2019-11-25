<?php 
//vendor('php-captcha.inc');
//App::import('Vendor','php-captcha.inc' ,array('file'=>'php-captcha.inc.php')); 
 
class CaptchaComponent extends Object
{
    function startup(&$controller)
    {
        $this->controller = $controller;
    }

    function render()
    {
        //vendor('kcaptcha/kcaptcha');
		App::import('Vendor','kcaptcha/kcaptcha'); 
        $kcaptcha = new KCAPTCHA();
		$this->controller->Session->write('captcha', $kcaptcha->getKeyString());
    }
}
?> 