create table item_a as 
select i1.id, i1.reference, i2.prix_de_vente
from item i1, item i2
where i2.reference = concat(i1.reference, '_A');

create table item_b as 
select i1.id, i1.reference, i2.prix_de_vente
from item i1, item i2
where i2.reference = concat(i1.reference, '_B');

update item set prix_a = (select prix_de_vente from item_a where id = item.id);

update item set prix_b = (select prix_de_vente from item_b where id = item.id)

