CREATE TABLE IF NOT EXISTS `food_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_id` int(11) NOT NULL,
  `related_food_id` int(11) NOT NULL,
  `similarity_score` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `food_id` (`food_id`),
  KEY `related_food_id` (`related_food_id`),
  CONSTRAINT `fk_food_relationships_food` FOREIGN KEY (`food_id`) REFERENCES `dishes` (`d_id`),
  CONSTRAINT `fk_food_relationships_related` FOREIGN KEY (`related_food_id`) REFERENCES `dishes` (`d_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 