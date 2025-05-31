<?php

namespace ResolutoDigital\DevChat\Core;

class PromptRegistry
{

    public function resolve(string $input): string
    {
        foreach (config('devchat.prompts') as $keyword => $class) {
            if (str_contains(strtolower($input), $keyword)) {
                return $class;
            }
        }

        return config('devchat.prompts.default');
    }

}