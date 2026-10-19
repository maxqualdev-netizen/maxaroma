<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use RuntimeException;

class OpenAIChatService
{
    protected OpenAIService $openAI;

    public function __construct(
        OpenAIService $openAI
    ) {
        $this->openAI = $openAI;
    }

    /**
     * Generate answer + exactly 3 follow-up questions.
     *
     * Rules:
     * - Use only supplied product information.
     * - Answer current question directly.
     * - Never guess or invent.
     * - Generate exactly 3 useful follow-ups.
     * - Semantic duplicate detection is handled by OpenAI.
     */
    public function generateResponse(
        string $question,
        array $productData,
        array $askedQuestions = []
    ): array {
        /*
         * Keep the original rules and meaning intact.
         */
        $instructions = <<<'SYSTEM'
You are the MAXAROMA product assistant.

Use ONLY the supplied product information.

Answer the customer's current question directly.

STYLE:

- Be natural, customer friendly, and conversational.
- Sound like a helpful store assistant.
- Use simple customer-friendly language.
- Prefer 1–2 short paragraphs.
- Do not sound like a product description, article, search result, or technical response.
- Do not repeat the customer's question.
- Do not add unnecessary information.
- Avoid phrases like "According to the product information", "The product states", or "It is described as".
- You may use **bold** for important product details.

ACCURACY:

- Never guess, assume, invent, or use outside knowledge.
- Use only the supplied product information.
- If the answer is unavailable, say:
  "Sorry, I don't have that information for this product."

FOLLOW-UP QUESTIONS:

Generate exactly 3 short follow-up questions.

The follow-up questions are for helping the customer explore OTHER useful information about this product.

IMPORTANT DUPLICATE RULES:

- The current customer question is already answered.
- Do NOT generate a follow-up that asks the same thing as the current customer question.
- Do NOT generate a follow-up that asks the same thing as any previously asked question.
- Do NOT simply change the wording of a previous question.
- Questions with different wording but the same meaning must also be treated as duplicates.
- Compare the MEANING and INTENT of each proposed follow-up against:
  1. The current customer question.
  2. Every previously asked question.
  3. The other follow-up questions being generated.
- If two questions have substantially the same intent, keep only one.
- If the current question already covers a topic, prefer other available topics.
- Do not repeat a topic merely by asking it in different words when another useful product topic is available.
- Choose follow-ups dynamically from the supplied product information.
- Each follow-up must be answerable from the supplied product information.
- Each follow-up should explore a different useful aspect of the product.
- Prefer different available attributes such as fragrance family, fragrance personality, seasons, occasions, formulation, gender, size, brand, or other information actually present in the product data.
- Do not ask about an attribute that is not available in the supplied product information.
- Never invent product attributes just to create a question.
- Always return exactly 3 useful follow-up questions.

IMPORTANT:

Before generating follow-ups, mentally compare every candidate question with the current question and all previously asked questions.

For example, if the customer asked:
"What is the fragrance family?"

Do not generate:
"What fragrance family does this perfume belong to?"

Instead, choose other available information such as:
"What size is this Eau De Parfum?"
"What season is it best for?"
"Is it suitable for formal occasions?"

If the customer asked:
"What season is it best for?"

Do not generate another question about seasons using different wording.

Use other available product information instead.

Do not mention AI, OpenAI, prompts, databases, APIs, internal processing, or product data.

Return only the structured response.
SYSTEM;

        $cleanProductData = $this->cleanProductData(
            $productData
        );

        $cleanAskedQuestions = $this->cleanAskedQuestions(
            $askedQuestions
        );

        $inputData = [
            'CURRENT_CUSTOMER_QUESTION' => trim($question),
            'PREVIOUSLY_ASKED_CUSTOMER_QUESTIONS' => $cleanAskedQuestions,
            'PRODUCT_INFORMATION' => $cleanProductData,
        ];

        $input = json_encode(
            $inputData,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        if ($input === false) {
            throw new RuntimeException(
                'Unable to prepare OpenAI request.'
            );
        }

        $schema = [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'answer' => [
                    'type' => 'string',
                ],
                'follow_up' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                    'minItems' => 3,
                    'maxItems' => 3,
                ],
            ],
            'required' => [
                'answer',
                'follow_up',
            ],
        ];

