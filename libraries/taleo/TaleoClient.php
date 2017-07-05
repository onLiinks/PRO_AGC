<?php
/**
 * Description of TaleoClient
 *
 * @author mathieu.perrin
 */
class TaleoClient {

    private $client;

    function __construct($wsdl = TALEO_FIND_WSDL) {
        try {
            $this->client = new SoapClient(
                $wsdl, array(
                    'login' => TALEO_USER,
                    'password' => TALEO_PASSWD,
                    'trace' => true,
                    'encoding'=>'UTF-8'
                )
            );
        } catch (Exception $ex) {
            var_dump($ex);
        }
    }

    function query($query = null, $xml = false, $attributes = null, $pagination = 1, $pageSize = 0) {
        if ($attributes == null) {
            $attributes = array('e'=>array('key' => '1'), 'mappingVersion' => 'http://www.taleo.com/ws/tee800/2009/01');
        }
        $query = '<ns1:query> ' . $query . '</ns1:query>';
        $xmlvar = new SoapVar($query, XSD_ANYXML);
        // Paramètre pour récupérer la page 1
        $atr = '
            <ns1:attributes>
                <ns1:entry>
                    <ns1:key>pageindex</ns1:key>
                    <ns1:value>'.$pagination.'</ns1:value>
                </ns1:entry>';
        if ($pageSize > 0) {
            $atr .= '<ns1:entry>
                    <ns1:key>pagingsize</ns1:key>
                    <ns1:value>'.$pageSize.'</ns1:value>
                </ns1:entry>';
        }
        $atr .= '</ns1:attributes>';
        $xmlvar2 = new SoapVar($atr, XSD_ANYXML);
        $params = new stdClass();
        $params->query = (Object) $xmlvar;
        $params->mappingVersion = $attributes['mappingVersion'];
        $params->attributes = (Object) $xmlvar2;

        $continue = true;
        $a = array();
        // Récupération au format XML de la réponse
        if ($xml === false) {
            $i = 0;
            // Requète continue tant qu'il reste des pages
            while ($continue/* && $i < 20*/) {
                try {
                    $result = $this->client->findPartialEntities($params);
                } catch (Exception $exc) {
                    echo $this->client->__getLastRequest().'<br/>';
                    echo $this->client->__getLastResponse().'<br/>';
                    echo $exc->getMessage();
                    //die();
                }
                //var_dump($result);
                // On stocke les inforamtions dans un tableau ou un objet
                if ($result->Entities->entityCount == 0) {
                    $a = null;
                } else if ($result->Entities->entityCount == 1) {
                    $a = $result->Entities->Entity;
                } else {
                    if (is_array($result->Entities->Entity)) {
                        foreach ($result->Entities->Entity as $entity) {
                            $o = new stdClass();
                            foreach ($entity as $property => $value) {
                                $o->$property = $value;
                            }
                            $a[] = $o;
                        }
                    } else {
                        $o = new stdClass();
                        foreach ($result->Entities->Entity as $property => $value) {
                            $o->$property = $value;
                        }
                        $a[] = $o;
                    }
                }

                // On passe à la page suivante
                $atr = '
                <ns1:attributes>
                    <ns1:entry>
                        <ns1:key>pageindex</ns1:key>
                        <ns1:value>' . (intval($result->Entities->pageIndex) + 1) . '</ns1:value>
                    </ns1:entry>';
                if ($pageSize > 0) {
                    $atr .= '<ns1:entry>
                            <ns1:key>pagingsize</ns1:key>
                            <ns1:value>'.$pageSize.'</ns1:value>
                    </ns1:entry>';
                }
                $atr .= '</ns1:attributes>';

                $xmlvar2 = new SoapVar($atr, XSD_ANYXML);
                $params->attributes = (Object) $xmlvar2;

                if(intval($result->Entities->pageIndex) == intval($result->Entities->pageCount) || $result->Entities->pageCount == 0) {
                    $continue = false;
                }
                $i++;
            }
        } else {
            try {
                $result = $this->client->findPartialEntities($params);
            } catch (Exception $exc) {
                echo $this->client->__getLastRequest().'<br/>';
                echo $this->client->__getLastResponse().'<br/>';
                echo $exc->getMessage();
            }
            return $this->client->__getLastResponse();
        }

        return $a;
    }

