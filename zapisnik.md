# TODO

Kod izmene sale ne vuce podatke.

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

za jedan ugovor ako je brojMesta = Math.ceil(brojZvanica / broj stolova) <= optimalan broj po stolu ici na optimalan broj, inace ici na brojMesta.

za vise ugovora ide se na optimalan broj za vise ugovora pa se ako ima viska oni rasporedjuju po popunjenim stolovima ako je visak <= od broja popunjenih stolova, a ako je visak veci onda se dodaje sto i racunaju se mesta za stolom na osnovu broja stolova +1