        try {
            /*
             * One OpenAI request only.
             */
            $result = $this->openAI->generateJson(
                $instructions,
                $input,
                $schema,
                220
            );
        } catch (\Throwable $e) {
            Log::error(
                'MAXAROMA OpenAI chat failed',
                [
                    'question' => $question,
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }

        $answer = '';

        if (
            isset($result['answer']) &&
            is_string($result['answer'])
        ) {
            $answer = trim(
                $result['answer']
            );
        }

        if ($answer === '') {
            throw new RuntimeException(
                'OpenAI returned an empty answer.'
            );
        }

        $followUps = [];

        if (
            isset($result['follow_up']) &&
            is_array($result['follow_up'])
        ) {
            $followUps = $result['follow_up'];
        }

        /*
         * PHP performs only exact/normalized duplicate cleanup.
         *
         * Semantic duplicate detection remains the responsibility
         * of the LLM as required by the existing rules.
         */
        $followUps = $this->cleanFollowUps(
            $followUps,
            $cleanAskedQuestions,
            $question
        );

        return [
            'answer' => $answer,
            'follow_up' => $followUps,
        ];
    }

    /**
     * Clean previously asked questions.
     */
    protected function cleanAskedQuestions(
        array $askedQuestions
    ): array {
        $unique = [];
        $seen = [];

        foreach ($askedQuestions as $question) {
            if (!is_string($question)) {
                continue;
            }

            $question = trim($question);

            if ($question === '') {
                continue;
            }

            $normalized = $this->normalizeQuestion(
                $question
            );

            if ($normalized === '') {
                continue;
            }

            if (isset($seen[$normalized])) {
                continue;
            }

            $seen[$normalized] = true;
            $unique[] = $question;
        }

        /*
         * Keep the latest 20.
         */
        return array_slice(
            $unique,
            -20
        );
    }

    /**
     * Clean generated follow-ups.
     *
     * Only exact/normalized duplicate detection is performed here.
     */
    protected function cleanFollowUps(
        array $followUps,
        array $askedQuestions,
        string $currentQuestion
    ): array {
        $result = [];

        $normalizedCurrent =
            $this->normalizeQuestion(
                $currentQuestion
            );

        $normalizedAsked = [];

        foreach ($askedQuestions as $question) {
            if (!is_string($question)) {
                continue;
            }

            $normalized = $this->normalizeQuestion(
                $question
            );

            if ($normalized !== '') {
                $normalizedAsked[$normalized] = true;
            }
        }

        $seen = [];

        foreach ($followUps as $followUp) {
            if (!is_string($followUp)) {
                continue;
            }

            $followUp = trim($followUp);

            if ($followUp === '') {
                continue;
            }

            $normalizedFollowUp =
                $this->normalizeQuestion(
                    $followUp
                );

            if ($normalizedFollowUp === '') {
                continue;
            }

            /*
             * Current question duplicate.
             */
            if (
                $normalizedFollowUp ===
                $normalizedCurrent
            ) {
                continue;
            }

            /*
             * Previously asked duplicate.
             */
            if (
                isset(
                    $normalizedAsked[
                        $normalizedFollowUp
                    ]
                )
            ) {
                continue;
            }

            /*
             * Duplicate follow-up.
             */
            if (
                isset(
                    $seen[
                        $normalizedFollowUp
                    ]
                )
            ) {
                continue;
            }

            $seen[$normalizedFollowUp] = true;

            $result[] = $followUp;

            if (count($result) >= 3) {
                break;
            }
        }

        return $result;
    }

    /**
     * Normalize question for exact duplicate comparison.
     */
    protected function normalizeQuestion(
        string $question
    ): string {
        $question = strtolower(
            trim($question)
        );

        $question = preg_replace(
            '/[^\p{L}\p{N}\s]/u',
            '',
            $question
        );

        $question = preg_replace(
            '/\s+/',
            ' ',
            $question
        );

        return trim(
            (string) $question
        );
    }

    /**
     * Remove null/empty product values and normalize text.
     *
     * No product attribute is removed.
     */
    protected function cleanProductData(
        array $productData
    ): array {
        $clean = [];

        foreach ($productData as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);

                if ($value === '') {
                    continue;
                }

                $value = preg_replace(
                    '/\s+/',
                    ' ',
                    $value
                );
            }

            $clean[$key] = $value;
        }

        return $clean;
    }
}