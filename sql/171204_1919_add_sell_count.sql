CREATE FUNCTION `GetSellCount`(`pid` int) RETURNS int(11)
BEGIN
		DECLARE c INT DEFAULT 0;
		SELECT Count(*) INTO c From order_product as od Where od.product_id = pid;
		RETURN c;
END