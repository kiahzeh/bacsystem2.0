@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'glassmorphism-input rounded-md shadow-sm']) !!}>
