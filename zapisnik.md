# TODO

- Prekontrolisati KorisnikController i view/korisnik/lista.twig zbog nivoa koji su izgleda pomesani
	- 0 je admin
	- 100 je pregled odnosno vlasnik
	- 200 je obrada odnosno zakazivac
	- 300 je osoblje

MOZDA DODATI JOS JEDNOG POLU-ADMINA ???


- Izvestaj za kuvare (broj i vrsta menija)
- Izvestaj za gazdu (periodicno, defaultna tekuca godina 01.01 - 31.12, po statusima obrda i zakljuceni po salama, pregledi kapara, sume uplata i cena)
- Dodatni nivo za kuvare
- Dodatni pregledi za gazdu (ugovori, termini)
- Zakljucavanje ugovora nakon zakljucenja
- Jasniji podaci o meniju, brojnom stanju u terminu
- Modal-kapara izmena uplate
- Konfimacija pre zakljucavanja sa tekstom molimo proverite da li su uneseni pravi podaci o broju zvanica
i ceni.

Chana:

- Napraviti stampu ugovora

views:

- Razmisliti o sortiranju
- kada se na jedan pogled dolazi sa vise mesta - ubaciti po dugme za vracanje na sva mesta
- kod ugovora treba dodati neku vezu ka terminu (sa liste ugovora)

Stasa:

- Info za tip dogadjaja u vezi multi/singl (RESENO, tekst je sranje)

### PROBLEMI

- ima neki zalud(twig) u upregledu ugovora sa uplatama i treba skinuti table-bordered

## DATABASE

## NAPOMENE

kad se brise ugovor iz liste ugovora vraca se na termin umesto na listu (ovo moramo da smislimo kako da se resi - da se vraca odakle je doslo :)
