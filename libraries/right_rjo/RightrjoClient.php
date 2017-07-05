<?php
/**
 * Description of RightrjoClient
 *
 * @author yannick.betou
 */
class RightrjoClient extends SoapClient {

    function __construct() {
        $wsdl = RIGHT_RJO_WSDL;
        try {
            $this->context = stream_context_create();
            parent::SoapClient(
                $wsdl, array(
                    /*'Username' => RIGHT_RJO_USER,
                    'Password' => RIGHT_RJO_PASSWD,*/
                    'trace' => true,
                    'encoding'=>'UTF-8',
                )
            );                
        } catch (Exception $ex) {
            var_dump($ex);
        }
    }

    function send($employerId, $jobs) {
        $request = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body>
<JobServiceAction xmlns="'.RIGHT_RJO_SERVICE_URL.'">
<Job>';
        foreach ($jobs as $job) {
            $request .= '<JobInformationRequest>
<Action>'.$job['action'].'</Action>
<JobTitle>'.$job['jobtitle'].'</JobTitle>
<JobDescription><![CDATA['.$job['jobdescription'].']]></JobDescription>
<CandidateSkills><![CDATA['.$job['candidateskills'].']]></CandidateSkills>
<PositionTypeID>'.$job['positiontypeid'].'</PositionTypeID>
<PositionTypeDescription>'.$job['positiontypedescription'].'</PositionTypeDescription>
<IndustrySectorID>'.$job['industrysectorid'].'</IndustrySectorID>
<IndustrySectorDescription>'.$job['industrysectordescription'].'</IndustrySectorDescription>
<SalaryOverride>'.$job['salaryoverride'].'</SalaryOverride>
<PublishDate>'.$job['publishdate'].'</PublishDate>
<ExpiryDate>'.$job['expirydate'].'</ExpiryDate>
<LocationID>'.$job['locationid'].'</LocationID>
<LocationDescription>'.$job['locationdescription'].'</LocationDescription>
<EmployerJobReferenceNumber>'.$job['employerjobreferencenumber'].'</EmployerJobReferenceNumber>
<ApplicationContactName>'.$job['applicationcontactname'].'</ApplicationContactName>
<ApplicationEmail>'.$job['applicationemail'].'</ApplicationEmail>
<ApplicationWebsite>'.$job['applicationwebsite'].'</ApplicationWebsite>
</JobInformationRequest>
';
        }
        $request .= '</Job>
<EmployerID>' . $employerId . '</EmployerID></JobServiceAction></soap:Body></soap:Envelope>';
		try {
                    $result = $this->__doRequest($request, ''.RIGHT_RJO_SERVICE_URL.'/jobservice.asmx', ''.RIGHT_RJO_SERVICE_URL.'/JobServiceAction', 1);
                } catch (Exception $exc) {
                     echo $this->__getLastRequest().'<br/>';
                     echo $this->__getLastResponse().'<br/>';
                     echo $exc->getMessage();
                }
        return $result;
    }
    
    
    function delete($employerId, $reqs) {
        $request = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body>
<JobServiceAction xmlns="'.RIGHT_RJO_SERVICE_URL.'">
<Job>';
        foreach ($reqs as $req) {
            $request .= '
<JobInformationRequest>
    <Action>update</Action>
    <EmployerJobReferenceNumber>'.$req['id'].'</EmployerJobReferenceNumber>
	<ExpiryDate>'.$req['closedate'].'</ExpiryDate>
    <CreatingBadJob>false</CreatingBadJob>
</JobInformationRequest>
';
        }
        $request .= '</Job>
<EmployerID>' . $employerId . '</EmployerID></JobServiceAction></soap:Body></soap:Envelope>';
           try {
                    $result = $this->__doRequest($request, ''.RIGHT_RJO_SERVICE_URL.'/jobservice.asmx', ''.RIGHT_RJO_SERVICE_URL.'/JobServiceAction', 1);
            } catch (Exception $exc) {
                     echo $this->__getLastRequest().'<br/>';
                     echo $this->__getLastResponse().'<br/>';
                     echo $exc->getMessage();
            }
            return $result;
    }
    
    function __doRequest($request, $location, $action, $version, $one_way = 0) {
        return (parent::__doRequest($request, $location, $action, $version, $one_way)); 
    }
}

?>
