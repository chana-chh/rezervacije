# TODO

- Zakljucavanje ugovora nakon zakljucenja
- Konfimacija pre zakljucavanja sa tekstom da li su uneseni pravi podaci o broju zvanica i ceni.

- kada se na jedan pogled dolazi sa vise mesta - ubaciti po dugme za vracanje na sva mesta
- kad se brise ugovor iz liste ugovora vraca se na termin umesto na listu (ovo moramo da smislimo kako da se resi - da se vraca odakle je doslo :)

### PROBLEMI !!!

## DATABASE

## NAPOMENE
	- Kod logovanja ugovora smo za kljucni podatak uzeli broj ugovora koji je ponekad prazan moramo da dodamo jos nesto
	- Mozda generalno u Logger klasi treci parametar promeniti u arrey i omoguciti dodavanje vise polja


# "BRISANJE KORISNIKA"

### Auth.php
login metoda
```
// Ako je korisnik nivo 5000 onda je "obrisan" (ne moze da se prijavi)
if ($user->nivo == 5000) {
	return false;
}
```

### KorisnikKontroler.php
brisanje metoda
```
// $success = $model->deleteOne($id);

// Korisnik koji se "brise" dobija nivo 5000
$success = $model->update(["nivo" => 5000], $id);
```

### lista.twig
prikaz reda sa podacima korisnika
```
{% if korisnik.nivo != 5000 %}
    <tr>
		...
	</tr>
{% endif %}
```