    function merge($query = null) {
        $xmlvar = new SoapVar($query, XSD_ANYXML);
        $params->merge = (Object) $xmlvar;
        $params->mappingVersion = 'http://www.taleo.com/ws/tee800/2009/01';

        $a = array();

        try {
            $result = $this->client->merge($params);
        } catch (Exception $exc) {
            echo $this->client->__getLastRequest().'<br/>';
            echo $this->client->__getLastResponse().'<br/>';
            echo $exc->getMessage();
        }

        // On stocke les inforamtions dans un tableau ou un objet
        if ($result->Entities->entityCount == 0) {
            $a = null;
        } else if ($result->Entities->entityCount == 1) {
            $a = $result->Entities->Entity;
        } else {
            foreach ($result->Entities->Entity as $entity) {
                $o = new stdClass();
                foreach ($entity as $property => $value) {
                    $o->$property = $value;
                }
                $a[] = $o;
            }
        }

        return $a;
    }

    function submitDocument($query = null) {
        $xmlvar = new SoapVar($query, XSD_ANYXML);
        $params->submitDocument = (Object) $xmlvar;
        $params->mappingVersion = 'http://www.taleo.com/ws/so800/2009/01';

        $this->client->__setSoapHeaders(array(
            new SOAPHeader('http://www.w3.org/2005/03/addressing', 'MessageID', "99999999"),
            new SOAPHeader('http://www.w3.org/2005/03/addressing', 'ReplyTo', new SoapVar("<ns2:ReplyTo><ns2:Address>http://www.taleo.com/ws/integration/toolkit/2005/07/addressing/queue</ns2:Address><ns2:ReferenceParameters></ns2:ReferenceParameters></ns2:ReplyTo>", XSD_ANYXML)),
            new SOAPHeader('http://www.w3.org/2005/03/addressing', 'Action', "http://www.taleo.com/ws/integration/toolkit/2005/07/action/import"),
        ));

        $a = array();

        try {
            $result = $this->client->submitDocument($params->submitDocument);
        } catch (Exception $exc) {
            echo $this->client->__getLastRequest().'<br/>';
            echo $this->client->__getLastResponse().'<br/>';
            echo $exc->getMessage();
        }

        // On stocke les inforamtions dans un tableau ou un objet
        if ($result->Entities->entityCount == 0) {
            $a = null;
        } else if ($result->Entities->entityCount == 1) {
            $a = $result->Entities->Entity;
        } else {
            foreach ($result->Entities->Entity as $entity) {
                $o = new stdClass();
                foreach ($entity as $property => $value) {
                    $o->$property = $value;
                }
                $a[] = $o;
            }
        }

        return $a;
    }

    function getCandidatesWithSS() {
        //get candidates
        $query = '<ns1:query alias="SimpleProjection" projectedClass="Study" locale="fr-FR" largegraph="true">
                   <ns1:projections>
                    <ns1:projection>
                            <ns1:field path="Application,Candidate,Number"/>
                    </ns1:projection>
                    <ns1:projection>
                            <ns1:field path="Application,Candidate,LastName"/>
                    </ns1:projection>
                    <ns1:projection>
                            <ns1:field path="Application,Candidate,FirstName"/>
                    </ns1:projection>
                    <ns1:projection>
                            <ns1:field path="Application,Candidate,EmployeeNumber"/>
                    </ns1:projection>
                    <ns1:projection>
                            <ns1:field path="N_b0_20de_20s_e9curit_e9_20sociale"/>
                    </ns1:projection>
                   </ns1:projections>
                   <ns1:filterings>
                      <ns1:filtering>
                        <ns1:or>
                          <ns1:contains>
                            <ns1:field path="N_b0_20de_20s_e9curit_e9_20sociale"/>
                            <ns1:string>0</ns1:string>
                          </ns1:contains>
                          <ns1:contains>
                            <ns1:field path="N_b0_20de_20s_e9curit_e9_20sociale"/>
                            <ns1:string>1</ns1:string>
                          </ns1:contains>
                        </ns1:or>
                      </ns1:filtering>
                      <ns1:filtering xmlns:quer="http://www.taleo.com/ws/integration/query">
                    <ns1:isNotNull><ns1:field path="Application,Candidate,Number"/></ns1:isNotNull>
                    </ns1:filtering>
                   </ns1:filterings>
                   <ns1:sortings>
                       <ns1:sorting ascending="true">
                           <ns1:field path="N_b0_20de_20s_e9curit_e9_20sociale" />
                       </ns1:sorting>
                   </ns1:sortings>
           </ns1:query>';
        $curpage = 1;
        $maxpage = 2;
        while ($curpage < $maxpage) {
            $xml = $this->query($query, true, null, $curpage);
            $dom = new DOMDocument($xml);
            $dom->loadXML($xml);

            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xpath->registerNamespace('ns1', 'http://www.taleo.com/ws/tee800/2009/01/find');
            $xpath->registerNamespace('root', 'http://www.taleo.com/ws/tee800/2009/01/find');
            $xpath->registerNamespace('e', 'http://www.taleo.com/ws/tee800/2009/01');

            $entities = $xpath->query("//e:Entity");
            $maxpage = (int)$xpath->query("//root:Entities")->item(0)->attributes->getNamedItem('pageCount')->textContent;
            $r = array();
            for ($i = 0; $i < $entities->length; ++$i) {
                $o = new stdClass();
                $entity = $entities->item($i);
                $candidate = $entity->childNodes->item(0)->childNodes->item(0)->childNodes->item(0)->childNodes->item(0)->childNodes;
                for ($j = 0; $j < $candidate->length; ++$j) {
                    switch ($candidate->item($j)->nodeName) {
                        case 'e:EmployeeNumber' :
                            $o->EmployeeNumber = utf8_decode($candidate->item($j)->textContent);
                            break;
                        case 'e:LastName' :
                            $o->LastName = utf8_decode($candidate->item($j)->textContent);
                            break;
                        case 'e:FirstName' :
                            $o->FirstName = utf8_decode($candidate->item($j)->textContent);
                            break;
                        case 'e:Number' :
                            $o->Number = utf8_decode($candidate->item($j)->textContent);
                            break;
                    }
                }
                $o->NumeroSS = utf8_decode($entity->childNodes->item(1)->childNodes->item(0)->textContent);
                $r[] = $o;
            }
            $curpage++;
        }
        return $r;
    }

