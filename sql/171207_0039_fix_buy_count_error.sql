DROP FUNCTION IF EXISTS `GetDiffUserBuyProduct`;

CREATE FUNCTION `GetDiffUserBuyProduct`(`pid` int) RETURNS int(11)
BEGIN
	DECLARE c INT DEFAULT 0;
	SELECT Count(DISTINCT o.customer_id) INTO c From order_product as od INNER JOIN `Order` as o ON o.id = od.order_id
Where od.product_id = pid;
	RETURN c;
END;