<?php

/**
 * Fichier Error.php
 *
 * @author Mathieu Perrin
 * @copyright Proservia
 * @package ProjetAGC
 */

/**
 * Déclaration de la classe Error
 */
class Error {

    private static $errorType = array(
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSING ERROR',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE ERROR',
        E_CORE_WARNING => 'CORE WARNING',
        E_COMPILE_ERROR => 'COMPILE ERROR',
        E_COMPILE_WARNING => 'COMPILE WARNING',
        E_USER_ERROR => 'USER ERROR',
        E_USER_WARNING => 'USER WARNING',
        E_USER_NOTICE => 'USER NOTICE',
        E_STRICT => 'STRICT NOTICE',
        E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR'
    );

    /**
     * Traite les erreurs PHP
     *
     * @param int Type d'erreur
     * @param string Message d'erreur
     * @param string Chemin vers le fichier générant l'erreur
     * @param int Ligne à laquelle est survenu l'erreur
     *
     * @return boolean
     */
    public static function errorHandler($errno, $errstr='', $errfile='', $errline='') {
        if (error_reporting() == 0) {
            return;
        }

        switch ($errno) {
            case E_STRICT:
                if (DEBUG >= 3) {
                    echo self::formatErrorToHtml($errno, $errstr, $errfile, $errline);
                }
                return;
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
            case E_DEPRECATED:
                if (DEBUG >= 2) {
                    echo self::formatErrorToHtml($errno, $errstr, $errfile, $errline);
                }
                return;
                break;

            default:
                if (DEBUG == 0) {
                    $id = uniqid();
                    self::displayClientMessage($id);
                    self::logError($errno, $errstr, $errfile, $errline, $id);
                    self::sendMailToAdmin($errno, $errstr, $errfile, $errline, $id);
                } elseif (DEBUG >= 1) {
                    echo self::formatErrorToHtml($errno, $errstr, $errfile, $errline);
                }
                break;
        }
    }

    /**
     * Traite les erreurs bloquant l'exécution
     *
     */
    public static function shutdownHandler() {
        $error = error_get_last();
        if ($error !== NULL) {
            switch ($error['type']) {
                case E_STRICT:
                    if (DEBUG >= 3) {
                        echo self::formatErrorToHtml($error['type'], $error['message'], $error['file'], $error['line']);
                    }
                    return;
                    break;

                case E_NOTICE:
                case E_USER_NOTICE:
                    if (DEBUG >= 2) {
                        echo self::formatErrorToHtml($error['type'], $error['message'], $error['file'], $error['line']);
                    }
                    return;
                    break;

                default:
                    if (DEBUG == 0) {
                        $id = uniqid();
                        self::displayClientMessage($id);
                        self::logError($error['type'], $error['message'], $error['file'], $error['line'], $id);
                        self::sendMailToAdmin($error['type'], $error['message'], $error['file'], $error['line'], $id);
                    } elseif (DEBUG >= 1) {
                        echo self::formatErrorToHtml($error['type'], $error['message'], $error['file'], $error['line']);
                    }
                    break;
            }
        }
    }

    /**
     * Affiche l'erreur graphiquement
     *
     * @param int Type d'erreur
     * @param string Message d'erreur
     * @param string Chemin vers le fichier générant l'erreur
     * @param int Ligne à laquelle est survenu l'erreur
     *
     * @return string
     */
    private static function formatErrorToHtml($errorNo, $erroMsg, $errorFile, $errorLine) {
        if (array_key_exists($errorNo, self::$errorType)) {
            $error = self::$errorType[$errorNo];
        } else {
            $error = 'CAUGHT EXCEPTION';
        }
        $error .= ' : ' . $erroMsg . ' in ' . $errorFile . ' on line ' . $errorLine;
        $html = '<font size="1"><table dir="ltr" border="1" cellspacing="0" cellpadding="1">';
        $html .= '<tbody><tr><th align="left" bgcolor="#f57900" colspan="5"><span style="background-color: #cc0000; color: #fce94f; font-size: x-large;">( ! )</span> ' . $error . '</th></tr>';
        $html .= '</tbody></table></font>';
        return $html;
    }

    /**
     * Affiche le problème à l'utilisateur
     */
    private static function displayClientMessage($id) {
        echo 'Une erreur critique est survenu. Un email contenant les informations de l\'erreur a été envoyé à l\'administrateur pour le traitement.<br />
              Identifiant d\'erreur : ' . $id;
    }

    /**
     * Log l'erreur dans un fichier de log
     *
     * @param int Type d'erreur
     * @param string Message d'erreur
     * @param string Chemin vers le fichier générant l'erreur
     * @param int Ligne à laquelle est survenu l'erreur
     * @param int Identifiant de l'erreur
     *
     */
    private static function logError($errorNo, $erroMsg, $errorFile, $errorLine, $id) {
        if (array_key_exists($errorNo, self::$errorType)) {
            $error = self::$errorType[$errorNo];
        } else {
            $error = 'CAUGHT EXCEPTION';
        }
        $error = '[AGC] ' . date('[d-M-Y H:i:s]') . ' [' . $id . '] PHP ' . $error . ' :  ' . $erroMsg . ' in ' . $errorFile . ' on line ' . $errorLine . '
';
        error_log($error, 3, "/var/log/apache2/php.log");
    }

    /**
     * Envoi l'erreur par mail à l'administrateur
     *
     * @param int Type d'erreur
     * @param string Message d'erreur
     * @param string Chemin vers le fichier générant l'erreur
     * @param int Ligne à laquelle est survenu l'erreur
     * @param int Identifiant de l'erreur
     *
     */
    private function sendMailToAdmin($errorNo, $erroMsg, $errorFile, $errorLine, $id) {
        $hdrs = array(
            'From' => 'error_agc@proservia.fr',
            'Subject' => 'AGC - ' . date('[d-M-Y H:i:s]') . ' ' . $erroMsg
        );
        $crlf = "\n";

        $mime = new Mail_mime($crlf);
        $mime->setHTMLBody('Erreur survenue sur l\'AGC le ' . date('d-m-Y à H:i:s') . ' généré par ' . $_SESSION[SESSION_PREFIX.'logged']->Id_utilisateur . ' (' . $_SERVER['REMOTE_ADDR'] . ')<br />
		URL de consultation : ' . $_SERVER['REQUEST_URI'] . '<br /><br />
		[' . $id . '] PHP ' . $error . ' :  ' . $erroMsg . ' in ' . $errorFile . ' on line ' . $errorLine);

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        $params['host'] = SMTP_HOST;
        $params['port'] = SMTP_PORT;
        $mail_object = Mail::factory('smtp', $params);

        $sendMail = true;
        $dateJour = date('d');
        $dateMois = date('m');
        $dateAnnee = date('Y');
        $dateHeure = date('h');
        if ($dateJour == '28' && $dateMois == '02' && $dateAnnee == '2016' && in_array($dateHeure, array('01','02','03','04','05','06'))) {
                $sendMail = false;
        }

        if ($sendMail) {
                $send = $mail_object->send(MAIL_ERREUR, $hdrs, $body);
                if (PEAR::isError($send)) {
                        print($send->getMessage());
                }
        }
    }

}

?>