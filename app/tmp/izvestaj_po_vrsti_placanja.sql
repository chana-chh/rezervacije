SELECT datum, nacin_placanja, SUM(iznos) AS iznos
FROM uplate
GROUP BY nacin_placanja WITH ROLLUP
HAVING datum BETWEEN '2019-12-01' AND '2020-01-20'