<?php

namespace App\Service;


class TakeVecteur
{
    public function httpClient($mot, $client)
    {
        $response = $client->request('POST', 'http://localhost:11434/api/embeddings', [
            'json' => ['model' => 'mistral','prompt' => $mot ],
        ]);

        $decodedPayload = $response->toArray();
        return $decodedPayload;
    }

    public function similar($vector1, $vector2) {
        // Calculate the dot product of the two vectors
        $dotProduct = array_sum(array_map(function($x, $y) {
            return $x->getValue() * $y->getValue();
        }, $vector1, $vector2));

        // Calculate the magnitudes of the vectors
        $magnitude1 = sqrt(array_sum(array_map(function($x) {
            return $x->getValue() * $x->getValue();
        }, $vector1)));

        $magnitude2 = sqrt(array_sum(array_map(function($x) {
            return $x->getValue() * $x->getValue();
        }, $vector2)));

        // Check for division by zero
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }

        // Compute the cosine similarity
        return round(($dotProduct / ($magnitude1 * $magnitude2))*100, 2);
    }
    public function similar2($vector1, $vector2) {
        // Calculate the dot product of the two vectors
        $dotProduct = array_sum(array_map(function($x, $y) {
            return $x->getValue() * $y;
        }, $vector1, $vector2));

        // Calculate the magnitudes of the vectors
        $magnitude1 = sqrt(array_sum(array_map(function($x) {
            return $x->getValue() * $x->getValue() ;
        }, $vector1)));

        $magnitude2 = sqrt(array_sum(array_map(function($x) {
            return $x * $x;
        }, $vector2)));

        // Check for division by zero
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }

        // Compute the cosine similarity
        return round(($dotProduct / ($magnitude1 * $magnitude2))*100, 2);
    }


}