<?php

/**
 * Fichier propSession.php
 *
 * @author    Frédérique Potet
 * @copyright    Proservia
 * @package    ProjetAGC
 */

/**
 * Déclaration de la classe propSession
 */
class PropSession {

    /**
     * Identifiant de l'instance de propSession
     *
     * @access public
     * @var int
     */
    public $Id_propSession;
    /**
     * Identifiant de la session correspondante
     *
     * @access public
     * @var int
     */
    public $Id_session;
    /**
     * Prix du formateur par jour
     *
     * @access public
     * @var double
     */
    public $coutFormateurJ;
    /**
     * Prix du formateur pour la session
     *
     * @access public
     * @var double
     */
    public $coutFormateur;
    /**
     * Prix de la salle par jour
     *
     * @access public
     * @var double
     */
    public $coutSalleJ;
    /**
     * Prix de la salle pour la session
     *
     * @access public
     * @var double
     */
    public $coutSalle;
    /**
     * Coût production de 1 support pédagogique pour stagiaire
     *
     * @access public
     * @var double
     */
    public $coutSupportU;
    /**
     * Coût production de tous les supports pédagogiques pour stagaire
     *
     * @access public
     * @var double
     */
    public $coutSupport;
    /**
     * Coût production des supports pédagogiques pour le formateur
     *
     * @access public
     * @var double
     */
    public $coutSupForm;
    /**
     * Prix des autres frais de la session
     *
     * @access public
     * @var double
     */
    public $autreFrais;
    /**
     * Explication des autres frais de la formation
     *
     * @access public
     * @var string
     */
    public $typeAutreFrais;
    /**
     * Somme des charges de la session
     *
     * @access public
     * @var double
     */
    public $charge;
    /**
     * Somme du chiffre d'affaire réalisé pour la session
     *
     * @access public
     * @var double
     */
    public $ca;
    /**
     * Marge réalisée pour la session
     *
     * @access public
     * @var double
     */
    public $marge;

    /**
     * Constructeur de la classe propSession
     *
     * Constructeur : initialiser suivant la présence ou non de l'identifiant
     * 	 
     * @param int Valeur de l'identifiant de l'instance propSession
     * @param array Tableau passé en argument : tableau $_POST ici
     */
    public function __construct($code, $tab) {
        try {
            /* Cas 1 : pas de code et pas de tableau : les champs sont vides */
            if (!$code && empty($tab)) {
                $this->Id_propSession = '';
                $this->Id_session = '';
                $this->coutFormateurJ = '';
                $this->coutFormateur = '';
                $this->coutSalleJ = '';
                $this->coutSalle = '';
                $this->coutSupportU = '';
                $this->coutSupport = '';
                $this->coutSupForm = '';
                $this->autreFrais = '';
                $this->typeAutreFrais = '';
                $this->charge = '';
                $this->ca = '';
                $this->marge = '';
            }

            /* Cas 2 : pas de code et un tableau : retour de formulaire en mode création  */
            elseif (!$code && !empty($tab)) {
                $this->Id_propSession = '';
                $this->Id_session = $tab['Id_session'];
                $this->coutFormateurJ = str_replace(',', '.', $tab['coutFormateurJ']);
                $this->coutFormateur = str_replace(',', '.', $tab['coutFormateur']);
                $this->coutSalleJ = str_replace(',', '.', $tab['coutSalleJ']);
                $this->coutSalle = str_replace(',', '.', $tab['coutSalle']);
                $this->coutSupportU = str_replace(',', '.', $tab['coutSupportU']);
                $this->coutSupport = str_replace(',', '.', $tab['coutSupport']);
                $this->coutSupForm = str_replace(',', '.', $tab['coutSupForm']);
                $this->autreFrais = str_replace(',', '.', $tab['autreFrais']);
                $this->typeAutreFrais = $tab['typeAutreFrais'];
                $this->charge = $tab['charge'];
                $this->ca = $tab['ca'];
                $this->marge = $tab['marge'];
            }

            /* Cas 3 : un code et pas de tableau : prendre les infos dans la base de données */
            elseif ($code && empty($tab)) {
                $db = connecter();
                $ligne = $db->query('SELECT * FROM proposition_session WHERE Id_propSession=' . mysql_real_escape_string((int) $code))->fetchRow();
                $this->Id_propSession = $code;
                $this->Id_session = $ligne->id_session;
                $this->coutFormateurJ = $ligne->coutformateurjour;
                $this->coutFormateur = $ligne->coutformateur;
                $this->coutSalleJ = $ligne->coutsallejour;
                $this->coutSalle = $ligne->coutsalle;
                $this->coutSupportU = $ligne->coutsupportunitaire;
                $this->coutSupport = $ligne->coutsupport;
                $this->coutSupForm = $ligne->coutsupform;
                $this->autreFrais = $ligne->autrefrais;
                $this->typeAutreFrais = $ligne->typeautrefrais;
                $this->charge = $ligne->charge;
                $this->ca = $ligne->ca;
                $this->marge = $ligne->marge;
            }

            /* Cas 4 : un code et un tableau : prendre infos dans la base de données et d'autres dans le tableau $_POST (modification) */
            elseif ($code && !empty($tab)) {
                $this->Id_propSession = $code;
                $this->Id_session = $tab['Id_session'];
                $this->coutFormateurJ = str_replace(',', '.', $tab['coutFormateurJ']);
                $this->coutFormateur = str_replace(',', '.', $tab['coutFormateur']);
                $this->coutSalleJ = str_replace(',', '.', $tab['coutSalleJ']);
                $this->coutSalle = str_replace(',', '.', $tab['coutSalle']);
                $this->coutSupportU = str_replace(',', '.', $tab['coutSupportU']);
                $this->coutSupport = str_replace(',', '.', $tab['coutSupport']);
                $this->coutSupForm = str_replace(',', '.', $tab['coutSupForm']);
                $this->autreFrais = str_replace(',', '.', $tab['autreFrais']);
                $this->typeAutreFrais = $tab['typeAutreFrais'];
                $this->charge = $tab['charge'];
                $this->ca = $tab['ca'];
                $this->marge = $tab['marge'];
            }
        } catch (Exception $e) {
            throw new AGCException($e->getMessage());
        }
    }

