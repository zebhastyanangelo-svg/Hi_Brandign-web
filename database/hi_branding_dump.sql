-- Base de datos MySQL para produccion: rosanny2_hibranding (hibranding.net)
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
DROP TABLE IF EXISTS `admin_password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `admin_password_reset_tokens_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `admin_password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES
(1,'Administrador Hi','admin@hibranding.com','$2y$12$oyi1DP8Au8V2CF0jVoyo/u58ApkvJJjIKaOy5RwHSttkxVTNt1Jxu',NULL,'2026-11-02 02:38:19','2026-11-02 02:38:19');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `focus` varchar(80) NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `meeting_type` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_scheduled_at_index` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `attendees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `document` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendees_booking_id_index` (`booking_id`),
  CONSTRAINT `attendees_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `attendees` WRITE;
/*!40000 ALTER TABLE `attendees` DISABLE KEYS */;
INSERT INTO `attendees` VALUES
(1,1,'Ana','Torres','V-11222333','ana.torres@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(2,1,'Iker','Salas','V-33222444','iker.salas@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(3,2,'Luis','Márquez','V-44333555','luis.marquez@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(4,2,'Sofía','Peña','V-55444666','sofia.pena@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(5,2,'Raúl','Gómez','V-66555777','raul.gomez@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(6,3,'Elena','Vidal','V-77666888','elena.vidal@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(7,3,'Nora','Campos','V-88777999','nora.campos@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(8,4,'Pablo','Cárdenas','V-99888111','pablo.cardenas@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(9,4,'Alicia','Rey','V-11999222','alicia.rey@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(10,4,'Bruno','Díaz','V-22111333','bruno.diaz@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(11,5,'Marta','Ledesma','V-33222555','marta.ledesma@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(12,5,'Iván','Soto','V-44333666','ivan.soto@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(13,6,'Javier','Peña','V-55444777','javier.pena@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(14,6,'Olga','Ferrer','V-66555888','olga.ferrer@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(15,7,'Tomás','Aguirre','V-77666999','tomas.aguirre@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(16,8,'Nadia','Fuentes','V-88777111','nadia.fuentes@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(17,9,'Sergio','Bravo','V-99888222','sergio.bravo@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20'),
(18,9,'Ada','Molina','V-11999333','ada.molina@example.com','2026-11-02 02:38:20','2026-11-02 02:38:20');
/*!40000 ALTER TABLE `attendees` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `reference` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tickets_count` int(10) unsigned NOT NULL,
  `status` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `payment_reference` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `proof_path` varchar(255) DEFAULT NULL,
  `payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_reference_unique` (`reference`),
  KEY `bookings_event_id_status_index` (`event_id`,`status`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `bookings_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES
(1,1,'BK-DEMO001',300.00,2,'aprobado','TRF-EMO001','2026-10-28',NULL,NULL,NULL,'2026-10-29 08:38:19','2026-10-29 02:38:20','2026-10-29 02:38:20'),
(2,1,'BK-DEMO002',450.00,3,'aprobado','TRF-EMO002','2026-10-28',NULL,NULL,NULL,'2026-10-29 08:38:20','2026-10-29 02:38:20','2026-10-29 02:38:20'),
(3,1,'BK-DEMO003',300.00,2,'pendiente','TRF-EMO003','2026-10-31',NULL,NULL,NULL,NULL,'2026-11-01 02:38:20','2026-11-01 02:38:20'),
(4,2,'BK-DEMO004',600.00,3,'aprobado','TRF-EMO004','2026-10-29',NULL,NULL,NULL,'2026-10-30 08:38:20','2026-10-30 02:38:20','2026-10-30 02:38:20'),
(5,2,'BK-DEMO005',400.00,2,'pendiente','TRF-EMO005','2026-10-31',NULL,NULL,NULL,NULL,'2026-11-01 02:38:20','2026-11-01 02:38:20'),
(6,3,'BK-DEMO006',200.00,2,'aprobado','TRF-EMO006','2026-10-30',NULL,NULL,NULL,'2026-10-31 08:38:20','2026-10-31 02:38:20','2026-10-31 02:38:20'),
(7,3,'BK-DEMO007',100.00,1,'rechazado','TRF-EMO007','2026-10-30',NULL,NULL,'El comprobante de pago no es legible. Se pidió reenviar la constancia.',NULL,'2026-10-31 02:38:20','2026-10-31 02:38:20'),
(8,4,'BK-DEMO008',50.00,1,'aprobado','TRF-EMO008','2026-10-31',NULL,NULL,NULL,'2026-11-01 08:38:20','2026-11-01 02:38:20','2026-11-01 02:38:20'),
(9,5,'BK-DEMO009',360.00,2,'pendiente','TRF-EMO009','2026-11-01',NULL,NULL,NULL,NULL,'2026-11-02 02:38:20','2026-11-02 02:38:20');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `community_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `community_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `category` varchar(80) NOT NULL,
  `initials` varchar(4) NOT NULL,
  `tone` varchar(20) NOT NULL DEFAULT 'clay',
  `email` varchar(255) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `community_members_slug_unique` (`slug`),
  KEY `community_members_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `community_members` WRITE;
/*!40000 ALTER TABLE `community_members` DISABLE KEYS */;
INSERT INTO `community_members` VALUES
(1,'Lucía Herrera','lucia-herrera','Norte Studio','Founder & Creative Director','Consumer & Creative','LH','clay','lucia@example.com','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=700&q=82','2026-11-02 02:38:19','2026-11-02 02:38:19'),
(2,'Mateo Ríos','mateo-rios','Forma Capital','Managing Partner','Fintech & VC','MR','olive','mateo@example.com','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=700&q=82','2026-11-02 02:38:19','2026-11-02 02:38:19'),
(3,'Camila Duarte','camila-duarte','Nativa Labs','Co-founder','AI & Deeptech','CD','blue','camila@example.com','https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=700&q=82','2026-11-02 02:38:19','2026-11-02 02:38:19'),
(4,'Andrés Soler','andres-soler','Casa Nómada','Founder','Consumer & Creative','AS','rose','andres@example.com','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=700&q=82','2026-11-02 02:38:19','2026-11-02 02:38:19'),
(5,'Valentina Cruz','valentina-cruz','Lumen Health','CEO','AI & Deeptech','VC','olive','valentina@example.com','https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=700&q=82','2026-11-02 02:38:19','2026-11-02 02:38:19'),
(6,'Diego Montes','diego-montes','Marea Ventures','Investor','Fintech & VC','DM','clay','diego@example.com','https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=700&q=82','2026-11-02 02:38:19','2026-11-02 02:38:19');
/*!40000 ALTER TABLE `community_members` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `event_payment_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_payment_method` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `payment_method_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_payment_method_event_id_payment_method_id_unique` (`event_id`,`payment_method_id`),
  KEY `event_payment_method_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `event_payment_method_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_payment_method_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `event_payment_method` WRITE;
/*!40000 ALTER TABLE `event_payment_method` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_payment_method` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `place` varchar(120) NOT NULL,
  `starts_at` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `capacity` int(10) unsigned NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_starts_at_index` (`starts_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES
(1,'Founder Dinner & Demo Night','CENA · NETWORKING','Una mesa íntima para compartir lo que estamos construyendo y conocer a quienes vienen a cambiar las reglas.','Casa Hi, Polanco','2026-11-08 19:00:00',150.00,50,NULL,1,1,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(2,'Construir una marca que perdure','WORKSHOP · BRAND','Una sesión práctica sobre estrategia, voz y decisiones de diseño con intención.','Estudio Norte','2026-11-15 10:30:00',200.00,30,NULL,0,1,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(3,'Capital con propósito','CONVERSACIÓN · CAPITAL','Fundadores e inversionistas conversan sobre crecimiento sostenible y nuevas formas de financiarlo.','Casa Hi, Polanco','2026-11-22 18:00:00',100.00,40,NULL,0,1,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(4,'Product office hours','CLÍNICA · PRODUCTO','Trae tu reto de producto y trabajémoslo con líderes que ya recorrieron ese camino.','Sala Estudio','2026-11-29 12:00:00',50.00,20,NULL,0,1,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(5,'Ritual de marca: propósito y posición','WORKSHOP · ESTRATEGIA','Una sesión intensiva para definir el propósito de tu marca y traducirlo en decisiones de posicionamiento concretas.','Casa Hi, Polanco','2026-12-06 10:00:00',180.00,25,NULL,0,0,'2026-11-02 02:38:19','2026-11-02 02:38:19');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `income_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) NOT NULL,
  `membership_slug` varchar(255) NOT NULL,
  `membership_name` varchar(255) NOT NULL,
  `amount_usd` decimal(10,2) NOT NULL,
  `amount_ves` decimal(12,2) NOT NULL,
  `bcv_rate` decimal(12,4) NOT NULL,
  `payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `payment_method_name` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `bank_reference` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `account_holder_name` varchar(255) NOT NULL COMMENT 'Titular de la cuenta origen',
  `account_holder_relationship` varchar(255) NOT NULL DEFAULT 'propia' COMMENT 'propia o tercero',
  `proof_path` varchar(255) DEFAULT NULL COMMENT 'Comprobante/captura de pago',
  `status` varchar(255) NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente, aprobado, rechazado',
  `processed_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `income_notifications_reference_unique` (`reference`),
  KEY `income_notifications_payment_method_id_foreign` (`payment_method_id`),
  KEY `income_notifications_status_created_at_index` (`status`,`created_at`),
  KEY `income_notifications_payment_date_index` (`payment_date`),
  CONSTRAINT `income_notifications_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `income_notifications` WRITE;
/*!40000 ALTER TABLE `income_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `income_notifications` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `membership_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) NOT NULL,
  `membership_slug` varchar(255) NOT NULL,
  `membership_name` varchar(255) NOT NULL,
  `membership_price` int(10) unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `tax_id` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `card_last4` varchar(4) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `transfer_reference` varchar(255) DEFAULT NULL,
  `discount_code` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'procesando',
  `paid_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_orders_reference_unique` (`reference`),
  KEY `membership_orders_status_created_at_index` (`status`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `membership_orders` WRITE;
/*!40000 ALTER TABLE `membership_orders` DISABLE KEYS */;
INSERT INTO `membership_orders` VALUES
(1,'MO-DEMO01A','daylight','Daylight',1800,'Camila Duarte','camila.duarte@example.com','J-40123456-7','Colombia','Bogotá','Calle 85 #12-40, Of. 302','paypal',NULL,'camila.duarte@example.com',NULL,NULL,1800.00,0.00,342.00,2142.00,'pagado','2026-10-28 02:38:19',NULL,'2026-10-28 02:38:19','2026-10-28 02:38:19'),
(2,'MO-DEMO02B','studio','Studio',4200,'Mateo Ríos','mateo.rios@example.com','J-40223456-8','México','Ciudad de México','Av. Presidente Masaryk 111, Polanco','transferencia',NULL,NULL,'TRF-77412-BBVA',NULL,4200.00,0.00,798.00,4998.00,'procesando',NULL,NULL,'2026-10-29 02:38:19','2026-10-29 02:38:19'),
(3,'MO-DEMO03C','house','House',8900,'Valentina Cruz','valentina.cruz@example.com','J-40323456-9','España','Madrid','Calle Serrano 45, 3ºB','tarjeta','9017',NULL,NULL,NULL,8900.00,0.00,1691.00,10591.00,'pagado','2026-10-30 02:38:19',NULL,'2026-10-30 02:38:19','2026-10-30 02:38:19'),
(4,'MO-DEMO04D','daylight','Daylight',1800,'Diego Montes','diego.montes@example.com','J-40423456-0','Chile','Santiago','Av. Apoquindo 4500, Of. 701','tarjeta','3382',NULL,NULL,NULL,1800.00,0.00,342.00,2142.00,'rechazado',NULL,'Pago rechazado por el banco emisor. Se solicitó un medio de pago alternativo.','2026-10-30 02:38:19','2026-10-30 02:38:19'),
(5,'MO-DEMO05E','studio','Studio',4200,'Andrés Soler','andres.soler@example.com','J-40523456-1','Argentina','Buenos Aires','Arroyo 878, Piso 4','transferencia',NULL,NULL,'TRF-88213-BBVA','HIBRAND10',4200.00,420.00,718.20,4498.20,'procesando',NULL,NULL,'2026-10-31 02:38:19','2026-10-31 02:38:19'),
(6,'MO-DEMO06F','house','House',8900,'Lucía Herrera','lucia.herrera@example.com','J-40623456-2','México','Guadalajara','Av. Chapultepec 580, Int. 12','paypal',NULL,'lucia.herrera@example.com',NULL,NULL,8900.00,0.00,1691.00,10591.00,'pagado','2026-11-01 02:38:19',NULL,'2026-11-01 02:38:19','2026-11-01 02:38:19');
/*!40000 ALTER TABLE `membership_orders` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `billing_period` varchar(255) NOT NULL DEFAULT 'mes',
  `capacity` int(10) unsigned DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `memberships_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `memberships` WRITE;
/*!40000 ALTER TABLE `memberships` DISABLE KEYS */;
INSERT INTO `memberships` VALUES
(1,'Daylight','daylight','Un lugar para empezar a encontrarte.',1800,'mes',NULL,0,'[\"Acceso flexible entre semana\",\"Caf\\u00e9 de especialidad\",\"Comunidad Hi\"]',1,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(2,'Studio','studio','Tu base para hacer crecer lo que sigue.',4200,'mes',NULL,1,'[\"Escritorio dedicado\",\"Salas de juntas \\u00b7 8 h\",\"Acceso a eventos privados\"]',2,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(3,'House','house','Un espacio propio para equipos ambiciosos.',8900,'mes',NULL,0,'[\"Oficina privada para 2\",\"Salas de juntas \\u00b7 20 h\",\"Acompa\\u00f1amiento estrat\\u00e9gico\"]',3,'2026-11-02 02:38:19','2026-11-02 02:38:19');
/*!40000 ALTER TABLE `memberships` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_10_02_195842_create_appointments_table',1),
(5,'2026_10_02_195843_create_memberships_table',1),
(6,'2026_10_02_205926_create_events_table',1),
(7,'2026_10_02_205927_create_community_members_table',1),
(8,'2026_10_04_220316_add_fields_to_events_table',1),
(9,'2026_10_04_220337_create_bookings_table',1),
(10,'2026_10_04_220354_create_attendees_table',1),
(11,'2026_10_04_220416_create_admin_users_table',1),
(12,'2026_10_04_221608_create_admin_password_reset_tokens_table',1),
(13,'2026_10_07_030825_add_capacity_to_memberships_table',1),
(14,'2026_10_07_030826_create_membership_orders_table',1),
(15,'2026_10_07_172646_create_payment_methods_table',1),
(16,'2026_10_07_172647_create_income_notifications_table',1),
(17,'2026_10_07_213026_refactor_event_payment_methods',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'pago_movil, transferencia, binance_pay, zelle, otro',
  `bank` varchar(255) DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL COMMENT 'RIF / DNI del titular',
  `account_number` varchar(255) DEFAULT NULL COMMENT 'Número de cuenta o teléfono',
  `account_holder` varchar(255) DEFAULT NULL COMMENT 'Titular de la cuenta',
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_methods_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES
(1,'Pago Móvil','pago_movil','Banco de Venezuela','J-40123456-7','0412-123-45-67','Hi Branding C.A.','Disponible 24/7. Envía el comprobante con la referencia SMS.',1,1,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(2,'Transferencia Banesco','transferencia','Banesco','J-40123456-7','0134-0000-00-00000000','Hi Branding C.A.','Transferencias nacionales. Verifica el titular antes de enviar.',1,2,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(3,'Transferencia Mercantil','transferencia','Mercantil','J-40123456-7','0105-0000-00-00000000','Hi Branding C.A.','Transferencias nacionales desde cualquier banco.',1,3,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(4,'Binance Pay','binance_pay',NULL,NULL,'348927155','HiBranding','Envíos en USDT (red BEP20). Indica tu ID de Binance Pay.',1,4,'2026-11-02 02:38:19','2026-11-02 02:38:19'),
(5,'Zelle','zelle',NULL,NULL,'pagos@hibranding.com','Hi Branding LLC','Pagos desde cuentas bancarias de Estados Unidos.',1,5,'2026-11-02 02:38:19','2026-11-02 02:38:19');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Test User','test@example.com','2026-11-02 02:38:18','$2y$12$JHtwjRDF4doXsNiZaGOCp.HHjE2iVgZUru0Oz5Ggr8j0eVEqCX6sq','gzrTcVA2s4','2026-11-02 02:38:19','2026-11-02 02:38:19');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


SET FOREIGN_KEY_CHECKS = 1;
