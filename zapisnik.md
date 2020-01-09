# TODO

Dodati relacije koje nedostaju u modelima

### PODSETNIK

- !!! Dodati kaparu u ugovor !!!
- bootstrap validacija na uplatu (na detalju ugovora)
- prepraviti modal za unos uplate da bude za izmenu uplate i odraditi izmenu uplate (na detalju ugovora)
- odraditi brisanje uplate (na detalju ugovora)
- !!! obavezno logovati sve oko uplata !!!
- kod izmene uplate zabraniti promenu opisa ako je opis kapara (moze samo da se promeni iznos ili obrise cela uplata)
- Logovanje

## DATABASE

STASHA - U sve tabele sam dodao korisnik_id i created_at. Created_at default vrednost postavljena na CURRENT_TIMESTAMP. Baza je u TMP

## RASPORED MESTA U SALI

Nisam siguran koliko ovaj proracun odgovara potrebama jer se dodaje sto za jednog coveka (koji se moze uglaviti za neki sto ako je broj mesta manji od max).

Ovo videti sa Acom kako da se rasporedjuju mesta.

Po meni:

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
