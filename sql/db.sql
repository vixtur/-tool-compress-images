DROP TABLE IF EXISTS `compression`
CREATE TABLE IF NOT EXISTS `compression` (
  `url` VARCHAR(255) UNIQUE,
  `size_temp` INT(11) NOT NULL,
  `size_compress` INT(11) NOT NULL DEFAULT 0,
  `size_origin` INT(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
