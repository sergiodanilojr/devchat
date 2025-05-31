<?php

namespace ResolutoDigital\DevChat\Commands;

use Illuminate\Console\Command;
use ResolutoDigital\DevChat\Core\DevChatAgent;
use ResolutoDigital\DevChat\Core\FileWriter;
use ResolutoDigital\DevChat\Core\HistoryLogger;

class DevChatCommand extends Command
{
    protected $signature = 'dev:chat {--dry-run : Simula a criação sem gravar os arquivos}';
    protected $description = 'Chat interativo com o agente Resoluto para gerar módulos Laravel de forma assistida por IA';

    protected array $context = [];

    public function handle(): void
    {
        $this->info('🤖 DevChat Ativado - Resoluto Architect Agent');
        $this->info('💬 Digite uma instrução para o agente ou "exit" para sair.');
        $context = [];

        if ($this->confirm('Deseja carregar o histórico anterior como contexto?', false)) {
            $context = app(HistoryLogger::class)->getAll();
            $this->info('📚 Contexto carregado com ' . count($context) . ' interações anteriores.');
        }

        while (true) {
            $input = $this->ask('🧠 Você');

            if (trim(strtolower($input)) === 'exit') {
                $this->info('👋 Até a próxima!');
                break;
            }

            if (trim(strtolower($input)) === 'clear') {
                $this->context = [];
                $this->warn('🧼 Contexto limpo.');
                continue;
            }

            try {
                $response = app(DevChatAgent::class)->process($input, $context);

                // salva a conversa local
                app(HistoryLogger::class)->store($input, $response);
                $context[] = ['instruction' => $input, 'response' => $response];

                if (isset($response['files'])) {
                    $this->info("📁 Arquivos sugeridos:");
                    foreach ($response['files'] as $file) {
                        $this->line("- {$file['path']}");
                    }

                    if (!$this->option('dry-run') && $this->confirm('Deseja gravar os arquivos?', true)) {
                        foreach ($response['files'] as $file) {
                            if (!app(FileWriter::class)->isSafePath($file['path'])) {
                                $this->warn("⚠️ Caminho inseguro ignorado: {$file['path']}");
                                continue;
                            }

                            app(FileWriter::class)->write($file['path'], $file['content']);
                            $this->info("✅ Criado: {$file['path']}");
                        }
                    } else {
                        $this->warn("🚫 Arquivos não gravados.");
                    }
                } else {
                    $this->line("💬 Resposta:");
                    $this->line($response['text'] ?? '[sem conteúdo]');
                }
            } catch (\Throwable $e) {
                $this->error("💥 Erro: {$e->getMessage()}");
            }

        }

        $this->info('👋 Chat encerrado!');
    }

}