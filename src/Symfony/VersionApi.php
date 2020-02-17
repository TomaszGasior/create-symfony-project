<?php

namespace Mushrooms\CreateSymfonyProject\Symfony;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class VersionApi
{
    private const API_URL = 'https://symfony.com/releases.json';

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getVersionOfRelease(string $release): string
    {
        try {
            $response = $this->httpClient->request('GET', self::API_URL);
            $data = $response->toArray();
        }
        catch (\Throwable $e) {
            $this->throwException('Failed to communicate with Symfony version API.', $e);
        }

        $validate = function(string $version): string {
            if (!preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/', $version)) {
                throw new \UnexpectedValueException;
            }

            return $version;
        };

        try {
            switch ($release) {
                case Release::LTS:
                    return $validate($data['symfony_versions']['lts']);

                case Release::STABLE:
                    return $validate($data['symfony_versions']['stable']);

                case Release::NEXT:
                    return $validate($data['symfony_versions']['next']);

                default:
                    throw new \InvalidArgumentException;
            }
        }
        catch (\Throwable $e) {
            $this->throwException('Failed to parse data from Symfony version API.', $e);
        }
    }

    private function throwException(string $message, \Throwable $originalException): void
    {
        throw new \RuntimeException(
            sprintf(
                '%s —— %s %s',
                $message,
                get_class($originalException),
                $originalException->getMessage()
            )
        );
    }
}
