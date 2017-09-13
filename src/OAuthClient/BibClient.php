<?php

namespace NYPL\HoldRequestResultConsumer\OAuthClient;

use NYPL\HoldRequestResultConsumer\Model\DataModel\Bib;
use NYPL\Starter\APILogger;
use NYPL\Starter\Config;

class BibClient extends APIClient
{
    /**
     * @param array $bibIds
     * @param $nyplSource
     * @return array|null
     */
    public static function getBibsByIds(array $bibIds)
    {
        if (count($bibIds) < 1) {
            APILogger::addError(
                'Failed',
                array('No bibIds provided to ' . __FUNCTION__)
            );
            return null;
        } elseif (count($bibIds) > 1) {
            $bibIdList = implode(",", $bibIds);
        } else {
            $bibIdList = $bibIds[0];
        }

        $bibs = array();

        $url = Config::get('API_BIB_URL') . '?id=' . $bibIdList;

        APILogger::addDebug('Retrieving bib by Id and Source', (array)$url);

        $response = ClientHelper::getResponse($url, __FUNCTION__);

        $statusCode = $response->getStatusCode();

        $response = json_decode((string)$response->getBody(), true);

        APILogger::addDebug(
            'Retrieved bibs by ids',
            $response['data']
        );

        // Check statusCode range
        if ($statusCode === 200) {
            foreach ($response['data'] as $bib) {
                array_push($bibs, $bib);
            }
            return $bibs;
        } else {
            APILogger::addError(
                'Failed',
                array('Failed to retrieve bib ', $bibIds, $response['type'], $response['message'])
            );
            return null;
        }
    }
}
