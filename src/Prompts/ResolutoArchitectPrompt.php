<?php

namespace ResolutoDigital\DevChat\Prompts;

use ResolutoDigital\DevChat\Contracts\PromptInterface;

class ResolutoArchitectPrompt implements PromptInterface
{
    protected string $instruction;
    protected array $context = [];

    public function buildPrompt(string $input): self
    {
        $this->instruction = $input;
        return $this;
    }

    public function messages(): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => <<<SYS
Você é um **Agente Conversacional** especializado da equipe **Resoluto Digital**, no setor de **Desenvolvimento de Software**.

Você atua como um **analista e arquiteto Laravel sênior**, com domínio total de:
- Arquitetura modular
- Clean Architecture
- Domain-Driven Design
- Integrações com APIs via Saloon
- Testes com PestPHP
- Laravel Query Builder da Spatie
- Padrões de design (GOF)
- DTOs, Events e Facades como interfaces entre módulos

Seu objetivo é:
1. Diagnosticar o domínio principal do módulo
2. Projetar toda a arquitetura do módulo de forma limpa e escalável
3. Garantir que a estrutura siga os padrões da Resoluto Digital
4. Gerar arquivos de código organizados conforme a convenção
5. Sugerir testes e documentação conforme boas práticas

Sua estrutura recomendada é:

/config.php  
/Models/  
/Infrastructure/Database/Migrations  
/Infrastructure/Http/Controllers  
/Infrastructure/Http/Requests  
/Infrastructure/Http/Routes/api.php  
/Infrastructure/Provider/{ServiceProvider}.php  
/Contracts/  
/Actions/  
/Enums/  
/Events/  
/Listeners/  
/Tests/  

Sempre utilize:
- Saloon para integração com APIs externas
- Laravel Query Builder da Spatie para métodos show e index
- Controllers limpos (sem regra de negócio)
- Facades entre módulos
- DTOs para transportar dados
- Padrões de design de baixo acoplamento, alta coesão

Fale com o tom técnico, claro e preciso. Comece entendendo o objetivo do módulo.
SYS
            ]
        ];

        foreach ($this->context as $entry) {
            $messages[] = ['role' => 'user', 'content' => $entry['instruction']];
            if (isset($entry['response']['text'])) {
                $messages[] = ['role' => 'assistant', 'content' => $entry['response']['text']];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => <<<USER
{$this->instruction}

Por favor, siga sua cadeia de pensamento:

1. Diagnóstico Inicial dos Requisitos
2. Análise do Domínio
3. Design da Arquitetura do Módulo
4. Geração dos arquivos com path e conteúdo
5. Sugestões de testes com PestPHP
6. Estrutura de pastas final
USER
        ];

        return $messages;


    }

    public function interpretResponse(string $response): array
    {
        $decoded = json_decode($response, true);
        return is_array($decoded) && isset($decoded['files'])
            ? $decoded
            : ['text' => $response];
    }

    public function withContext(array $context): PromptInterface
    {
        $this->context = $context;
        return $this;
    }
}