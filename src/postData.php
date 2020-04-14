<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once './estimator.php';

  
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(empty($data->periodType)  )
{
    http_response_code(400);

    echo json_encode(array("Error" => "periodType is required."));
}
else if(empty($data->timeToElapse)  )
{
    http_response_code(400);
    echo json_encode(array("Error" => "timeToElapse is required."));
}
else if(empty($data->reportedCases)  )
{
    http_response_code(400);
    echo json_encode(array("Error" => "reportedCases is required."));
}
else if(empty($data->totalHospitalBeds)  )
{
    http_response_code(400);
    echo json_encode(array("Error" => "totalHospitalBeds is required."));
}
else if(empty($data->population)  )
{
    http_response_code(400);
    echo json_encode(array("Error" => "population is required."));
}
else
{  
    $periodType = $data->periodType;
    $timeToElapse = $data->timeToElapse;
    $reportedCases = $data->reportedCases;
    $totalHospitalBeds = $data->totalHospitalBeds;
    $population = $data->population;

        $postData = array(
            'region'=>array(
                'name'=>'Africa',
                'avgAge'=>19.7,
                'avgDailyIncomeInUSD'=>4,
                'avgDailyIncomePopulation'=>0.73,
            ),
            'periodType'=>$periodType,
            'timeToElapse'=>$timeToElapse,
            'reportedCases'=>$reportedCases,
            'population'=>$population,
            'totalHospitalBeds'=>$totalHospitalBeds
    );
    
    covid19ImpactEstimator($postData);
}

//  if (isset($_GET['format'])) {
//     $format = $_GET['format'];
//     if (!preg_match('/json|xml/',$format)) {
//         print "Please choose a format: json or xml";
//         exit;
//     }
// } else {
//     print "Please choose a format: json or xml";
//     exit;
// }
// $friendlyDate = date("M d, Y");
// $unixTime = time();
// $month = date("M");
// $dayOfWeek = date("l");
// $year = date("Y");
// $tests = array('impact' => $impact, 'severeImpact'=>$severeImpact);
// $returnData = array(
//         "friendlyDate" => $friendlyDate,
//         "unixTime" => $unixTime,
//         "monthNum" => $month,
//         "dayOfWeek" => $dayOfWeek,
//         "yearNum" => $year
// );
//     $xml = new DOMDocument();
//     $dateInfoElement = $xml->createElement("covid-19");
//     foreach ($severeImpact as $key => $value) {
//         $xmlNode = $xml->createElement($key,$value);
//         $dateInfoElement->appendChild($xmlNode);
//     }
//     $xml->appendChild($dateInfoElement);
//     $output = $xml->saveXML();
//     $header = "Content-Type:text/xml";

// header($header);
// echo $output;
  
?>