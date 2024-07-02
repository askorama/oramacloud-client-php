<?php
namespace OramaCloud;

class Endpoints
{
    const SEARCH = 'https://cloud.orama.run/v1/indexes/[endpoint]/search';

    const API_BASE_URL = 'https://api.askorama.ai';

    const API_V1_BASE_URL = API_BASE_URL.'/api/v1/';
    
    const SNAPSHOT = API_V1_BASE_URL.'snapshot';
    
    const NOTIFY = API_V1_BASE_URL.'notify';

    const DEPLOY = API_V1_BASE_URL.'deploy';

    const HAS_DATA = API_V1_BASE_URL.'has-data';
}