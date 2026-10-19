<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class OpenAIService
{
    protected string $apiKey;

    protected string $model;

    protected string $endpoint =
        'https://api.openai.com/v1/responses';

    public function __construct()
    {
        $this->apiKey = trim(
            (string) config(
                'services.openai.api_key'
            )
        );

        $this->model = trim(
            (string) config(
                'services.openai.model',
                'gpt-5.6-luna'
            )
        );

        if ($this->apiKey === '') {
            throw new RuntimeException(
                'OpenAI API key is not configured.'
            );
        }

        if ($this->model === '') {
            throw new RuntimeException(
                'OpenAI model is not configured.'
            );
        }
    }

    /**
     * Generate structured JSON using OpenAI Responses API.
     */
    public function generateJson(
        string $instructions,
        string $input,
        array $schema,
        int $maxOutputTokens = 180
    ): array {
        $payload = [
            'model' => $this->model,

            /*
             * No reasoning for faster ecommerce chat responses.
             */
            'reasoning' => [
                'effort' => 'none',
            ],

            'instructions' => $instructions,

            'input' => $input,

            /*
             * Structured JSON output.
             */
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'maxaroma_product_response',
                    'strict' => true,
                    'schema' => $schema,
                ],
            ],

            /*
             * Keep output bounded.
             *
             * 220 is retained because the application requires:
             * - answer
             * - exactly 3 follow-up questions
             */
            'max_output_tokens' => $maxOutputTokens,

            /*
             * Do not store the response.
             */
            'store' => false,
        ];

        $start = microtime(true);

        try {
            $response = Http::withToken(
                $this->apiKey
            )
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(20)
                ->post(
                    $this->endpoint,
                    $payload
                );
        } catch (\Throwable $e) {
            $duration = round(
                microtime(true) - $start,
                3
            );

            Log::error(
                'MAXAROMA OpenAI connection failed',
                [
                    'model' => $this->model,
                    'time' => $duration,
                    'error' => $e->getMessage(),
                ]
            );

            throw new RuntimeException(
                'Unable to connect to OpenAI.'
            );
        }

        $duration = round(
            microtime(true) - $start,
            3
        );

        if (!$response->successful()) {
            $message = $this->getErrorMessage(
                $response
            );

            Log::error(
                'MAXAROMA OpenAI API error',
                [
                    'status' => $response->status(),
                    'model' => $this->model,
                    'time' => $duration,
                    'message' => $message,
                ]
            );

            throw new RuntimeException(
                'OpenAI API error (' .
                $response->status() .
                '): ' .
                $message
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'OpenAI returned an invalid API response.'
            );
        }

        /*
         * Preferred Responses API output.
         */
        $text = '';

        if (
            isset($data['output_text']) &&
            is_string($data['output_text'])
        ) {
            $text = trim(
                $data['output_text']
            );
        }

        /*
         * Fallback parser.
         */
        if (
            $text === '' &&
            isset($data['output']) &&
            is_array($data['output'])
        ) {
            foreach ($data['output'] as $output) {
                if (
                    !isset($output['content']) ||
                    !is_array($output['content'])
                ) {
                    continue;
                }

                foreach ($output['content'] as $content) {
                    if (
                        isset($content['text']) &&
                        is_string($content['text'])
                    ) {
                        $text .= $content['text'];
                    }
                }
            }

            $text = trim($text);
        }

        if ($text === '') {
            Log::error(
                'MAXAROMA OpenAI empty response',
                [
                    'model' => $this->model,
                    'time' => $duration,
                    'response_id' =>
                        $data['id'] ?? null,
                ]
            );

            throw new RuntimeException(
                'OpenAI returned an empty response.'
            );
        }

        try {
            $result = json_decode(
                $text,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            Log::error(
                'MAXAROMA OpenAI invalid JSON',
                [
                    'model' => $this->model,
                    'time' => $duration,
                    'error' => $e->getMessage(),
                ]
            );

            throw new RuntimeException(
                'OpenAI returned invalid JSON.'
            );
        }

        if (!is_array($result)) {
            throw new RuntimeException(
                'OpenAI returned an invalid structured response.'
            );
        }

        Log::info(
            'MAXAROMA OpenAI response received',
            [
                'model' => $this->model,
                'time' => $duration,
            ]
        );

        return $result;
    }

    /**
     * Get OpenAI API error message.
     */
    protected function getErrorMessage(
        $response
    ): string {
        $data = $response->json();

        if (
            is_array($data) &&
            isset($data['error']['message'])
        ) {
            return (string)
                $data['error']['message'];
        }

        $body = trim(
            $response->body()
        );

        return $body !== ''
            ? $body
            : 'Unknown OpenAI API error.';
    }
}