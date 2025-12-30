@php
    $changeColors = [
        'up' => 'text-green-500',
        'down' => 'text-red-500',
        'neutral' => 'text-slate-400',
    ];
    $changeColor = $changeColors[$changeType] ?? $changeColors['neutral'];
@endphp

<div class="bg-white p-5 rounded-xl shadow-sm border {{ $highlight ? 'border-red-100' : 'border-slate-100' }} relative overflow-hidden group hover:shadow-lg transition-all duration-150">
    @if($highlight)
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full z-0 group-hover:scale-125 transition-transform duration-150"></div>
    @endif
    <div class="flex justify-between items-start z-10 relative">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide {{ $highlight ? 'text-red-500' : 'text-slate-500' }}">
                {{ $title }}
            </p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $value }}</h3>
            <p class="text-xs mt-2 font-medium {{ $changeColor }}">
                @if($changeType === 'up')
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i>
                @elseif($changeType === 'down')
                    <i class="fa-solid fa-arrow-trend-down mr-1"></i>
                @endif
                {{ $change }}
            </p>
        </div>
        <div class="{{ $iconBg }} p-3 rounded-lg {{ $iconColor }}">
            <i class="{{ $icon }} text-xl"></i>
        </div>
    </div>
</div>
