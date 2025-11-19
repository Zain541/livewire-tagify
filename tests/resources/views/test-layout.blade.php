@php use Codekinz\LivewireTagify\Tests\Support\TestModel;use Codekinz\LivewireTagify\Tests\Support\TestTagComponent; @endphp

<!DOCTYPE html>
<html>
<head>
    <!-- The basics needed for your component -->
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
    <title></title>
</head>
<body>

{{--@livewire(TestTagComponent::class, [--}}
{{--    'modelId' => 1, --}}
{{--    'modelClass' => TestModel::class,--}}
{{--    'tagType' => 'firstType'--}}
{{--])--}}

@livewire('test-tag-component', [
    'modelId' => 1, 
    'modelClass' => TestModel::class,
    'tagType' => 'firstType'
])

@livewireScripts
</body>
</html>