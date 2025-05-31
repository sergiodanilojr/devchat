<?php

namespace ResolutoDigital\DevChat\Core;

use Illuminate\Support\Traits\Conditionable;

class DevChatAgent
{
    use Conditionable;

    protected array $context = [];

    public function __construct(
        protected PromptRegistry $registry
    )
    {
    }

    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    public function process(string $input): array
    {
        $promptClass = $this->registry->resolve($input);
        $prompt = app($promptClass)->buildPrompt($input, $this->context);

        return app(OpenAiCodeGenerator::class)->chat($prompt);
    }

}