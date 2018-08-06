

# Replace placeholder table for bi_line with correct view syntax
# ------------------------------------------------------------

DROP TABLE `bi_line`;

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

# Replace placeholder table for bi_sale with correct view syntax
# ------------------------------------------------------------

DROP TABLE `bi_sale`;

create or replace view bi_sale
as select
   d.document_type as document_type,
   d.status as document_status,
   d.created_at as created_at,
   d.updated_at as updated_at,
   d.due_date as due_date,
   d.price_htva as price_htva,
   ifnull(concat(c.nom, ' ', c.prenom),c.autre_nom) as client_name,
   c.pays as client_country
from (document d join client c) where (d.client_id = c.id)
;


# Replace placeholder table for bi_work with correct view syntax
# ------------------------------------------------------------

DROP TABLE `bi_work`;

CREATE or replace VIEW bi_work
AS SELECT
   d.document_type as document_type,
   d.status as document_status,
   d.name as document_name,
   d.created_at AS created_at,
   d.updated_at AS updated_at,
   d.price_htva AS total_price_htva,
   1 + dl.id - (select min(id) from document_line where document_id = d.id) as document_line,
   (ifnull(dl.price_htva,0)+ifnull(dl.extra_htva,0)) AS line_price_htva,
   i2.libelle_court AS line_item_name,
   i2.categorie AS item_categorie,
   i2.yii_category AS item_yii_category,
   i.libelle_court AS work_item_name,
   t.name AS task_name,
   w.status as work_status,
   wl.status as work_line_status,
   wl.position as position,
   wl.created_at AS date_start,
   wl.updated_at AS date_finish,
   (UNIX_TIMESTAMP(wl.updated_at) - UNIX_TIMESTAMP(wl.created_at)) as duration   
 FROM work_line wl,
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

# Replace placeholder table for document_account_line with correct view syntax
# ------------------------------------------------------------

DROP TABLE `document_account_line`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yii2print`@`localhost` SQL SECURITY DEFINER VIEW `document_account_line`
AS SELECT
   `dl`.`document_id` AS `document_id`,
   `i`.`comptabilite` AS `comptabilite`,
   `dl`.`vat` AS `taux_de_tva`,if(isnull(`dl`.`vat`),0,sum(round(((if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`) + if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) * (`dl`.`vat` / 100)),2))) AS `total_vat`,sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) AS `total_price_htva`,sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`)) AS `total_extra_htva`,(sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) AS `total_htva`,if(isnull(`dl`.`vat`),0,round(((sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) * (`dl`.`vat` / 100)),2)) AS `total_vat_ctrl`
FROM (`document_line` `dl` join `item` `i`) where (`dl`.`item_id` = `i`.`id`) group by `dl`.`document_id`,`i`.`comptabilite`,`dl`.`vat`;


# Replace placeholder table for document_size with correct view syntax
# ------------------------------------------------------------

DROP TABLE `document_size`;

CREATE OR REPLACE
VIEW `document_size` AS
select `dl`.`quantity` AS `quantity`,
`dl`.`work_width` AS `largest`,
`dl`.`work_height` AS `shortest` from (`document_line` `dl` join `document` `d`)
where (   (`d`.`id` = `dl`.`document_id`)
      and (`d`.`document_type` = 'ORDER')
      and (`dl`.`work_width` is not null)
      and (`dl`.`work_height` is not null) and (`dl`.`work_width` >= `dl`.`work_height`)
) union
select `dl`.`quantity` AS `quantity`,
       `dl`.`work_height` AS `largest`,
       `dl`.`work_width` AS `shortest` from (`document_line` `dl` join `document` `d`)
where (   (`d`.`id` = `dl`.`document_id`)
      and (`d`.`document_type` = 'ORDER')
      and (`dl`.`work_width` is not null)
      and (`dl`.`work_height` is not null)
      and (`dl`.`work_width` < `dl`.`work_height`)
);


# Replace placeholder table for document_account_line with correct view syntax
# ------------------------------------------------------------
DROP TABLE `document_account_line`;

CREATE OR REPLACE
VIEW `document_account_line` AS
select `dl`.`document_id` AS `document_id`,
`i`.`comptabilite` AS `comptabilite`,
`dl`.`vat` AS `taux_de_tva`,
if(isnull(`dl`.`vat`),0,sum(round(((if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`) + if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) * (`dl`.`vat` / 100)),2))) AS `total_vat`,
sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) AS `total_price_htva`,
sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`)) AS `total_extra_htva`,
(sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) AS `total_htva`,
if(isnull(`dl`.`vat`),0,round(((sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) * (`dl`.`vat` / 100)),2)) AS `total_vat_ctrl`
from (`document_line` `dl` join `item` `i`)
where (`dl`.`item_id` = `i`.`id`)
group by `dl`.`document_id`,`i`.`comptabilite`,`dl`.`vat`;
