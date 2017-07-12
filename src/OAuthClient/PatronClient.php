<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class PatronClient extends APIClient
{
    /**
     * @param string $patronId
     *
     * @return Patron
     */
    public static function getPatronById($patronId = '')
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patronId;

        APILogger::addDebug('Retrieving patron by id', (array) $url);

        $response = self::get($url);

        $response = json_decode((string) $response->getBody(), true);

        return new Patron($response['data']);
    }
}