    /**
     * Formulaire de création / modification de l'instance propSession
     *
     * @return string
     */
    public function form() {
        //Tableau indiquant le détail des charges de la session
        $html = '<input type="hidden" name="Id_propSession" id="Id_propSession" value="' . $this->Id_propSession . '" />
				<div class="left">
				 <br/><br/>
				<input type="button" onclick="calculCout()" value="Calcul coût formateur et salle">
					<div id="coutF"><table><tr>
						<td width="180" height="25">Coût formateur : </td>
						<td width="120" height="25">coût journalier :</td>
						<td width="120" height="25">
							<input type="text" id="coutFormateurJ" name="coutFormateurJ" 
							  onKeyup="calculcoutFTotal()" value="' . $this->coutFormateurJ . '" size="8"> euros</td>
					</tr><tr>
						<td width="180" height="25"> </td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25"><div id="coutFormateurTotal">
							<input type="text" id="coutFormateur" name="coutFormateur" 
							onKeyup="chargeTotalSession(0)" value="' . $this->coutFormateur . '" size="8"> euros</div></td>
					</tr></table></div>
					<br/><br/>
					<div id="coutS"><table><tr>
						<td width="180" height="25">Coût de la salle : </td>
						<td width="120" height="25">coût journalier :</td>
						<td width="120" height="25"> 
							<input type="text" id="coutSalleJ" name="coutSalleJ" 
							 onKeyup="calculcoutSTotal()" value="' . $this->coutSalleJ . '" size="8"> euros</td>
					</tr><tr>
						<td width="180" height="25"></td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25"><div id="coutSalleTotal">
							<input type="text" id="coutSalle" name="coutSalle" 
							 onKeyup="chargeTotalSession(0)" value="' . $this->coutSalle . '" size="8"> euros</div></td>
					</tr></table></div>
				 <br/><br/>
		        <table><tr>
						<td width="180" height="25">Coût support stagiaire :</td>
						<td width="120" height="25">coût unitaire :</td>
						<td width="120" height="25">
							<input type="text" id="coutSupportU" name="coutSupportU" 
							 onKeyup="calculcoutSupTotal()" value="' . $this->coutSupportU . '" size="8"> euros</td>
					</tr><tr>
						<td width="180" height="25"></td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25"><div id="coutSupportTotal">
							<input type="text" id="coutSupport" name="coutSupport" 
							 onKeyup="chargeTotalSession(0)" value="' . $this->coutSupport . '" size="8"> euros</div></td>
					</tr><tr><td width="180" height="25"></td><td width="120" height="25"></td><td width="120" height="25"></td></tr><tr>
						<td width="180" height="25">Coût support formateur:</td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25">
							<input type="text" id="coutSupForm" name="coutSupForm" 
							 onKeyup="chargeTotalSession(0)" value="' . $this->coutSupForm . '" size="8"> euros</td>
					</tr><tr><td width="180" height="25"></td><td width="120" height="25"></td><td width="120" height="25"></td></tr><tr>
						<td width="180" height="25">Autres frais :</td>
						<td width="120" height="25"></td>
						<td width="120" height="25">
							<input type="text" id="autreFrais" name="autreFrais" 
							 onKeyup="chargeTotalSession(0)" value="' . $this->autreFrais . '" size="8"> euros</td>
					</tr>
				</table>
			</div>	
			<div class="right">
				Détails :<br/><textarea name="typeAutreFrais" id="tinyarea2">' . $this->typeAutreFrais . '</textarea><br/><br/> 
			</div>';

        //affichage des affaires associées à la session pour les formuloaire en mode modification
        if ($this->Id_session) {
            $html .= '<div class="center"><br/><br/><b>Liste des affaires associées</b><br/><div id="tableauAffaire">' .
                    $this->sessionCasesList(0, '') . '</div><br/></div>';
        }
        // affichage de la somme des charges, du ca et de la marge
        $html .= '<div class="center"><br/>
					<br/><br/>
			<div id="chargeSession"><br/><br/>
				<table> 
					<tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left" class="rowodd">CA total :</td>
						<td width="200" height="25" align="left" class="rowodd">
							<input type="text" id="ca" name="ca" value="' . (float) $this->ca . '" readonly=true > euros </td>
						<td width="100" height="25"></td>
					</tr><tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left" class="rowodd">Total des charges :</td>
						<td width="200" height="25" align="left" class="rowodd">
							<input type="text" id="charge" name="charge" value="' . (float) $this->charge . '" readonly=true > euros </td>
						<td width="100" height="25"></td>
					</tr>
					<tr>
						<td width="50" height="25"></td>
						<td width="110" height="25" align="left" class="rowodd">Marge :</td>
						<td width="200" height="25" align="left" class="rowodd">
							<input type="text" id="marge" name="marge" value="' . (float) $this->marge . '" readonly=true > % </td>
						<td width="100" height="25"></td>
					</tr>
				</table></div></div>';

        return $html;
    }

