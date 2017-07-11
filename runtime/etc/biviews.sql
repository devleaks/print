CREATE ALGORITHM=UNDEFINED DEFINER=yii2print@mac-de-pierre.local SQL SECURITY DEFINER VIEW bi_sale
AS SELECT
   d.document_type AS document_type,
   d.created_at AS created_at,
   d.updated_at AS updated_at,
   d.due_date AS due_date,date_format(d.created_at,'%Y') AS date_year,date_format(d.created_at,'%m') AS date_month,
   d.price_htva AS price_htva,
   c.pays AS pays,
   c.lang AS lang
FROM (document d join client c) where (d.client_id = c.id);

CREATE ALGORITHM=UNDEFINED DEFINER=yii2print@mac-de-pierre.local SQL SECURITY DEFINER VIEW bi_line
AS SELECT
   d.document_type AS document_type,date_format(d.created_at,'%Y') AS date_year,date_format(d.created_at,'%m') AS date_month,
   c.pays AS pays,
   c.lang AS lang,
   dl.work_width AS work_width,
   dl.work_height AS work_height,
   dl.unit_price AS unit_price,
   dl.quantity AS quantity,
   dl.extra_type AS extra_type,
   dl.extra_amount AS extra_amount,
   dl.extra_htva AS extra_htva,
   dl.price_htva AS price_htva,
   i.id AS item_id,
   i.categorie AS categorie,
   i.yii_category AS yii_category,
   i.comptabilite AS comptabilite
FROM (((document_line dl join document d) join item i) join client c) where ((dl.document_id = d.id) and (dl.item_id = i.id) and (d.client_id = c.id));

CREATE ALGORITHM=UNDEFINED DEFINER=yii2print@mac-de-pierre.local SQL SECURITY DEFINER VIEW bi_work
AS SELECT
   wl.created_at AS date_start,
   wl.updated_at AS date_finish,
   t.name AS task_name,
   i.categorie AS categorie,
   i.yii_category AS yii_category
FROM (((work w join work_line wl) join item i) join task t) where ((wl.work_id = w.id) and (wl.item_id = i.id) and (wl.task_id = t.id));
