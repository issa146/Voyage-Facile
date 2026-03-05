<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class UnsplashService
{
    public function __construct(private HttpClientInterface $client, private string $accessKey,) {

    }


    public function getImageUrl($destination) {
        $response = $this->client->request('GET', 'https://api.unsplash.com/search/photos', [ # j'envoie une requête GET à l'API
            'headers' => [
                'Authorization' => 'Client-ID ' . $this->accessKey, 
                'Accept-Version' => 'v1',
            ],
            'query' => [
                'query' => $destination, 
                'per_page' => 1,
                'orientation' => 'landscape',
            ],
            
        ]);
            
            $data = $response->toArray();

            if(!isset($data['results'][0])) { # je vérifie si j'ai au moin un résulta sinon je retourne null
                return null;
            }


            return $data['results'][0]['urls']['regular'] ?? null; # si aucune image est trouvé je retourne null
    }
}




?>