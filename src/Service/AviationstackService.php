<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AviationstackService
{
    // Liste des villes disponibles et leurs codes IATA.
    private const AIRPORTS = [
        'Paris' => 'CDG',
        'Londres' => 'LHR',
        'Madrid' => 'MAD',
        'Rome' => 'FCO',
        'Tokyo' => 'HND',
        'New York' => 'JFK',
        'Istanbul' => 'IST',
        'Marrakech' => 'RAK',
    ];

    // Noms complets des aeroports.
    private const AIRPORT_NAMES = [
        'CDG' => 'Paris Charles de Gaulle',
        'LHR' => 'Londres Heathrow',
        'MAD' => 'Madrid Barajas',
        'FCO' => 'Rome Fiumicino',
        'HND' => 'Tokyo Haneda',
        'JFK' => 'New York John F. Kennedy',
        'IST' => 'Istanbul Airport',
        'RAK' => 'Marrakech Menara',
    ];

    private const AIRLINES = [ // Compagnies aériennes fictives pour les vols de secours.
        'Air France',
        'EasyJet',
        'British Airways',
    ];

    public function __construct(private HttpClientInterface $httpClient, private string $apiKey)
    {
    }

    public function searchFlight(string $departureCity, string $arrivalCity, string $date): array
    {
        $departureCode = self::AIRPORTS[$departureCity] ?? null; // On récupère les codes IATA des villes de départ et d'arrivée sinon null.
        $arrivalCode = self::AIRPORTS[$arrivalCity] ?? null;

        // Si une ville n'est pas dans notre liste on ne lance pas la requete
        if (!$departureCode || !$arrivalCode) {
            return [];
        }

        $response = $this->httpClient->request('GET', 'http://api.aviationstack.com/v1/flights', [ // je fais une requete GET à l'API pour récupérer les vols
            'query' => [ 
                'access_key' => $this->apiKey,
                'dep_iata' => $departureCode, // je filtre les vols par code IATA de départ, d'arrivée et par date 
                'arr_iata' => $arrivalCode,
                'flight_date' => $date,
                'limit' => 5,
            ],
        ]);

        // si l'API refuse la requete ou ne trouve rien on affiche des vols de secours
        if ($response->getStatusCode() >= 400) {
            return $this->getDefaultFlights($departureCity, $arrivalCity, $date, $departureCode, $arrivalCode);
        }

        $data = $response->toArray(false); // on convertit la réponse en tableau associatif. Le false empêche de lancer une exception

        if (empty($data['data'])) { // si l'API ne trouve aucun vol on affiche des vols de secours
            return $this->getDefaultFlights($departureCity, $arrivalCity, $date, $departureCode, $arrivalCode);
        }

        $flights = [];

        foreach ($data['data'] as $flight) { // je parcours les vols retournés par l'API et je récupère les info que je veux afficher
            $flights[] = [
                'compagnie' => $flight['airline']['name'] ?? 'Non renseigne',
                'numeroVol' => $flight['flight']['iata'] ?? 'Non renseigne',
                'aeroportDepart' => $flight['departure']['airport'] ?? 'Non renseigne',
                'codeDepart' => $flight['departure']['iata'] ?? 'Non renseigne',
                'heureDepart' => $flight['departure']['scheduled'] ?? null,
                'aeroportArrivee' => $flight['arrival']['airport'] ?? 'Non renseigne',
                'codeArrivee' => $flight['arrival']['iata'] ?? 'Non renseigne',
                'heureArrivee' => $flight['arrival']['scheduled'] ?? null,
                'statutVol' => $flight['flight_status'] ?? 'Non renseigne',
                'prixEstime' => null,
                'source' => 'api',
            ];
        }

        return $flights;
    }

    private function getDefaultFlights(string $departureCity, string $arrivalCity, string $date, string $departureCode, string $arrivalCode): array
    {
        $flights = [];
        $times = [ 
            ['08:30', '10:15'],
            ['13:20', '15:05'],
            ['18:10', '19:55'],
        ];

        foreach ($times as $index => [$departureTime, $arrivalTime]) { 
            $flights[] = [
                'compagnie' => self::AIRLINES[$index] ?? 'Voyage Facile Airlines',  // si j'ai un tableau défini je prends la compagnie correspondante sion je mets une compagnie par défaut
                'numeroVol' => 'VF' . ($index + 101), 
                'aeroportDepart' => self::AIRPORT_NAMES[$departureCode] ?? $departureCity, // si j'ai un tableau défini je l’utilises sinon j'affiche la ville
                'codeDepart' => $departureCode, 
                'heureDepart' => $date . ' ' . $departureTime, 
                'aeroportArrivee' => self::AIRPORT_NAMES[$arrivalCode] ?? $arrivalCity,
                'codeArrivee' => $arrivalCode,
                'heureArrivee' => $date . ' ' . $arrivalTime,
                'statutVol' => 'Programme',
                'prixEstime' => 120 + ($index * 45),
                'source' => 'local',
            ];
        }

        return $flights;
    }
}
