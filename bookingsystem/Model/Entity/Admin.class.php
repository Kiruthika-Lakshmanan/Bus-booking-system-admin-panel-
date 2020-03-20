<?php
namespace bookingsystem\Model\Entity;

class Admin {
   
    protected $name;
    protected $emailId;
    protected $password;
    protected $phoneNumber;
    protected $gender;
    protected $age;
    protected $isAdmin;
    protected $errors = [];
    protected $success = [];
    protected $resetKey;
    
    public function __construct($id = 0)
    {
        $this->resetKey = '';
        if (!empty($id)) {
            $this->id = $id;
            $this->get();
        }
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
    
    public function setResetKey($resetKey)
    {
        $this->resetKey = $resetKey;
    }

    public function getResetKey()
    {
        return $this->resetKey;
    }
    
    public function  setGender($gender)
    {
       $this->gender = $gender;
    }

    public function getGender()
    {
        return $this->gender;
    }
    
    public function  setAge($age)
    {
       $this->age = $age;
    }

    public function getAge()
    {
        return $this->age;
    }
    
    public function  setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function  setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }
    
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }
   
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getSuccess()
    {
        return $this->success;
    }
    
    public function save() 
    {
        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `user`
            SET name          = "' . $this->name . '",
                age           = "' . $this->age . '",
                gender        = "' . $this->gender . '",
                phone_number  = "' . $this->phoneNumber . '", 
                email         = "' . $this->emailId . '",
                password      = "' . $this->password . '",
                is_admin      = "' . $this->isAdmin . '",    
                reset_key     = "' . $this->resetKey . '"' .
                (!empty($this->id) ? 'WHERE `id` = ' . $this->id : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the user data.';
        }
        return $result;
    }
    
    public function getByEmail()
    {
        if (empty($this->emailId)) {
            return false;
        }

        $sql    = 'SELECT * FROM `user` WHERE `email` = "' . $this->emailId . '"';
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists with the mentioned email ID.';
            return false;
        }

        $user              = $result->fetch_assoc();
        $this->id          = $user['id'];
        $this->name        = $user['name'];
        $this->age         = $user['age'];
        $this->gender      = $user['gender'];
        $this->phoneNumber = $user['phone_number'];
        $this->emailId     = $user['email'];
        $this->password    = $user['password'];
        $this->isAdmin     = $user['is_admin'];
        $this->resetKey    = $user['reset_key'];

        return true;
    }
    
    public function getAll()
    {
        $result = \bookingsystem\Config\Db::getInstance()->query('SELECT `id` FROM `user`');
        $resultArray = [];
//      echo $result->row_count;die;
        while ($row = $result->fetch_assoc()) {
            $resultArray[] = new Admin($row['id']);
        }
        return $resultArray;
    }
    
    public function check()        
    {   
         $sql = '
            SELECT email, password, is_admin FROM `user`
            WHERE email = "' . $this->emailId . '" AND
                  password = "' . $this->password .'" AND
                  is_admin = "' . 1 .'"
            ';
            
        $db = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($db->num_rows >0) {
            $this->success[] = "sucessfully registered.";
        } else {
            $this->errors[] = 'UserId or Password is incorrect';
        }
        if (!empty($this->errors)) {
           return false;
        } else {
           return true;
        }
    }
    
    public function delete()
    {
        if (!empty($this->validation())) {
            
            return false;
        }
        if (empty($this->id)) {
            return false;
        }

        return \bookingsystem\Config\Db::getInstance()->query(
            'DELETE FROM `user` WHERE `id` = ' . $this->id
        ); 
        
    }

}


