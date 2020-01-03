# TODO

~~Class USER izmeniti Auth~~

## DATABASE

Da li da u svakoj tabeli imamo korisnika i (mislim da je bolje) modified_at ili da se oslanjamo na log. Cenim da je lakse pronaci "pocinioca" ako je zapisana poslednja promena u tabeli.

STASA - Ma jok, samo ugovori, termin i uplate i to created_at kao sto i jeste. Neko moze da promeni nesto beznacajno posle pocinioca i da bude pogresno optuzen.
Zato moramo da se oslonimo na log.

CHANA - Kapara treba da se izbaci iz ugovora i da se proknjizi kao uplata. Na pregledu ugovora da ostane kapara, ali da se
vice iz uplata po opisu. Ovo mislim da olaksava kasnije operacije sa uplatama.
