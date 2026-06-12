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

    #[Computed]
    public function hasSosAlert(): bool
    {
        return Message::query()
            ->where('channel_id', 2)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();
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

        // Strict validation for image_path
        if ($this->activeChannel === 3 && $this->image_path) {
            $messageData['image_path'] = $this->image_path;
        }

        Message::create($messageData);

        $this->reset(['body', 'image_path']);

        $this->dispatch('message-sent');
    }

    public function setChannel(int $channelId): void
    {
        $this->activeChannel = $channelId;
    }
}; ?>

<div wire:poll.5s class="flex flex-col h-full bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
    <!-- Tabs Header -->
    <div class="flex border-b border-neutral-200 dark:border-zinc-800 p-1 bg-neutral-50 dark:bg-zinc-950">
        <button
            wire:click="setChannel(1)"
            @class([
                'flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors',
                'bg-white dark:bg-zinc-800 shadow-sm text-neutral-900 dark:text-zinc-100' => $activeChannel === 1,
                'text-neutral-500 dark:text-zinc-400 hover:text-neutral-700 dark:hover:text-zinc-200' => $activeChannel !== 1,
            ])
        >
            <span>War Room</span>
            <span>⚔️</span>
        </button>

        <button
            wire:click="setChannel(2)"
            @class([
                'flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors relative overflow-hidden',
                'bg-white dark:bg-zinc-800 shadow-sm text-neutral-900 dark:text-zinc-100' => $activeChannel === 2,
                'text-neutral-500 dark:text-zinc-400 hover:text-neutral-700 dark:hover:text-zinc-200' => $activeChannel !== 2,
                'animate-pulse ring-2 ring-red-500 ring-inset' => $this->hasSosAlert,
            ])
        >
            @if($this->hasSosAlert)
                <span class="absolute inset-0 bg-red-500/10 dark:bg-red-500/20"></span>
            @endif
            <span class="relative z-10">SOS</span>
            <span class="relative z-10">🚨</span>
            @if($this->hasSosAlert)
                 <span class="absolute top-1 right-1 flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
            @endif
        </button>

        <button
            wire:click="setChannel(3)"
            @class([
                'flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors',
                'bg-white dark:bg-zinc-800 shadow-sm text-neutral-900 dark:text-zinc-100' => $activeChannel === 3,
                'text-neutral-500 dark:text-zinc-400 hover:text-neutral-700 dark:hover:text-zinc-200' => $activeChannel !== 3,
            ])
        >
            <span>Vibe Ward</span>
            <span>🎉</span>
        </button>
    </div>

    <!-- Messages Area -->
    <div
        x-data="{
            scrollToBottom() {
                $el.scrollTop = $el.scrollHeight;
            }
        }"
        x-init="scrollToBottom()"
        @message-sent.window="$nextTick(() => scrollToBottom())"
        class="flex-1 overflow-y-auto p-4 space-y-4 min-h-[300px] max-h-[500px]"
    >
        @foreach($this->messages as $message)
            <div @class([
                'flex flex-col',
                'items-end' => $message->user_id === auth()->id(),
                'items-start' => $message->user_id !== auth()->id(),
            ])>
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

    <!-- Input Area -->
    <div class="p-4 border-t border-neutral-200 dark:border-zinc-800 bg-neutral-50/50 dark:bg-zinc-950/50">
        <form wire:submit="sendMessage" class="flex gap-2">
            <div class="flex-1">
                <flux:input
                    wire:model="body"
                    placeholder="Type a message..."
                    autocomplete="off"
                    class="!bg-white dark:!bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500"
                />
            </div>
            <flux:button type="submit" variant="primary" icon="paper-airplane" />
        </form>
    </div>
</div>
