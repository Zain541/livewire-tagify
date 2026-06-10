@php use Codekinz\LivewireTagify\Tests\Support\TestModel; @endphp

<!DOCTYPE html>
<html>
<head>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <script src="/vendor/livewire-tagify/livewire-tagify.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
    <title></title>
</head>
<body>

@livewire('test-tag-component', [
    'modelId' => 1, 
    'modelClass' => TestModel::class,
    'tagType' => 'firstType'
])

@livewireScripts
</body>
</html>
