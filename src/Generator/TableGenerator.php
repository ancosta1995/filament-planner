<?php

namespace AncostaDev\FilamentPlanner\Generator;

class TableGenerator
{
    public function generate(array $tableSchema): string
    {
        $code = "        return \$table\n";
        $code .= "            ->columns([\n";

        foreach ($tableSchema['columns'] as $column) {
            $code .= $this->generateColumn($column, 4);
        }

        $code .= "            ])\n";
        $code .= "            ->filters([\n";
        $code .= "                //\n";
        $code .= "            ])\n";
        $code .= "            ->actions([\n";
        $code .= "                Tables\\Actions\\EditAction::make(),\n";
        $code .= "            ])\n";
        $code .= "            ->bulkActions([\n";
        $code .= "                Tables\\Actions\\BulkActionGroup::make([\n";
        $code .= "                    Tables\\Actions\\DeleteBulkAction::make(),\n";
        $code .= "                ]),\n";
        $code .= "            ]);";
        return $code;
    }

    protected function generateColumn(array $column, int $indent): string
    {
        $indentStr = str_repeat(' ', $indent * 4);
        $type = $column['type'];
        $name = $column['name'];

        $code = "{$indentStr}Tables\\Columns\\{$type}::make('{$name}')";

        // Handle common attributes
        if (isset($column['searchable']) && $column['searchable']) {
             $code .= "\n{$indentStr}    ->searchable()";
        }
        if (isset($column['sortable']) && $column['sortable']) {
             $code .= "\n{$indentStr}    ->sortable()";
        }
        if (isset($column['label'])) {
             $code .= "\n{$indentStr}    ->label('{$column['label']}')";
        }

        $code .= ",\n";
        return $code;
    }
}
