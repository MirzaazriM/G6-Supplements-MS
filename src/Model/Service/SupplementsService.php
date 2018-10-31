<?php

namespace Model\Service;

use Model\Entity\Shared;
use Model\Entity\Supplement;
use Model\Service\Facade\CollectionToArrayConvertor;
use Model\Entity\ResponseBootstrap;
use Model\Mapper\SupplementsMapper;
use Model\Service\Facade\MicroservicesCommunicator;
use Monolog\Logger;


class SupplementsService
{
    private $supplementsMapper;
    private $configuration;
    private $monolog;

    public function __construct(SupplementsMapper $supplementsMapper)
    {
        $this->supplementsMapper = $supplementsMapper;
        $this->configuration = $supplementsMapper->getConfiguration();
        $this->monolog = new Logger('monolog');
    }

    /**
     * Get supplement by id
     *
     * @param int $id
     * @return ResponseBootstrap
     */
    public function getSupplement(int $id):ResponseBootstrap
    {
        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Supplement();
            $entity->setId($id);

            // call mapper for data
            $data = $this->supplementsMapper->getSupplement($entity);

            // convert collection data to an array in a facade object
            $facade = new CollectionToArrayConvertor($data);
            $convertedData = $facade->convertData();

            // get microservices data using MicroservicesCommunicator object
            $communicator = new MicroservicesCommunicator($convertedData, $this->configuration);
            $convertedData = $communicator->integrateMicroservicesData();

            // check data and set appropriate response
            if(!empty($convertedData)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData($convertedData);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch(\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get supplements by ids
     *
     * @param $ids
     * @return ResponseBootstrap
     */
    public function getSupplementsByIds($ids):ResponseBootstrap {

        try {
            // create new response object
            $response = new ResponseBootstrap();

            // create entity and add ids value
            $entity = new Supplement();
            $entity->setIds($ids);

            // call mapper for data
            $data = $this->supplementsMapper->getSupplementsByIds($entity);

            // convert collection data to an array in a facade object
            $facade = new CollectionToArrayConvertor($data);
            $convertedData = $facade->convertData();

            // get microservices data using MicroservicesCommunicator object
            $communicator = new MicroservicesCommunicator($convertedData, $this->configuration);
            $convertedData = $communicator->integrateMicroservicesData();

            // check data and set appropriate response
            if(!empty($convertedData)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData($convertedData);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch(\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get supplements by ids service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get multiple supplement
     *
     * @param int $from
     * @param int $limit
     * @return ResponseBootstrap
     */
    public function getSupplements(int $from, int $limit):ResponseBootstrap {

        try {
            // create new response object
            $response = new ResponseBootstrap();

            // create entity and add from and limit values
            $entity = new Supplement();
            $entity->setFrom($from);
            $entity->setLimit($limit);

            // call mapper for data
            $data = $this->supplementsMapper->getSupplements($entity);

            // convert collection data to an array in a facade object
            $facade = new CollectionToArrayConvertor($data);
            $convertedData = $facade->convertData();

            // get microservices data using MicroservicesCommunicator object
            $communicator = new MicroservicesCommunicator($convertedData, $this->configuration);
            $convertedData = $communicator->integrateMicroservicesData();

            // check data and set appropriate response
            if(!empty($convertedData)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData($convertedData);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch(\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get supplements service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get search results
     *
     * @param string $term
     * @return ResponseBootstrap
     */
    public function getSearchResults(string $term):ResponseBootstrap {

        try {
            // create new response object
            $response = new ResponseBootstrap();

            // create entity and add from and limit values
            $entity = new Supplement();
            $entity->setName($term);

            // call mapper for data
            $data = $this->supplementsMapper->getSearchedSupplements($entity);

            // convert collection data to an array in a facade object
            $facade = new CollectionToArrayConvertor($data);
            $convertedData = $facade->convertData();

            // get microservices data using MicroservicesCommunicator object
            $communicator = new MicroservicesCommunicator($convertedData, $this->configuration);
            $convertedData = $communicator->integrateMicroservicesData();

            // check data and set appropriate response
            if(!empty($convertedData)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData($convertedData);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return response
            return $response;

        }catch(\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get searched supplements service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Add supplement service
     *
     * @param string $name
     * @param string $description
     * @param array $images
     * @param array $tags
     * @return ResponseBootstrap
     */
    public function addSupplement(string $name, string $description, array $images, array $tags):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Supplement();
            $entity->setName($name);
            $entity->setDescription($description);
            $entity->setImages($images);
            $entity->setTags($tags);

            // create shared entity
            $shared = new Shared();

            // call mapper for inserting data and returning result of action
            $result = $this->supplementsMapper->addSupplement($entity, $shared);

            // check data and set response
            if ($result->getState() === 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            } else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Add supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Edit supplement
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $outOfStock
     * @param array $images
     * @param array $tags
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function editSupplement(int $id, string $name, string $description, string $outOfStock, array $images, array $tags):ResponseBootstrap {

        try {
            // create new response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Supplement();
            $entity->setId($id);
            $entity->setName($name);
            $entity->setDescription($description);
            $entity->setOutOfStock($outOfStock);
            $entity->setImages($images);
            $entity->setTags($tags);

            // create shared entity
            $shared = new Shared();

            // get response
            $result = $this->supplementsMapper->editSupplement($entity, $shared);

            // check state and set response
            if($result->getState() === 200){
                // delete cache at products MS
                $communicator = new MicroservicesCommunicator([], $this->configuration);
                $communicator->callProductsMsToDeleteCache();

                // set response
                $response->setStatus(200);
                $response->setMessage('Success');
            }else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Edit supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Delete supplement
     *
     * @param int $id
     * @return ResponseBootstrap
     */
    public function deleteSupplement(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Supplement();
            $entity->setId($id);

            // create shared entity
            $shared = new Shared();

            // call mapper to delete supplement
            $result = $this->supplementsMapper->deleteSupplement($entity, $shared);

            // check state and set response
            if($result->getState() === 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            } else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Edit supplement service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }

}