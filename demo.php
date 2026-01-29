<?php

// MOCKS para evitar dependência do Laravel completo neste teste rápido
namespace Illuminate\Support\Facades {
    class File {
        public static function isDirectory($path) { return true; } // Mock: sempre true para teste
        public static function directories($path) { 
            // Mock: retorna alguns diretórios simulados
            return [
                $path . '/resources',
                $path . '/forms',
            ]; 
        }
        public static function exists($path) { return true; }
        public static function get($path) { return "# Skill Content for " . basename(dirname($path)); }
    }
}

namespace Illuminate\Support {
    class Str {
        public static function contains($haystack, $needles) {
            foreach ((array) $needles as $needle) {
                if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                    return true;
                }
            }
            return false;
        }
        public static function endsWith($haystack, $needles) {
            foreach ((array) $needles as $needle) {
                if ((string) $needle === substr($haystack, -strlen($needle))) return true;
            }
            return false;
        }
        public static function plural($value) { return $value . 's'; }
        public static function before($subject, $search) { return $search === '' ? $subject : explode($search, $subject)[0]; }
    }
}

// ---------------------------------------------------------

namespace {
    
    // Autoloader Simples para nossas classes
    spl_autoload_register(function ($class) {
        $prefix = 'AncostaDev\\FilamentPlanner\\';
        $base_dir = __DIR__ . '/src/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
    });


    // require __DIR__ . '/vendor/autoload.php'; // Removido pois usamos mocks


    if (!function_exists('base_path')) {
        function base_path($path = '') {
            return __DIR__ . ($path ? DIRECTORY_SEPARATOR . $path : $path);
        }
    }

    if (!function_exists('class_basename')) {
        function class_basename($class) {
            $class = is_object($class) ? get_class($class) : $class;
            return basename(str_replace('\\', '/', $class));
        }
    }

    use AncostaDev\FilamentPlanner\FilamentPlanner;

    // 1. Instanciar o Planner
    $planner = new FilamentPlanner();

    // 2. Simular um Blueprint (JSON que viria da IA)
    $blueprint = [
        'resources' => [
            [
                'name' => 'ProductResource',
                'model' => 'App\\Models\\Product',
                'form' => [
                    'schema' => [
                        [
                            'type' => 'TextInput',
                            'name' => 'name',
                            'label' => 'Product Name',
                            'required' => true,
                            'methods' => ['uppercase']
                        ],
                         [
                            'type' => 'Select',
                            'name' => 'status',
                            'methods' => [
                                ['options' => ['draft' => 'Draft', 'published' => 'Published']]
                            ]
                        ]
                    ]
                ],
                'table' => [
                    'columns' => [
                        [
                            'type' => 'TextColumn',
                            'name' => 'name',
                            'searchable' => true,
                            'sortable' => true
                        ]
                    ]
                ]
            ]
        ]
    ];

    // 3. Gerar arquivos
    echo "Gerando código...\n";
    try {
        $files = $planner->generate($blueprint);
        
        foreach ($files as $filename => $content) {
            echo "Arquivo Gerado: $filename\n";
            echo "---------------------------------------------------\n";
            echo $content;
            echo "\n---------------------------------------------------\n";
        }

        // 4. Testar recuperação de contexto
        echo "\nTestando Contexto para 'Create a user resource':\n";
        $context = $planner->getOptimizationContext("Create a user resource");
        echo "Tamanho do Contexto: " . strlen($context) . " caracteres\n";
        
        // Verificação básica se o contexto contém algo
        if (strlen($context) > 100) {
             echo "Contexto gerado com sucesso.\n";
        }

    } catch (\Exception $e) {
        echo "Erro: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString();
    }
}
