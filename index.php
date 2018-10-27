<?php
ini_set('max_execution_time', 2500);
include_once('functions.php');
$time_now = date('H-i');

if(file_exists('tehmovies_series.csv')){
    // global $time_now;
    $file = fopen("tehmovies_series.csv","r");
    $allSeriesNames = fgetcsv($file);
    $stoppingZone = 10;
    
    for($i=0;$i<count($allSeriesNames);$i++){
        getSeriesDetails($allSeriesNames[$i]);
        if($i == $stoppingZone){
            $stoppingZone = $stoppingZone+10;
            sleep(5);
            // break;
        }
    }
    $json_file_contents = file_get_contents('tehmovies_series_tmdb_info_'.date("Y-m-d").'_'.$time_now.'.json');
    $json_file_contents[strripos($json_file_contents,',')] = ' ';
    file_put_contents('tehmovies_series_tmdb_info_'.date("Y-m-d").'_'.$time_now.'.json','tehmovies_series:{'.$json_file_contents.'}');
}else{
    getSeries();
    // global $time_now;
    $file = fopen("tehmovies_series.csv","r");
    $allSeriesNames = fgetcsv($file);
    $stoppingZone = 10;
    
    for($i=0;$i<count($allSeriesNames);$i++){
        getSeriesDetails($allSeriesNames[$i]);
        if($i == $stoppingZone){
            $stoppingZone = $stoppingZone+10;
            sleep(5);
            // break;
        }
    }
    $json_file_contents = file_get_contents('tehmovies_series_tmdb_info_'.date("Y-m-d").'_'.$time_now.'.json');
    $json_file_contents[strripos($json_file_contents,',')] = ' ';
    file_put_contents('tehmovies_series_tmdb_info_'.date("Y-m-d").'_'.$time_now.'.json','tehmovies_series:{'.$json_file_contents.'}');
}



function getSeriesDetails($series_name){
    global $time_now;
    $Beutifier = new Debug();
//The URL that we want to GET.
    $series_name = preg_replace('/(\d+)/', '${1} ', $series_name);
    $url_ready_series_name = str_ireplace(" ","%20",$series_name);
    $url = 'https://api.themoviedb.org/3/search/tv?api_key=7b69b802c5039ff25e80563b348c8f2b&language=en-US&query='.$url_ready_series_name.'&page=1';
    // echo($url);
    //Use file_get_contents to GET the URL in question.
    $contents = file_get_contents($url,0,null,null);
    
    //If $contents is not a boolean FALSE value.
    if($contents !== false){
        //Print out the contents.
        $json_output = json_decode($contents,true); 
        // print_r($json_output);
        // header('Content-Type: application/json');
        // echo($json_output['results'][0]);
        if($json_output['results'] != null){
            if($json_output['results'][0] != null){
                $series_details_array[$series_name] = array();

                $series_original_name = $json_output['results'][0]['original_name'];
                $series_details_array[$series_name]['Original_Name'] = $series_original_name;
                $series_poster_path = 'http://image.tmdb.org/t/p/w154'.$json_output['results'][0]['poster_path'];
                $series_details_array[$series_name]['Poster_Path'] = $series_poster_path;
                $series_first_air_date = $json_output['results'][0]['first_air_date'];
                $series_details_array[$series_name]['First_Air_Date'] = $series_first_air_date;
                $series_popularity = $json_output['results'][0]['popularity'];
                $series_details_array[$series_name]['Popularity'] = $series_popularity;
                $series_overview = $json_output['results'][0]['overview'];
                $series_details_array[$series_name]['Overview'] = $series_overview;

                
                $Beutifier->array_to_html($series_details_array);
                $jsondata = json_encode($series_details_array,JSON_PRETTY_PRINT);
                file_put_contents('tehmovies_series_tmdb_info_'.date("Y-m-d").'_'.$time_now.'.json',$jsondata.',',FILE_APPEND);
            }
        }
    }
}

?>
