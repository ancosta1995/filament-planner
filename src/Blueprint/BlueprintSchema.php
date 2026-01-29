<?php

namespace AncostaDev\FilamentPlanner\Blueprint;

class BlueprintSchema
{
    public static function getPromptTemplate(): string
    {
        return <<<'EOT'
You are a Filament v5 Architect.
Your goal is to output a JSON Blueprint that describes the Filament resources, forms, and tables needed.
Do not output PHP code directly. Output only the valid JSON Blueprint.

Structure your JSON as follows:
{
    "resources": [
        {
            "name": "ResourceName", 
            "model": "App\\Models\\ModelName",
            "icon": "heroicon-o-user",
            "form": {
                "schema": [
                    {
                        "type": "TextInput",
                        "name": "field_name",
                        "label": "Field Label",
                        "required": true,
                        // ... other specific methods like email(), numeric(), etc.
                        "methods": ["email", "unique"] 
                    }
                ]
            },
            "table": {
                "columns": [
                    {
                        "type": "TextColumn",
                        "name": "field_name",
                        "label": "Column Label",
                        "searchable": true,
                        "sortable": true
                    }
                ]
            }
        }
    ]
}

Ensure all types match valid Filament v5 components (e.g., TextInput, Select, TextColumn, BooleanColumn).
EOT;
    }
}
