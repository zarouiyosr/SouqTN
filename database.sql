CREATE DATABASE IF NOT EXISTS souqtn
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE souqtn;

CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(100)  NOT NULL,
  email      VARCHAR(150)  NOT NULL UNIQUE,
  password   VARCHAR(255)  NOT NULL,
  role       ENUM('admin','client') NOT NULL DEFAULT 'client',
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS categories (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(120) NOT NULL
);


CREATE TABLE IF NOT EXISTS produits (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(200)   NOT NULL,
  description TEXT,
  category    VARCHAR(60),
  category_id INT,
  price       DECIMAL(10,3)  NOT NULL DEFAULT 0,
  orig_price  DECIMAL(10,3)  DEFAULT NULL,
  stock       INT            NOT NULL DEFAULT 0,
  image_url   VARCHAR(255)   DEFAULT '',
  rating      DECIMAL(3,2)   DEFAULT 0,
  reviews     INT            DEFAULT 0,
  badge       VARCHAR(40)    DEFAULT NULL,
  region      VARCHAR(120)   DEFAULT NULL,
  artisan     VARCHAR(150)   DEFAULT NULL,
  created_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS cart (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  product_id   INT NOT NULL,
  qty          INT NOT NULL DEFAULT 1,
  date_ajout   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_cart (user_id, product_id),
  FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES produits(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS favoris (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  product_id   INT NOT NULL,
  date_ajout   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_favori (user_id, product_id),
  FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES produits(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  total      DECIMAL(10,3) DEFAULT 0,
  statut     ENUM('en_cours','livree') NOT NULL DEFAULT 'en_cours',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  order_id   INT NOT NULL,
  product_id INT NOT NULL,
  qty        INT NOT NULL DEFAULT 1,
  price      DECIMAL(10,3) DEFAULT 0,
  FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES produits(id) ON DELETE CASCADE
);


INSERT INTO categories (nom) VALUES
  ('Artisanat'), ('Gastronomie'), ('Bijoux'),
  ('Textile'), ('Beauté'), ('Maison');

INSERT INTO produits
  (name, description, category, price, orig_price, stock, rating, reviews, badge, region, artisan) VALUES
  ('Vase Berbère Nabeul','Vase en terre cuite peint à la main selon les techniques ancestrales de Nabeul.','artisanat',85.000,120.000,15,4.90,142,'bestseller','Nabeul','Fatma Ben Salah'),
  ('Tapis Berbère Kairouan','Tapis noué à la main en laine vierge, motifs géométriques berbères.','textile',650.000,NULL,8,4.80,67,'premium','Kairouan','Mohamed Trabelsi'),
  ('Collier Filigrane Argent','Collier en filigrane d''argent 925, technique ancestrale de la médina de Tunis.','bijoux',195.000,240.000,22,4.95,231,'promo','Tunis Médina','Amira Chokri'),
  ('Huile d''Olive Extra Vierge Bio','Huile extra vierge première pression à froid, variété Chetoui. Bio certifiée.','gastronomie',45.000,NULL,120,4.70,489,'bio','Sfax','Habib Mansouri'),
  ('Savon au Lait d''Ânesse Djerba','Savon artisanal au lait d''ânesse enrichi en huile d''argan et rose musquée.','beaute',28.000,35.000,45,4.85,178,'new','Djerba','Sonia Baccar'),
  ('Lanterne Zellige Cuivrée','Lanterne orientale en cuivre ciselé à la main, incrustations de zellige.','artisanat',320.000,NULL,6,4.90,89,'premium','Sidi Bou Saïd','Rafik Jlassi'),
  ('Fouta de Hammam Authentique','Fouta authentique 100% coton tissée sur métier à bras.','textile',75.000,90.000,60,4.75,312,'promo','Mahdia','Mohamed Trabelsi'),
  ('Miel de Sidra Zaghouan','Miel de jujubier pur, récolté dans les montagnes de Zaghouan.','gastronomie',38.000,NULL,40,4.80,201,NULL,'Zaghouan','Kacem Zouabi'),
  ('Bague Berbère Argent & Corail','Bague en argent massif ornée de corail rouge naturel.','bijoux',145.000,180.000,12,4.90,156,'promo','Tunis Médina','Amira Chokri'),
  ('Harissa Artisanale de Nabeul','Harissa traditionnelle tunisienne préparée selon la recette ancestrale.','gastronomie',18.000,NULL,200,4.60,567,'bestseller','Nabeul','Fatma Ben Salah'),
  ('Tableau Calligraphie Arabe','Calligraphie arabe à l''encre de chine sur papier Canson.','artisanat',180.000,220.000,9,4.85,74,'new','Tunis','Noureddine Kaabi'),
  ('Eau de Rose Damas Biologique','Eau de rose pure distillée à la vapeur, roses de Damas biologiques.','beaute',22.000,NULL,85,4.90,298,'bio','Djerba','Sonia Baccar');


