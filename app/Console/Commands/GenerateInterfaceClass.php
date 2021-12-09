<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateInterfaceClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {interface}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate interface class.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prepare the target path by reading '/' in inputted name
     * 
     * @return string
     */
    private function targetPath(string $filename)
    {
        $targetPath = $rootPath = app_path('Interfaces');
        if (! file_exists($rootPath)) {
            shell_exec('mkdir ' . $rootPath);
        }

        if (string_contains($filename, '/')) {
            $explode = explode('/', $filename);
            array_pop($explode);
            $path = implode('/', $explode);

            $targetPath .= $path;
        }

        return $targetPath;
    }

    /**
     * Prepare namespace
     * 
     * @return string
     */
    private function prepareNamespace(string $filename)
    {
        //
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $interface = $this->argument('interface');

        
    }
}
