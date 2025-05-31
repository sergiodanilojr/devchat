<?php

use ResolutoDigital\DevChat\Prompts\ResolutoArchitectPrompt;

return [
    'openai_api_key'=> env('OPENAI_API_KEY', ''),
    'history_path' => storage_path('devchat/history.json'),
    'prompts' => [
        'default'=> ResolutoArchitectPrompt::class,
        'modular' => ResolutoArchitectPrompt::class,
        'módulo' => ResolutoArchitectPrompt::class,
        'estrutura' => ResolutoArchitectPrompt::class,
    ],
];
