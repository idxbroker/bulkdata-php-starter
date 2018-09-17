<?php
declare(strict_types=1);

namespace Idx\Resource;

use GuzzleHttp\RequestOptions;

trait GetTrait
{
    /**
     * Get Data
     *
     * @param array $query
     *
     * @return array
     *
     * @throws Exception|RequestException
     */
    public function get(array $query = []): array
    {
        if (!isset($this->client) || !isset($this->config) || !isset($this->token)) {
            throw new Exception('Misses one or more depenedecies.');
        }
        $response = $this->client->request(
            'GET',
            $this->config->get('api-endpoint').self::PATH,
            [
                RequestOptions::HEADERS => [
                    'x-api-key' => $this->config->get('x-api-key'),
                    'accessToken' => $this->token->getAccessToken()
                ],
                'query' => $query
            ]
        );
        $content = json_decode($response->getBody()->getContents(), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $content;
        }
        throw new Exception(json_last_error_msg());
    }

}
