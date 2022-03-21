<?php

namespace zinapse\RentManager\Models;

use APIException;
use GuzzleHttp\Client;
use MissingRequiredException;
use GuzzleHttp\Exception\ClientException;

class Authorize {

    /**
     * The Rent Manager API subdomain.
     *
     * @var string
     */
    protected string $subdomain;

    /**
     * HTTP headers array.
     *
     * @var array
     */
    protected array $headers;

    /**
     * Guzzle Client object.
     *
     * @var Client
     */
    public Client $client;

    /**
     * The Rent Manager API username.
     *
     * @var string
     */
    protected string $username;

    /**
     * The Rent Manager API password.
     *
     * @var string
     */
    protected string $password;

    /**
     * The Rent Manager API location ID.
     *
     * @var string
     */
    protected string $location;

    /**
     * The base URI for the Rent Manager API.
     *
     * @var string
     */
    protected string $base_uri;

    /**
     * Create a new object for authorization.
     *
     * @param string $subdomain
     */
    public function __construct(string $subdomain = null) {
        if(empty($subdomain)) {
            // If we don't have a subdomain try to find one in the config
            $subdomain = config('rentmanager.subdomain');

            // Throw exception if no subdomain is found
            if(empty($subdomain)) {
                throw new MissingRequiredException('subdomain');
                return false;
            }
        }

        // Make the base URL to use for the API
        $this->base_uri = 'https://' . $subdomain . '.api.rentmanager.com/';

        // Get the username
        $rmUsername = config('rentmanager.username');
        if(empty($rmUsername)) {
            throw new MissingRequiredException('username');
            return false;
        }
        $this->username = $rmUsername;

        // Get the password
        $rmPassword = config('rentmanager.password');
        if(empty($rmPassword)) {
            throw new MissingRequiredException('password');
            return false;
        }
        $this->password = $rmPassword;

        // Get the location
        $rmLocation = config('rentmanager.location');
        if(empty($rmLocation)) {
            throw new MissingRequiredException('location');
            return false;
        }
        $this->location = $rmLocation;

        // Authorize this object
        $this->RMAuthorize();
        
    }

    /**
     * Generate an API token to be stored in this object's header array.
     * If the $data array contains any of the following keys they will
     * overwrite the current keys: 'username', 'password', 'location'
     *
     * @param array $data An optional array of data to overwrite
     * @return void
     */
    protected function RMAuthorize(array $data = []) : void {
        // Define our headers
        $this->headers = ['Content-Type' => 'application/json'];

        // Create our Guzzle Client object
        $this->client = new Client([
            'base_uri' => $this->base_uri
        ]);

        // If the $data array isn't empty we might be updating variables
        if(!empty($data)) {
            // Get data from passed keys
            $rmUsername = $data['username'] ?? null;
            $rmPassword = $data['password'] ?? null;
            $rmLocation = $data['location'] ?? null;

            // If they aren't empty then set the variables
            if(!empty($rmUsername)) $this->username = $rmUsername;
            if(!empty($rmPassword)) $this->password = $rmPassword;
            if(!empty($rmLocation)) $this->location = $rmLocation;
        }
        
        // Define the authorization JSON
        $validationEndpoint = 'Authentication/AuthorizeUser';
        $authJSON = "{
            \"Username\": \"$this->username\",
            \"Password\": \"$this->password\",
            \"LocationID\": \"$this->location\"
        }";

        // Get our auth token
        try {
            $response = $this->client->post($validationEndpoint, [
                'body'  => $authJSON,
                'headers' => $this->headers
            ]);
        } catch (ClientException $e) {
            $code = $e->getResponse()->getStatusCode();
            if ($code == 401) throw new APIException('Invalid API Authorization Credentials');
        }

        // Get our token
        $token = trim($response->getBody(), '\"');

        // Use our token in the header to authorize
        $this->headers['X-RM12Api-ApiToken'] = $token;
    }

    /**
     * Returns true if the header token is still valid.
     *
     * @param boolean $reauth If set to true this function will try to reauthorize automatically
     * @return boolean
     */
    public function isValid(bool $reauth = false) : bool {
        if(!empty($this->client)) {
            try {
                $this->client->get('Amenities/1', [
                    'headers' => $this->headers
                ]);
            } catch(ClientException $e) {
                $code = $e->getResponse()->getStatusCode();
                if($code != 200) {
                    if($reauth) {
                        $this->RMAuthorize();
                        return true;
                    }
                    return false;
                }
            }

            return true;
        }

        // If we're here we have no client object
        return false;
    }
}