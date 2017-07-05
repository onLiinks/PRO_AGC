<?php

/**
 * Description of RessourceFactory
 *
 * @author mathieu.perrin
 */
class RessourceFactory {
    public static function create($type, $id, $tab) {
        switch ($type) {
            case 'SAL':
                $ressource = new Salarie($id, $tab);
            break;
            case 'SAL_STA':
                $ressource = new PersonnelStructure($id, $tab);
            break;
            case 'ST':
                $ressource = new SousTraitant($id, $tab);
            break;
            case 'INT':
                $ressource = new Interimaire($id, $tab);
            break;
            case 'MAT':
                $ressource = new Materiel($id, $tab);
            break;
            case 'CAN_AGC':
                $ressource = new CandidatAGC($id, $tab);
            break;
            case 'CAN_TAL':
                $ressource = new CandidatTaleo($id, $tab);
            break;
            case 'CAN_STA':
                $ressource = new CandidatTaleoStaff($id, $tab);
            break;
            case '':
                $ressource = new Salarie($id, $tab);
            break;
            default:
                throw new AGCException('Type de ressource inconnue.');
            break;
        }
        return $ressource;
    }
}

?>
