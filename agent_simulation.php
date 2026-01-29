<?php


// Custom Autoloader for Simulation Environment
spl_autoload_register(function ($class) {
    if (strpos($class, 'Illuminate\\') === 0 && !class_exists($class)) {
        // Very basic mock for Illuminate classes if needed, but we mostly mock Facades
        return; 
    }

    $prefix = 'AncostaDev\\FilamentPlanner\\';
    $base_dir = __DIR__ . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});


// MOCKS for Laravel Environment
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

// Simple Str Mock
class Str {
    public static function contains($haystack, $needles) {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) return true;
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
if (!class_exists('Illuminate\Support\Str')) {
    class_alias(Str::class, 'Illuminate\Support\Str');
}


// ---------------------------------------------------------
// SIMULATING THE APPLICATION BOOTSTRAP
// ---------------------------------------------------------

// Setup Container Mock
class ContainerMock {
    protected $bindings = [];
    public function singleton($abstract, $concrete) { $this->bindings[$abstract] = $concrete; }
    public function make($abstract) { return call_user_func($this->bindings[$abstract], $this); }
    public function runningInConsole() { return true; }
}

class ServiceProviderMock {
    protected $app;
    public function __construct($app) { $this->app = $app; }
    public function register() {}
    public function boot() {}
    public function commands($commands) {}
}

// Aliasing for our Service Provider to extend
if (!class_exists('Illuminate\Support\ServiceProvider')) {
    class_alias(ServiceProviderMock::class, 'Illuminate\Support\ServiceProvider');
}

$container = new ContainerMock();
class FacadeMock {
    protected static $app;
    public static function setFacadeApplication($app) { self::$app = $app; }
}
if (!class_exists('Illuminate\Support\Facades\Facade')) {
    class_alias(FacadeMock::class, 'Illuminate\Support\Facades\Facade');
}

class CommandMock {
    const SUCCESS = 0;
    const FAILURE = 1;
    protected $signature;
    protected $description;
    public function info($msg) { echo "ℹ️ INFO: $msg\n"; }
    public function error($msg) { echo "❌ ERROR: $msg\n"; }
    // We mock argument() via property injection or override in actual usage during test
    public function argument($key) { return 'agent_plan.json'; } 
}
if (!class_exists('Illuminate\Console\Command')) {
    class_alias(CommandMock::class, 'Illuminate\Console\Command');
}

// Facade::setFacadeApplication($container); // Not needed for our simple file mock strategy


// Bind File Facade (Simple Mock)
$container->singleton('files', function () {
    return new class {
        public function exists($path) { return file_exists($path); }
        public function get($path) { return file_get_contents($path); }
        public function put($path, $content) { echo "Writing to $path:\n" . substr($content, 0, 50) . "...\n"; }
        public function isDirectory($path) { return is_dir($path); }
        public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false) { 
             if (!is_dir($path)) mkdir($path, $mode, $recursive); 
        }
    };
});
class_alias(Illuminate\Support\Facades\File::class, 'File');

// Register our Service Provider
$provider = new AncostaDev\FilamentPlanner\FilamentPlannerServiceProvider($container);
$provider->register();

// Get the Planner instance
$planner = $container->make(AncostaDev\FilamentPlanner\FilamentPlanner::class);

// ---------------------------------------------------------
// SIMULATING THE AGENT WORKFLOW
// ---------------------------------------------------------

echo "🤖 Agent: I read SCHEMA.md. Preparing JSON...\n";

$mockAgentJson = json_encode([
    "resources" => [
        [
            "name" => "BlogPostResource", 
            "model" => "App\\Models\\Post", 
            "form" => [
                "schema" => [
                    ["type" => "TextInput", "name" => "title", "required" => true],
                    ["type" => "RichEditor", "name" => "content", "label" => "Post Body"]
                ]
            ],
            "table" => [ "columns" => [] ]
        ]
    ]
], JSON_PRETTY_PRINT);

file_put_contents('agent_plan.json', $mockAgentJson);
echo "🤖 Agent: Saved 'agent_plan.json'. Executing Command Logic...\n";

// Execute Command Logic (Manually triggering handle since we don't have full Artisan console)
$command = new AncostaDev\FilamentPlanner\Commands\BuildPlanCommand();

// Mocking argument retrieval
$reflection = new ReflectionClass($command);
// We can't easily mock 'argument' method of Command without a full console app mock from Orchestra Testbench.
// So we will just invoke the Planner directly as the Command `handle` method does.

echo "🖥️ System: Running Planner logic...\n";
try {
    $files = $planner->generate(json_decode($mockAgentJson, true));
    
    foreach ($files as $name => $code) {
        $destination = base_path("dist_agent/{$name}");
        if (!is_dir(dirname($destination))) mkdir(dirname($destination), 0777, true);
        file_put_contents($destination, $code);
        echo "✅ Created: dist_agent/$name\n";
    }
    echo "🎉 Success! The Agent flow works.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
