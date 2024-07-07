<?php

namespace OramaCloud\Manager;

use OramaCloud\Manager\Endpoints;

class IndexManager
{
    private $manager;
    private $indexId = null;

    public function __construct($indexID, CloudManager $manager)
    {
        $this->manager = $manager;
        $this->indexId = $indexID;

        $this->manager->setIndexId($indexID);
    }

    public function empty()
    {
        return $this->callIndexWebhook(Endpoints::SNAPSHOT, []);
    }

    public function snapshot($data)
    {
        return $this->callIndexWebhook(Endpoints::SNAPSHOT, $data);
    }

    public function insert($data)
    {
        return $this->callIndexWebhook(Endpoints::NOTIFY, ['upsert' => $data]);
    }

    public function update($data)
    {
        return $this->callIndexWebhook(Endpoints::NOTIFY, ['upsert' => $data]);
    }

    public function delete($data)
    {
        return $this->callIndexWebhook(Endpoints::NOTIFY, ['remove' => $data]);
    }

    public function deploy()
    {
        return $this->callIndexWebhook(Endpoints::DEPLOY);
    }

    public function hasPendingOperations()
    {
        $response = $this->callIndexWebhook(Endpoints::HAS_DATA);
        return $response->hasData;
    }

    private function checkIndexID()
    {
        if (!$this->indexId) {
            throw new \Exception('Index ID is not set');
        }
    }

    private function callIndexWebhook($endpoint, $payload = [])
    {
        $this->checkIndexID();

        return $this->manager->callIndexWebhook($endpoint, $payload);
    }
}
