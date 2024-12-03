<?php

require __DIR__ . '/pipedrive_lead_integration.php';
$apiKey = 'API_KEY'; //FYLL INN API KEY HER
$baseUrl = 'https://nettbureauasdevelopmentteam.pipedrive.com/api/v1/';

$data = [
    'lead_title' => 'testTittel',
    'organization_name' => 'Eksempel AS',
    'name' => 'Ola Nordmann',
    'email' => 'ola.nordmann@online.no',
    'phone' => '12345678',
    'housing_type' => "Enebolig",
    'property_size' => 160,
    'deal_type' => "Spotpris", 
    'contact_type' => "Privat", 
    'comment' => 'Dette er en kommentar'
];

$integration = new PipedriveIntegration($apiKey, $baseUrl);
$leadId = $integration->createLeadWorkflow($data);

echo "Lead opprettet med ID: $leadId";


