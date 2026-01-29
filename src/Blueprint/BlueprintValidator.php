<?php

namespace AncostaDev\FilamentPlanner\Blueprint;

class BlueprintValidator
{
    public function validate(array $blueprint): bool
    {
        if (! isset($blueprint['resources']) || ! is_array($blueprint['resources'])) {
           return false;
        }

        foreach ($blueprint['resources'] as $resource) {
            if (! isset($resource['name']) || ! isset($resource['model'])) {
                return false;
            }
        }

        return true;
    }
}
