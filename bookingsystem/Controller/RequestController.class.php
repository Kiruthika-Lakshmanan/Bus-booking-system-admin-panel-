<?php
namespace bookingsystem\Controller;

class RequestControllerException extends \Exception {}

class RequestController {

    protected $module;
    protected $action;
    protected $postParams = [];
    protected $template;
    protected $navigationList = [];
//    protected $response = [];

    /**
     * Constructor to validate the request
     */
    public function __construct()
    {
        $this->template = new \HTML_Template_Sigma(ROOT_DIR . '/View/Template/Frontend/');
        $this->template->setErrorHandling(PEAR_ERROR_DIE);

        try {
            $this->validateRequest();
        } catch (RequestControllerException $e) {
            return $e->getMessage();
        }
    }

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Validate request parameters
     *
     * @throws ApiRequestHandlerException
     */
    protected function validateRequest()
    {
        $this->module = isset($_GET['module']) && !empty($_GET['module']) ? $_GET['module'] : '';
        $this->action = isset($_GET['act']) && !empty($_GET['act']) ? $_GET['act'] : '';
        $this->setPostParams();
    }

    /**
     * Fetch and set the post input parameter values
     */
    protected function setPostParams()
    {
        $this->postParams = !empty($_POST) ? $_POST : [];
    }

    /**
     * Get the post parameters
     *
     * @return array Post parameters
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    public function processRequest()
    {
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
                    $response = $loginController->userLogin();
                   break;
            }
        }    
        if ($response) {
          $this->finalResponse($response);
        }
    }
     
    public function submitRequest()
    {
       
        $loginController = new \bookingsystem\Controller\LoginController($this);    
        $response = $loginController->sucess();
        
        if ($response) {
          $this->finalResponse($response);
        }
       
    }        

    public function finalResponse($output)
    {
        $template = clone $this->template;
        $template->loadTemplateFile('layout.html');

        // Parse the navigation
        if (!empty($this->navigationList)) {
            foreach ($this->navigationList as $module => $navigation) {
                $navigationText = $navigation;
                $navigationLink = 'http://www.busbooking.local/admin/?module=' . $module;
                if (is_array($navigation)) {
                    $navigationText = $navigation['name'];
                } else {
                    $template->hideBlock('show_sub_navigation');
                }
                $template->setVariable([
                    'NAVIGATION_LINK' => $module == '#' ? '#' : $navigationLink,
                    'NAVIGATION_TEXT' => $navigationText,
                    'ACTIVE'          => !empty($_GET['module']) && $_GET['module'] == $module ? 'active' : '' 
                ]);
                $template->parse('show_navigation');
                if (is_array($navigation) && isset($navigation['children'])) {
                    foreach ($navigation['children'] as $action => $subNavigation) {
                        $subNavigationLink = $navigationLink . '&act=' . $action;
                        $template->setVariable([
                            'SUBNAVIGATION_LINK' => $module == '#' ? '#' : $subNavigationLink,
                            'SUBNAVIGATION_TEXT' => $subNavigation,
                            'ACTIVE'             => !empty($_GET['act']) && $_GET['act'] == $action ? 'active' : ''
                        ]);
                        $template->parse('show_sub_navigation_menu');
                    }
                }
            }
        }
        $template->setVariable([
            'CONTENT' => $output,
            'BACKGROUND_CLASS' =>  $this->module 
        ]);
        echo $template->get();
        exit;
    }
}