    /**
     * Enregistrement des données de la propSession dans la base de données
     *
     */
    public function save() {
        $db = connecter();
        $set = ' SET Id_session = "' . mysql_real_escape_string((int) $this->Id_session) . '", 
						coutFormateurJour = "' . mysql_real_escape_string((float) $this->coutFormateurJ) . '", 
						coutFormateur = "' . mysql_real_escape_string((float) $this->coutFormateur) . '", 
						coutSalleJour = "' . mysql_real_escape_string((float) $this->coutSalleJ) . '", 
						coutSalle = "' . mysql_real_escape_string((float) $this->coutSalle) . '", 
						coutSupportUnitaire ="' . mysql_real_escape_string((float) $this->coutSupportU) . '",
						coutSupport ="' . mysql_real_escape_string((float) $this->coutSupport) . '",
						coutSupForm ="' . mysql_real_escape_string((float) $this->coutSupForm) . '",
						autreFrais = "' . mysql_real_escape_string((float) $this->autreFrais) . '", 
						typeAutreFrais="' . mysql_real_escape_string($this->typeAutreFrais) . '",
						ca="' . mysql_real_escape_string((float) $this->ca) . '",
						charge="' . mysql_real_escape_string((float) $this->charge) . '",
						marge="' . mysql_real_escape_string((float) $this->marge) . '"';
        if ($this->Id_propSession) {
            $requete = 'UPDATE proposition_session ' . $set . ' WHERE Id_propSession = ' . mysql_real_escape_string((int) $this->Id_propSession);
        } else {
            $requete = 'INSERT INTO proposition_session ' . $set;
        }
        $db->query($requete);
    }

