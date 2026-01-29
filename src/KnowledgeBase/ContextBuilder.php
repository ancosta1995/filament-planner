<?php

namespace AncostaDev\FilamentPlanner\KnowledgeBase;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ContextBuilder
{
    protected DocIndex $docIndex;

    public function __construct(DocIndex $docIndex)
    {
        $this->docIndex = $docIndex;
    }

    public function buildContext(string $query): string
    {
        $skills = $this->docIndex->scan();
        $relevantSkills = [];

        // Always include high-priority skills for context
        foreach ($skills as $skill) {
            if ($skill['is_priority']) {
                $relevantSkills[$skill['name']] = $skill;
            }
        }

        // Add skills based on simple keyword matching
        foreach ($skills as $name => $skill) {
            if (Str::contains(strtolower($query), strtolower($name))) {
                $relevantSkills[$name] = $skill;
            }
        }

        $context = "You are an expert Filament v5 developer. Use the following documentation context to answer the user request.\n\n";

        foreach ($relevantSkills as $skill) {
            $context .= "## Skill: {$skill['title']} ({$skill['name']})\n";
            $context .= File::get($skill['path']) . "\n\n";
        }

        return $context;
    }
}
