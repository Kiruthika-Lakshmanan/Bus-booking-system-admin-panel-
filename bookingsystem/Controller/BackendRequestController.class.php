<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace bookingsystem\Controller;

/**
 * Description of BackendRequestController
 *
 * @author ss4u
 */
class BackendRequestController extends RequestController {
    /**
     * Constructor to validate the request
     */

    public function __construct()
    {
        
        $this->template = new \HTML_Template_Sigma(ROOT_DIR . '/View/Template/Backend/');
        $this->template->setErrorHandling(PEAR_ERROR_DIE);

        $this->navigationList = [
//            'dashboard' => 'Dashboard',
            'dashboard' => 'Dashboard',
            'bus'       => 'Bus',
            'agency'    => 'Travel Agency',
            'settings'  => [
                'name'  => 'Settings',
                'children' => [
                    'route' => 'Routes',
                    'city'  => 'Cities'
                ]
            ]
        ];

        try {
            $this->validateRequest();
        } catch (RequestControllerException $e) {
            return $e->getMessage();
        }
    }
    

    public function processRequest()
    {
        $loginController = new \bookingsystem\Controller\LoginController($this);
        $response = $loginController->login();
        if ($this->module == '' || $this->module == 'login') {
            $loginController = new \bookingsystem\Controller\LoginController($this);
            switch ($this->action) {
                case 'signup':
                    $response = $loginController->signup();
                   break;
                case 'forget':
                    $response = $loginController->forgetPassword();
                   break;
                case 'reset':
                    $response = $loginController->resetPassword();
                   break;
                default:
                    $response = $loginController->login();
                   break;
            } 
        } else  if ($this->module == 'settings') {
            $loginController = new \bookingsystem\Controller\LoginController($this);
            $settingsController = new \bookingsystem\Controller\SettingsController($this);
            switch ($this->action) {
                case 'settings':
                    $response = $settingsController->settings();
                    break;
                case 'route':
                    $response = $settingsController->route();
                    break;
                case 'edit':
                    $response = $settingsController->edit();
                    break;
                case 'delete':
                    $response = $settingsController->delete();
                    break;
                case 'addroute':
                    $response = $settingsController->addRoute();
                    break;
                case 'addcity':
                    $response = $settingsController->addCity();
                    break;
                case 'city':
                    $response = $settingsController->city();
                    break;
                case 'editcity':
                    $response = $settingsController->editCity();
                    break;
                case 'deletecity':
                    $response = $settingsController->deleteCity();
                    break;
                default:
                    $response = $settingsController->route();
                    break;
            }
        } elseif ($this->module == 'bus') {
                $busController = new \bookingsystem\Controller\BusController($this);
               
                switch ($this->action) {
                    case 'buses':
                       $response = $busController->bus();
                       break;
                    case 'editbus':
                       $response = $busController->editBus();
                       break;
                    case 'deletebus':
                       $response = $busController->deleteBus();
                       break;
                    case 'addbus':
                       $response = $busController->addBus();
                       break;
                    case 'busdetails':
                       $response = $busController->busDetails();
                       break;
                    default:
                       $response = $busController->bus();
                       break;
            }
        } elseif ($this->module == 'agency') {
                $agencyController = new \bookingsystem\Controller\AgencyController($this);
                switch ($this->action) {
                    case 'agency':
                       $response = $agencyController->travelAgency();
                       break;
                    case 'editagency':
                       $response = $agencyController->editAgency();
                       break;
                    case 'deleteagency':
                       $response = $agencyController->deleteAgency();
                       break;
                    case 'addagency':
                       $response = $agencyController->addAgency();
                       break;
                   case 'agencydetails':
                       $response = $agencyController->agencyDetails();
                       break;
                    default:
                       $response = $agencyController->travelAgency();
                       break;
                    
                }   
        } elseif ($this->module == 'dashboard') {   
            $dashboardController = new \bookingsystem\Controller\DashboardController($this);
            switch ($this->action) {
                  default:
                       $response = $dashboardController->dashboard();
                       break;
        
            }       
        }    
        if ($response) {
          $this->finalResponse($response);
        }
   
    }
}
