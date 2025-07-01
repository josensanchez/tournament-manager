<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallPreCommit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:pre-commit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing pre-commit hooks...');

        $hooks = [
            'pre-commit' => 'sail bin duster',
        ];

        foreach ($hooks as $hook => $command) {
            $hookPath = base_path('.git/hooks/' . $hook);
            file_put_contents($hookPath, "#!/bin/sh\n$command\n");
            chmod($hookPath, 0755);
            $this->info("Installed $hook hook.");
        }

        $this->info('Pre-commit hooks installed successfully.');
    }
}
