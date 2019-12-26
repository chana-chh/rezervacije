# TODO

Kapara ne znači ništa kod preklapajućih događaja

~~Class USER izmeniti Auth~~

Tip dogadjaja bi trebalo da bude u terminu, a trenutno je u ugovoru. Mozda treba da bude dve vrste tipova dogadjaja. TREBA

- trenutni tipovi da budu za termin

Prebaciti da se svi id-jevi na klik pune iz data-id="{{ xxx.id }}"
Dodati old.value svuda gde nedostaje

## DATABASE

### SALE

- dodati kapacitet stolova

### S_MENIJI

- dodati cenu

### TERMINI

- dodati tip dogadjaja
- izbaciti preklapanje (prebaciti u s_tip_dogadjaja)

### S_TIP_DOGADJAJA

- dodati preklapanje (multi ugovori)

### UGOVORI

- izbaciti tip dogadjaja
- dodati meni_id
- dodati cenu (meni->cena \* broj_mesta ili slobodan unos)
- dodati posebne zahteve (text)
- dodati broj stolova (napraviti kalkulator mesta i stolova)
- dodati broj mesta po stolu
- muzika_chk
- muzika_opis
- muzika_ugovor
- fotograf_chk
- fotograf_opis
- torta_chk
- torta_opis
- dekoracija_chk
- dekoracija_opis
- vocni_sto_chk
- vocni_sto_opis
- slatki_sto_chk
- slatki_sto_opis
- kokteli_chk
- kokteli_opis

### S_NACIN_PLACANJA (dodati)

- ja bih ovo shinuo u enum u tabeli uplate

### UPLATE (dodati)

- id
- ugovor_id
- datum
- iznos
- nacin_placanja
