CREATE TABLE IF NOT EXISTS notifications (
  id int(11) NOT NULL AUTO_INCREMENT,
  message varchar(255) NOT NULL,
  checked int(11) NOT NULL DEFAULT '0',
  nfor int(11) NOT NULL,
  PRIMARY KEY (id)
);