<?php

namespace Model\Mapper;

use Model\Entity\Supplement;
use Model\Entity\SupplementsCollection;
use Model\Entity\Shared;
use PDO;
use PDOException;
use Component\DataMapper;


class SupplementsMapper extends DataMapper
{

    /**
     * Return configuration to service
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Get supplement
     *
     * @param Supplement $supplement
     * @return Supplement
     */
    public function getSupplement(Supplement $supplement):SupplementsCollection
    {
        // create response object
        $supplementCollection = new SupplementsCollection();

        try{
            // set database instructions
            $sql = "SELECT 
                        s.id,
                        s.name,
                        s.description,
                        s.out_of_stock,
                        s.date,
                        GROUP_CONCAT(DISTINCT si.image_name) AS images,
                        GROUP_CONCAT(DISTINCT st.tag_id) AS tags
                    FROM supplements AS s 
                    LEFT JOIN supplement_images AS si ON s.id = si.supplement_parent
                    LEFT JOIN supplement_tags AS st ON s.id = st.supplement_parent
                    WHERE s.id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $supplement->getId()
            ]);

            // fetch data
            $data = $statement->fetch(PDO::FETCH_ASSOC);

            // check if data is empty
            if (isset($data['id'])){
                // create supplement entity
                $supplementContainer = new Supplement();

                // set its values
                $supplementContainer->setId($data['id']);
                $supplementContainer->setName($data['name']);
                $supplementContainer->setDescription($data['description']);
                $supplementContainer->setOutOfStock($data['out_of_stock']);
                $supplementContainer->setDate($data['date']);
                $supplementContainer->setTags($data['tags']);

                // add prefixes to image names to create link
                $images = explode(',', $data['images']);
                for($i = 0; $i < count($images); $i++){
                    $images[$i] = $this->configuration['asset_link'] . $images[$i];
                }
                $supplementContainer->setImages($images);

                // add container to collection
                $supplementCollection->addEntity($supplementContainer);
            }

        } catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get supplement mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get supplement mapper: ' . $e);
            }
        }

        // return $response;
        return $supplementCollection;
    }


    /**
     * Get supplement by ids
     *
     * @param Supplement $supplement
     * @return SupplementsCollection
     */
    public function getSupplementsByIds(Supplement $supplement):SupplementsCollection
    {
        // create response object
        $supplementCollection = new SupplementsCollection();

        // extract ids
        $ids = $supplement->getIds();

        try{
            // set database instructions
            $sql = "SELECT 
                        s.id,
                        s.name,
                        s.description,
                        s.out_of_stock,
                        s.date,
                        GROUP_CONCAT(DISTINCT si.image_name) AS images,
                        GROUP_CONCAT(DISTINCT st.tag_id) AS tags
                    FROM supplements AS s 
                    LEFT JOIN supplement_images AS si ON s.id = si.supplement_parent
                    LEFT JOIN supplement_tags AS st ON s.id = st.supplement_parent
                    WHERE s.id IN (" . $ids . ")
                    GROUP BY s.id";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // loop through data and add collections entities
            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                // create supplement entity
                $supplementContainer = new Supplement();

                // set its values
                $supplementContainer->setId($row['id']);
                $supplementContainer->setName($row['name']);
                $supplementContainer->setDescription($row['description']);
                $supplementContainer->setOutOfStock($row['out_of_stock']);
                $supplementContainer->setDate($row['date']);
                $supplementContainer->setTags($row['tags']);

                // add prefixes to image names to create link
                $images = explode(',', $row['images']);
                for($i = 0; $i < count($images); $i++){
                    $images[$i] = $this->configuration['asset_link'] . $images[$i];
                }
                $supplementContainer->setImages($images);

                // add container to collection
                $supplementCollection->addEntity($supplementContainer);
            }

        } catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get supplements by ids mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get supplements by ids mapper: ' . $e);
            }
        }

        // return $response;
        return $supplementCollection;
    }

    /**
     * Get all supplement
     *
     * @return SupplementsCollection
     */
    public function getSupplements(Supplement $supplement):SupplementsCollection
    {
        // create response object
        $supplementCollection = new SupplementsCollection();

        try{
            // set database instructions
            $sql = "SELECT 
                        s.id,
                        s.name,
                        s.description,
                        s.out_of_stock,
                        s.date,
                        GROUP_CONCAT(DISTINCT si.image_name) AS images,
                        GROUP_CONCAT(DISTINCT st.tag_id) AS tags
                    FROM supplements AS s 
                    LEFT JOIN supplement_images AS si ON s.id = si.supplement_parent
                    LEFT JOIN supplement_tags AS st ON s.id = st.supplement_parent
                    GROUP BY s.id
                    LIMIT :from,:limit";
            $from = $supplement->getFrom();
            $limit = $supplement->getLimit();
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':from', $from, PDO::PARAM_INT);
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->execute();

            // loop through data and add collections entities
            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                // create supplement entity
                $supplementContainer = new Supplement();

                // set its values
                $supplementContainer->setId($row['id']);
                $supplementContainer->setName($row['name']);
                $supplementContainer->setDescription($row['description']);
                $supplementContainer->setOutOfStock($row['out_of_stock']);
                $supplementContainer->setDate($row['date']);
                $supplementContainer->setTags($row['tags']);

                // add prefixes to image names to create link
                $images = explode(',', $row['images']);
                for($i = 0; $i < count($images); $i++){
                    $images[$i] = $this->configuration['asset_link'] . $images[$i];
                }
                $supplementContainer->setImages($images);

                // add container to collection
                $supplementCollection->addEntity($supplementContainer);
            }

        } catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get supplements mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get supplements mapper: ' . $e);
            }
        }

        // return $response;
        return $supplementCollection;
    }


    /**
     * Get all supplement
     *
     * @return SupplementsCollection
     */
    public function getSearchedSupplements(Supplement $supplement):SupplementsCollection
    {
        // create response object
        $supplementCollection = new SupplementsCollection();

        try{
            // set database instructions
            $sql = "SELECT 
                        s.id,
                        s.name,
                        s.description,
                        s.out_of_stock,
                        s.date,
                        GROUP_CONCAT(DISTINCT si.image_name) AS images,
                        GROUP_CONCAT(DISTINCT st.tag_id) AS tags
                    FROM supplements AS s 
                    LEFT JOIN supplement_images AS si ON s.id = si.supplement_parent
                    LEFT JOIN supplement_tags AS st ON s.id = st.supplement_parent
                    WHERE s.name LIKE ?
                    GROUP BY s.id
                    LIMIT 10";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                '%' . $supplement->getName() . '%'
            ]);

            // loop through data and add collections entities
            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                // create supplement entity
                $supplementContainer = new Supplement();

                // set its values
                $supplementContainer->setId($row['id']);
                $supplementContainer->setName($row['name']);
                $supplementContainer->setDescription($row['description']);
                $supplementContainer->setOutOfStock($row['out_of_stock']);
                $supplementContainer->setDate($row['date']);
                $supplementContainer->setTags($row['tags']);

                // add prefixes to image names to create link
                $images = explode(',', $row['images']);
                for($i = 0; $i < count($images); $i++){
                    $images[$i] = $this->configuration['asset_link'] . $images[$i];
                }
                $supplementContainer->setImages($images);

                // add container to collection
                $supplementCollection->addEntity($supplementContainer);
            }

        } catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get searched supplements mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get searched supplements mapper: ' . $e);
            }
        }

        // return $response;
        return $supplementCollection;
    }


    /**
     * Insert supplement
     *
     * @param Supplement $supplement
     * @return Shared
     */
    public function addSupplement(Supplement $supplement, Shared $shared):Shared
    {
        try {

            // beginn transaction
            $this->connection->beginTransaction();

            // set database instructions for inserting product info
            $sql = "INSERT INTO 
                      supplements 
                      (name, description)
                      VALUES (?, ?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $supplement->getName(),
                $supplement->getDescription()
            ]);

            // check if anything is inserted in database, procede with rest of actions and set shared state
            if($statement->rowCount() > 0){
                // set product parent id
                $supplementParent = $this->connection->lastInsertId();

                // insert supplement images
                $images = $supplement->getImages();
                $sqlImages = "INSERT INTO 
                                  supplement_images
                                  (image_name, supplement_parent)
                                  VALUES (?, ?)";
                $statementImages = $this->connection->prepare($sqlImages);
                foreach ($images as $image){
                    $statementImages->execute([
                        $image,
                        $supplementParent
                    ]);
                }

                // insert supplement tags
                $tags = $supplement->getTags();
                $sqlTags = "INSERT INTO 
                              supplement_tags
                              (tag_id, supplement_parent)
                              VALUES (?, ?)";
                $statementTags = $this->connection->prepare($sqlTags);
                foreach ($tags as $tag){
                    $statementTags->execute([
                        $tag,
                        $supplementParent
                    ]);
                }

                // if everything pass good set appropriate state
                $shared->setState(200);

            }else {
                $shared->setState(304);
            }

            // commit transaction
            $this->connection->commit();

        }catch (PDOException $e){
            // rollback everything in case of failure
            $this->connection->rollBack();

            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry depending on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Add supplement mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Add supplement mapper: ' . $e);
            }
        }

        // return response
        return $shared;
    }


    /**
     * Edit supplement
     *
     * @param Supplement $supplement
     * @param Shared $shared
     * @return Shared
     */
    public function editSupplement(Supplement $supplement, Shared $shared):Shared {

        try{
            // begin transaction
            $this->connection->beginTransaction();

            // set database instructions for updating core supplement info
            $sql = "UPDATE supplements SET 
                      name = ?,
                      description = ?,
                      out_of_stock = ?
                    WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $supplement->getName(),
                $supplement->getDescription(),
                $supplement->getOutOfStock(),
                $supplement->getId()
            ]);

            // delete all data for this supplement in child tables
            $sqlDelete = "DELETE 
                              si.*,
                              st.*
                          FROM supplement_images AS si
                          LEFT JOIN supplement_tags AS st ON si.supplement_parent = st.supplement_parent
                          WHERE si.supplement_parent = ?";
            $statementDelete = $this->connection->prepare($sqlDelete);
            $statementDelete->execute([
                $supplement->getId()
            ]);

            // if some data is deleted procede with rest of the actions
            if($statementDelete->rowCount() > 0){
                // UPDATE IMAGES
                $sqlInsertImages = "INSERT INTO supplement_images (image_name, supplement_parent) VALUES (?, ?)";
                $statementInsertImages = $this->connection->prepare($sqlInsertImages);
                $images = $supplement->getImages();
                foreach ($images as $image){
                    $statementInsertImages->execute([
                        $image,
                        $supplement->getId()
                    ]);
                }

                // UPDATE TAGS
                $sqlInsertTags = "INSERT INTO supplement_tags (tag_id, supplement_parent) VALUES (?, ?)";
                $statementInsertTags = $this->connection->prepare($sqlInsertTags);
                $tags = $supplement->getTags();
                foreach ($tags as $tag){
                    $statementInsertTags->execute([
                        $tag,
                        $supplement->getId()
                    ]);
                }
            }

            // commit transaction
            $this->connection->commit();

            // after all actions passed set appropriate state
            if($statement->rowCount() > 0 or $statementDelete->rowCount() > 0){
                $shared->setState(200);
            }else {
                $shared->setState(304);
            }

        } catch (PDOException $e){
            // rollback everything in case of failure
            $this->connection->rollBack();

            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Edit supplement mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Edit supplement mapper: ' . $e);
            }
        }

        // return response
        return $shared;
    }


    /**
     * Delete supplement
     *
     * @param Supplement $supplement
     * @return Shared
     */
    public function deleteSupplement(Supplement $supplement, Shared $shared):Shared {

       try {
           // begin transaction
           $this->connection->beginTransaction();

           // set database instructions
           $sql = "DELETE 
                    s.*,
                    si.*,
                    st.*,
                    ps.*
                   FROM supplements AS s
                   LEFT JOIN supplement_images AS si ON s.id = si.supplement_parent
                   LEFT JOIN supplement_tags AS st ON s.id = st.supplement_parent
                   LEFT JOIN product_supplements AS ps ON ps.supplement_id = s.id
                   WHERE s.id = ?";
           $statement = $this->connection->prepare($sql);
           $statement->execute([
               $supplement->getId()
           ]);

           // commit transaction
           $this->connection->commit();

           // check if anything changed after executing statement and set appropriate state
           if($statement->rowCount() > 0){
               $shared->setState(200);
           }else {
               $shared->setState(304);
           }

       }catch (PDOException $e){
           // rollback everything in case of failure
           $this->connection->rollBack();
die($e->getMessage());
           // set state
           $shared->setState(304);

           // get error code
           $code = $e->errorInfo[1];

           // set appropriate monolog entry dependeng on error code value
           if((int)$code >= 1000 && (int)$code <= 1749){
               $this->monolog->addError('Delete supplement mapper: ' . $e);
           }else {
               $this->monolog->addWarning('Delete supplement mapper: ' . $e);
           }
       }

       // return response
       return $shared;
    }

}

