SELECT ter.tip_dogadjaja_id, s_tip_dogadjaja.tip, ter.datum, SUM(ugo.ug_broj_mesta) AS broj_mesta, SUM((ugo.ug_iznos_meni + ugo.ug_iznos_usluge)) AS iznos, SUM(ugo.ug_uplate_iznos) AS uplate_iznos,
		(SUM((ugo.ug_iznos_meni + ugo.ug_iznos_usluge)) - SUM(ugo.ug_uplate_iznos)) AS dug
FROM termini AS ter
JOIN (SELECT ug.id, ug.termin_id, SUM(ug.broj_mesta) AS ug_broj_mesta, SUM(ug.broj_stolova) AS ug_broj_stolova, SUM(ug.iznos) AS ug_iznos_meni,
				 SUM((ug.muzika_iznos +
				 ug.fotograf_iznos +
				 ug.torta_iznos +
				 ug.dekoracija_iznos +
				 ug.kokteli_iznos +
				 ug.slatki_sto_iznos +
				 ug.vocni_sto_iznos +
				 ug.posebni_zahtevi_iznos)) AS ug_iznos_usluge,
				 SUM(up.uplate_iznos) AS ug_uplate_iznos
		FROM ugovori AS ug
		LEFT JOIN
			(SELECT uplate.ugovor_id, SUM(uplate.iznos) AS uplate_iznos
				FROM uplate
				GROUP BY uplate.ugovor_id) AS up
		ON up.ugovor_id = ug.id
		GROUP BY ug.termin_id) AS ugo
ON ugo.termin_id = ter.id
JOIN s_tip_dogadjaja ON s_tip_dogadjaja.id = ter.tip_dogadjaja_id
GROUP BY ter.tip_dogadjaja_id WITH ROLLUP
HAVING ter.datum BETWEEN '2020-01-01' AND '2020-01-04'