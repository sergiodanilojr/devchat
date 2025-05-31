<?php

namespace ResolutoDigital\DevChat\Core;

use OpenAI\Laravel\Facades\OpenAI;
use ResolutoDigital\DevChat\Contracts\PromptInterface;

class OpenAiCodeGenerator
{
    public function chat(PromptInterface $prompt): array
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => $prompt->messages(),
        ]);

        $content = $result['choices'][0]['message']['content'] ?? null;

        return $prompt->interpretResponse($content);
    }

}