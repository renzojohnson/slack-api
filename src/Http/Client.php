<?php

/**
 * Slack API
 *
 * @package   RenzoJohnson\Slack
 * @author    Renzo Johnson <hello@renzojohnson.com>
 * @copyright 2026 Renzo Johnson
 * @license   MIT
 * @link      https://renzojohnson.com
 */

declare(strict_types=1);

namespace RenzoJohnson\Slack\Http;

use RenzoJohnson\Slack\Exception\AuthenticationException;
use RenzoJohnson\Slack\Exception\RateLimitException;
use RenzoJohnson\Slack\Exception\SlackException;

final class Client
{
    private const BASE_URL = 'https://slack.com/api/';

    public function __construct(
        private readonly string $botToken,
        private readonly int $timeout = 30,
    ) {}

    public function post(string $method, array $payload): array
    {
        $ch = curl_init(self::BASE_URL . $method);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->botToken,
                'Content-Type: application/json; charset=utf-8',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_THROW_ON_ERROR),
        ]);

        return $this->execute($ch);
    }

    public function get(string $method, array $params = []): array
    {
        $url = self::BASE_URL . $method;

        if ($params !== []) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->botToken,
            ],
        ]);

        return $this->execute($ch);
    }

    public static function postWebhook(string $webhookUrl, array $payload, int $timeout = 30): void
    {
        $ch = curl_init($webhookUrl);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_THROW_ON_ERROR),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new SlackException('cURL error: ' . $error);
        }

        if ($httpCode >= 400) {
            throw new SlackException('Webhook error: ' . $response, $httpCode);
        }
    }

    private function execute(\CurlHandle $ch): array
    {
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $retryAfter = (int) curl_getinfo($ch, CURLINFO_HEADER_OUT);
        curl_close($ch);

        if ($response === false) {
            throw new SlackException('cURL error: ' . $error);
        }

        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($httpCode === 401 || ($decoded['error'] ?? '') === 'invalid_auth') {
            throw new AuthenticationException(
                $decoded['error'] ?? 'Invalid authentication',
                401,
                $decoded,
            );
        }

        if ($httpCode === 429) {
            throw new RateLimitException(
                'Rate limit exceeded',
                (int) ($decoded['retry_after'] ?? 0),
                $decoded,
            );
        }

        if ($httpCode >= 400) {
            throw new SlackException(
                $decoded['error'] ?? 'API error',
                $httpCode,
                $decoded,
            );
        }

        if (isset($decoded['ok']) && $decoded['ok'] === false) {
            throw new SlackException(
                $decoded['error'] ?? 'Unknown error',
                0,
                $decoded,
            );
        }

        return $decoded;
    }
}
