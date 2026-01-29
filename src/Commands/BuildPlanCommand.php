<?php

namespace AncostaDev\FilamentPlanner\Commands;

use Illuminate\Console\Command;
use AncostaDev\FilamentPlanner\FilamentPlanner;
use Illuminate\Support\Facades\File;

class BuildPlanCommand extends Command
{
    protected $signature = 'filament:build-plan {path : Path to the blueprint JSON file}';

    protected $description = 'Generate Filament v5 code from a Blueprint JSON file';

    public function handle(FilamentPlanner $planner)
    {
        $path = $this->argument('path');

        if (! File::exists($path)) {
            $this->error("Blueprint file not found: {$path}");
            return Command::FAILURE;
        }

        $content = File::get($path);
        $blueprint = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON in blueprint file: " . json_last_error_msg());
            return Command::FAILURE;
        }

        $this->info("Generating Filament code from blueprint...");

        try {
            $files = $planner->generate($blueprint);

            foreach ($files as $filename => $code) {
                // In a real package, we would write to actual paths (app/Filament/Resources/...)
                // For now, let's output to a 'dist' folder or just log it
                $destination = base_path("dist/{$filename}");
                $directory = dirname($destination);
                
                if (! File::isDirectory($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                File::put($destination, $code);
                $this->info("Generated: {$destination}");
            }

            $this->info("Build completed successfully!");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error generating code: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
