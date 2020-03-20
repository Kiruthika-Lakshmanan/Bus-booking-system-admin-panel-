<?php
namespace BookingSystem\Model\Entity;

class Bus {
    protected $id;
    protected $busNumber;
    protected $coachType;
    protected $status;
    protected $route=[];
    protected $travelAgency;
    protected $rating;
    protected $agencyId;


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
    
    public function setAgencyId($agencyId)
    {
         $this->agencyId = $agencyId;
    }
    
    public function getAgencyId()
    {
        return $this->agencyId;
    }
    
    public function setBusNumber($busNumber)
    {
        $this->busNumber = $busNumber;
    }

    public function getBusNumber()
    {
        return $this->busNumber;
    }
    
    public function setCoachType($coachType)
    {
        $this->coachType = $coachType;
    }

    public function getCoachType()
    {
        return $this->coachType;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
    
    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function getRoute()
    {
        return $this->route;
    }
    
    public function setTravelAgency($travelAgency)
    {
        $this->travelAgency = $travelAgency;
    }

    public function getTravelAgency()
    {
        return $this->travelAgency;
    }
    
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function getRating()
    {
        return $this->rating;
    }
    
    public function Validate() {
        if(empty($this->busNumber)||empty($this->route||empty($this->rating)||empty($this->route||empty($this->coachType)||empty($this->travelAgency)))) {
            $this->errors[] = 'Fill Mandatory Field * ';
        }
        return (bool) empty($this->errors);
    }
    
    public function save() 
    {
        if (!$this->validate()) {
            return false;
        }
        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `bues`
            SET bus_number       = "' . $this->busNumber . '",
                status           = "' . $this->status . '",
                coach_type       = "' . $this->coachType . '",
                route            = "' . $this->route . '",  
                rating           = "' . $this->rating . '",     
                travel_agency    = "' . $this->travelAgency . '"' .
                (!empty($this->id) ? 'WHERE `id` = ' . $this->id : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the  data.';
        }
        return $result;
    }  
    
    public function get()
    {
        if (empty($this->id)) {
            return false;
        }
        $sql    = 'SELECT * FROM `bues` WHERE `id` = ' . $this->id;
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'Bus not exists.';
            return false;
        }
        //$die($sql);
        $bus = $result->fetch_assoc();
        $this->id            = $bus['id'];
        $this->busNumber     = $bus['bus_number'];
        $this->status        = $bus['status'];
        $this->coachType     = new Coach($bus['coach_type']);
        $this->route         = new Route($bus['route']);
        $this->rating        = $bus['rating'];
        $this->travelAgency  = new Agency($bus['travel_agency']);
        return true;
    }        

    public function delete()
    {
        if (empty($this->id)) {
            return false;
        }
        return \bookingsystem\Config\Db::getInstance()->query(
            'DELETE FROM `bues` WHERE `id` = ' . $this->id
        ); 
    }     
    
    public function getAll() 
    {
        $sql = 'SELECT * FROM  `bues` ' .
                (!empty($this->agencyId) ? 'WHERE `travel_agency` = ' . $this->agencyId : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        //die($sql);
        $resultArray = [];
         while ($row = $result->fetch_assoc()) {
            $resultArray[] = new Bus($row['id']);
        }
        return $resultArray;
    }    
}         
