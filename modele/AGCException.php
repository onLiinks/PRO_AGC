<?php
/**
  * Fichier AGCException.php
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Déclaration de la classe AGCException
  */
class AGCException extends Exception 
{
    const DEBUG = true;
    private $msgPrive;

	public function __construct($prive, $public=ASK_ERROR) 
	{
        $this->msgPrive = $prive;
        parent::__construct($public);
    } 

	public function getPublicMessage() 
    {
        return $this->getMessage();
    }

    public function getPrivateMessage() 
    {
        return $this->msgPrive;
    }
  
    public function display() 
    {
        $html = $this->getPublicMessage();
        if (AGCException::DEBUG == true) {
            $html .= '<br />'.MSG_ERROR.' <br />' . $this->getPrivateMessage();
        }
        return $html;
    }

    public function displayErrorItem($class) 
    {
        $html = '<div>'.MISS_DATA.'</div>';
        return $html;
    }
}
?>