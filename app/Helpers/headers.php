<?php

use App\Models\AppClient;

/**
* makes array of request headers
* @return true if value of $rh[‘Origin’] is in array of urls otherwise returns false
*/
function requestOriginIsValid()
{
    if (request()->get('access_code', null) == 'JSGDHhjfgkn2j3b4jdbgd4g') { //hack for outer order form
        return true;
    }
    $rh = getallheaders(); // request headers
    $rh = is_array($rh) ? $rh : [];
    if (!array_key_exists('Origin', $rh)) {
        return true;
    } // this should mean it's not ajax request
//    dd('da');
    $urls = AppClient::getAjaxURLsArray();
    $allows_to_proceed = false;

    foreach ($urls as $url) {
        if ($rh['Origin'] === $url) {
            $allows_to_proceed = true;
        }
    }
    return $allows_to_proceed;
}

/**
* if array of headers has key ‘Origin’ this should mean it's ajax request and modify response object
*
* @param {response}
*
* @return {response}
*/
function setResponseHeaders($response = null)
{
    $rh = getallheaders(); // request headers
    $rh = is_array($rh) ? $rh : [];
    return $response
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS, DELETE');
        // ->header('Access-Control-Allow-Headers', 'Content-Type, x-xsrf-token, Authorization'); // uncommented due front errors
        // ->header('Access-Control-Allow-Headers', ['*']);
}
