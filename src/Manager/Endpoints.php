<?php

namespace OramaCloud\Manager;

class Endpoints
{
    const WEBHOOKS_BASE_URL = 'https://api.askorama.ai/api/v1/webhooks';

    const DEPLOY = self::WEBHOOKS_BASE_URL . '/[indexID]/deploy';

    const EMPTY = self::WEBHOOKS_BASE_URL . '/[indexID]/empty';

    const HAS_DATA = self::WEBHOOKS_BASE_URL . '/[indexID]/has-data';

    const NOTIFY = self::WEBHOOKS_BASE_URL . '/[indexID]/notify';

    const SNAPSHOT = self::WEBHOOKS_BASE_URL . '/[indexID]/snapshot';
}
