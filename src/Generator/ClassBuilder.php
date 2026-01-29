<?php

namespace AncostaDev\FilamentPlanner\Generator;

class ClassBuilder
{
    protected string $namespace;
    protected array $imports = [];
    protected string $className;
    protected string $extends;
    protected array $methods = [];

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function addImport(string $class): self
    {
        if (! in_array($class, $this->imports)) {
            $this->imports[] = $class;
        }
        return $this;
    }

    public function setClassName(string $className): self
    {
        $this->className = $className;
        return $this;
    }

    public function setExtends(string $extends): self
    {
        $this->extends = $extends;
        return $this;
    }

    public function addMethod(string $name, string $returnType, string $body, string $visibility = 'public', bool $static = false): self
    {
        $this->methods[] = [
            'name' => $name,
            'returnType' => $returnType,
            'body' => $body,
            'visibility' => $visibility,
            'static' => $static,
        ];
        return $this;
    }

    public function build(): string
    {
        $code = "<?php\n\n";
        $code .= "namespace {$this->namespace};\n\n";

        sort($this->imports);
        foreach ($this->imports as $import) {
            $code .= "use {$import};\n";
        }
        $code .= "\n";

        $extendsStr = $this->extends ? " extends {$this->extends}" : "";
        $code .= "class {$this->className}{$extendsStr}\n{\n";

        foreach ($this->methods as $method) {
            $staticStr = $method['static'] ? ' static' : '';
            $code .= "    {$method['visibility']}{$staticStr} function {$method['name']}({$method['returnType']})\n";
            $code .= "    {\n";
            $code .= $method['body'];
            $code .= "    }\n\n";
        }

        $code .= "}\n";

        return $code;
    }
}
