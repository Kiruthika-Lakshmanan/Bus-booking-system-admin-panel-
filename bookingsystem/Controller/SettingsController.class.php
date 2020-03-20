<?php
namespace bookingsystem\Controller;
ini_set('display_errors', 'on');
error_reporting(E_ALL);
class SettingsController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request =  $request;
        $this->template = clone $request->getTemplate();
    } 
    
    public function route()
    {
        $this->template->loadTemplateFile('route.html'); 
        $postValues = $this->request->getPostParams();
        $successMsg = '';
        $deleteMsg  = '';
        if (isset($_GET['msg']) && $_GET['msg'] == 'sucess') {
            $successMsg = " successfully saved";
        } else if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
            $deleteMsg = " successfully deleted";
        }
        $route   = new \BookingSystem\Model\Entity\Route();
        $results =  $route->getAll();
        foreach ($results as $result) {
            $this->template->setVariable([
                'ID'            => $result->getId(),
                'ROUTE'         => $result->getRouteName(),
                'SOURCE'        => $result->getSource()->getCityName(),
                'DESTINATION'   => $result->getDestination()->getCityName(),
                'BOARDING'      => $result->getBoardingPoint(),
                'STARTING'      => $result->getStartTime(),
                'JOURNEY_TIME'  => $result->getJourneyTime(),
                'PRICE'         => $result->getPrice(),
                'SUCESS'        => !empty($successMsg) ? $successMsg : '' ,
                'DELETE'        => !empty($deleteMsg) ? $deleteMsg : '' 
        ]);
        $this->template->parse('data');
        }
        return $this->template->get();
    }
    
    public function edit()
    {
        $this->template->LoadTemplateFile('editroute.html');
        $postValues = $this->request->getPostParams();
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $city   = new \BookingSystem\Model\Entity\City();
        $route  = new \BookingSystem\Model\Entity\Route($id);
        if (isset($postValues['submit'])) {
            $route->setRouteName($postValues['routeName']);
            $route->setSource($postValues['source']);
            $route->setDestination($postValues['destination']);
            $route->setBoardingPoint($postValues['boarding']);
            $route->setStartTime($postValues['starting']);
            $route->setJourneyTime($postValues['ending']);
            $route->setPrice($postValues['price']);
            if(!$route->save()) {
                $error = implode(' ', $route->getErrors());
            } else {
                header('Location: http://www.busbooking.local/admin/?module=settings&act=route&msg=sucess');
            }
            $sourceCity = $postValues['source'];
            $destinationCity = $postValues['destination'];
        } else if (!empty($route->getId())) {
            $this->template->setVariable([
                'ROUTE_NAME'       => $route->getRouteName(),
                'SOURCE_NAME'      => $route->getSource(),
                'DESTINATION_NAME' => $route->getDestination(), 
                'BOARDING_POINT'   => $route->getBoardingPoint(),
                'STARTING'         => $route->getStartTime(),
                'ENDING'           => $route->getJourneyTime(),
                'PRICE'            => $route->getPrice()  
            ]);
            $sourceCity = $route->getSource()->getCityName();
            $destinationCity = $route->getDestination()->getCityName();
        }
        $results =  $city->getAll();
        foreach ($results as $sources) {
            $selected = isset($sourceCity) && $sourceCity == $sources->getCityName() ? 'selected' : '';
            $this->template->setVariable([
                'SOURCE_ID'       => $sources->getId(),
                'SOURCE_NAME'     => $sources->getCityName(),
                'SOURCE_SELECTED' => $selected
            ]);
            $this->template->parse('show_source');
        }
        foreach ($results as $destination) {
            $selected = isset($destinationCity) && $destinationCity == $destination->getCityName() ? 'selected' : '';
            $this->template->setVariable([
                'DESTINATION_ID'       => $destination->getId(),
                'DESTINATION_NAME'     => $destination->getCityName(),
                'DESTINATION_SELECTED' => $selected
            ]);
            $this->template->parse('show_destination');
        }
        $this->template->setVariable([
            'ERROR_MESSAGE'   => !empty($error) ? $error : '' 
         ]);
        return $this->template->get();
    }
    
    public function addRoute()
    {
        $this->template->LoadTemplateFile('addroute.html');
        $postValues = $this->request->getPostParams();
        $route  = new \BookingSystem\Model\Entity\Route();
        $city  = new \BookingSystem\Model\Entity\City();
        if (isset($postValues['submit'])) {
            $route->setRouteName($postValues['routeName']);
            $route->setSource($postValues['source']);
            $route->setDestination($postValues['destination']);
            $route->setBoardingPoint($postValues['boarding']);
            $route->setStartTime($postValues['starting']);
            $route->setJourneyTime($postValues['ending']);
            $route->setPrice($postValues['price']);
            if(!$route->save()) {
                $error = implode(' ', $route->getErrors());
            } else {
                header('Location: http://www.busbooking.local/admin/?module=settings&act=route&msg=sucess');
            }
        }
        $results =  $city->getAll();
        foreach ($results as $sources) {
            $selected = isset($postValues['source']) && $postValues['source'] == $sources->getCityName() ? 'selected' : '';
            $this->template->setVariable([
                'SOURCE_ID'       => $sources->getId(),
                'SOURCE_NAME'     => $sources->getCityName(),
                'SOURCE_SELECTED' => $selected
            ]);
            $this->template->parse('show_city');
        }
        foreach ($results as $destination) {
            $selected = isset($postValues['destination']) && $postValues['destination'] == $destination->getCityName() ? 'selected' : '';
            $this->template->setVariable([
                'DESTINATION_ID'       => $destination->getId(),
                'DESTINATION_NAME'     => $destination->getCityName(),
                'DESTINATION_SELECTED' => $selected
            ]);
            $this->template->parse('show_cities');
        }
        $this->template->setVariable([
            'ROUTE'           => $route->getRouteName(),
            'SOURCE'          => $route->getSource(),
            'DESTINATION'     => $route->getDestination(),
            'BOARDING'        => $route->getBoardingPoint(),
            'STARTING'        => $route->getStartTime(),
            'ENDING'          => $route->getJourneyTime(),
            'PRICE'           => $route->getPrice(),
            'ERROR'           => !empty($errors) ? $errors : '' ,
            'ERROR_MESSAGE'   => !empty($error) ? $error : '' 
            ]);
        return $this->template->get();
    }

    public function city()
    {
        $this->template->LoadTemplateFile('cities.html');
        $postValues = $this->request->getPostParams();
        $successMsg = '';
        $deleteMsg  = '';
        if (isset($_GET['msg']) && $_GET['msg'] == 'sucess') {
            $successMsg = " successfully saved";
        } else if (isset($_GET['msg']) && $_GET['msg'] == 'delete') {
           $deleteMsg = " successfully deleted";
        }
        $city  = new \BookingSystem\Model\Entity\City();
        $results =  $city->getAll();
        foreach ($results as $result) {
            $this->template->setVariable([
                'ID'            => $result->getId(),
                'CITY'          => $result->getCityName(),
                'SUCESS'        => !empty($successMsg) ? $successMsg : '' ,
                'DELETE'        => !empty($deleteMsg) ? $deleteMsg : '' 
            ]);
            $this->template->parse('city_data');
        }
        return $this->template->get();
    }
    
    public function addCity()
    {
        $this->template->LoadTemplateFile('addcity.html');
        $postValues = $this->request->getPostParams();
        $city  = new \BookingSystem\Model\Entity\City();
        if (isset($postValues['submit'])) {
            $city->setCityName($postValues['cityName']);
            if(!$city->save()) {
                $error = "unable to save";
            } else {
                header('Location: http://www.busbooking.local/admin/?module=settings&act=city&msg=sucess');
            }
        }
        $this->template->setVariable([
            'ROUTE'            => $city->getCityName(),
            'ERROR_MESSAGE'    => !empty($error) ? $error : ''  
            ]);
        return $this->template->get();
    }
    
    public function editCity()
    {
        $this->template->LoadTemplateFile('editcity.html');
        $postValues = $this->request->getPostParams();
        $city  = new \BookingSystem\Model\Entity\City($_GET['id']);
       
        if (isset($_GET['id'])) {
            if(!$city->get()) {
                $error = "data not found";
            } else {
                $this->template->setVariable([
                    'CITY_NAME'      => $city->getCityName(),
                ]);
            }
        }
        if (isset($postValues['submit'])) {
            $city->setCityName($postValues['cityName']);
            if(!$city->save()) {
                $error = "unable to save";
            } else {
                header('Location: http://www.busbooking.local/admin/?module=settings&act=city&msg=sucess');
            }
        }
        $this->template->setVariable([
            'ERROR_MESSAGE'  => !empty($error) ? $error : '' 
        ]);
        return $this->template->get();

    }
    
    public function deleteCity()
    {
        $this->template->LoadTemplateFile('cities.html');
        $postValues = $this->request->getPostParams();
        $city  = new \BookingSystem\Model\Entity\City($_GET['id']);
        if($_GET['id']) {
            if(! $city->delete()) {
                $error = "Can't delete data";
            } else {
                header('Location: http://www.busbooking.local/admin/?module=settings&act=city&msg=delete');
            }
        }
        $this->template->setVariable([
            'ERROR' => !empty($error) ? $error : '' ,
        ]);
        return $this->template->get();
    }
               
    public function delete()
    {
        $this->template->LoadTemplateFile('route.html');
        $postValues = $this->request->getPostParams();
        $route  = new \BookingSystem\Model\Entity\Route($_GET['id']);
        if($_GET['id']) {
            if(! $route->delete()) {
                $error = "Can't delete data";
            } else {
                 header('Location: http://www.busbooking.local/admin/?module=settings&act=route&msg=delete');
            }
        }  
        $this->template->setVariable([
            'ERROR'  => !empty($error) ? $error : '' ,
        ]);
        return $this->template->get();
    }
    
}    