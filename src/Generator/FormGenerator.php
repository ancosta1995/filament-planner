<?php

namespace AncostaDev\FilamentPlanner\Generator;

class FormGenerator
{
    public function generate(array $formSchema): string
    {
        $code = "        return \$form\n";
        $code .= "            ->schema([\n";

        foreach ($formSchema['schema'] as $component) {
            $code .= $this->generateComponent($component, 4);
        }

        $code .= "            ]);";
        return $code;
    }

    protected function generateComponent(array $component, int $indent): string
    {
        $indentStr = str_repeat(' ', $indent * 4);
        $type = $component['type'];
        $name = $component['name'];

        $code = "{$indentStr}{$type}::make('{$name}')";
        
        if (isset($component['methods']) && is_array($component['methods'])) {
            foreach ($component['methods'] as $method) {
                // Determine if method has arguments or is just a flag
                if (is_array($method)) {
                   $methodName = array_key_first($method);
                   $methodArgs = implode(', ', array_map(fn($v) => "'$v'", $method[$methodName]));
                   $code .= "\n{$indentStr}    ->{$methodName}({$methodArgs})";
                } else {
                    $code .= "\n{$indentStr}    ->{$method}()";
                }
            }
        }
        
        // Handle common attributes directly if not in methods array
        if (isset($component['required']) && $component['required']) {
             $code .= "\n{$indentStr}    ->required()";
        }
        if (isset($component['label'])) {
             $code .= "\n{$indentStr}    ->label('{$component['label']}')";
        }

        $code .= ",\n";
        return $code;
    }
}
