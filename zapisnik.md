# TODO

Kod izmene sale ne vuce podatke.

## DATABASE

Da li da u svakoj tabeli imamo korisnika i (mislim da je bolje) modified_at ili da se oslanjamo na log. Cenim da je lakse pronaci "pocinioca" ako je zapisana poslednja promena u tabeli.

STASA - Ma jok, samo ugovori, termin i uplate i to created_at kao sto i jeste. Neko moze da promeni nesto beznacajno posle pocinioca i da bude pogresno optuzen.
Zato moramo da se oslonimo na log.

CHANA - Kapara treba da se izbaci iz ugovora i da se proknjizi kao uplata. Na pregledu ugovora da ostane kapara, ali da se
vice iz uplata po opisu. Ovo mislim da olaksava kasnije operacije sa uplatama.

## RASPORED MESTA U SALI

Nisam siguran koliko ovaj proracun odgovara potrebama jer se uvek dodaje sto.

Kada je jedan ugovor (najcesci slucaj) treba uzeti max broj stolova i podeliti broj zvanica sa njim da se dobije priblizan broj mesta po stolu uz neki minimalni broj mesta za stolom (da ne bude po 3 coveka za stolom).

Kada je vise ugovora treba obratiti paznju na preostale stolove i mesta. Odabrati neki broj mesta za stolom (10)

- ako je ostatak <= zauzeti stolovi onda dodati po jos jedno mesto za stolom
- ako je broj mesta za stolom veliki (12+) dodati jos jedan sto pa podeliti broj zvanica sa novim brojem stolova da se dobije broj mesta za stolom
- broj mesta za stolom treba da bude okviran npr 12 +- 1
