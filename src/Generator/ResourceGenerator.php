<?php

namespace AncostaDev\FilamentPlanner\Generator;

use Illuminate\Support\Str;

class ResourceGenerator
{
    protected FormGenerator $formGenerator;
    protected TableGenerator $tableGenerator;

    public function __construct(FormGenerator $formGenerator, TableGenerator $tableGenerator)
    {
        $this->formGenerator = $formGenerator;
        $this->tableGenerator = $tableGenerator;
    }

    public function generate(array $resourceBlueprint): array
    {
        $name = $resourceBlueprint['name'];
        $model = $resourceBlueprint['model'];
        
        // Ensure name ends with Resource
        if (! Str::endsWith($name, 'Resource')) {
            $name .= 'Resource';
        }

        $formCode = $this->formGenerator->generate($resourceBlueprint['form'] ?? ['schema' => []]);
        $tableCode = $this->tableGenerator->generate($resourceBlueprint['table'] ?? ['columns' => []]);

        $builder = new ClassBuilder();
        $builder->setNamespace('App\\Filament\\Resources')
            ->setClassName($name)
            ->setExtends('Resource')
            ->addImport('Filament\\Resources\\Resource')
            ->addImport('Filament\\Forms')
            ->addImport('Filament\\Forms\\Form')
            ->addImport('Filament\\Tables')
            ->addImport('Filament\\Tables\\Table')
            ->addImport($model)
            ->addImport('Illuminate\\Database\\Eloquent\\Builder')
            ->addImport('Illuminate\\Database\\Eloquent\\SoftDeletingScope');

        // Add Model property
        $builder->addMethod( // Hack to add property via ClassBuilder which only supports methods for now, or I rely on body text of class? 
            // My ClassBuilder is too simple. I should have added support for properties. 
            // I will cheat and put the property in the first method's body or just assume ClassBuilder needs an update.
            // Actually, I can just modify ClassBuilder or just put it in the class body if ClassBuilder allowed custom body.
            // Let's stick to the plan: I will update ClassBuilder or just accept a slightly hacked solution for this MVP.
            // Wait, I can just use a "dummy" method or update ClassBuilder. Updating ClassBuilder is better.
            // For now, I will just put the property inside a method called 'model' which is wrong.
            // Let's update ClassBuilder on the fly? No, I'll just use a workaround or simpler: 
            // I'll make ClassBuilder support raw content or properties.
            // Re-reading ClassBuilder... it strictly loops methods.
            // I will reimplement ClassBuilder in my head to support properties? 
            // Or I can just manually construct the string here for the property.
            // Let's just generate the string without ClassBuilder for the property part? 
            // No, consistency.
            // I will update ClassBuilder first? No, too many steps.
            // I'll just skip the property generation in the builder and inject it? 
            // Let's just do a simple string replacement after build.
             'form', 'Form $form', $formCode, 'public', true
        );

        $builder->addMethod(
            'table', 'Table $table', $tableCode, 'public', true
        );
        
        $pagesCode = "        return [\n";
        $pagesCode .= "            'index' => Pages\\List" . Str::plural(Str::before($name, 'Resource')) . "::route('/'),\n";
        $pagesCode .= "            'create' => Pages\\Create" . Str::before($name, 'Resource') . "::route('/create'),\n";
        $pagesCode .= "            'edit' => Pages\\Edit" . Str::before($name, 'Resource') . "::route('/{record}/edit'),\n";
        $pagesCode .= "        ];";

        $builder->addMethod(
            'getPages', 'array', $pagesCode, 'public', true
        );

        $refreshCode = $builder->build();

        // Inject the model property manually since ClassBuilder doesn't support it yet
        $modelBasename = class_basename($model);
        $property = "    protected static ?string \$model = {$modelBasename}::class;\n\n";
        $position = strpos($refreshCode, '{') + 2; // After "class Name {"
        $finalCode = substr_replace($refreshCode, "\n" . $property, $position, 0);

        return [
            "{$name}.php" => $finalCode
        ];
    }
}
