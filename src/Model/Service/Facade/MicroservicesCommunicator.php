<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 9/4/18
 * Time: 11:27 AM
 */

namespace Model\Service\Facade;


class MicroservicesCommunicator
{

    private $data;
    private $configuration;
    private $client;

    public function __construct(array $data, array $configuration)
    {
       $this->data = $data;
       $this->configuration = $configuration;
       $this->client = new \GuzzleHttp\Client();
    }


    /**
     * Integrate MSs data
     */
    public function integrateMicroservicesData(){
        // loop through data and call neccessary MSs for each array item
        for($i = 0; $i < count($this->data); $i++){
            // get ids to send
            $tagIds = $this->data[$i]['tags'];

            // set new data to tags index
            $this->data[$i]['tags'] = json_decode($this->callTagsMS($tagIds));
        }

        // return full data
        return $this->data;
    }


    /**
     * Call tags MS
     *
     * @param string $ids
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function callTagsMS(string $ids){
        // call Supplements MS for supplements data
      //  $client = new \GuzzleHttp\Client();
        $result = $this->client->request('GET', $this->configuration['tags_url'] . '/tags/ids?ids=' . $ids, []);
        $tags = $result->getBody()->getContents();

        // return data
        return $tags;
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function callProductsMsToDeleteCache(){
        // send request for deleting
        $this->client->request('DELETE', $this->configuration['products_url'] . '/products/cache', []);
    }
}