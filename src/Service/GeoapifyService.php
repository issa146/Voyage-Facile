<?php

namespace App\Service;

use Doctrine\DBAL\Query\Limit;
use symfony\contracts\HttpClient\HttpClientInterface;


class GeoapifyService {

private HttpClientInterface $client;
private string $apiKey;


public function __construct(HttpClientInterface $client, string $apiKey) {

    $this->client = $client;
    $this->apiKey = $apiKey;
    
}


public function getSearchCity(string $city): ?array  {
        $response = $this->client->request('GET', 'https://api.geoapify.com/v1/geocode/search', [ # j'envoie une requette HTTP GET à l'API
            'query' => [
                'text' => $city, 
                'apiKey' => $this->apiKey,
            ],
        ]);

        $data = $response->toArray();

        if(empty($data['features'][0]['properties']['lat']) || empty($data['features'][0]['properties']['lon'])) { # si la valeur est vide je retourne null
            return null;
        }

        return [
            'lat' => $data['features'][0]['properties']['lat'],
            'lon' => $data['features'][0]['properties']['lon'],
        ];
    }    

    public function getPlaceCity(string $city) {
        $coordinates = $this->getSearchCity($city); # j'appelle la méthode et je lui passe la ville en parmètre 

        if(!$coordinates) { # si c'est vide je renvoie un tableau vide
            return [];
        }

        $lat = $coordinates['lat'];
        $lon = $coordinates['lon'];

        $response = $this->client->request('GET', 'https://api.geoapify.com/v2/places', [ # j'envoie une requette HTTP GET à l'API
            'query' => [
                'categories' => 'tourism.sights,entertainment,museum,leisure.park', # je demande des lieu touristique...
                'filter' => 'circle:' . $lon . ',' . $lat . ',5000', # je demande des lieu dans un rayon de 5 km 
                'limit' => 10,  # je veut maximum 10 résulta
                'apiKey' => $this->apiKey, 
            ],
        ]);

        $data = $response->toArray();


        if(empty($data['features'])) { # je vérifie si c'est vide 
            return null;
        }

        $places = [];

        foreach($data['features'] as $place) { # je boucle la liste des lieu 
            $properties = $place['properties'] ?? []; 

            $places[] = [
                'name' => $properties['name'] ?? 'lieu sans nom',
                'address' => $properties['formatted'] ?? null,
                'category' => $properties['categories'][0] ?? null,
                'lat' => $properties['lat'] ?? null,
                'lon' => $properties['lon'] ?? null, 
            ];
        }

        return $places;
    }
}


?>