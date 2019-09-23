<?php

namespace App\Providers;

use Symfony\Component\HttpClient\HttpClient;

class Apifootball implements ProviderInterface
{
    private     $token;

    public function __construct($token)
    {
        $this->token = $token;    
    }

    protected function getCompleteUrl() : string
    {
        $from = new \DateTime('1 week ago');
        $to = new \DateTime();
        $leagueId = '176'; // Ligue 1
        return 'https://apiv2.apifootball.com/?action=get_events&from='.$from->format('Y-m-d').'0&to='.$to->format('Y-m-d').'&league_id='.$leagueId.'&APIkey='.$this->token;
    }

    public function getSentences() : array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $this->getCompleteUrl());
        if (200 !== $response->getStatusCode())
            return [];
        $results = $response->toArray();
        $selection = [];
        foreach ($results as $line)
        {
            if ('Finished' === $line['match_status'])
            {
                $selection[] = $line['match_hometeam_name'] .' vs '. $line['match_awayteam_name'] .' : '.$line['match_hometeam_score'].' - '.$line['match_awayteam_score'];
            }
        }
        return $selection;
    }
}
