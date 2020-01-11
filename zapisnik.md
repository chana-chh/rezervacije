# TODO

Dodati relacije koje nedostaju u modelima

!!! POSTO CE BITI GOMILA DOKUMENATA (predpostavljam) PROVERITI KOLIKO PROSTORA DAJE PROVIDER !!!

### PODSETNIK

- u pregled ugovora dodati dug
- bootstrap validacija na uplatu (na detalju ugovora)
- prepraviti modal za unos uplate da bude za izmenu uplate i odraditi izmenu uplate (na detalju ugovora)
- odraditi brisanje uplate (na detalju ugovora)
- !!! obavezno logovati sve oko uplata !!!
- kod izmene uplate zabraniti promenu opisa ako je opis kapara (moze samo da se promeni iznos ili obrise cela uplata)

- Logovanje je odradjeno. Sta logovati za 'upload'?
- za logovanje u kontroler treba dodati (use App\Classes\Logger;)
- za koriscenje videti dodavanje, izmenu i brisanje u TerminController

- !!! kod pregleda ugovora sa uplatama obavezno dodati dodatne usluge zbog upiredjivanja uplata i usluga !!!

- redni broj u tabelarnom prikazu kod paginacije nema smisla

## DATABASE

## RASPORED MESTA U SALI

za jedan ugovor se racuna brojStolica i ako je on <= optimalan broj po stolu ici na optimalan broj, inace ici na brojStolica.

```
brojStolica = Math.ceil(brojZvanica / maxBrojStolova)
if(brojStolica <= optBrojStolicaZaSingle) {
	brojStolica = optBrojStolicaZaSingle
} else {
	brojStolica = brojStolica
}
```

za vise ugovora ide se na optimalan broj za vise ugovora pa se ako ima viska oni rasporedjuju po popunjenim stolovima ako je visak <= od broja popunjenih stolova, a ako je visak veci onda se dodaje sto i racunaju se mesta za stolom na osnovu broja stolova +1

```
brojStolova = Math.flor(brojZvanica / optBrojStolicaZaMulti)
visak = brojZvanica - (brojStolova * optBrojStolicaZaMulti)

if(visak <= brojStolova) {
	brojStolica = optBrojStolicaZaMulti + 1
} else {
	brojStolova++
	brojStolica = Math.ceil(brojZvanica / brojStolova)
}
```
