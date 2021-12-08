<?php

namespace App\Connectors;

use Exception;
use GuzzleHttp\Client;

class CognitoJwksConnector
{

    public static function getCongnitoJwks()
    {
        $poolId = $_ENV['AWS_COGNITO_USER_POOL_ID'];
        $cacheKey = "COGNITO_USER_POOL_JWKS_".$poolId;
        return cache()->rememberForever($cacheKey, function() use($poolId) {
            $region = $_ENV['AWS_COGNITO_REGION'];
            $client = new Client();
            $jwksURL = 'https://cognito-idp.'.$region.'.amazonaws.com/'.$poolId.'/.well-known/jwks.json';
            $res = $client->get($jwksURL);
            if ($res->getStatusCode() != 200) {
                throw new Exception("Failed while loading cognito public key");
            }
            return json_decode($res->getBody()->getContents(), true);
        });
    }
}
