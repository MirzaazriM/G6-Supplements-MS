<?php

namespace Application\Controller;

use Model\Entity\Supplement;
use Model\Entity\SupplementsCollection;
use Model\Entity\ResponseBootstrap;
use Model\Service\SupplementsService;
use Symfony\Component\HttpFoundation\Request;

class SupplementsController
{
    private $supplementsService;

    public function __construct(SupplementsService $supplementsService)
    {
        $this->supplementsService = $supplementsService;
    }

    /**
     * Get single supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function get(Request $request):ResponseBootstrap {
        // get id
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if id is set
        if (isset($id)){
            return $this->supplementsService->getSupplement($id);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Get supplement by ids
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getIds(Request $request):ResponseBootstrap {
        // get ids
        $ids = $request->get('ids');

        // create response object
        $response = new ResponseBootstrap();

        // check if ids are set
        if (isset($ids)){
            return $this->supplementsService->getSupplementsByIds($ids);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Get all supplements
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function getAll(Request $request):ResponseBootstrap {
        // get data from url
        $from = $request->get('from');
        $limit = $request->get('limit');

        // create response object
        $response = new ResponseBootstrap();

        // check if neccesary data is set
        if(isset($from) && isset($limit)){
            // call service
            return $this->supplementsService->getSupplements($from, $limit);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Get searched supplements
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function getSearch(Request $request):ResponseBootstrap {
        // get data from url
        $term = $request->get('term');

        // create response object
        $response = new ResponseBootstrap();

        // check if neccesary data is set
        if(isset($term)){
            // call service
            return $this->supplementsService->getSearchResults($term);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Add supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function post(Request $request):ResponseBootstrap
    {
        // get body data
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $description = $data['description'];
        $images = $data['images'];
        $tags = $data['tags'];

        // create response object
        $response = new ResponseBootstrap();

        // check data
        if (isset($name) && isset($description) && isset($images) && isset($tags)){
            return $this->supplementsService->addSupplement($name, $description, $images, $tags);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Edit supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(Request $request):ResponseBootstrap {
        // get body data
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $outOfStock = $data['out_of_stock'];
        $images = $data['images'];
        $tags = $data['tags'];

        // create response object
        $response = new ResponseBootstrap();

        // check if data is set
        if(isset($id) && isset($name) && isset($description) && isset($outOfStock) && isset($images) && isset($tags)){
            return $this->supplementsService->editSupplement($id, $name, $description, $outOfStock, $images, $tags);
        } else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }


    /**
     * Delete supplement
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function delete(Request $request):ResponseBootstrap {
        // get id from url
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if id is set
        if (isset($id)){
            // call service
            return $this->supplementsService->deleteSupplement($id);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return response
        return $response;
    }

}