<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 rounded-full font-semibold text-xs uppercase tracking-widest glass-badge bg-white/10 text-white border border-white/20 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-0 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
