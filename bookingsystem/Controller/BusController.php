<?php
namespace bookingsystem\Controller;
ini_set('display_errors', 'on');
error_reporting(E_ALL);
class BusController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request =  $request;
        $this->template = clone $request->getTemplate();
    }
    
    public function bus()
    {
        $this->template->LoadTemplateFile('bus.html');
        $postValues = $this->request->getPostParams();
        $successMsg = '';
        $deleteMsg  = '';
        if (isset($_GET['msg']) && $_GET['msg'] == 'sucess') {
            $successMsg = " successfully saved";
        } else if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
            $deleteMsg  = " successfully deleted";
        }
        $busObj  = new \BookingSystem\Model\Entity\Bus();
        $buses   = $busObj->getAll();
        foreach ($buses as $bus) {
            $this->template->setVariable([
                'ID'            => $bus->getId(),
                'BUS_NUMBER'    => $bus->getBusNumber(),
                'STATUS'        => $bus->getStatus(),
                'COACH_TYPE'    => $bus->getCoachType()->getCoachName(), 
                'ROUTE'         => $bus->getRoute()->getRouteName(),
                'RATING'        => $bus->getRating(),
                'AGENCY'        => $bus->getTravelAgency()->getAgencyName(),
                'SUCESS'        => !empty($successMsg) ? $successMsg : '' ,
                'DELETE'        => !empty($deleteMsg) ? $deleteMsg : '' 
            ]);
            $this->template->parse('bus_data');
        }
        return $this->template->get();
    }
    
    public function editBus()
    {
        $this->template->LoadTemplateFile('editbus.html');
        $postValues = $this->request->getPostParams();
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $bus    = new \BookingSystem\Model\Entity\Bus($id);
        $coach  = new \BookingSystem\Model\Entity\Coach();
        $agency = new \BookingSystem\Model\Entity\Agency();
        $route  = new \BookingSystem\Model\Entity\Route();
        if (isset($postValues['submit'])) {
            $bus->setBusNumber($postValues['busNumber']);
            $bus->setStatus($postValues['status']);
            $bus->setCoachType($postValues['coach']);
            $bus->setRoute($postValues['route']);
            $bus->setRating($postValues['rating']);
            $bus->setTravelAgency($postValues['agency']);
            if(!$bus->save()) {
                $error = "Fill all the fields";
            } else {
                header('Location: http://www.busbooking.local/admin/?module=bus&msg=sucess');
            }
            $coachValue  = $postValues['coach'];
            $routeValue  = $postValues['route'];
            $agencyValue = $postValues['agency'];
        } else if (!empty($bus->getId())) {
            $this->template->setVariable([
                'ID'            => $bus->getId(),
                'BUS_NUMBER'    => $bus->getBusNumber(),
                'STATUS'        => $bus->getStatus(),
                'COACH_NAME'    => $bus->getCoachType(), 
                'ROUTE_NAME'    => $bus->getRoute(),
                'RATING'        => $bus->getRating(),
                'AGENCY_NAME'   => $bus->getTravelAgency()
            ]);
            $routeValue  = $bus->getRoute()->getRouteName();
            $agencyValue = $bus->getTravelAgency()->getAgencyName();
            $coachValue  = $bus->getCoachType()->getCoachName();
        }
        $results =  $route->getAll();
        foreach ($results as $sources) {
            $selected = isset($routeValue) && $routeValue == $sources->getRouteName() ? 'selected' : '';
            $this->template->setVariable([
                'ROUTE_ID'       => $sources->getId(),
                'ROUTE_NAME'     => $sources->getRouteName(),
                'ROUTE_SELECTED' => $selected
            ]);
            $this->template->parse('show_route');
        }
        $coaches =  $coach->getAll();
        foreach ($coaches as $sou) {
            $selected = isset($coachValue) && $coachValue == $sou->getCoachName() ? 'selected' : '';
            $this->template->setVariable([
                'COACH_ID'       => $sou->getId(),
                'COACH_NAME'     => $sou->getCoachName(),
                'COACH_SELECTED' => $selected
            ]);
            $this->template->parse('show_coach');
        }
        $result = $agency->getAll();
        foreach ($result as $destination) {
            $selected = isset($agencyValue) && $agencyValue == $destination->getAgencyName() ? 'selected' : '';
            $this->template->setVariable([
                'AGENCY_ID'       => $destination->getId(),
                'AGENCY_NAME'     => $destination->getAgencyName(),
                'AGENCY_SELECTED' => $selected
            ]);
            $this->template->parse('show_agency');
        }
        $this->template->setVariable([
            'ID'            => $bus->getId(),
            'BUS_NUMBER'    => $bus->getBusNumber(),
            'STATUS'        => $bus->getStatus(),
            'COACH'         => $bus->getCoachType(), 
            'ROUTE'         => $bus->getRoute(),
            'RATING'        => $bus->getRating(),
            'AGENCY'        => $bus->getTravelAgency(),
            'ERROR_MESSAGE' => !empty($error) ? $error : '' 
        ]);
        return $this->template->get();
    }
    
    public function deleteBus()
    {
        $this->template->LoadTemplateFile('bus.html');
        $postValues = $this->request->getPostParams();
        $bus  = new \BookingSystem\Model\Entity\Bus($_GET['id']);
        if($_GET['id']) {
            if(! $bus->delete()) {
                $error = "Can't delete data";
            } else {
                header('Location: http://www.busbooking.local/admin/?module=bus&msg=delete');
            }
        }  
        $this->template->setVariable([
            'ERROR'    => !empty($error) ? $error : '' 
        ]);
        return $this->template->get();
    }

    public function addBus()
    {
        $this->template->LoadTemplateFile('addbus.html');
        $postValues = $this->request->getPostParams();
        $bus    = new \BookingSystem\Model\Entity\Bus();
        $route  = new \BookingSystem\Model\Entity\Route();
        $coach  = new \BookingSystem\Model\Entity\Coach();
        $agency = new \BookingSystem\Model\Entity\Agency();
        if (isset($postValues['submit'])) {
            $bus->setBusNumber($postValues['busNumber']);
            $bus->setStatus($postValues['status']);
            $bus->setCoachType($postValues['coach']);
            $bus->setRoute($postValues['route']);
            $bus->setRating($postValues['rating']);
            $bus->setTravelAgency($postValues['agency']);
            if(!$bus->save()) {
                $error = 'Fill all the fields';
            } else {
               header('Location: http://www.busbooking.local/admin/?module=bus&msg=sucess');
            }
        }   
        $results =  $route->getAll();
        foreach ($results as $routes) {
            $selected = isset($postValues['route']) && $postValues['route'] == $routes->getRouteName() ? 'selected' : '';
            $this->template->setVariable([
                'ROUTE_ID'       => $routes->getId(),
                'ROUTE_NAME'     => $routes->getRouteName(),
                'ROUTE_SELECTED' => $selected
            ]);
            $this->template->parse('show_route');
        }
       
        $types =$coach->getAll();
        foreach ($types as $coaches) {
            $selected = isset($postValues['coach']) && $postValues['coach'] == $coaches->getCoachName() ? 'selected' : '';
            $this->template->setVariable([
                'COACH_ID'       => $coaches->getId(),
                'COACH_NAME'     => $coaches->getCoachName(),
                'COACH_SELECTED' => $selected
            ]);
            $this->template->parse('show_coach');
        }
        $agencies =$agency->getAll();
        foreach ($agencies as $travels) {
            $selected = isset($postValues['agency']) && $postValues['agency'] == $travels->getAgencyName() ? 'selected' : '';
            $this->template->setVariable([
                'AGENCY_ID'       => $travels->getId(),
                'AGENCY_NAME'     => $travels->getAgencyName(),
                'AGENCY_SELECTED' => $selected
            ]);
        $this->template->parse('show_agency');
        }
        $this->template->setVariable([
            'BUS_NUMBER'       => $bus->getBusNumber(),
            'STATUS'           => $bus->getStatus(),
            'COACH'            => $bus->getCoachType(), 
            'ROUTE'            => $bus->getRoute(),
            'RATING'           => $bus->getRating(),
            'TRAVEL_AGENCY'    => $bus->getTravelAgency(),
            'ERROR_MESSAGE'    => !empty($error) ? $error : '' 
        ]);
        return $this->template->get();
    }  
    
    public function busdetails()
    {  
        $this->template->LoadTemplateFile('busdetails.html');
        $postValues = $this->request->getPostParams();
        $bus  = new \BookingSystem\Model\Entity\Bus($_GET['id']);
        if($_GET['id']) {
            $buses = $bus->get();
            $this->template->setVariable([
                'ID'            => $bus->getId(),
                'BUS_NUMBER'    => $bus->getBusNumber(),
                'STATUS'        => $bus->getStatus(),
                'COACH_TYPE'    => $bus->getCoachType()->getCoachName(), 
                'ROUTE_NAME'    => $bus->getRoute()->getRouteName(),
                'SOURCE'        => $bus->getRoute()->getSource()->getCityName(),
                'DESTINATION'   => $bus->getRoute()->getDestination()->getCityName(),
                'START_TIME'    => $bus->getRoute()->getStartTime(),
                'JOURNEY_TIME'  => $bus->getRoute()->getJourneyTime(),
                'PRICE'         => $bus->getRoute()->getPrice(),
                'RATING'        => $bus->getRating(),
                'AGENCY_NAME'   => $bus->getTravelAgency()->getAgencyName(),
                'AGENCY_NUMBER' => $bus->getTravelAgency()->getPhoneNumber(),
                'AGENCY_ADDRESS'=> $bus->getTravelAgency()->getOfficeAddress()
            ]);
            $this->template->parse('bus_data');
            }    
        return $this->template->get();
    }
}    