    /**
     * Affichage des données de la proposition de la session en consultation
     *
     * @return string
     */
    public function consultation() {
        //Tableau indiquant le détail des charges de la session
        $html = '
		    <div class="left">
				<br/><br/>
				<table>
				    <tr>
						<td width="180" height="25"><b>Coût formateur : </b></td>
						<td width="120" height="25">coût journalier :</td>
						<td width="120" height="25">' . $this->coutFormateurJ . ' euros</td>
					</tr><tr>
						<td width="180" height="25"> </td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25">' . $this->coutFormateur . ' euros</div></td>
					</tr><tr><td width="180" height="25"></td><td width="120" height="25"></td><td width="120" height="25"></td></tr><tr>
						<td width="180" height="25"><b>Coût de la salle : </b></td>
						<td width="120" height="25">coût journalier :</td>
						<td width="120" height="25">' . $this->coutSalleJ . ' euros</td>
					</tr><tr>
						<td width="180" height="25"></td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25">' . $this->coutSalle . ' euros</div></td>
					</tr><tr><td width="180" height="25"></td><td width="120" height="25"></td><td width="120" height="25"></td></tr><tr>
						<td width="180" height="25"><b>Coût support :</b></td>
						<td width="120" height="25">coût unitaire :</td>
						<td width="120" height="25">' . $this->coutSupportU . ' euros</td>
					</tr><tr>
						<td width="180" height="25"></td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25">' . $this->coutSupport . ' euros</div></td>
					</tr><tr><td width="180" height="25"></td><td width="120" height="25"></td><td width="120" height="25"></td></tr><tr>
						<td width="180" height="25"><b>Coût support formateur:</b></td>
						<td width="120" height="25">coût total :</td>
						<td width="120" height="25">' . $this->coutSupForm . ' euros</td>
					</tr><tr><td width="180" height="25"></td><td width="120" height="25"></td><td width="120" height="25"></td></tr><tr>
						<td width="180" height="25"><b>Autres frais :</b></td>
						<td width="120" height="25">' . $this->autreFrais . ' euros</td>
						<td width="120" height="25"></td>
					</tr>
				</table>
			</div><br/><br/>
			<div class="right">
				<b>Détails : </b><br/><br/>' . $this->typeAutreFrais . '<br/><br/> 
			</div><br/><br/>
		    <div class="center"><br/><br/><b>Liste des affaires associées</b><br/>' . $this->sessionCasesList(2, '') . '<br/></div>
		    <div class="center"> <br/><br/>
				<table> 
					<tr align="left">
						<td width="100" height="25"><span class="marge"></span></td>
						<td width="200" height="25"  class="roweven"><span class="marge"></span><b>CA total :</b></td>
						<td width="100" height="25" class="roweven">' . (float) $this->ca . ' euros </td>
						<td width="100" height="25"></td>
					</tr>
					<tr align="left">
						<td width="100" height="25"><span class="marge"></span></td>
						<td width="200" height="25"  class="rowodd"><span class="marge"></span><b>Total des charges :</b></td>
						<td width="100" height="25" class="rowodd">' . (float) $this->charge . ' euros </td>
						<td width="100" height="25"></td>
					</tr>
					<tr align="left">
						<td width="100" height="25"><span class="marge"></span></td>
						<td width="200" height="25" class="roweven"><span class="marge"></span><b>Marge :</b></td>
						<td width="100" height="25" class="roweven">' . (float) $this->marge . ' % </td>
						<td width="100" height="25"></td>
					</tr>
				</table>
			</div>';
        return $html;
    }

