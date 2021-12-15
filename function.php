<?php
require_once('constant.php');

function callApi($startdate,$enddate){
    
    $api = NEOAPI;
    $curl = curl_init();
    $base = "https://api.nasa.gov/neo/rest/v1/feed?start_date=".$startdate."&end_date=".$enddate."&api_key=".$api;

    curl_setopt($curl, CURLOPT_URL, $base);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $result = curl_exec($curl);
    if(!$result){
        die("Connection Failure");
    }
    curl_close($curl);
    $response = json_decode($result, true);
    return $response;

}

function changedateformat($date){

    //$original_date = "12/17/2021";
    $timestamp = strtotime($date);
    $new_date = date("Y-m-d", $timestamp);
    return $new_date;
}

function calculatenumberofdays($edate,$sdate){
        $now =  strtotime($edate);
        $your_date = strtotime($sdate);
        $datediff =  $now-$your_date;
        $numday =round($datediff / (60 * 60 * 24));
        return $numday;

}


//$response = callApi('2015-07-07','2015-07-10');
//echo $response['element_count'];

?>