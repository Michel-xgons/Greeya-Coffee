<x-filament::widget>
    <div wire:poll.5s="poll"
         class="p-4 bg-red-500 text-white rounded-xl">

         Pesanan Baru: {{ $count }}

    </div>
</x-filament::widget>