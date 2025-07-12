-- ただ単にテーブル作成のSQLです。アプリケーションでの利用はありません。

CREATE TABLE `session_data` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` int DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `payload` text COMMENT '''errorsなど格納''',
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id_UNIQUE` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;