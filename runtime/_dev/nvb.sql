Même nom prénom:
---------------
select * from client where concat(upper(nom), ' ', upper(prenom)) in (select upper(nom) from client_nvb)


Même nom de famille:
-------------------
select nvb.nom, c.nom, c.prenom, c.* from client c, client_nvb nvb
where upper(c.nom) = substr(upper(nvb.nom), 1, length(c.nom))

