<?php

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
function dump($ver)
{
    echo "<pre>";
    var_dump($ver);
    echo "</pre>";
}
class es{
    private $hosts = ['127.0.0.1:9200'];
    private $client = null;
    private $index = null;

    public function __construct()
    {
        $this->setClient(ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts(['127.0.0.1:9200'])      // Set the hosts
        ->build());              // Build the client object
    }

    public function createIndex(string $index, array $options = [])
    {
        $res = $this->client->indices()->exists([
            'index' => $index,
        ]);
        if ($res) {
            $this->index = $index;
            return ;
        }
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 2,
                    'number_of_replicas' => 0
                ]
            ]
        ];
        $params = array_merge($params, $options);
        $this->client->indices()->create($params);
        $this->index = $index;
    }

    public function createDoc(array $data, $options = []): bool
    {
        $params = [
            'index' => $this->index,
            'type' => '_doc',
            'id' => time(),
            'body' => $data
        ];
        $params = array_merge($params, $options);
        $response = $this->client->index($params);
        if ($response['result'] == 'created') {
            return true;
        }
        return false;
    }

    public function get()
    {

    }

    public function search(array $query)
    {
        $params = [
            'index' => $this->index,
            'body' => [
                'query' => [
                    'match' => $query
                ]
            ]
        ];
        return $this->client->search($params);
    }
    /**
     * @param Client $client
     * @return es
     */
    public function setClient(Client $client): es
    {
        $this->client = $client;
        return $this;
    }

}

$es = new es();
$es->createIndex('ssss_index');
//$es->createDoc([
//    'title' => ' ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
//    'name' => 'nekgod'
//]);
$es->search([
    'title' => '??????'
]);
//$es->createIndex('my_index');
//$es->ser();
