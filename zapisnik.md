# TODO

Kod izmena podataka da se ne menja korisnik_id posto je created_at treba da ostane onak korisnik koji je dodao


### PODSETNIK

- !!! Dodati kaparu u ugovor !!!
- bootstrap validacija na uplatu (na detalju ugovora)
- prepraviti modal za unos uplate da bude za izmenu uplate i odraditi izmenu uplate (na detalju ugovora)
- odraditi brisanje uplate (na detalju ugovora)
- !!! obavezno logovati sve oko uplata !!!
- kod izmene uplate zabraniti promenu opisa ako je opis kapara (moze samo da se promeni iznos ili obrise cela uplata)
- Logovanje

## DATABASE

Da li da u svakoj tabeli imamo korisnika i (mislim da je bolje) modified_at ili da se oslanjamo na log. Cenim da je lakse pronaci "pocinioca" ako je zapisana poslednja promena u tabeli.

STASA - Ma jok, samo ugovori, termin i uplate i to created_at kao sto i jeste. Neko moze da promeni nesto beznacajno posle pocinioca i da bude pogresno optuzen.
Zato moramo da se oslonimo na log.

CHANA - Kapara treba da se izbaci iz ugovora i da se proknjizi kao uplata. Na pregledu ugovora da ostane kapara, ali da se
vice iz uplata po opisu. Ovo mislim da olaksava kasnije operacije sa uplatama.

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
