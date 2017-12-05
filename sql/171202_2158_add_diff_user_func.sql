CREATE FUNCTION `GetDiffUserBuyProduct`(`pid` int) RETURNS int(11)
BEGIN
	DECLARE c INT DEFAULT 0;
	SELECT Count(*) INTO c From order_product as od INNER JOIN `Order` as o ON o.id = od.order_id
Where od.product_id = pid Group By o.customer_id;
	RETURN c;
END