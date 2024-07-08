<?php

namespace OramaCloud\Manager;

use OramaCloud\Manager\Endpoints;

class IndexManager
{
    private $manager;
    private $indexId = null;

    public function __construct($indexID, $manager)
    {
        $this->indexId = $indexID;
        $this->init($manager);
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
        return $this->callIndexWebhook(Endpoints::HAS_DATA);
    }

    private function checkIndexID()
    {
        if (!$this->indexId) {
            throw new \Exception('Index ID is not set');
        }
    }

    private function callIndexWebhook($endpoint, $payload = null)
    {
        $this->checkIndexID();

        return $this->manager->callIndexWebhook($endpoint, $payload);
    }

    private function init($manager)
    {
        if ($manager instanceof CloudManager) {
            $this->manager = $manager;
        } else if ($manager instanceof string) {
            $this->manager = new CloudManager($manager);
        } else {
            throw new \Exception('Invalid manager parameter. It should be an instance of CloudManager or an API key string.');
        }

        $this->manager->setIndexId($this->indexId);
    }
}
