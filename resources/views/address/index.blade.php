<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Address') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    This is the address page. To add details, click <span class="text-red-600"><a href="{{ route('address.create') }}">here</a></span>.
                </div>
            </div>
        </div>
    </div>

    @if(!empty($address))
    <div class="pt-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class=" p-6 bg-white border-b border-gray-200">
              Your address is:
              <ul class="list-inside">
                <li>Street + number: {{ $address->ship_address }}</li>
                @if(!empty($address->address2))
                <li>Apartment, unit, suite, or floor: {{ $address->address2 }}</li>
                @endif
                <li>City: {{ $address->locality }}</li>
                <li>State: {{ $address->state }}</li>
                <li>Postcode: {{ $address->postcode }}</li>
                <li>Country: {{ $address->country }}</li>
              </ul>
            </div>  
          </div>
      </div>
    </div>
    @endif

</x-app-layout>
