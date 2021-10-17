// Clase para albergar datos de tiendas
class MiStore {
    constructor(nombre, latitud, longitud) {
        this.nombre = nombre;
        this.latitud = latitud;
        this.longitud = longitud;
    }
}

class MapaDinamico {
    constructor() {
        $(document).ready(this.initMap);
    }

    // Configuración del mapa dinámico
    initMap() {
        // Tomamos como centro Madrid
        var centro = { lat: 40.3999984, lng: -3.6833306 };
    
        // Creación del mapa
        var mapa = new google.maps.Map(document.getElementById("mapa"), {
            center: centro,
            zoom: 6
        });
    
        // Datos de las tiendas
        var tiendas = [
            new MiStore("Parque Principado", 43.40055454244398, -5.801211894343763),
            new MiStore("Parque Sur", 40.33995774450828, -3.73494941714026),
            new MiStore("Marineda City", 43.34519144053473, -8.427767359406465),
            new MiStore("Puerto Venecia", 41.60916906047347, -0.8870078441014926),
            new MiStore("Nervion", 37.38322955598094, -5.972819173020419),
            new MiStore("Málaga - Plaza Mayor", 36.65690751200057, -4.478607430704698),
            new MiStore("Larios", 36.71619498596841, -4.432387459539112),
            new MiStore("Nueva Condomina", 38.03914252821425, -1.1481973595144261),
            new MiStore("La Gavia", 40.36906315980859, -3.5979232152914773),
            new MiStore("Parque Sur", 40.33989387831134, -3.734954888304828),
            new MiStore("Plenilunio", 40.4473982081659, -3.5871231171381135),
            new MiStore("Sol", 40.428462088888466, -3.7022027803139026),
            new MiStore("Xanadú", 40.29910698460046, -3.928362617141109),
            new MiStore("Ruzafa", 39.46836114528053, -0.3753348018158265),
            new MiStore("Bonaire", 39.47094102155846, -0.4896666459930975),
            new MiStore("La Vaguada", 40.48009989336665, -3.705331574808363),
            new MiStore("Plaza Norte 2", 40.54076692580527, -3.6139684324780728),
            new MiStore("Nevada", 37.15115717770685, -3.6127371357090348),
            new MiStore("Gran Plaza 2", 40.49139276260552, -3.8980600594663177),
            new MiStore("Salera Carretera", 39.97963504640802, -0.06301177297021533),
            new MiStore("Parc Central", 41.11701170033112, 1.2382316828755495),
            new MiStore("Splau", 41.34785360421446, 2.0769581845358718),
            new MiStore("Finestrelles", 41.37705608859319, 2.0980810982227736),
            new MiStore("Gran Vía 2", 41.35876408498317, 2.1284084963741523),
            new MiStore("La Maquinista", 41.4534072027208, 2.19898202958535),
            new MiStore("Granollers", 41.60889261468904, 2.2891985405566673),
            new MiStore("Arenas", 28.12932551192364, -15.449392385484769),
            new MiStore("Tenerife", 28.484319288186004, -16.268693710068913),
            new MiStore("L'illa Diagonal", 41.40016458367108, 2.130472432245615)
        ];
    
        // Imagen de los marcadores
        const iconoMarcador = {
            url: "https://cdn.iconscout.com/icon/free/png-256/mi-1693561-1442602.png",
            scaledSize: new google.maps.Size(32, 32),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 0)
        };
    
        // Se crea un marcador por cada tienda
        for (var i in tiendas) {
            var marcador = new google.maps.Marker({
                position: { lat: tiendas[i].latitud, lng: tiendas[i].longitud },
                mapa,
                title: tiendas[i].nombre,
                animation: google.maps.Animation.DROP,
                icon: iconoMarcador
            });
    
            marcador.setMap(mapa);
        }
    }
}

var mapaDinamico = new MapaDinamico();