<?php
namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Patron;
use NYPL\Starter\APIException;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;
use NYPL\Starter\Model\Response\ErrorResponse;

class PatronClient extends APIClient
{
    /**
     * @param string $patronId
     * @return null|Patron
     * @throws APIException
     */
    public static function getPatronById($patronId = '')
    {
        $url = Config::get('API_PATRON_URL') . '/' . $patronId;

        APILogger::addDebug('Retrieving patron by id', (array) $url);

        $response = self::get($url);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string) $response->getBody(), true);

        // Check statusCode range
        if ($statusCode === 200) {
            return new Patron($response['data']);
        } elseif ($statusCode >= 500 && $statusCode <= 599) {
            throw new APIException(
                'Server Error',
                'getPatronById met a server error',
                $statusCode,
                null,
                $statusCode,
                new ErrorResponse(
                    $statusCode,
                    'internal-server-error',
                    'getPatronById met a server error'
                )
            );
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve patron ', $patronId, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
