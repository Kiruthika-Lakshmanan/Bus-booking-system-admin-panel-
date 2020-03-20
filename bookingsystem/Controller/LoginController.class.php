<?php
namespace bookingsystem\Controller;
ini_set('display_errors', 'on');
error_reporting(E_ALL);
class LoginController {
    protected $template;
    protected $request;

    public function __construct($request)
    {
        $this->request =  $request;
        $this->template = clone $request->getTemplate();
    }
 
    public function signup()
    {
        $this->template->loadTemplateFile('signup.html');
        $postValues = $this->request->getPostParams();
        //var_dump($postValues); 
        $passenger = new \bookingsystem\Model\Entity\Passenger();
        if (isset($postValues['submit'])) {
            $passenger->setName($postValues['name']);
            $passenger->setPassword($postValues['password']);
            $passenger->setEmailId($postValues['emailId']);
            $passenger->setPhoneNumber($postValues['mobileNumber']);
            $passenger->setAge($postValues['age']);
            $passenger->setGender($postValues['gender']);
            $key=0;
            $passenger->setIsAdmin($key);
            $errors = '';
            if ($passenger->save()) {
               // die("jsgd");
                header('Location: http://www.busbooking.local/?module=login&msg=register');
            } else {
                $errors = implode(' ', $passenger->getErrors());
            }
        }
         $availableGender = [
            1 => 'Male',
            2 => 'Female',
           
        ];
        foreach ($availableGender as $genderId => $availableGender) {
            $selected = isset($postValues['gender']) && $postValues['gender'] == $genderId ? 'selected' : '';
            $this->template->setVariable([
                'GENDER_NAME' => $availableGender,
                'GENDER_ID'   => $genderId,
                'GENDER_SELECTED' => $selected
            ]);
            $this->template->parse('show_gender');
        }
         
        $this->template->setVariable([
            'ID'         => $passenger->getId(),
            'NAME'       => $passenger->getName(),
            'EMAIL'      => $passenger->getEmailId(),
            'PASSWORD'   => $passenger->getPassword(),
            'PHONE'      => $passenger->getPhoneNumber(),
            'GENDER'     => $passenger->getGender(),
            'AGE'        => $passenger->getAge(), 
            'ERROR_MESSAGE' => (!empty($errors) ? $errors : '')
        ]);
        return $this->template->get();
        
    }
     
    public function login()
    {
        $this->template->loadTemplateFile('login.html');
        $postValues = $this->request->getPostParams();
        $passenger = new \bookingsystem\Model\Entity\Passenger();
        $sucess = '';
        if (isset($postValues['login'])) {
            $passenger->setEmailId($postValues['userName']);
            $passenger->setPassword($postValues['password']);
            if (!$passenger->check()) {
                 $error = implode('  ', $passenger->getErrors());
            } else {
                 $sucess = implode('  ', $passenger->getSuccess());
                 header('Location: http://www.busbooking.local/admin/?module=login&act=dashboard');
            }
        }
        $this->template->setVariable([
            
            'ERROR_MESSAGE' => !empty($error) ? $error : '',
            'SUCESS_MESSAGE'=> !empty($sucess) ? $sucess : ''
        ]);
        return $this->template->get();
        
    }
    
    public function userLogin()
    {
       $this->template->loadTemplateFile('userlogin.html');
        $postValues = $this->request->getPostParams();
       
        $successMsg = '';
        if (isset($_GET['msg']) && $_GET['msg'] == 'register') {
            $successMsg = "Student registered successfully.";
        }
        
        if (isset($postValues['login'])) {
            $passenger = new \bookingsystem\Model\Entity\Passenger();
            $passenger->setUserName($postValues['userName']);
            $passenger->setPassword($postValues['password']);
            
            if ($passenger->check()) {
               
               $success = implode('  ', $passenger->getSuccess()); 
            } else {
               $error = implode('  ', $passenger->getErrors());
            }
        }
        $this->template->setVariable([
           
            'SUCCESS_MESSAGE' => !empty($successMsg) ? $successMsg : '',
            'SUCESS'          => !empty($success) ? $success : '',
            'ERROR_MESSAGE'   => !empty($error) ? $error : ''
        ]);
        return $this->template->get();
    }        

    public function forgetPassword()
    {
        $this->template->loadTemplateFile('forgetpage.html');  
        $postValues = $this->request->getPostParams();
        if (isset($postValues['submit'])) {
            $passenger = new \bookingsystem\Model\Entity\Passenger();
            $passenger->setEmailId($postValues['email']);
            if (!$passenger->getByEmail()) {
                $error = implode(' ', $passenger->getErrors());
            } else {
                $key = md5($passenger->getEmailId());
                $passenger->setResetKey($key);
                if (!$passenger->save()) {
                    $error = implode(' ', $passenger->getErrors());
                } else {
                    $resetMessage = '<a href="http://www.busbooking.local/?module=login&act=reset&rk='. $key .'">Click here</a><span style="color:white;"> to reset your password.</span>';
                }
            }
        }

        if (!empty($resetMessage)) {
            $this->template->hideBlock('show_forget_password_form');
        }
        $this->template->setVariable([
            'ERROR_MESSAGE' => !empty($error) ? $error : '',
            'RESET_CONTENT' => $resetMessage ?? ''
        ]);

        return $this->template->get();
    } 
    
    public function dashBoard()
    {
       $this->template->loadTemplateFile('dashboard.html'); 
       return $this->template->get();
    }        

        public function resetPassword()
    {
        $this->template->loadTemplateFile('reset.html');
        $postValues = $this->request->getPostParams();
        if (isset($postValues['submit'])) {
            $passenger = new \bookingsystem\Model\Entity\Passenger();
            $passenger->setEmailId($postValues['email']);
            if (!$passenger->getByEmail()) {
                $error = implode(' ', $passenger->getErrors());
            } else {
                if (
                    $_GET['rk'] != $passenger->getResetKey() &&
                    $postValues['new_password'] != $postValues['confirm_password']
                ) {
                    $resetMsg = 'password mismatches,please enter correct password';
                } else {
                    $passenger->setPassword($postValues['new_password']);
                    $passenger->setResetKey(0);
                    $passenger->save();
                    $updateSucess = 'Password updated sucessfully';
                }
            }
        }

        $this->template->setVariable([
            'ERROR_MESSAGE' => !empty($error) ? $error : '',
            'RESET_MESSAGE' => !empty($resetMsg) ? $resetMsg : '',
            'UPDATE_MESSAGE' => !empty($updateSucess) ? $updateSucess : ''
        ]);
        return $this->template->get();
    }
}
 
