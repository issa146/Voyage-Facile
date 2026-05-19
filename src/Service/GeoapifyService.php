<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;



class GeoapifyService {

private HttpClientInterface $client;
private string $apikey;


public function __construct(HttpClientInterface $client, string $apikey) {

    $this->client = $client;
    $this->apikey = $apikey;
    
}


public function getSearchCity(string $city): ?array  {
        $response = $this->client->request('GET', 'https://api.geoapify.com/v1/geocode/search', [ # j'envoie une requette HTTP GET à l'API
            'query' => [
                'text' => $city,
                'lang' => 'fr', 
                'apiKey' => $this->apikey,
            ],
        ]);

        $data = $response->toArray();

        if(empty($data['features'][0]['geometry']['coordinates'])) {
            return null;
        }

        [$lon, $lat] = $data['features'][0]['geometry']['coordinates'];

        return [
            'lat' => $lat,
            'lon' => $lon,
        ];

    }    

    public function getPlaceCity(string $city, int $limit) {
        $coordinates = $this->getSearchCity($city); # j'appelle la méthode et je lui passe la ville en parmètre 

        if(!$coordinates) { # si c'est vide je renvoie un tableau vide
            return [];
        }

        $lat = $coordinates['lat'];
        $lon = $coordinates['lon'];

        $response = $this->client->request('GET', 'https://api.geoapify.com/v2/places', [ # j'envoie une requette HTTP GET à l'API
            'query' => [
                'categories' => 'tourism.sights,entertainment,entertainment.museum,leisure.park', # je demande des lieu touristique
                'lang' => 'fr',
                'filter' => 'circle:' . $lon . ',' . $lat . ',5000', # je demande des lieu dans un rayon de 5 km 
                'limit' => $limit * 2, 
                'apiKey' => $this->apikey, 
            ],
        ]);

        $data = $response->toArray();


        if(empty($data['features'])) { # je vérifie si c'est vide 
            return null;
        }

        $places = [];
        $seenPlaces = [];

        foreach($data['features'] as $place) { # je boucle la liste des lieu 
            $properties = $place['properties'] ?? []; 

            $name = $properties['name'] ?? 'lieu sans nom';
            $address = $properties['formatted'] ?? null;
            
            $fusion = mb_strtolower($name . '|' . $address);

            if (isset($seenPlaces[$fusion])) {
                continue;
            }

            $seenPlaces[$fusion] = true;

            $places[] = [
                'name' => $name,
                'address' => $address,
                'category' => $properties['categories'][0] ?? null,
                'lat' => $properties['lat'] ?? null,
                'lon' => $properties['lon'] ?? null, 
            ];

            if(count($places) >= $limit) {
                break;
            }
        }

        return $places;
    }
}


?>