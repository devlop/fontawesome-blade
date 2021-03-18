@if ($shouldOutputSymbol)
<svg style="display: none;"><symbol id="{{ $id }}" viewBox="{{ $viewBox }}"><path fill="currentColor" d="{{ $path }}"/></symbol></svg>
@endif

<svg xmlns="http://www.w3.org/2000/svg"
    {{ $attributes->merge([
        'class' => $inlineSvgClasses,
        'aria-hidden' => 'true',
        'focusable' => 'false',
        'role' => 'img',
    ]) }}
>
    <use xlink:href="#{{ $id }}" />
    {{-- <path fill="currentColor" d="{{ $path }}"/> --}}
</svg>
