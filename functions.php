<?php
include_once 'simple_html_dom.php';
include_once 'beautify.php';
ini_set("memory_limit","100M");

    

 function getSeries(){
    $Beutifier = new Debug();
    $mainUrl = 'http://dl.tehmovies.pro/94/series';
    $html = file_get_html($mainUrl);
    
    $allSeriesLinks = array();
    $allSeriesNames = array();
    $allSeriesNamesFixed = array();
    
    // Find all links
    foreach($html->find('a') as $element){
        $suffix = $element->href;
        if(strcasecmp($suffix,"../")!== 0){
        array_push($allSeriesLinks, $mainUrl.$suffix);
        array_push($allSeriesNames,str_ireplace('/','',$suffix));
        }
    }
    $html->clear();

    foreach($allSeriesNames as $particularSeries){
        $particularSeriesFixed = urldecode($particularSeries);
        $particularSeriesFixed = str_ireplace("."," ",$particularSeriesFixed);
        array_push($allSeriesNamesFixed,$particularSeriesFixed);
    }
    
    // $Beutifier->array_to_html($allSeriesNamesFixed);
    // return $allSeriesNamesFixed;
    //  die;

    $fp = fopen('tehmovies_series.csv', 'w');

    fputcsv($fp, $allSeriesNamesFixed);

    fclose($fp);
    
}

?>