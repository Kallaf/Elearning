<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Middleware;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Exception;
use App\Connectors\CognitoJwksConnector;


/**
 * Description of CognitoToken
 *
 * @author Kallaf
 */
class CognitoToken
{
        /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $forbidden = 403;
        if (! $token = $token = $request->bearerToken()) {
            return response('Token not provided', $forbidden);
        }
        try {
            $jwks = CognitoJwksConnector::getCongnitoJwks();
        } catch (Exception $e) {
            throw $e;
        }
        $supportedAlgorithm = $this->getSupportedAlgorithm($jwks);
        $invalidTokenMsg = 'Invalid token';
        try {
             $payload = JWT::decode($token, JWK::parseKeySet($jwks), $supportedAlgorithm);
        } catch (ExpiredException $e) {
            return response("Expired token", $forbidden);
        } catch (Exception $e) {
            return response($invalidTokenMsg, $forbidden);
        }
        if ($payload->sub) {
            $request->attributes->add(['userId' => $payload->sub]);
        } else {
            return response($invalidTokenMsg, $forbidden);
        }
        return $next($request);
    }

    private function getSupportedAlgorithm($jwks) {
        $map = function ($k) { return $k["alg"]; };
        return array_map($map, $jwks["keys"]);
    }
}