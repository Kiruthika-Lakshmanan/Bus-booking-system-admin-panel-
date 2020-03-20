<?php
namespace bookingsystem\Controller;
ini_set('display_errors', 'on');
error_reporting(E_ALL);
class DashboardController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request =  $request;
        $this->template = clone $request->getTemplate();
    }
    
    public function dashboard()
    {
       $this->template->loadTemplateFile('dashboard.html'); 
       return $this->template->get();
    } 
}    