const cityInput = document.querySelector('#city');
const addressInput = document.querySelector('#address');
const latInput = document.querySelector('#lat');
const longInput = document.querySelector('#long');

const autoCompleteJS = new autoComplete({
    selector: '#autoComplete',
    data: {
        'src': async (query) => {
            try {
                const source = await fetch(`/location/${query}`);
                let data = await source.json();

                return data;
            } catch (error) {
                return error;
            }
        },
        'keys': ['location'],
    },
    events: {
        input: {
            selection: (event) => {
                const selection = event.detail.selection.value;
                autoCompleteJS.input.value = selection.location;

                cityInput.value = selection.city;
                addressInput.value = selection.address;
                latInput.value = selection.lat;
                longInput.value = selection.long;
            },
            change: (event) => {
                if (autoCompleteJS.input.value === '') {
                    cityInput.value = '';
                    addressInput.value = '';
                    latInput.value = '';
                    longInput.value = '';
                }
            },
        },
    },
    searchEngine: 'loose',
    debounce: 300,
});
