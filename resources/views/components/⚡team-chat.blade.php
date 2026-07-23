<?php

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

new class extends Component
{
    public int $activeChannel = 1;
    public string $body = '';
    public ?string $image_path = null;

    // Tracks the highest message ID seen for each channel
    public array $lastSeenIds = [1 => 0, 2 => 0, 3 => 0];

    public function mount(): void
    {
        // When the dashboard first loads, mark existing messages as read
        foreach ([1, 2, 3] as $id) {
            $this->lastSeenIds[$id] = Message::where('channel_id', $id)->max('id') ?? 0;
        }
    }

    /**
     * Runs automatically right before the component renders on every lifecycle step (including polls)
     */
    public function rendering(): void
    {
        // Continuously mark the active channel's newest messages as read
        $this->lastSeenIds[$this->activeChannel] = Message::where('channel_id', $this->activeChannel)->max('id') ?? 0;
    }

    #[Computed]
    public function messages()
    {
        return Message::query()
            ->with('user')
            ->where('channel_id', $this->activeChannel)
            ->latest()
            ->limit(50)
            ->get()
            ->reverse();
    }

    // Helper method to grab unread counts for the frontend tabs
    public function getUnreadCount(int $channelId): int
    {
        if ($channelId === $this->activeChannel) {
            return 0;
        }

        return Message::where('channel_id', $channelId)
            ->where('id', '>', $this->lastSeenIds[$channelId] ?? 0)
            ->count();
    }

    public function sendMessage(): void
    {
        $this->validate([
            'body' => 'required|string|max:1000',
        ], [
            'body.required' => 'Message body is required.',
        ]);

        $messageData = [
            'user_id' => Auth::id(),
            'channel_id' => $this->activeChannel,
            'body' => e($this->body),
        ];

        if ($this->activeChannel === 3 && $this->image_path) {
            $messageData['image_path'] = $this->image_path;
        }

        Message::create($messageData);

        $this->reset(['body', 'image_path']);

        $this->dispatch('message-sent');
    }

    public function setChannel(int $channelId): void
    {
        // Catch up on current channel before switching away
        $this->lastSeenIds[$this->activeChannel] = Message::where('channel_id', $this->activeChannel)->max('id') ?? 0;

        $this->activeChannel = $channelId;

        // Catch up on new channel immediately upon entry
        $this->lastSeenIds[$channelId] = Message::where('channel_id', $channelId)->max('id') ?? 0;
    }
}; ?>

