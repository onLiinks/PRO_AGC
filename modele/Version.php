<?php
/**
  * Fichier Version.php
  *
  * @author Mathieu PERRIN
  * @copyright Proservia
  * @package ProjetAGC
  */

/**
  * Déclaration de la classe Version
  */
class Version {
    /**
     * Identifiant de la version
     *
     * @access public
     * @var int 
     */
    public $Id_version;
    
    /**
     * Numéro de version (avec .)
     *
     * @access public
     * @var String 
     */
    public $version;
    
    /**
     * Date de la version
     *
     * @access public
     * @var DateTime 
     */
    public $date_version;
    
    /**
     * Date de la verision au format français
     *
     * @access public
     * @var String 
     */
    public $datefr_version;
    
    /**
     * Message de version
     *
     * @access public
     * @var String 
     */
    public $message;
    
    /**
     * Constructeur de la classe Version
     *
     *
     * @param int Numéro de version
     */
    public function __construct($version) {
        $db = connecter();
        $version = $db->query('SELECT * FROM version WHERE Id_version = ' . (int) $version)->fetchRow();
        $this->Id_version = $version->id_version;
        $this->version = $version->version;
        $this->date_version = DateTime::createFromFormat('Y-m-d H:i:s', $version->date_version);
        $this->datefr_version = DateMysqltoFr($version->date_version);
        $this->message = $version->message;
    }
    
    public static function getCurrentVersion() {
        $currentVersion = str_replace('.', '', VERSION);
        $version = new Version($currentVersion);
        return $version;
    }
    
    public function getMessage($alwaysShow = false) {
        if ($_SESSION[SESSION_PREFIX.'logged']->utilisateur->version < $this->Id_version || $alwaysShow === true) {
            $_SESSION[SESSION_PREFIX.'logged']->utilisateur->version = $this->Id_version;
            $html =  '
                <fieldset>
                    <legend class="cliquable" id="news_lengend" onclick="toggleNews(\'news_content\')">
                        <img id="news_contentImage" src="../ui/images/plus.gif">MISE A JOUR ' . $this->version . ' (' . $this->datefr_version . ')
                    </legend>
                    <br />
                    <div id="news_content" style="display:none">
                        ' . $this->message . '
                    </div>
                </fieldset>
                <br /><br />
                <script type="text/javascript">
                    new Effect.Parallel([
                        new Effect.Pulsate("news_lengend", { pulses: 3 }),
                        new Effect.Highlight("news_lengend", { startcolor: "#ffff99", endcolor: "#ffffff" })],
                        {duration: 1.5}
                    );
                </script>';
            return $html;
        }
    }
    
    public static function getVersions() {
        $db = connecter();
        $versions = $db->query('SELECT * FROM version ORDER BY date_version DESC');
        $html = '<div id="news">';
        while ($version = $versions->fetchRow()) {
            $html .= '<h2>Pour consulter les mises à jour du ' . DateMysqltoFr($version->date_version) . ' cliquez <a href="../membre/index.php?a=consulterNews&amp;version=' . $version->id_version .'">ici</a></h2>';
        }
        $html .= '</div>';
        return $html;
    }
}

?>
