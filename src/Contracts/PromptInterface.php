<?php

namespace ResolutoDigital\DevChat\Contracts;

interface PromptInterface
{
    public function buildPrompt(string $input): self;

    public function messages(): array;

    public function interpretResponse(string $response): array;

    public function withContext(array $context): self;
}