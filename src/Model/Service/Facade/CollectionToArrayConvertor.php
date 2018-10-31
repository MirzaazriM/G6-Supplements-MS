<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 9/3/18
 * Time: 4:17 PM
 */

namespace Model\Service\Facade;


use Model\Entity\SupplementsCollection;

class CollectionToArrayConvertor
{

    private $collection;


    public function __construct(SupplementsCollection $collection){
        $this->collection = $collection;
    }


    public function convertData():array {
        // create new array
        $data = [];

        // loop through data
        for($i = 0; $i < count($this->collection); $i++){
            $data[$i]['id'] = $this->collection[$i]->getId();
            $data[$i]['name'] = $this->collection[$i]->getName();
            $data[$i]['description'] = $this->collection[$i]->getDescription();
            $data[$i]['out_of_stock'] = $this->collection[$i]->getOutOfStock();
            $data[$i]['date_added'] = $this->collection[$i]->getDate();
            $data[$i]['images'] = $this->collection[$i]->getImages();
            $data[$i]['tags'] = $this->collection[$i]->getTags();
        }

        // return converted data
        return $data;
    }
}