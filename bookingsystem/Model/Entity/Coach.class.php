<?php
namespace BookingSystem\Model\Entity;

class Coach {
    
    protected $id;
    protected $coachName;
    protected $errors;

    public function __construct($id = 0)
    {
        if (!empty($id)) {
            $this->id = $id;
            $this->get();
        }
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function setCoachName($coachName)
    {
        $this->coachName = $coachName;
    }

    public function getCoachName()
    {
        return $this->coachName;
    }
    
    public function save() 
    {
        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `coach`
            SET coaches   = "' . $this->coachName . '"' .
                (!empty($this->id) ? 'WHERE `id` = ' . $this->id : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the data.';
        }
        return $result;
    }
    
    public function getAll()
    {
        $sql = \bookingsystem\Config\Db::getInstance()->query('SELECT `id` FROM `coach`');
        $resultArray = [];
        while ($row = $sql->fetch_assoc()) {
            $resultArray[] = new Coach($row['id']);
        }
        return $resultArray;
    }  
    
    public function get()
    {
        if (empty($this->id)) {
            return false;
        }
        $sql  = 'SELECT * FROM `coach` WHERE `id` = ' . $this->id;
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists s.';
            return false;
        }
        $coach   = $result->fetch_assoc();
        $this->id             = $coach['id'];
        $this->coachName      = $coach['coaches'];
        return true;
    }
    
    public function delete()
    {
        return \bookingsystem\Config\Db::getInstance()->query(
            'DELETE FROM `coach` WHERE `id` = ' . $this->cityId
        ); 
    }
} 