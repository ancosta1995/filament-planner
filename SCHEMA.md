# Filament Planner Schema Definition

> **For AI Agents (Antigravity/Cursor/Windsurf)**: When a user asks you to create Filament resources, generate a JSON file following this schema and run `php artisan filament:build-plan path/to/file.json`.

## JSON Structure

The root object must contain a `resources` array.

```json
{
  "resources": [
    {
      "name": "ResourceName", 
      "model": "App\\Models\\ModelName",
      "form": {
        "schema": [
          {
            "type": "TextInput", // Must match Filament Forms component class name
            "name": "field_name",
            "required": true,
            "label": "Custom Label",
            "methods": [
               "email", 
               "unique",
               {"default": ["value"]} // Use object for methods with arguments, e.g. ->default('value')
            ]
          }
        ]
      },
      "table": {
        "columns": [
          {
            "type": "TextColumn", // Must match Filament Tables column class name
            "name": "column_name",
            "searchable": true,
            "sortable": true,
            "label": "Custom Label",
            "methods": [
                "date",
                {"dateTime": ["d/m/Y H:i"]}
            ]
          }
        ]
      }
    }
  ]
}
```

## Component Reference

The Planner supports ANY Filament component that follows the standard `make()` pattern. Below is a list of commonly used v5 components to prioritize, but you are not limited to this list if the component exists in the user's Filament version.

### Form Components (`Filament\Forms\Components`)
- **Text**: `TextInput`, `Textarea`, `RichEditor`, `MarkdownEditor`
- **Select/Choice**: `Select`, `Checkbox`, `CheckboxList`, `Radio`, `Toggle`
- **Date/Time**: `DatePicker`, `DateTimePicker`, `TimePicker`
- **Upload**: `FileUpload`
- **Layout**: `Section`, `Grid`, `Group` (Note: Layout components may require nested `schema` property in the JSON, which the Planner handles recursively)

### Table Columns (`Filament\Tables\Columns`)
- **Text**: `TextColumn`
- **Visual**: `ImageColumn`, `IconColumn`, `ColorColumn`
- **Status**: `BooleanColumn`, `SelectColumn`, `ToggleColumn`

## Instructions for Agents
1.  **Analyze** the user request.
2.  **Map** the request to the Schema above.
    - If a specific Filament method configuration is needed (e.g. `->relationship('author', 'name')`), add it to the `"methods"` array as `{"relationship": ["author", "name"]}`.
3.  **Create** a temporary file e.g., `plan.json`.
4.  **Execute** `php artisan filament:build-plan plan.json`.