    function getCandidateInformation($id = null) {
        $query = '
        <ns1:query alias="ProjectionWithRelations" projectedClass="Candidate" locale="fr-FR">
            <ns1:projections>
                <ns1:projection>
                    <ns1:field path="Number"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="Civilite,Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="LastName"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="FirstName"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Nom_20jeune_20fille"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="Address"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="Address2"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="ZipCode"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="City"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="Pays,Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="HomePhone"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="MobilePhone"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="EmailAddress"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Number"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,N_b0_20de_20s_e9curit_e9_20sociale"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Date_20de_20naissance"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Lieu_20de_20naissance"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Nationalit_e9,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Contrat_20Proservia,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Pays_20de_20naissance,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,D_e9partement_20de_20naissance,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Etat_20matrimonial,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Agence_20de_20rattachement,UDSElement.Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Agence_20de_20rattachement,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Profil_20embauche,UDSElement.Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Profil_20embauche,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Type_20de_20contrat_20sign_e9,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Date_20de_20fin_20contrat_20_28si_20CDD_29"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Motif_20du_20CDD,UDSElement.Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Motif_20du_20CDD,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Salari_e9_20remplac_e9"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Date_20embauche"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Heure_20embauche"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Statut_20embauche,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Salaire_20annuel_20_28en_20K_u20ac_29"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Service_20embauche,UDSElement.Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Informations_20compl_e9mentaires_20au_20contrat"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,LibelleEmploi,UDSElement.Description"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,Staff,UDSElement.Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,TypeEmbauche,UDSElement.Code"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="TalentUser,Profile,Studies,LibelleEmploiComp"/>
                </ns1:projection>
                <ns1:projection>
                    <ns1:field path="RQTH,Code"/>
                </ns1:projection>
            </ns1:projections>
            <ns1:filterings>
                <ns1:filtering>
                    <ns1:equal>
                        <ns1:field path="Number"/>
                        <ns1:integer>' . (int) $id . '</ns1:integer>
                    </ns1:equal>
                </ns1:filtering>
            </ns1:filterings>
        </ns1:query>';
        $xml = $this->query($query, true);
        $dom = new DOMDocument($xml);
        $dom->loadXML($xml);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xpath->registerNamespace('ns1', 'http://www.taleo.com/ws/tee800/2009/01/find');
        $xpath->registerNamespace('root', 'http://www.taleo.com/ws/tee800/2009/01/find');
        $xpath->registerNamespace('e', 'http://www.taleo.com/ws/tee800/2009/01');

        $o = new stdClass();
        $o->Number = utf8_decode($xpath->query('//root:Entities/e:Entity/e:Number')->item(0)->textContent);
        $o->Civilite = utf8_decode($xpath->query('//root:Entities/e:Entity/e:UDFs/e:UDF[@name="Civilite"]')->item(0)->textContent);
        $o->LastName = utf8_decode($xpath->query('//e:Entity/e:LastName')->item(0)->textContent);
        $o->FirstName = utf8_decode($xpath->query('//root:Entities/e:Entity/e:FirstName')->item(0)->textContent);
        $o->NomJeuneFille = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Nom_20jeune_20fille"]')->item(0)->textContent);
        $o->Address = utf8_decode($xpath->query('//root:Entities/e:Entity/e:Address')->item(0)->textContent);
        $o->Address2 = utf8_decode($xpath->query('//root:Entities/e:Entity/e:Address2')->item(0)->textContent);
        $o->ZipCode = utf8_decode($xpath->query('//root:Entities/e:Entity/e:ZipCode')->item(0)->textContent);
        $o->City = utf8_decode($xpath->query('//root:Entities/e:Entity/e:City')->item(0)->textContent);
        $o->Country = utf8_decode($xpath->query('//root:Entities/e:Entity/e:UDFs/e:UDF[@name="Pays"]')->item(0)->textContent);
        $o->HomePhone = utf8_decode($xpath->query('//root:Entities/e:Entity/e:HomePhone')->item(0)->textContent);
        $o->MobilePhone = utf8_decode($xpath->query('//root:Entities/e:Entity/e:MobilePhone')->item(0)->textContent);
        $o->EmailAddress = utf8_decode($xpath->query('//root:Entities/e:Entity/e:EmailAddress')->item(0)->textContent);
        $o->DateNaissance = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Date_20de_20naissance"]')->item(0)->textContent);
        $o->LieuNaissance = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Lieu_20de_20naissance"]')->item(0)->textContent);
        $o->InfoComplementaire = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Informations_20compl_e9mentaires_20au_20contrat"]')->item(0)->textContent);
        $o->DepartementNaissance = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="D_e9partement_20de_20naissance"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->PaysNaissance = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Pays_20de_20naissance"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->Nationalite = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Nationalit_e9"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->NumeroSS = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="N_b0_20de_20s_e9curit_e9_20sociale"]')->item(0)->textContent);
        $o->ContratProservia = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Contrat_20Proservia"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->EtatMatrimonial = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Etat_20matrimonial"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->IdAgenceRattachement = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Agence_20de_20rattachement"]/e:UDSElement/e:Code')->item(0)->textContent);
        $o->AgenceRattachement = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Agence_20de_20rattachement"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->IdProfil = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Profil_20embauche"]/e:UDSElement/e:Code')->item(0)->textContent);
        $o->ProfilCegid = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Profil_20embauche"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->TypeEmbauche = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Type_20de_20contrat_20sign_e9"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->Statut = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Statut_20embauche"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->DateFinContrat = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Date_20de_20fin_20contrat_20_28si_20CDD_29"]')->item(0)->textContent);
        $o->MotifCDD = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Motif_20du_20CDD"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->IdMotifCDD = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Motif_20du_20CDD"]/e:UDSElement/e:Code')->item(0)->textContent);
        $o->SalarieRemplace = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Salari_e9_20remplac_e9"]')->item(0)->textContent);
        $o->DateEmbauche = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Date_20embauche"]')->item(0)->textContent);
        $o->HeureEmbauche = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Heure_20embauche"]')->item(0)->textContent);
        $o->Salaire = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Salaire_20annuel_20_28en_20K_u20ac_29"]')->item(0)->textContent);
        $o->IdService = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Service_20embauche"]/e:UDSElement/e:Code')->item(0)->textContent);
        $o->Profil = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="LibelleEmploi"]/e:UDSElement/e:Description')->item(0)->textContent);
        $o->Staff = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="Staff"]/e:UDSElement/e:Code')->item(0)->textContent);
        $o->TypeEmbauche2 = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="TypeEmbauche"]/e:UDSElement/e:Code')->item(0)->textContent);
        $o->LibelleEmploiComp = utf8_decode($xpath->query('//e:Entity/e:TalentUser/e:TalentUser/e:Profile/e:ProfileInformation/e:Studies/e:Study/e:UDFs/e:UDF[@name="LibelleEmploiComp"]')->item(0)->textContent);

        $o->TH = utf8_decode($xpath->query('//root:Entities/e:Entity/e:UDFs/e:UDF[@name="RQTH"]')->item(0)->textContent);

        return $o;
    }

}

?>
