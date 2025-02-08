<x-layout>
<table id="category" class="border-collapse table-auto w-full text-sm my-6 pt-6">
    <thead>
    <tr>

        <th class="text-left pr-3">anzahl</th>
        <th class="text-left pr-3">preis</th>
        <th class="text-left pr-3">ein- /ausgaben</th>
        <th class="text-left pr-3">avgPrice</th>
        <th class="text-left pr-3">totalAmount</th>
        <th class="text-left pr-3">ges</th>
        <th class="text-left pr-3">sum buy</th>
        <th class="text-left pr-3">sum sell</th>
        <th class="text-left pr-3">buy</th>
        <th class="text-left pr-3">sell</th>
    </tr>
    </thead>
    <tbody>
    @foreach($stocks as $stock)
        <tr>
            <td class="border p-4 font-bold text-red-600">{{$stock->amount}}</td>
            <td class="border p-4 font-bold text-red-600">{{$stock->price}}€</td>
            <td class="border p-4 font-bold text-red-600">{{$stock->balance}} €</td>
            <td class="border p-4 font-bold text-red-600">{{$stock->avgPrice}}</td>
            <td class="border p-4 font-bold text-red-600">{{$stock->total_amount}}</td>
            <td class="border p-4">
            @if($stock->sell_at)
                {{$stock->total}} €
            @endif
            </td>
            <td class="border p-4 font-bold text-red-600">{{$stock->buy_amount}}</td>
            <td class="border p-4 font-bold text-red-600">{{$stock->sell_amount}}</td>
            <td class="border p-4">{{$stock->buy_at}}</td>
            <td class="border p-4">{{$stock->sell_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
    </x-layout>
