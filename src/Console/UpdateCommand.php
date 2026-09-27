<?php

declare(strict_types=1);

namespace Instruckt\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class UpdateCommand extends Command
{
    protected $signature = 'instruckt:update';

    protected $description = 'Update the Instruckt MCP configuration for supported agents';

    /**
     * @return list<array{path: string, key: string, name: string}>
     */
    private function mcpFiles(): array
    {
        return [
            ['path' => '.mcp.json', 'key' => 'mcpServers', 'name' => 'Claude Code'],
            ['path' => '.cursor/mcp.json', 'key' => 'mcpServers', 'name' => 'Cursor'],
            ['path' => '.vscode/mcp.json', 'key' => 'servers', 'name' => 'GitHub Copilot'],
            ['path' => 'opencode.json', 'key' => 'mcp', 'name' => 'OpenCode'],
        ];
    }

    public function handle(): int
    {
        $updated = false;
        $serverConfig = [
            'command' => 'php',
            'args' => [base_path('artisan'), 'mcp:start', 'instruckt'],
        ];

        foreach ($this->mcpFiles() as $file) {
            $path = base_path($file['path']);

            if (! File::exists($path)) {
                File::ensureDirectoryExists(dirname($path));
                File::put($path, json_encode([$file['key'] => ['instruckt' => $serverConfig]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
                $updated = true;
                $this->components->twoColumnDetail($file['path'], '<fg=green>created</>');
                continue;
            }

            $contents = File::get($path);
            $config = json_decode($contents, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->components->warn("Could not parse {$file['path']} — skipping.");
                continue;
            }

            $config[$file['key']] ??= [];
            $config[$file['key']]['instruckt'] = $serverConfig;

            File::put($path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
            $updated = true;
            $this->components->twoColumnDetail($file['path'], '<fg=green>updated</>');
        }

        if (! $updated) {
            $this->warn('No supported MCP config files were found.');

            return self::FAILURE;
        }

        $this->info('Instruckt MCP config updated.');

        return self::SUCCESS;
    }
}
