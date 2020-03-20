<?php
namespace BookingSystem\Model\Entity;

class Route {
    
    protected $id;
    protected $routeName;
    protected $source;
    protected $destination;
    protected $boardingPoint;
    protected $startTime;
    protected $journeyTime;
    protected $price;
    protected $cities;
    protected $errors = [];

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
    
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }
    
    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }
    
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    public function getDestination()
    {
        return $this->destination;
    }
    
    public function setBoardingPoint($boardingPoint)
    {
        $this->boardingPoint = $boardingPoint;
    }

    public function getBoardingPoint()
    {
        return $this->boardingPoint;
    }
    
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }
    
    public function setJourneyTime($journeyTime)
    {
        $this->journeyTime = $journeyTime;
    }

    public function getJourneyTime()
    {
        return $this->journeyTime;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function setCities($cities)
    {
        $this->cities = $cities;
    }   
    
    public function getCities()
    {
        return $this->cities;
    }        

    public function Validate() {
        if(empty($this->routeName)||empty($this->price)||empty($this->boardingPoint)||empty($this->source)||empty($this->destination)) {
            $this->errors[] = 'Fill Mandatory Field * ';
        }
        return (bool) empty($this->errors);
    }
    
    public function save() 
    {
        if (!$this->validate()) {
            return false;
        }
        $sql = (empty($this->id) ? 'INSERT INTO' : 'UPDATE') . ' `route`
            SET route_name       = "' . $this->routeName . '",
                source           = "' . $this->source . '",
                destination      = "' . $this->destination . '",
                boarding_point   = "' . $this->boardingPoint . '",
                starting_time    = "' . $this->startTime . '", 
                journey_time     = "' . $this->journeyTime . '",   
                price            = "' . $this->price . '"' .
                (!empty($this->id) ? 'WHERE `id` = ' . $this->id : '');
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if (!$result) {
            $this->errors[] = 'Failed to ' . (empty($this->id) ? 'store' : 'update') . ' the  data.';
        }
        return $result;
    }
    
    public function getAll()
    {
        $sql = \bookingsystem\Config\Db::getInstance()->query('SELECT `id` FROM `route`');
        $resultArray = [];
        while ($row = $sql->fetch_assoc()) {
            $resultArray[] = new Route($row['id']);
        }
        return $resultArray;
    }  
    
    public function get()
    {
        if (empty($this->id)) {
            return false;
        }
        $sql  = 'SELECT * FROM `route` WHERE `id` = ' . $this->id;
        $result = \bookingsystem\Config\Db::getInstance()->query($sql);
        if ($result->num_rows != 1) {
            $this->errors[] = 'User not exists s.';
            return false;
        }
        $route   = $result->fetch_assoc();
        $this->id            = $route['id'];
        $this->routeName     = $route['route_name'];
        $this->source        = new City($route['source']);
        $this->destination   = new City($route['destination']);
        $this->boardingPoint = $route['boarding_point'];
        $this->startTime     = $route['starting_time'];
        $this->journeyTime   = $route['journey_time'];
        $this->price         = $route['price'];
        return true;
    }
    
    public function delete()
    {
        if (empty($this->id)) {
            return false;
        }
        return \bookingsystem\Config\Db::getInstance()->query(
            'DELETE FROM `route` WHERE `id` = ' . $this->id
        ); 
    }
}         