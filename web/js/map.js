const map = document.querySelector('#map');

if (map) {
    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map('map', {
            center: [map.dataset.lat, map.dataset.long],
            zoom: 17
        });
    }
}

