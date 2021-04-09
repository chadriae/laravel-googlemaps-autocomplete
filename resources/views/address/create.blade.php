<x-app-layout>
  <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: "Roboto", sans-serif;
        font-size: 18px;
        color: #686868;
      }

      form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        max-width: 400px;
        padding: 20px;
      }

      input {
        width: 100%;
        height: 1.2rem;
        margin-top: 0;
        padding: 0.5em;
        border: 0;
        border-bottom: 2px solid gray;
        font-family: "Roboto", sans-serif;
        font-size: 18px;
      }

      input:focus {
        border-bottom: 4px solid black;
      }

      input[type="reset"] {
        width: auto;
        height: auto;
        border-bottom: 0;
        background-color: transparent;
        color: #686868;
        font-size: 14px;
      }

      .title {
        width: 100%;
        margin-block-end: 0;
        font-weight: 500;
      }

      .note {
        width: 100%;
        margin-block-start: 0;
        font-size: 12px;
      }

      .form-label {
        width: 100%;
        padding: 0.5em;
      }

      .full-field {
        flex: 400px;
        margin: 15px 0;
      }

      .slim-field-left {
        flex: 1 150px;
        margin: 15px 15px 15px 0px;
      }

      .slim-field-right {
        flex: 1 150px;
        margin: 15px 0px 15px 15px;
      }

      .my-button {
        background-color: #000;
        border-radius: 6px;
        color: #fff;
        margin: 10px;
        padding: 6px 24px;
        text-decoration: none;
      }

      .my-button:hover {
        background-color: #666;
      }

      .my-button:active {
        position: relative;
        top: 1px;
      }
    </style>
    <script>
      // This sample uses the Places Autocomplete widget to:
      // 1. Help the user select a place
      // 2. Retrieve the address components associated with that place
      // 3. Populate the form fields with those address components.
      // This sample requires the Places library, Maps JavaScript API.
      // Include the libraries=places parameter when you first load the API.
      // For example: <script
      // src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      let autocomplete;
      let address1Field;
      let address2Field;
      let postalField;

      function initAutocomplete() {
        address1Field = document.querySelector("#ship-address");
        address2Field = document.querySelector("#address2");
        postalField = document.querySelector("#postcode");
        // Create the autocomplete object, restricting the search predictions to
        // addresses in the US and Canada.
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
          componentRestrictions: { country: ["us", "ca"] },
          fields: ["address_components", "geometry"],
          types: ["address"],
        });
        address1Field.focus();
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
          const componentType = component.types[0];

          switch (componentType) {
            case "street_number": {
              address1 = `${component.long_name} ${address1}`;
              break;
            }

            case "route": {
              address1 += component.short_name;
              break;
            }

            case "postal_code": {
              postcode = `${component.long_name}${postcode}`;
              break;
            }

            case "postal_code_suffix": {
              postcode = `${postcode}-${component.long_name}`;
              break;
            }
            case "locality":
              document.querySelector("#locality").value = component.long_name;
              break;

            case "administrative_area_level_1": {
              document.querySelector("#state").value = component.short_name;
              break;
            }
            case "country":
              document.querySelector("#country").value = component.long_name;
              break;
          }
        }
        address1Field.value = address1;
        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address2Field.focus();
      }
    </script>



  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Address') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Note: The address components in this sample are based on North American address format. You might need to adjust them for the locations relevant to your app. For more information, see
                https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
                    -->


                    <form method="post" id="address-form" action="{{ route('address.store') }}"  autocomplete="off">
                      @csrf
                      <p class="title">Sample address form for North America</p>
                      <p class="note"><em>* = required field</em></p>
                      <label class="full-field">
                        <!-- Avoid the word "address" in id, name, or label text to avoid browser autofill from conflicting with Place Autocomplete. Star or comment bug https://crbug.com/587466 to request Chromium to honor autocomplete="off" attribute. -->
                        <span class="form-label">Deliver to*</span>
                        <input
                          id="ship-address"
                          name="ship_address"
                          required
                          autocomplete="off"
                        />
                      </label>
                      <label class="full-field">
                        <span class="form-label">Apartment, unit, suite, or floor #</span>
                        <input id="address2" name="address2" />
                      </label>
                      <label class="full-field">
                        <span class="form-label">City*</span>
                        <input id="locality" name="locality" required />
                      </label>
                      <label class="slim-field-left">
                        <span class="form-label">State/Province*</span>
                        <input id="state" name="state" required />
                      </label>
                      <label class="slim-field-right" for="postal_code">
                        <span class="form-label">Postal code*</span>
                        <input id="postcode" name="postcode" required />
                      </label>
                      <label class="full-field">
                        <span class="form-label">Country/Region*</span>
                        <input id="country" name="country" required />
                      </label>

                      <x-button class="ml-4">
                        {{ __('Update address') }}
                      </x-button>

                      @if(Session::has('success'))
                        <div class="text-green-700 bg-green-100" role="alert">
                            {{ Session::get('success') }}
                        </div>
                      @endif

        
                      <!-- Reset button provided for development testing convenience.
                  Not recommended for user-facing forms due to risk of mis-click when aiming for Submit button. -->
                      <input type="reset" value="Clear form" />
                    </form>
                
                    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
                    <script
                      src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"
                      async
                    ></script>                
                </div>
          </div>
      </div>
  </div>
</x-app-layout>
