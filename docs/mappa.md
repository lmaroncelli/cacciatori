$coordinate_zona[$zona_azione->id] = $coordinata_zona;


ogni azione ha le coordinate delle zone su cui è definita



$coordinate_unita[$zona_azione->id][$unita->id]


Per ogni azione ho le coordinate delle UG relative

array:3 [▼
  780 => array:2 [▼
    43 => Illuminate\Support\Collection {#399 ▶}
    42 => Illuminate\Support\Collection {#5520 ▶}
  ]
  794 => array:2 [▼
    38 => Illuminate\Support\Collection {#829 ▶}
    43 => Illuminate\Support\Collection {#15813 ▶}
  ]
  786 => array:2 [▼
    41 => Illuminate\Support\Collection {#404 ▶}
    43 => Illuminate\Support\Collection {#24694 ▶}
  ]
]



siccome le azioni posso avere unità in comune in realtà mi porto dietro le coordinate della stessa unità ripetuta (ad esempio la 43 è ripetuta 3 volte)

$coordinate_distretto[$unita->id]

ogni unità ha le coordinate del suo distretto associato

MA SE LE UNITA' HANNO LO STESSO DISTRETTO, come sotto, mi porto dietro 4 distretti invece di 2!!

array:4 [▼
  43 => Illuminate\Support\Collection {#9821 ▶}
  42 => Illuminate\Support\Collection {#2349 ▶}
  38 => Illuminate\Support\Collection {#10330 ▶}
  41 => Illuminate\Support\Collection {#20901 ▶}
]


Quindi assegno 
$coordinate_distretto[$unita->id] = $distretto->id

Quindi aggiungo un array 

distretto_coo

che per ogni $distretto->id associa le coordinate (se quel distretto è presente in 14 UG avrò SOLO 1 array con 1 $distretto->id)


$distretto_coo[$distretto->id] = $poligono_distretto->coordinate->pluck('long','lat');



- ho ottimizzato lo store dei dati con questi doppi array

- in Utility::getAzioniMappaNew() quando creo l'array delle azioni creo per ogni azione un nuovo oggetto con solo i dati che mi serve visualizzare; le note devono prevedere una sostituzione dei singoli apici