<div wire:poll.5s class="flex flex-col h-full bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
    <!-- Channel Tabs -->
    <div class="flex border-b border-neutral-200 dark:border-zinc-800 p-1 bg-neutral-50 dark:bg-zinc-950">
        <button
            wire:key="channel-tab-1"
            wire:click="setChannel(1)"
            type="button"
            @class([
                'flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors relative',
                'bg-white dark:bg-zinc-800 shadow-sm text-neutral-900 dark:text-zinc-100' => $activeChannel === 1,
                'text-neutral-500 dark:text-zinc-400 hover:text-neutral-700 dark:hover:text-zinc-200' => $activeChannel !== 1,
            ])
        >
            <span>War Room</span>
            <span>⚔️</span>
            @if($this->getUnreadCount(1) > 0)
                <span class="flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-neutral-400 dark:bg-zinc-600 text-[10px] font-bold text-white dark:text-zinc-200">
                    {{ $this->getUnreadCount(1) }}
                </span>
            @endif
        </button>

        <button
            wire:key="channel-tab-2"
            wire:click="setChannel(2)"
            type="button"
            @class([
                'flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors relative overflow-hidden',
                'bg-white dark:bg-zinc-800 shadow-sm text-neutral-900 dark:text-zinc-100' => $activeChannel === 2,
                'text-neutral-500 dark:text-zinc-400 hover:text-neutral-700 dark:hover:text-zinc-200' => $activeChannel !== 2,
                'animate-pulse ring-2 ring-red-500 ring-inset' => $this->getUnreadCount(2) > 0 && $activeChannel !== 2,
            ])
        >
            @if($this->getUnreadCount(2) > 0 && $activeChannel !== 2)
                <span class="absolute inset-0 bg-red-500/10 dark:bg-red-500/20"></span>
            @endif
            <span class="relative z-10">SOS</span>
            <span class="relative z-10">🚨</span>
            @if($this->getUnreadCount(2) > 0 && $activeChannel !== 2)
                <span class="absolute top-1 right-1 flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                <span class="relative z-10 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">
                    {{ $this->getUnreadCount(2) }}
                </span>
            @endif
        </button>

        <button
            wire:key="channel-tab-3"
            wire:click="setChannel(3)"
            type="button"
            @class([
                'flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors',
                'bg-white dark:bg-zinc-800 shadow-sm text-neutral-900 dark:text-zinc-100' => $activeChannel === 3,
                'text-neutral-500 dark:text-zinc-400 hover:text-neutral-700 dark:hover:text-zinc-200' => $activeChannel !== 3,
            ])
        >
            <span>Vibe Ward</span>
            <span>🎉</span>
            @if($this->getUnreadCount(3) > 0)
                <span class="flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-blue-500 text-[10px] font-bold text-white">
                    {{ $this->getUnreadCount(3) }}
                </span>
            @endif
        </button>
    </div>

    <!-- Messages Container with Smart Mobile Scroll -->
    <div
        x-data="{
            scrollToBottom(force = false) {
                $nextTick(() => {
                    const isNearBottom = $el.scrollHeight - $el.scrollTop - $el.clientHeight < 120;
                    if (force || isNearBottom) {
                        $el.scrollTop = $el.scrollHeight;
                    }
                });
            },
            init() {
                this.scrollToBottom(true);
                const observer = new MutationObserver(() => this.scrollToBottom(false));
                observer.observe($el, { childList: true, subtree: true });
            }
        }"
        @message-sent.window="scrollToBottom(true)"
        class="flex-1 overflow-y-auto p-4 space-y-4 min-h-[300px] max-h-[500px]"
    >
        @foreach($this->messages as $message)
            <div
                wire:key="msg-{{ $message->id }}"
                @class([
                    'flex flex-col',
                    'items-end' => $message->user_id === auth()->id(),
                    'items-start' => $message->user_id !== auth()->id(),
                ])
            >
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-semibold text-neutral-500 dark:text-zinc-400">
                        {{ $message->user->name }}
                    </span>
                    <span class="text-[10px] text-neutral-400 dark:text-zinc-500">
                        {{ $message->created_at->format('H:i') }}
                    </span>
                </div>

                <div @class([
                    'max-w-[85%] rounded-2xl px-4 py-2 text-sm',
                    'bg-neutral-100 dark:bg-zinc-800 text-neutral-900 dark:text-zinc-100 rounded-tr-none' => $message->user_id === auth()->id() && $activeChannel === 1,
                    'bg-blue-600 text-white rounded-tr-none' => $message->user_id === auth()->id() && $activeChannel !== 1,
                    'bg-neutral-100 dark:bg-zinc-700 text-neutral-900 dark:text-zinc-100 rounded-tl-none' => $message->user_id !== auth()->id(),
                    'border-l-4 border-red-500' => $activeChannel === 2,
                    'font-mono' => $activeChannel === 1,
                ])>
                    <p class="whitespace-pre-wrap break-words leading-relaxed">{{ $message->body }}</p>

                    @if($activeChannel === 3 && $message->image_path)
                        <div class="mt-2 rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                            <img src="{{ asset('storage/' . $message->image_path) }}" alt="Attachment" class="max-w-full h-auto">
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if($this->messages->isEmpty())
            <div class="flex flex-col items-center justify-center h-full text-neutral-400 dark:text-neutral-500 space-y-2 py-10">
                <flux:icon name="chat-bubble-left-right" variant="outline" class="size-8 opacity-20" />
                <p class="text-sm">No messages yet. Start the conversation!</p>
            </div>
        @endif
    </div>

    <!-- Input Form (Protected against DOM morphing focus loss) -->
    <div wire:ignore.self class="p-4 border-t border-neutral-200 dark:border-zinc-800 bg-neutral-50/50 dark:bg-zinc-950/50">
        <form wire:submit="sendMessage" class="flex gap-2">
            <div class="flex-1">
                <flux:input
                    wire:model="body"
                    wire:key="chat-body-input"
                    placeholder="Type a message..."
                    autocomplete="off"
                    class="!bg-white dark:!bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500"
                />
            </div>
            <flux:button type="submit" variant="primary" icon="paper-airplane" />
        </form>
    </div>
</div>
