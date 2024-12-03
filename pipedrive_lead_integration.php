<?php

class PipedriveIntegration {

    private $apiKey;
    private $baseUrl;
    
    public function __construct($apiKey,$baseUrl) {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    // Funksjon for å sende HTTP-forespørsel
    private function sendRequest($endpoint, $method, $data = []) {
        // Bygg URL
        $url = $this->baseUrl . $endpoint . '?api_token=' . $this->apiKey;
    
        // Velg HTTP-metode
        $options = [
            'http' => [
                'method' => $method,
                'header' => "Content-Type: application/json",
                'content' => json_encode($data)
            ]
        ];

        // Lager kontekst for HTTP-forespørselen
        $context = stream_context_create($options);

        echo "Request URL: $url\n";
        echo "Request Data: " . json_encode($data) . "\n";

        // Utfør forespørselen og hent svar
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            echo "HTTP request failed.\n";
            print_r($http_response_header); // Log response headers for debugging
            return null;
        }

        // Returner JSON-dekodet svar
        return json_decode($response, true);
    }

    // Funksjon for å opprette organisasjon
    public function createOrganization($organizationName) {
        $data = [
            'name' => $organizationName
        ];

        // Send forespørsel for å opprette organisasjon
        $response = $this->sendRequest('organizations', 'POST', $data);

        if (isset($response['data']['id'])) {
            return $response['data']['id'];
        }

        return null;
    }

    // Funksjon for å opprette person
    public function createPerson($name, $email, $phone, $contact_type, $organizationId) {
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'org_id' => $organizationId,
            'fd460d099264059d975249b20e071e05392f329d' => $contact_type
        ];

        // Send forespørsel for å opprette person
        $response = $this->sendRequest('persons', 'POST', $data);

        if (isset($response['data']['id'])) {
            return $response['data']['id'];
        }

        return null;
    }

    // Funksjon for å opprette lead
    public function createLead($lead_title, $personId, $organizationId, $dealType, $housingType, $propertySize, $comment) {
        $data = [
            'title' => $lead_title,
            'person_id' => $personId,
            'org_id' => $organizationId,
            'cebe4ad7ce36c3508c3722b6e0072c6de5250586' => $dealType,
            '9cbbad3c5d83d6d258ef27db4d3784b5e0d5fd32' => $housingType,
            '7a275c324d7fbe5ab62c9f05bfbe87dad3acc3ba' => $propertySize,
            '479370d7514958b2b4b4049c37be492f357fe7d8' => $comment
        ];

        // Send forespørsel for å opprette lead
        $response = $this->sendRequest('leads', 'POST', $data);

        if (isset($response['data']['id'])) {
            return $response['data']['id'];
        }

        return null;
    }

    // Hovedfunksjon for å opprette organisasjon, person og lead
    public function createLeadWorkflow($data) {
        // Opprett organisasjon
        $organizationId = $this->createOrganization($data['organization_name']);
        if (!$organizationId) {
            return 'Failed to create organization';
        }

        // Opprett person
        $personId = $this->createPerson($data['name'], $data['email'], $data['phone'], $data['contact_type'], $organizationId);
        if (!$personId) {
            return 'Failed to create person';
        }

        // Opprett lead
        $leadId = $this->createLead(
            $data['lead_title'],
            $personId,
            $organizationId,
            $data['deal_type'],
            $data['housing_type'],
            $data['property_size'],
            $data['comment']
        );

        if (!$leadId) {
            return 'Failed to create lead';
        }

        return $leadId; // Returner lead ID
    }
}
?>
