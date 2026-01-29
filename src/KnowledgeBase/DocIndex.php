<?php

namespace AncostaDev\FilamentPlanner\KnowledgeBase;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocIndex
{
    protected string $docsPath;

    public function __construct(string $docsPath = null)
    {
        $this->docsPath = $docsPath ?? base_path('filament-docs');
    }

    public function scan(): array
    {
        if (! File::isDirectory($this->docsPath)) {
            return [];
        }

        $skills = [];
        $directories = File::directories($this->docsPath);

        foreach ($directories as $directory) {
            $skillFile = $directory . '/SKILL.md';
            if (File::exists($skillFile)) {
                $name = basename($directory);
                $content = File::get($skillFile);
                $title = $this->extractTitle($content);
                
                $skills[$name] = [
                    'name' => $name,
                    'path' => $skillFile,
                    'title' => $title,
                    'is_priority' => $this->isPriority($name),
                ];
            }
        }

        return $skills;
    }

    protected function extractTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        return 'Untitled Skill';
    }

    protected function isPriority(string $name): bool
    {
        // Based on README.md priority list
        return in_array($name, ['resources', 'forms', 'tables', 'actions', 'introduction']);
    }
}