    /**
     * Information de la proposition de la session à joindre et à afficher dans l'affaire 
     *
     * @param int identifiant de la session dont il faut récupérer la proposition
     * @param float ca de l'affaire pour pouvoir calculer la marge
     * @param int nombre d'inscription pour l'affaire
     * @param int identifiant de l'affaire où il faut afficher les données
     *
     * @return string
     */
    public static function infoSession($Id_session, $ca, $nb_inscrit, $Id_affaire) {
        $db = connecter();
        //récup de la charge totale de la session
        $ligne = $db->query('SELECT charge, coutSupportUnitaire, coutSupport FROM proposition_session 
				     WHERE Id_session = "' . mysql_real_escape_string((int) $Id_session) . '"')->fetchRow();

        //récup du nombre d'inscrits à la session
        $ligne2 = $db->query('SELECT type, nb_inscrits FROM session WHERE Id_session = "' . mysql_real_escape_string((int) $Id_session) . '"')->fetchRow();

        //récup du nombre d'inscrits à l'affaire
        $ligne3 = $db->query('SELECT pf.nb_inscrit 
					  FROM proposition_formation AS pf INNER JOIN proposition AS p ON pf.Id_proposition=p.Id_proposition 
					  WHERE p.Id_affaire ="' . mysql_real_escape_string((int) $Id_affaire) . '"')->fetchRow();

        /* calcul du nouveau nombre d'inscrits (nombre d'inscrits total moins l'ancien nombre d'inscrits pour 
          l'affaire puis ajout du nouveau nombre d'inscrits pour l'affaire) */
        $nb_participant = (int) $ligne2->nb_inscrits + (int) $nb_inscrit - (int) $ligne3->nb_inscrit;

        //calcul du nouveau total des charges après modificcations du cout total des support selon le nombre d'inscrits 
        $charge = ((float) $ligne->charge - (float) $ligne->coutsupport ) + ((float) $ligne->coutsupportunitaire * (float) $nb_participant);

        //si la session est une session de type intra-entreprise, les charges de l'affaire correspondent aux charges de la session
        if ($ligne2->type == 1) {
            $cout = $charge;
        }
        //si la session est une session inter-entreprise, calcul des charges au prorata du nombre d'inscrits
        else if ($nb_participant && is_numeric($nb_participant)) {
            $cout = ((float) $charge / (float) $nb_participant) * (float) $nb_inscrit;
            $cout = (float) ((int) ($cout * 100)) / 100;
        }

        //calcul de la marge de l'affaire
        if ($ca && is_numeric($ca)) {
            $marge = ((float) (($ca - $cout) * 100)) / $ca;
            $marge = (float) ((int) ($marge * 100)) / 100;
        }
        // affichage des données dans l'affaire
        $html .='
			<table> 
				<tr>
					<td width="110" height="25"></td>
					<td width="110" height="25" align="left">Total des charges :</td>
					<td width="200" height="25" align="left">
						<input type="text" id="cout_total" name="cout_total" value="' . $cout . '" readonly=true> euros </td>
					<td width="100" height="25"></td>
				</tr>
				<tr>
					<td width="110" height="25"></td>
					<td width="110" height="25" align="left">Marge :</td>
					<td width="200" height="25" align="left">
						<input type="text" id="marge_totale" name="marge_totale" value="' . $marge . '" readonly=true> % </td>
					<td width="100" height="25"></td>
				</tr>
			</table>';

        return $html;
    }

    /**
     * Affichage de la liste des affaires associées à la session dans le formulaire ou dans la consultation d'une session
     *
     * @return string
     */
    public function sessionCasesList($cas, $charge) {
        $db = connecter();
        // cas 1 : prévisualisation des nouveaux coût et marge après modification des coût de la session avant enregistrement
        if ($cas == 1) {
            $ligne = $db->query('SELECT nb_inscrits FROM session WHERE archive=0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session))->fetchRow();
            if ($ligne->nb_inscrits) {
                $chargeU = $charge / $ligne->nb_inscrits;
            } else {
                $chargeU = 0;
            }
        }

        //sélection de toutes les affaires associées à la session
        $result = $db->query('SELECT Id_affaire, Id_compte FROM affaire WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));

        $affaire = array();
        $affaire['Id_affaire'] = array();
        $affaire['compte'] = array();
        $affaire['ca'] = array();
        $affaire['cout'] = array();
        $affaire['marge'] = array();
        $affaire['nb_inscrit'] = array();
        $nb_affaire = 0;

        //récupération des données de chaque affaire
        while ($ligne = $result->fetchRow()) {
            ++$nb_affaire;
            array_push($affaire['Id_affaire'], $ligne->id_affaire);
            $compte = CompteFactory::create(null, $ligne->id_compte);
            array_push($affaire['compte'], $compte->nom);
            $result2 = $db->query('SELECT p.cout, p.chiffre_affaire, p.marge, pf.nb_inscrit 
						   FROM proposition AS p INNER JOIN proposition_formation AS pf ON p.Id_proposition=pf.Id_proposition 
						   WHERE p.Id_affaire=' . mysql_real_escape_string((int) $ligne->id_affaire));

            while ($ligne2 = $result2->fetchRow()) {
                array_push($affaire['ca'], $ligne2->chiffre_affaire);
                array_push($affaire['cout'], $ligne2->cout);
                array_push($affaire['marge'], $ligne2->marge);
                array_push($affaire['nb_inscrit'], $ligne2->nb_inscrit);
            }
        }

        //affichage des affaires en tableau
        if ($nb_affaire) {
            if ($cas != 2) {
                $bouton = '
			    <tr>
					<td width="20" height="25"></td>
					<td width="60" height="25"></td>
					<td width="200" height="25"></td>
					<td width="200" height="25"></td>
					<td width="200" height="25"></td>
					<td width="200" height="25" align="right"><input type="button" onclick="calculAffaire()" value="Calcul affaire"></td>
					<td width="200" height="25"></td>
				</tr>';
            }
            $html = '
			    <table>
					<tr>
						<td width="20" height="25"></td>
						<td width="60" height="25" even><b>N° affaire</b></td>
						<td width="200" height="25" align="left"><b>Compte</b></td>
						<td width="200" height="25" align="left"><b>Nb inscrits</b></td>
						<td width="200" height="25" align="left"><b>Chiffre affaire</b> en euros</td>
						<td width="200" height="25" align="left"><b>Charge</b> en euros</td>
						<td width="200" height="25" align="left"><b>Marge</b> en %</td>
					</tr>';
            $i = 0;
            while ($i < $nb_affaire) {
                $j = ($i % 2 == 0) ? 'odd' : 'even';
                if ($cas == 1) {
                    $charge = $chargeU * $affaire['nb_inscrit'][$i];
                    $affaire['cout'][$i] = (float) (int) ($charge * 100) / 100;
                    //calcul de la marge de l'affaire
                    if ($affaire['ca'][$i]) {
                        $marge = (($affaire['ca'][$i] - $charge) * 100) / $affaire['ca'][$i];
                        $affaire['marge'][$i] = (float) ((int) ($marge * 100)) / 100;
                    }
                }
                $html .= '
				    <tr>
						<td width="20" height="25"></td>
						<td width="60" height="25" class="row' . $j . '">' . $affaire['Id_affaire'][$i] . '</td>
						<td width="200" height="25" align="left" class="row' . $j . '">' . $affaire['compte'][$i] . '</td>
						<td width="200" height="25" align="left" class="row' . $j . '">' . $affaire['nb_inscrit'][$i] . '</td>
						<td width="200" height="25" align="left" class="row' . $j . '">' . $affaire['ca'][$i] . '</td>
						<td width="200" height="25" align="left" class="row' . $j . '">' . $affaire['cout'][$i] . '</td>
						<td width="200" height="25" align="left" class="row' . $j . '">' . $affaire['marge'][$i] . '</td>
					</tr>';
                ++$i;
            }
            $html .= $bouton . '</table>';
        } else {
            $html = '<br/>Il n\'y a pas d\'affaire associée à cette session <br/>';
        }

        return $html;
    }

    /**
     * Mise à jour des données associées à la session (lors de sa modification) dans les affaires associées
     *   
     * @param int le nombre de participant à la session
     * @param int type de la session : Inter / Intra
     *   
     */
    public function updateRelatedCases($nb_participant, $type) {
        //récupération des affaires associées à la session
        $db = connecter();
        $result = $db->query('SELECT affaire.Id_affaire FROM affaire INNER JOIN affaire_pole ON affaire_pole.Id_affaire=affaire.Id_affaire
		WHERE Id_pole=3 AND archive=0 AND Id_session IS NOT NULL AND Id_session!=0 AND Id_session="' . mysql_real_escape_string($this->Id_session) . '"');

        while ($ligne = $result->fetchRow()) {
            //récupération du nombre d'inscrit et du ca de l'affaire
            $ligne1 = $db->query('SELECT p.chiffre_affaire, pf.nb_inscrit 
						 FROM proposition AS p INNER JOIN proposition_formation AS pf ON p.Id_proposition=pf.Id_proposition 
						 WHERE p.Id_affaire=' . mysql_real_escape_string((int) $ligne->id_affaire))->fetchRow();

            //calcul des charges au prorata du nombre d'inscrits
            if ($type == 1) {
                $charge_Affaire = $this->charge;
            } else if ($nb_participant) {
                $charge_Affaire = ((float) $this->charge / (float) $nb_participant) * (float) $ligne1->nb_inscrit;
                $charge_Affaire = (float) (int) ($charge_Affaire * 100) / 100;
            }
            //calcul de la marge de l'affaire
            if ($ligne1->chiffre_affaire) {
                $marge_Affaire = (($ligne1->chiffre_affaire - $charge_Affaire) * 100) / $ligne1->chiffre_affaire;
                $marge_Affaire = (float) ((int) ($marge_Affaire * 100)) / 100;
            }
            // mise à jours de la base
            $result1 = $db->query('UPDATE proposition SET cout="' . mysql_real_escape_string($charge_Affaire) . '", marge="' . mysql_real_escape_string($marge_Affaire) . '" 
						   WHERE Id_affaire=' . mysql_real_escape_string($ligne->id_affaire));
        }
    }

    /**
     * Mise à jour des informations de la session lors de l'enregistrement ou de la modification d'une affaire qui lui est associée
     *
     * @param int type de la session : Inter / Intra
     *
     */
    public function updateSession($type) {
        //récupération de toutes les affaires qui sont associées à cette session
        $db = connecter();
        $result = $db->query('SELECT Id_affaire FROM affaire WHERE archive = 0 AND Id_session=' . mysql_real_escape_string((int) $this->Id_session));

        $nb_participant = 0;
        $ca = 0;
        while ($ligne = $result->fetchRow()) {
            //pour chacunes des affaires, récupération du nombre d'inscrits et du ca de l'affaire
            $ligne1 = $db->query('SELECT pf.nb_inscrit, p.chiffre_affaire 
								FROM proposition_formation AS pf INNER JOIN proposition AS p ON pf.Id_proposition=p.Id_proposition 
								WHERE p.Id_affaire=' . mysql_real_escape_string((int) $ligne->id_affaire))->fetchRow();

            //calcul de nombre de particpants et du ca pour la session
            $nb_participant += $ligne1->nb_inscrit;
            $ca += (float) $ligne1->chiffre_affaire;
        }

        //mise à jour de la table session
        $result1 = $db->query('UPDATE session SET nb_inscrits= ' . mysql_real_escape_string($nb_participant) . ' WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session));

        //calcul du coût total des supports à partir du coût unitaire et du nombre participant et ajout à la charge total de la session
        $cout_support = $this->coutSupportU * $nb_participant;
        $this->charge -= $this->coutSupport;
        $this->charge += $cout_support;

        //calcul de la marge avec le nouveau ca
        $marge = 0;
        if ($ca) {
            $marge = ((float) (($ca - $this->charge) * 100)) / $ca;
            $marge = (float) ((int) ($marge * 100)) / 100;
        }
        //mise à jour de la proposition de la session
        $result1 = $db->query('UPDATE proposition_session SET ca= ' . mysql_real_escape_string((float) $ca) . ', charge=' . mysql_real_escape_string((float) $this->charge) . ', 
                                        marge=' . mysql_real_escape_string((float) $marge) . ', coutSupport=' . mysql_real_escape_string((float) $cout_support) . ' 
					 WHERE Id_session=' . mysql_real_escape_string((int) $this->Id_session));

        //mise à jour des charges des autres affaires associées à la session si il s'agit d'une session de type inter-entreprise
        if ($type == 2) {
            $this->updateRelatedCases($nb_participant, $type);
        }
    }

}

?>