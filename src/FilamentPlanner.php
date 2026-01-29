<?php

namespace AncostaDev\FilamentPlanner;

use AncostaDev\FilamentPlanner\KnowledgeBase\DocIndex;
use AncostaDev\FilamentPlanner\KnowledgeBase\ContextBuilder;
use AncostaDev\FilamentPlanner\Blueprint\BlueprintSchema;
use AncostaDev\FilamentPlanner\Blueprint\BlueprintValidator;
use AncostaDev\FilamentPlanner\Generator\FormGenerator;
use AncostaDev\FilamentPlanner\Generator\TableGenerator;
use AncostaDev\FilamentPlanner\Generator\ResourceGenerator;

class FilamentPlanner
{
    protected ContextBuilder $contextBuilder;
    protected ResourceGenerator $resourceGenerator;
    protected BlueprintValidator $blueprintValidator;

    public function __construct()
    {
        $this->contextBuilder = new ContextBuilder(new DocIndex());
        $this->resourceGenerator = new ResourceGenerator(new FormGenerator(), new TableGenerator());
        $this->blueprintValidator = new BlueprintValidator();
    }

    public function getOptimizationContext(string $userRequest): string
    {
        $context = $this->contextBuilder->buildContext($userRequest);
        $prompt = BlueprintSchema::getPromptTemplate();
        
        return $prompt . "\n\n" . "USER REQUEST: " . $userRequest . "\n\n" . $context;
    }

    public function generate(array $blueprint): array
    {
        if (! $this->blueprintValidator->validate($blueprint)) {
            throw new \Exception("Invalid Blueprint Schema");
        }

        $files = [];
        foreach ($blueprint['resources'] as $resource) {
            $generated = $this->resourceGenerator->generate($resource);
            $files = array_merge($files, $generated);
        }

        return $files;
    }
}
