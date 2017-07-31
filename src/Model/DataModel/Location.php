<?php

namespace NYPL\HoldRequestResultConsumer\Model\DataModel;

use NYPL\Starter\APIException;
use NYPL\Starter\Model\Response\ErrorResponse;

class Location
{
    /**
     * @param $locationCode
     * @return string
     * @throws APIException
     */
    public static function getLocationName($locationCode)
    {
        // TODO: Make filename a configuration setting
        $data = file_get_contents(dirname(__DIR__) . '/../../src/locations.json');

        $json = json_decode($data, true);

        foreach ($json['@graph'] as $field => $value) {
            if ($value['skos:notation'] === $locationCode) {
                return (string) $value['skos:prefLabel'];
            }
        }

        return '';
    }
}