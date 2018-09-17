<?php

require 'vendor/autoload.php';

use Idx\Resource\ResourceFactory;

$mlsInfoService = ResourceFactory::create(ResourceFactory::MLSINFO_SERVICE);
$listingService = ResourceFactory::create(ResourceFactory::LISTING_SERVICE);
$imageService = ResourceFactory::create(ResourceFactory::IMAGE_SERVICE);
$agentService = ResourceFactory::create(ResourceFactory::AGENT_SERVICE);
$officeService = ResourceFactory::create(ResourceFactory::OFFICE_SERVICE);

$idxID = 'a525';
if (is_null($idxID)) {
    die('$idxID can not be null.'.PHP_EOL);
}

$query = [ 'idxID' => $idxID ];

// Query mlsinfo.
$mlsInfo = $mlsInfoService->get($query);
echo json_encode($mlsInfo, JSON_PRETTY_PRINT).PHP_EOL;

// Query listings.
// $listings = $listingService->get($query);
// echo json_encode($listings, JSON_PRETTY_PRINT).PHP_EOL;

// Query images.
// $images = $imageService->get($query);
// echo json_encode($images, JSON_PRETTY_PRINT).PHP_EOL;

// Query agents.
// $agents = $agentService->get($query);
// echo json_encode($agents, JSON_PRETTY_PRINT).PHP_EOL;

// Query offices.
// $offices = $officeService->get($query);
// echo json_encode($offices, JSON_PRETTY_PRINT).PHP_EOL;


 // Using next to loop through all listing data.
// do {
//     $response = $listingService->get($query);
//     $nextQuery = parse_url($response['next'], PHP_URL_QUERY);
//     parse_str($nextQuery, $query);
//     // Process data.
//     echo json_encode($response['data'], JSON_PRETTY_PRINT).PHP_EOL;
// } while ($response['next'] !== null);
