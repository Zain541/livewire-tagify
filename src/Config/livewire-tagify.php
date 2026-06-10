<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Frontend Library
    |--------------------------------------------------------------------------
    |
    | The CSS framework used for rendering the tag dropdown and color picker.
    | Supported: "tailwind", "bootstrap", "none"
    |
    */

    'frontend_library' => 'tailwind',

    /*
    |--------------------------------------------------------------------------
    | Frontend Views
    |--------------------------------------------------------------------------
    |
    | Maps each frontend library to its corresponding Blade view. You can
    | override these to point to your own custom dropdown views.
    |
    */

    'frontend_views' => [
        'tailwind' => 'livewire-tagify::livewire.tailwind',
        'bootstrap' => 'livewire-tagify::livewire.bootstrap',
        'none' => 'livewire-tagify::livewire.none',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tag Colors (Light Mode)
    |--------------------------------------------------------------------------
    |
    | Named category colors available in the color picker. Keys are used as
    | human-readable labels (e.g. shown in button titles). Values must be
    | valid hex codes. You can add, remove, or reorder these freely.
    |
    */

    'colors' => [
        'coral' => '#E8634A',
        'amber' => '#D4930D',
        'emerald' => '#1A9A6B',
        'ocean' => '#2B7CD1',
        'violet' => '#7C4DDB',
        'rose' => '#D44B8A',
        'slate' => '#5A6978',
        'teal' => '#0E8C8C',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Tag Color (Light Mode)
    |--------------------------------------------------------------------------
    |
    | The color applied to new tags when no color is explicitly chosen.
    | Must be a valid hex code.
    |
    */

    'default_color' => '#2B7CD1',

    /*
    |--------------------------------------------------------------------------
    | Tag Colors (Dark Mode)
    |--------------------------------------------------------------------------
    |
    | Brighter variants of each category color optimized for dark backgrounds.
    | Keys should match the light mode 'colors' array. When dark mode is
    | active, these replace the light palette in the color picker and
    | tag rendering. Leave empty to use the light colors in dark mode.
    |
    */

    'dark_colors' => [
        'coral' => '#F0816C',
        'amber' => '#E8AD3B',
        'emerald' => '#34D399',
        'ocean' => '#60A5FA',
        'violet' => '#A78BFA',
        'rose' => '#F472B6',
        'slate' => '#94A3B8',
        'teal' => '#2DD4BF',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Tag Color (Dark Mode)
    |--------------------------------------------------------------------------
    |
    | The default color for new tags when dark mode is active.
    | Falls back to 'default_color' if not set.
    |
    */

    'dark_default_color' => '#60A5FA',

    /*
    |--------------------------------------------------------------------------
    | Theme Mode
    |--------------------------------------------------------------------------
    |
    | Controls the light/dark appearance of the tag input.
    |
    | Supported: "light", "dark", "auto"
    |   - "light" — always light background
    |   - "dark"  — always dark background
    |   - "auto"  — follows the user's OS/browser prefers-color-scheme
    |
    */

    'theme_mode' => 'light',

    /*
    |--------------------------------------------------------------------------
    | Theme Direction (Visual Style)
    |--------------------------------------------------------------------------
    |
    | The overall visual direction for the tag input UI.
    |
    | Supported: "refined", "bold", "glass"
    |   - "refined" — clean lines, subtle tinted backgrounds, color dot indicator
    |   - "bold"    — full-color tag backgrounds, auto-contrast text, chunky feel
    |   - "glass"   — frosted/translucent layers, luminous accents, backdrop blur
    |
    */

    'theme_direction' => 'refined',

    /*
    |--------------------------------------------------------------------------
    | Shape
    |--------------------------------------------------------------------------
    |
    | The border-radius style applied to tags and the input field.
    |
    | Supported: "pill", "rounded", "square"
    |   - "pill"    — fully rounded ends (tags 100px, input 12–14px)
    |   - "rounded" — softly rounded corners (tags 8–10px, input 10–12px)
    |   - "square"  — minimal rounding (tags 4–6px, input 6–8px)
    |
    | Exact values vary slightly by direction (refined/bold/glass).
    |
    */

    'tag_shape' => 'square',

    /*
    |--------------------------------------------------------------------------
    | Input Padding
    |--------------------------------------------------------------------------
    |
    | The internal padding of the tag input container. Accepts any valid CSS
    | padding value (e.g. "6px 8px", "8px 12px 8px 10px").
    |
    | Set to null to use the direction's default padding.
    |
    */

    'input_padding' => null,

    /*
    |--------------------------------------------------------------------------
    | Load Google Fonts
    |--------------------------------------------------------------------------
    |
    | When true, the package loads the appropriate Google Font for the active
    | direction (DM Sans for refined, Space Grotesk for bold, Inter for glass).
    | Set to false if you already load these fonts in your app, or want to
    | use your own font stack.
    |
    */

    'load_fonts' => true,

    /*
    |--------------------------------------------------------------------------
    | Maximum Tag Length
    |--------------------------------------------------------------------------
    |
    | The maximum number of characters allowed per tag value.
    |
    */

    'max_tag_length' => 255,

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Toggle individual CRUD operations on tags. When set to false, the
    | corresponding action is disabled for all users regardless of gates.
    |
    */

    'permissions' => [
        'create' => true,
        'read' => true,
        'update' => true,
        'delete' => true,
        'change_color' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Gates
    |--------------------------------------------------------------------------
    |
    | Optional Laravel Gate names for fine-grained authorization. When a gate
    | name is provided, it will be checked before allowing the action. Set
    | to null to skip gate checks (the 'permissions' toggle still applies).
    |
    | Example: 'create' => 'create-tags' will call Gate::allows('create-tags')
    |
    */

    'permission_gates' => [
        'create' => null,
        'read' => null,
        'update' => null,
        'delete' => null,
        'change_color' => null,
    ],
];
