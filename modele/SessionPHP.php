<?php

class SessionPHP
{
	public $session_time = 18000; //5 heures
	public $session      = array();
	private $db;
	
	public function __construct($sql_host, $sql_user, $sql_password, $sql_db)
	{
		$this->host     = SERVEUR;
		$this->user     = USER;
		$this->password = PASSWD;
		$this->dba      = BASE;
	}
	
	public function open()//pour l'ouverture
	{
		$this->connect = mysql_connect($this->host, $this->user, $this->password,1);//on se connecte a la bdd
		$bdd = mysql_select_db($this->dba,$this->connect);//on s�lectionne la base de donn�es
		$this->gc();//on appelle la fonction gc		
		return $bdd;//true ou false selon la r�ussite ou non de la connexion � la bdd
	}
	
	public function read($sid)//lecture
	{
        $sid = mysql_real_escape_string($sid,$this->connect);
		$sql = 'SELECT sess_datas FROM session_php
				WHERE sess_id = "'.$sid.'" ';
		
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());	
		$data  = mysql_fetch_array($query);
		
		if(empty($data)) return false;
		else return $data['sess_datas'];//on retourne la valeur de sess_datas
	}
	
	public function write($sid, $data)//�criture
	{
		$expire = intval(time() + $this->session_time);//calcul de l'expiration de la session
		$data   = mysql_real_escape_string($data,$this->connect);//si on veut stocker du code sql 
		
		$sql = 'SELECT COUNT(sess_id) AS total
			FROM session_php
			WHERE sess_id = "'.$sid.'" ';
		
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		$return = mysql_fetch_array($query);
		if($return['total'] == 0)//si la session n'existe pas encore
		{
			$sql = 'INSERT INTO session_php
				VALUES("'.$sid.'","'.$data.'","'.$expire.'")';//alors on la cr�e
		} else {
			$sql = 'UPDATE session_php 
				SET sess_datas = "'.$data.'",
				sess_expire = "'.$expire.'"
				WHERE sess_id = "'.$sid.'" ';//on la modifie
		}		
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		return $query;
	}
	
	public function close()//fermeture
	{
		mysql_close($this->connect);//on ferme la bdd
	}
	
	public function destroy ($sid)//destruction
	{
		$sql = 'DELETE FROM session_php
			WHERE sess_id = "'.$sid.'" ';//on supprime la session de la bdd
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		return $query;
	}
	
	public function gc ()//nettoyage
	{
		$sql = 'DELETE FROM session_php 
				WHERE sess_expire < "'.time().'"'; //on supprime les vieilles sessions 
		$query = mysql_query($sql,$this->connect) or exit(mysql_error());
		return $query;
	}
	
/*ini_set('session.save_handler', 'user');//on d�finit l'utilisation des sessions en personnel

//fin de la classe

$session = new SessionPHP($sql_host, $sql_user, $sql_password, $sql_db);//on d�clare la classe
session_set_save_handler(array($session, 'open'),
                         array($session, 'close'),
                         array($session, 'read'),
                         array($session, 'write'),
                         array($session, 'destroy'),
                         array($session, 'gc'));//on pr�cise les m�thodes � employer pour les sessions
						 
session_start();//on d�marre la session	*/

}
?>