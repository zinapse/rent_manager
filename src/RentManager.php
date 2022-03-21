<?php

namespace zinapse\RentManager;

use zinapse\RentManager\Models\Authorize;
use zinapse\RentManager\Models\Query;

class RentManager {

    /**
     * Simple constant.
     */
    private const NO_DATA = 'NONE';

    /**
     * Auth variable to handle API authorization.
     *
     * @var Authorize
     */
    private Authorize $auth;

    /**
     * Query variable to handle getting responses.
     *
     * @var Query
     */
    protected Query $query;

    /**
     * Array holding data for the Query object.
     *
     * @var array
     */
    protected array $data;

    public function __construct(array $data) {
        
        // Create the auth variable
        $this->auth = new Authorize();

        // Create the query variable
        $this->query = new Query($data);

    }

    /*
    * GETTERS AND SETTERS
    ***********************
    |--------------------------------------------------------------------------
    | ID
    */
    public function setID(string $id) : void {
        $this->query->setID($id);
    }
    public function getID() : string {
        return $this->query->data['id'] ?? RentManager::NO_DATA;
    }

    /*
    |--------------------------------------------------------------------------
    | Entity
    */
    public function setEntity(string $entity) : void {
        $this->query->setEntity($entity);
    }
    public function getEntity() : string {
        return $this->query->data['entity'] ?? RentManager::NO_DATA;
    }

    /*
    |--------------------------------------------------------------------------
    | Embeds
    */
    public function setEmbeds(array $data) : void {
        $this->query->setEmbeds($data);
    }
    public function getEmbeds() : array|string {
        return $this->query->data['embeds'] ?? RentManager::NO_DATA;
    }

    /*
    |--------------------------------------------------------------------------
    | Fields
    */
    public function setFields(array $data) : void {
        $this->query->setFields($data);
    }
    public function getFields() : array|string {
        return $this->query->data['fields'] ?? RentManager::NO_DATA;
    }
}