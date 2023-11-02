<div {{ $attributes
    ->class([
        'btn-toolbar',
    ])
    ->merge([
        'role' => 'toolbar',
    ]) }}>{{ $slot }}</div>
