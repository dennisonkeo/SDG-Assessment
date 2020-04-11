<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");
  

 function periodTpeToDays($periodType, $timeToElapse)
{

	switch ($periodType) {
		case 'days':
			return $timeToElapse;
			break;
		case 'weeks':
			return $timeToElapse * 7;
			break;
		case 'months':
			return $timeToElapse * 30;
			break;
		
		default:
			return $timeToElapse;
			break;
	}
}


function covid19ImpactEstimator($data)
{

	$factor = intval(periodTpeToDays($data['periodType'], $data['timeToElapse']) / 3);


	$impactCurrentlyInfected = $data['reportedCases'] * 10;
	$severeCurrentlyInfected = $data['reportedCases'] * 50;

	$impactInfectionsByRequestedTime = $impactCurrentlyInfected * pow(2, $factor);
	$severeInfectionsByRequestedTime = $severeCurrentlyInfected * pow(2, $factor);

	$impactSevereCasesByRequestedTime = 0.15 * $impactInfectionsByRequestedTime;
	$severeImpactSevereCasesByRequestedTime = 0.15 * $severeInfectionsByRequestedTime;

	$impactHospitalBedsByRequestedTime = ($data['totalHospitalBeds'] * 0.35) - $impactSevereCasesByRequestedTime;
	$severeHospitalBedsByRequestedTime =($data['totalHospitalBeds'] * 0.35)- $severeImpactSevereCasesByRequestedTime;

	$impactCasesForICUByRequestedTime = 0.05 * $impactInfectionsByRequestedTime;
	$severeCasesForICUByRequestedTime = 0.05 * $severeInfectionsByRequestedTime;

	$impactCasesForVentilatorsByRequestedTime = 0.02 * $impactInfectionsByRequestedTime;
	$severeCasesForVentilatorsByRequestedTime = 0.02 * $severeInfectionsByRequestedTime;

	$impactDollarsInFlight = ($impactInfectionsByRequestedTime * 4 * 0.73)*periodTpeToDays($data['periodType'],$data['timeToElapse']);
	$severeDollarsInFlight = ($severeInfectionsByRequestedTime * 0.73 * 4)*periodTpeToDays($data['periodType'],$data['timeToElapse']);

	$postData = array(
			'data'=>$data,
'estimate' => array(
	'impact' => array(
			'currentlyInfected'=>intval($impactCurrentlyInfected),
			'infectionsByRequestedTime'=>intval($impactInfectionsByRequestedTime),
			'severeCasesByRequestedTime'=>intval($impactSevereCasesByRequestedTime),
			'hospitalBedsByRequestedTime'=>intval($impactHospitalBedsByRequestedTime),
			'casesForICUByRequestedTime'=>intval($impactCasesForICUByRequestedTime),
			'casesForVentilatorsByRequestedTime'=>intval($impactCasesForVentilatorsByRequestedTime),
			'dollarsInFlight'=>intval($impactDollarsInFlight),
	),

	'severeImpact' => array(
			'currentlyInfected'=>intval($severeCurrentlyInfected),
			'infectionsByRequestedTime'=>intval($severeInfectionsByRequestedTime),
			'severeCasesByRequestedTime'=>intval($severeImpactSevereCasesByRequestedTime),
			'hospitalBedsByRequestedTime'=>intval($severeHospitalBedsByRequestedTime),
			'casesForICUByRequestedTime'=>intval($severeCasesForICUByRequestedTime),
			'casesForVentilatorsByRequestedTime'=>intval($severeCasesForVentilatorsByRequestedTime),
			'dollarsInFlight'=>intval($severeDollarsInFlight),
	),
 )
);


	echo json_encode($postData);


}


