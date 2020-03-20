<?php
namespace BookingSystem\Model\Entity;

class Agency {
    protected $id;
    protected $agencyName;
    protected $officeAddress;
    protected $phoneNumber;
    protected $bus;


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
    
    public function setAgencyName($agencyName)
    {
        $this->agencyName = $agencyName;
    }

    public function getAgencyName()
    {
        return $this->agencyName;
    }
    
    public function setOfficeAddress($officeAddress)
    {
        $this->officeAddress = $officeAddress;
    }

    public function getOfficeAddress()
    {
        return $this->officeAddress;
    }
    
    public function setBus($bus)
    {
        $this->bus = $bus;
    }

    public function getBus()
    {
        return $this->bus;
    }
    
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function Validate() {
        if(empty($this->agencyName)||empty($this->phoneNumber)||empty($this->officeAddress)) {
            $this->errors[] = 'Fill Mandatory Field * ';
        }
        return (bool) empty($this->errors);
    }
    
    
    public function save() 
    {
        if (!$this->validate()) {
            return false;
        }
        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `agency`
            SET agency_name       = "' . $this->agencyName . '",
                office_address    = "' . $this->officeAddress . '",
                phone_number      = "' . $this->phoneNumber . '"' . 
              
                (!empty($this->id) ? 'WHERE `id` = ' . $this->id : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the  data.';
        }
        return $result;
    }
    
    public function getAll()
    {
        $sql = \bookingsystem\Config\Db::getInstance()->query('SELECT `id` FROM `agency`');
        $resultArray = [];
        while ($row = $sql->fetch_assoc()) {
            $resultArray[] = new Agency($row['id']);
        }
        return $resultArray;
    }  
    
    public function get()
    {
        if (empty($this->id)) {
            return false;
        }
        $sql  = 'SELECT * FROM `agency` WHERE `id` = ' . $this->id;
       //$sql  = ' SELECT * FROM `agency` as a LEFT JOIN `bues` as b ON (a.id=b.travel_agency) WHERE a.id= ' . $this->id;
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists s.';
            return false;
        }
        //die($sql);
        $agency   = $result->fetch_assoc();
        $this->id             = $agency['id'];
        $this->agencyName     = $agency['agency_name'];
        $this->officeAddress  = $agency['office_address'];
        $this->phoneNumber    = $agency['phone_number'];

        return true;
    }
     
    public function delete()
    {
        if (empty($this->id)) {
            return false;
        }
        return \bookingsystem\Config\Db::getInstance()->query(
            'DELETE FROM `agency` WHERE `id` = ' . $this->id
        ); 
        
    }
    
}         

//  SELECT agency.id, bues.bus_number, agency.office_address FROM agency INNER JOIN bues ON agency.id=bues.travel_agency  
