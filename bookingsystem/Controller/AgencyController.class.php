<?php
namespace bookingsystem\Controller;
ini_set('display_errors', 'on');
error_reporting(E_ALL);
class AgencyController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request =  $request;
        $this->template = clone $request->getTemplate();
    } 
    
    public function travelAgency()
    {
        $this->template->LoadTemplateFile('agency.html');
        $postValues = $this->request->getPostParams();
        $successMsg = '';
        $deleteMsg  = '';
        if (isset($_GET['msg']) && $_GET['msg'] == 'sucess') {
            $successMsg = " successfully saved";
        } else if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
            $deleteMsg = " successfully deleted";
        }
        $agency  = new \BookingSystem\Model\Entity\Agency();
        $agences =  $agency->getAll();
        foreach ($agences as $travel) {
                $this->template->setVariable([
                    'ID'              => $travel->getId(),
                    'AGENCY_NAME'     => $travel->getAgencyName(),
                    'OFFICE_ADDRESS'  => $travel->getOfficeAddress(),
                    'PHONE_NUMBER'    => $travel->getPhoneNumber(),
                    'SUCESS'          => !empty($successMsg) ? $successMsg : '',
                    'DELETE'          => !empty($deleteMsg) ? $deleteMsg : '' 
              
            ]);
            $this->template->parse('agency_data');
        }
        return $this->template->get();

    }
    
    public function addAgency()
    {
        $this->template->LoadTemplateFile('addagency.html');
        $postValues = $this->request->getPostParams();
        $agency = new \BookingSystem\Model\Entity\Agency();
        if (isset($postValues['submit'])) {
            $agency->setAgencyName($postValues['agencyName']);
            $agency->setPhoneNumber($postValues['phoneNumber']);
            $agency->setOfficeAddress($postValues['officeAddress']);
            if(!$agency->save()) {
                $error = "unable to save";
            } else {
                 header('Location: http://www.busbooking.local/admin/?module=agency&msg=sucess');
            }
        }   
       
        $this->template->setVariable([
                'AGENCY_NAME'       => $agency->getAgencyName(),
                'PHONE_NUMBER'      => $agency->getPhoneNumber(),
                'OFFICE_ADDRESS'    => $agency->getOfficeAddress(), 
                'ERROR_MESSAGE'    => !empty($error) ? $error : '' 
                ]);
        return $this->template->get();

    }  
    
    public function deleteAgency()
    {
        $this->template->LoadTemplateFile('agency.html');
        $postValues = $this->request->getPostParams();
        $agency = new \BookingSystem\Model\Entity\Agency($_GET['id']);
        if($_GET['id']) {
            if(! $agency->delete()) {
                $error = "Can't delete data";
            } else {
               header('Location: http://www.busbooking.local/admin/?module=agency&msg=delete');
            }
        }  
        $this->template->setVariable([
                'ERROR'    => !empty($error) ? $error : '' 
                ]);
        return $this->template->get();
    }
    
    public function editAgency()
    {
        $this->template->LoadTemplateFile('editagency.html');
        $postValues = $this->request->getPostParams();
        $agency = new \BookingSystem\Model\Entity\Agency($_GET['id']);
        if (isset($_GET['id'])) {
            if(!$agency->get()) {
                $error = "data not found";
            } else {
               $this->template->setVariable([
                    'AGENCY_NAME'       => $agency->getAgencyName(),
                    'PHONE_NUMBER'      => $agency->getPhoneNumber(),
                    'OFFICE_ADDRESS'    => $agency->getOfficeAddress(), 
                    'ERROR_MESSAGE'     => !empty($error) ? $error : '' 
            ]);
            }
        }    
        if (isset($postValues['submit'])) {
            $agency->setAgencyName($postValues['agencyName']);
            $agency->setPhoneNumber($postValues['phoneNumber']);
            $agency->setOfficeAddress($postValues['officeAddress']);
            if(!$agency->save()) {
                $error = "unable to save";
            } else {
                header('Location: http://www.busbooking.local/admin/?module=agency&msg=sucess');
            }
        }   
        $this->template->setVariable([
              
                'AGENCY_NAME'       => $agency->getAgencyName(),
                'PHONE_NUMBER'      => $agency->getPhoneNumber(),
                'OFFICE_ADDRESS'    => $agency->getOfficeAddress(), 
                'ERROR_MESSAGE'     => !empty($error) ? $error : ''   
        ]);
        return $this->template->get();
    }
    
//    public function agencyDetails()
//    {
//        $this->template->LoadTemplateFile('agencydetails.html');
//        $postValues = $this->request->getPostParams();
//        $agency = new \BookingSystem\Model\Entity\Agency($_GET['id']);
//        if($agency->get()) {
//          $agencyId = $agency->getId();
//        } else {
//            $error = "data not found";
//        }
//        $busObj  = new \BookingSystem\Model\Entity\Bus();
//        $buses   = $busObj->getAll();
//        foreach ($buses as $bus) {
//            $travelAgency = $bus->getTravelAgency();
//            if($travelAgency == $agencyId) {
//                $this->template->setVariable([
//                    'ID'            => $bus->getId(),
//                    'BUS_NUMBER'    => $bus->getBusNumber(),
//                    'STATUS'        => $bus->getStatus(),
//                    'COACH_TYPE'    => $bus->getCoachType()->getCoachName(), 
//                    'ROUTE'         => $bus->getRoute()->getRouteName(),
//                    'RATING'        => $bus->getRating(),
//                    'ERROR'         => !empty($error) ? $error : ''
//                    
//                ]);
//                $this->template->parse('agency_data');
//            } else {
//                continue;
//            }
//        }
//        return $this->template->get();
//    }
    
    public function agencydetails()
    {
        $this->template->LoadTemplateFile('agencydetails.html');
        $postValues = $this->request->getPostParams();
        $agency = new \BookingSystem\Model\Entity\Agency($_GET['id']);  
        $bus    = new \BookingSystem\Model\Entity\Bus();
        $key =  $agency->getId();
        $bus->setAgencyId($key);
        //die($bus->getAgencyId());
        $buses   = $bus->getAll();
        foreach ($buses as $bus) {
            $this->template->setVariable([
                'ID'            => $bus->getId(),
                'BUS_NUMBER'    => $bus->getBusNumber(),
                'STATUS'        => $bus->getStatus(),
                'COACH_TYPE'    => $bus->getCoachType()->getCoachName(), 
                'ROUTE'         => $bus->getRoute()->getRouteName(),
                'AGENCY_NAME'   => $bus->getTravelAgency()->getAgencyName(),
                'RATING'        => $bus->getRating(),
                'ERROR'         => !empty($error) ? $error : ''
                ]);
            $this->template->parse('agency_data');
        }    
       return $this->template->get();  
    }
}    