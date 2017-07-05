<?php
/**
  * Fichier Mailing.php
  *
  * @author Anthony Anne
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Déclaration de la classe Mailing
  */

class Mailing 
{
    /**
	  * Identifiant du mailing
	  *
	  * @access private
	  * @var int 
	  */
	private $Id_mailing;
	
	/**
	  * Titre du mailing
	  *
	  * @access private
	  * @var string 
	  */
    private $titre;
	
	/**
	  * Date du mailing
	  *
	  * @access private
	  * @var date 
	  */
	private $date;
	
	/**
	  * Expediteur du mailing
	  *
	  * @access private
	  * @var string 
	  */
	private $expediteur;
	
	/**
	  * Destinataire du mailing
	  *
	  * @access private
	  * @var string 
	  */
	private $destinataire;	
	
	/**
	  * Corps du mailing
	  *
	  * @access private
	  * @var string 
	  */
	private $corps;
	
	/**
	  * Signature du mailing
	  *
	  * @access private
	  * @var string 
	  */
	private $signature;
	
	/**
	  * Constructeur de la classe Mailing
	  *
	  * Constructeur : initialiser suivant la présence ou non de l'identifiant
	  *			
            * @param int Valeur de l'identifiant du mailing
            * @param array Tableau passé en argument : tableau $_POST ici 
            */
    public function __construct($code, $tab)  
	{
	    try {
            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            if (!$code && count($tab) != 0) {
                $this->Id_mailing   = '';
				$this->titre        = htmlscperso(stripslashes($tab['titre']), ENT_QUOTES);
				$this->date         = DATE;
				$this->corps        = stripslashes($tab['corps']);
				$utilisateur        = new Utilisateur($_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur,array());
				$this->expediteur   = $utilisateur->mail;
				$this->destinataire = explode(';', htmlscperso(stripslashes($tab['destinataire']), ENT_QUOTES));
			}
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
	}
	
    /**
	  * Formulaire de demande de recrutement
	  *
	  * @return string	   
	  */
	public function previsualiser()
	{	
		$html = '
            <form name="formulaire" enctype="multipart/form-data" action="index.php?a=envoyer_demande_ressource" method="post">
		    <div class="center">
                <span>Titre du Mail :</span>
                <span><input type="text" name="titre" value="'.$this->titre.'" size="50" /></span><br /><br />
				<span>Destinataire(s) :</span>
                <span><input type="text" name="destinataire" size="50" /></span> <span class="rouge">( Séparer par des ; )</span><br /><br />
                <span>Corps du mail :</span>
			    <textarea name="corps" id="tinyarea1">'.$this->corps.'</textarea>
            </div>
		    <div class="submit">
		        <input type="hidden" name="Id_mailing" value="'.$this->Id_mailing.'" />
			    <input type="button" onclick="if (confirm(\''.SEND_RH_MAIL.'\')) { document.formulaire.submit(); }"  value="Envoyer la demande" />
		    </div>
            </form>
';
        return $html;
	}

    /**
	  * Envoyer un mail avec un fichier en pièce jointe
	  *
	  * @param Informations relatives au fichier en pièce jointe du mail
	  */	
	public function envoyer($_FILES)
	{
		if(strlen($_FILES['fichier']['name'])) {
		    $rep     = '../upload/'; 
            $fichier = $_FILES['fichier'];
            $temp    = $fichier['tmp_name'];
            $name    = $fichier['name'];
            $size    = $fichier['size'];
            $type    = $fichier['type'];
            $file    = $rep.$name;
		}
	
		$params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
		$text           = $this->titre;
        $html           = $this->corps;
		$crlf           = "\n";
        $mime           = new Mail_mime($crlf);
        $mime->setTXTBody($text);
        $mime->setHTMLBody($html);
		if(strlen($_FILES['fichier']['name'])) {
            $mime->addAttachment($file, $type);
		}
		$body        = $mime->get();
        $mail_object = Mail::factory('smtp', $params);
		$n = count($this->destinataire);
		$i = 0;
		while($i < $n) {
			unset($mime->_headers['To']);
			$hdrs['From']     = $this->expediteur;
			$hdrs['Subject']  = $this->titre;
			$hdrs['To']       = $this->destinataire[$i];
			$hdrs['Reply-To'] = $this->expediteur;
		    $headers          = $mime->headers($hdrs);
			$mail_object->send($this->destinataire[$i],$headers,$body);
		    ++$i;
		}
		unset($mime->_headers['To']);
		$hdrs['To']       = $this->expediteur;
		$headers          = $mime->headers($hdrs);
		$mail_object->send($this->expediteur,$headers,$body);
		if(strlen($_FILES['fichier']['name'])) {
		    @unlink($file);
		}
    }
}
?>
