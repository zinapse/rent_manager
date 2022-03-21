<?php

namespace zinapse\RentManager\Models;

use GuzzleHttp\Exception\ClientException;
use zinapse\RentManager\Models\Authorize;

class Query {

    /**
     * Data for the API query.
     *
     * @var array
     */
    protected array $data;

    protected Authorize $auth;

    /**
     * Create a new object for API querying.
     *
     * @param array $data
     */
    public function __construct(array $data = []) {
        
        // Create an auth object and optionally set the data
        $this->auth = new Authorize();

        // Populate from $data if passed
        if(!empty($data)) $this->populateFromData($data);
    }

    /**
     * Populate this object's variables from the $data array.
     *
     * @param array $data Array that should contain one or all of: [string id, string entity, array embeds, array fields]
     * @return void
     */
    public function populateFromData(array $data) : void {
        // Set the variables
        $id = $data['id'] ?? null;
        $entity = $data['entity'] ?? null;
        $embeds = $data['embeds'] ?? null;
        $fields = $data['fields'] ?? null;

        // If they're not empty then set them
        if(!empty($id)) $this->setID($id);
        if(!empty($entity)) $this->setEntity($entity);
        if(!empty($embeds)) $this->setEmbeds($embeds);
        if(!empty($fields)) $this->setFields($fields);
    }

    /**
     * Update the local array to set the ID to get from the API.
     *
     * @param string $id
     * @return void
     */
    public function setID(string $id) : void {
        $this->data['id'] = $id ?? '';
    }

    /**
     * Update the local array to set the entity type from the API.
     *
     * @param string $entity
     * @return void
     */
    public function setEntity(string $entity) : void {
        $this->data['entity'] = $entity ?? '';
    }

    /**
     * Update the local array to set any data to embed from the API.
     *
     * @param array $embeds
     * @return void
     */
    public function setEmbeds(array $embeds) : void {
        $this->data['embeds'] = $embeds ?? [];
    }

    /**
     * Update the local array to set any include fields from the API.
     *
     * @param array $fields
     * @return void
     */
    public function setFields(array $fields) : void {
        $this->data['fields'] = $fields ?? [];
    }

    /**
     * Run the query.
     *
     * @return boolean|array
     */
    protected function run() : bool|array {
        if(!empty($this->auth)) {
            // Define variables
            $entity = $this->data['entity']     ?? null;
            $id = $this->data['id']             ?? null;
            $embeds = $this->data['embeds']     ?? null;
            $fields = $this->data['fields']     ?? null;

            // Make sure we have the required variables
            if(empty($entity)) return false;

            // Craft the URI
            $uri = $entity;
            
            // If we're given an ID
            if(!empty($id)) {
                $uri .= '/' . $id;
            } else {
                if(is_array($embeds ?? null)) {
                    // Append the URI
                    $uri .= 'embeds=';

                    // Append the embeds
                    foreach($embeds as $em) $uri .= $em . ',';

                    // Remove any trailing commas
                    $uri = rtrim($uri, ',');
                }

                // Remove any "&" characters just in case
                $uri = rtrim($uri, '&');

                if(is_array($fields ?? null)) {
                    // Append the URI
                    $uri .= '&fields=';

                    // Append the fields
                    foreach($fields as $field) $uri .= $field . ',';

                    // Removing any trailing commas
                    $uri = rtrim($uri, ',');
                }
            }

            // Make sure we have a valid token
            $this->auth->isValid(true);

            // Send the request
            try {
                $response = $this->auth->client->get($uri, [
                    'headers' => $this->auth->headers
                ]);
            } catch(ClientException $e) {
                $code = $e->getResponse()->getStatusCode();
                if($code != 200) {
                    return false;
                }
            }

            // Decode the response output
            $ret = json_decode($response->getBody(), true);
            
            // Return the array or true
            return is_array($ret) ? $ret : true;
        }
    }
}