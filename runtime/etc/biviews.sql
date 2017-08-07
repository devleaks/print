create or replace view bi_sale
as select
   d.document_type as document_type,
   d.status as document_status,
   d.created_at as created_at,
   d.updated_at as updated_at,
   d.due_date as due_date,
   date_format(d.created_at,'%Y') as date_year,
   date_format(d.created_at,'%m') as date_month,
   d.price_htva as price_htva,
   c.prenom as client_fn,
   c.nom as client_ln,
   c.autre_nom as client_an,
   c.pays as country,
   c.lang as language,
   c.id as client_id
from (document d join client c) where (d.client_id = c.id)
;

create or replace view bi_line
as select
   d.document_type as document_type,
   d.status as document_status,
   d.name as document_name,
   date_format(dl.created_at,'%Y-%m-%dT%TZ') as created_at,
   dl.work_width as work_width,
   dl.work_height as work_height,
   dl.unit_price as unit_price,
   dl.quantity as quantity,
   dl.extra_type as extra_type,
   dl.extra_amount as extra_amount,
   dl.extra_htva as extra_htva,
   dl.price_htva as price_htva,
   (dl.price_htva + ifnull(dl.extra_htva,0)) as total_htva,
   i.libelle_court as item_name,
   i.categorie as categorie,
   i.yii_category as yii_category,
   i.comptabilite as comptabilite
 from document_line dl,
      document d,
      item i
where (dl.document_id = d.id)
  and (dl.item_id = i.id)
;

create or replace view bi_work
as select
   d.document_type as document_type,
   d.status as document_status,
   d.name as document_name,
   d.created_at as created_at,
   d.updated_at as updated_at,
   d.price_htva as total_price_htva,
   1 + dl.id - (select min(id) from document_line where document_id = d.id) as document_line,
   (ifnull(dl.price_htva,0)+ifnull(dl.extra_htva,0)) as line_price_htva,
   i2.libelle_court as line_item_name,
   i2.categorie as item_categorie,
   i2.yii_category as item_yii_category,
   i.libelle_court as work_item_name,
   t.name as task_name,
   w.status as work_status,
   wl.status as work_line_status,
   wl.position as position,
   wl.created_at as date_start,
   wl.updated_at as date_finish,
   (unix_timestamp(wl.updated_at) - unix_timestamp(wl.created_at)) as duration   
 from work_line wl,
 	  document_line dl,
      work w,
      document d,
      item i,
      item i2,
      task t
where wl.work_id = w.id
  and wl.document_line_id = dl.id
  and w.document_id = d.id
  and wl.item_id = i.id
  and dl.item_id = i2.id
  and wl.task_id = t.id
;
