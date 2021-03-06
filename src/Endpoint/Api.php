<?php


namespace OnFact\Endpoint;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use OnFact\Exception\ExceptionFactory;
use OnFact\Helper\Index;
use OnFact\Model\Model;
use OnFact\Model\ModelFactory;

abstract class Api
{

    const BASE_URI = 'https://api5.onfact.be/';
    const ENDPOINT = '/v1/resource';

    private static $apiKey;
    private static $companyUuid;

    private $response;

    public function __construct()
    {
    }

    /**
     * @param mixed $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @param mixed $companyUuid
     */
    public static function setCompanyUuid($companyUuid)
    {
        self::$companyUuid = $companyUuid;
    }

    private static function getClient() {
        return new Client([
            'base_uri' => self::BASE_URI,
            'timeout'  => 10.0,
        ]);
    }

    private function getHeaders(array $actions = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        if (!empty($actions)) {
            $headers['X-ACTIONS'] = join(',', $actions);
        }

        if (self::$apiKey) {
            $headers['X-SESSION-KEY'] = self::$apiKey;
        }

        if (self::$companyUuid) {
            $headers['X-COMPANY-UUID'] = self::$companyUuid;
        }

        return $headers;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    protected function _get($endpoint, $queryParams = [], $headers = []) {
        try {
            $client = self::getClient();
            $endpoint .= '.json';

            $request = $client->request('GET', $endpoint, [
                'headers' => array_merge(self::getHeaders(), $headers),
                'query' => $queryParams,
            ]);

            return json_decode($request->getBody()->__toString(), true);
        } catch (ClientException $e) {
            throw ExceptionFactory::create($e);
        }
    }

    protected function _post(string $endpoint, Model $model, array $actions = []) {
        $client = self::getClient();
        $endpoint .= '.json';

        $request = $client->request('POST', $endpoint, [
            'headers' => self::getHeaders($actions),
            'body' => json_encode($model),
        ]);

        return json_decode($request->getBody()->__toString());
    }

    protected function _put(string $endpoint, Model $model) {
        $client = self::getClient();
        $endpoint .= '.json';

        $request = $client->request('PUT', $endpoint, [
            'headers' => self::getHeaders(),
            'body' => json_encode($model),
        ]);

        return json_decode($request->getBody()->__toString());
    }

    protected function _delete(string $endpoint) {
        $client = self::getClient();
        $endpoint .= '.json';

        $request = $client->request('DELETE', $endpoint, [
            'headers' => self::getHeaders(),
        ]);

        return json_decode($request->getBody()->__toString());
    }

    public function index($queryParams = [], $headers = []) {
        $response = $this->_get(static::ENDPOINT, $queryParams, $headers);
        $this->response = $response;

        return new Index(substr(strrchr(get_class($this), "\\"), 1), $response);
    }

    public function create(Model $model, $actions = []) {
        $response = $this->_post(static::ENDPOINT, $model, $actions);
        $this->response = $response;
        $model->setId($response->id);

        return $response->id;
    }

    public function read(int $id, $queryParams = [], $headers = []) {
        $model = substr(strrchr(get_class($this), "\\"), 1);
        $response = $this->_get(static::ENDPOINT . '/' . $id, $queryParams, $headers);
        $this->response = $response;

        return ModelFactory::create($model, $response);
    }

    public function update(Model $model) {
        $response = $this->_put(static::ENDPOINT . '/' . $model->getId(), $model);
        $this->response = $response;

        return $response->id;
    }

    public function delete(int $id) {
        $response = $this->_delete(static::ENDPOINT . '/' . $id);
        $this->response = $response;

        return $response->id;
    }


}
