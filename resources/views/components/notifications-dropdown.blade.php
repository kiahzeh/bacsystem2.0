<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50">
        
        <div class="py-2">
            <div class="px-4 py-2 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            </div>
            
            <div class="max-h-64 overflow-y-auto">
                @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
                    <div class="px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if(!$notification->read_at)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                @else
                                    <div class="w-2 h-2 bg-gray-300 rounded-full mt-2"></div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                                @if(isset($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" 
                                       class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-block">
                                        View Details →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                        No notifications
                    </div>
                @endforelse
            </div>
            
            @if(auth()->user()->notifications->count() > 10)
                <div class="px-4 py-2 border-t border-gray-200">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                        View all notifications
                    </a>
                </div>
            @endif
        </div>
    </div>
</div> 