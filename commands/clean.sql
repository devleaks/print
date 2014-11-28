delete from picture;
delete from work_line;
delete from work;
delete from order_line_detail;
delete from order_line;
update `order` set parent_id = null;
delete from `order`;
