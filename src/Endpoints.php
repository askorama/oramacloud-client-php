<?php
namespace OramaCloud;

class Endpoints
{
    const WEBHOOKS_BASE_URL = 'https://api.askorama.ai/api/v1/webhooks';

    const DEPLOY = WEBHOOKS_BASE_URL . '/[indexID]/deploy';

    const EMPTY = WEBHOOKS_BASE_URL . '/[indexID]/empty';

    const HAS_DATA = WEBHOOKS_BASE_URL . '/[indexID]/has-data';

    const NOTIFY = WEBHOOKS_BASE_URL . '/[indexID]/notify';

    const SNAPSHOT = WEBHOOKS_BASE_URL . '/[indexID]/snapshot';    
}