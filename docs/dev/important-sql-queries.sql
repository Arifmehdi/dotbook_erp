-- Get DESC by substring at `-`
SELECT * FROM sales ORDER BY SUBSTRING(`invoice_id`, POSITION("-" IN `invoice_id`) + 1, CHAR_LENGTH(`invoice_id`)) DESC LIMIT 1;
