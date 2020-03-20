<?php
namespace BookingSystem\Model\Entity;

class City {
    
    protected $id;
    protected $cityName;
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
    
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    public function getCityName()
    {
        return $this->cityName;
    }
    
    public function save() 
    {
        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `cities`
            SET cityNames            = "' . $this->cityName . '"' .
                (!empty($this->id) ? 'WHERE `id` = ' . $this->id : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the data.';
        }
        return $result;
    }
    
    public function getAll()
    {
        $sql = \bookingsystem\Config\Db::getInstance()->query('SELECT `id` FROM `cities`');
        $resultArray = [];
        while ($row = $sql->fetch_assoc()) {
            $resultArray[] = new City($row['id']);
        }
        return $resultArray;
    }  
    
    public function get()
    {
        if (empty($this->id)) {
            return false;
        }
        $sql  = 'SELECT * FROM `cities` WHERE `id` = ' . $this->id;
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists s.';
            return false;
        }
        $city   = $result->fetch_assoc();
        $this->id            = $city['id'];
        $this->cityName      = $city['cityNames'];
        return true;
    }
    
    public function delete()
    {
        return \bookingsystem\Config\Db::getInstance()->query(
            'DELETE FROM `cities` WHERE `id` = ' . $this->id
        ); 
    }
} 