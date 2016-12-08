create or replace view document_account_line
as
select dl.document_id as document_id,
       i.comptabilite as comptabilite,
       dl.vat as taux_de_tva,
       if(isnull(dl.vat), 0, sum( round(( if(isnull(dl.extra_htva),0,dl.extra_htva) + if(isnull(dl.price_htva),0,dl.price_htva) ) * (dl.vat / 100), 2))) as total_vat,
	   sum(if(isnull(dl.price_htva),0,dl.price_htva)) as total_price_htva,
	   sum(if(isnull(dl.extra_htva),0,dl.extra_htva)) as total_extra_htva,
	   sum(if(isnull(dl.price_htva),0,dl.price_htva)) + sum(if(isnull(dl.extra_htva),0,dl.extra_htva)) as total_htva,
       if( isnull(dl.vat), 0, round( (sum(if(isnull(dl.price_htva),0,dl.price_htva)) + sum(if(isnull(dl.extra_htva),0,dl.extra_htva))) * (dl.vat / 100), 2) ) as total_vat_ctrl
  from document_line dl,
       item i
 where dl.item_id = i.id
 group by dl.document_id,
          i.comptabilite,
          dl.vat
;
create or replace view document_size as
select quantity, work_width as largest, work_height as shortest
  from document_line dl, document d
 where d.id = dl.document_id
   and d.document_type = 'ORDER'
   and work_width is not null
   and work_height is not null
   and work_width >= work_height
union
select quantity, work_height as largest, work_width as shortest
  from document_line dl, document d
 where d.id = dl.document_id
   and d.document_type = 'ORDER'
   and work_width is not null
   and work_height is not null
   and work_width < work_height
;
