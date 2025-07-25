-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-07-2025 a las 05:24:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mydb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ahorros`
--

CREATE TABLE `ahorros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_organizacion` int(11) NOT NULL,
  `id_beneficiario` int(11) NOT NULL,
  `monto_ahorrado` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ahorros`
--

INSERT INTO `ahorros` (`id`, `id_organizacion`, `id_beneficiario`, `monto_ahorrado`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1000.00, '2025-07-22 10:50:20', '2025-07-22 10:50:20'),
(2, 1, 1, 10000.00, '2025-07-22 10:54:08', '2025-07-22 10:54:08'),
(3, 3, 3, 12000.00, '2025-07-23 09:51:48', '2025-07-23 09:51:48'),
(4, 2, 4, 12500.00, '2025-07-23 09:51:58', '2025-07-23 09:51:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indicador_generos`
--

CREATE TABLE `indicador_generos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_caja_rural` varchar(255) NOT NULL,
  `departamento` varchar(255) NOT NULL,
  `municipio` varchar(255) NOT NULL,
  `comunidad` varchar(255) NOT NULL,
  `nombre_apellidos` varchar(255) NOT NULL,
  `etnia` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int(11) NOT NULL,
  `identidad` varchar(255) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sexo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9gNFddeRAxSxLlTzG5coxNCrPeWc03eaLLW7NYzj', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJakZFU0hOb2NHaEtNM0pVUVUxMFdHeERiekJIWm5jOVBTSXNJblpoYkhWbElqb2lRMFJ0ZEV4eFZIaDFiV2hZWmxRMVlVbGtkMXBPT0dOQk5tdHlhWFJXTW1vcmJuazBSblJSUWt3d1dIWm5VVms0UmsxdFlXRm9iU3RpU1RsTVRXRk1OR0kyYmt4cU4yNWtaRzB6YURrck1UZEJReXR0Y0dWTFlscDVURmhuV25OQ2Mxa3hUbVZxTkVKM1ZHaDRTbXRKU25oa1NVUk5lbWM0Um1SbGVuZ3pMMDFCTVZBck9EWndVMDlRUWpkd05VeDJhRXg0V2xCTVdYRnFaRVJHV2pZd05VUlJVbFUzZEV0RGVIbDJOV05QU0RrMWVXWm9RbFUwT1dvNGNFbHRVV0pDVVZGbVkzQlFNeTk1WWl0cFZHNVFLM1p4ZUc1eWQyTjBSWGRTZUU1SWNISm9WalJ2SzJGb1ExVnRkVmd3VEc5QlREUjBiVEIwVlM5Q09FNTVWbXMyTUhwVFJETXpaMWhVVDNwblpsZGtlR1Z5VjNFeE9XOWFNazlzWlZZNGNFNTFOVEJ3ZW1SM2VVdEdXRlJGUlVGS2RWVTJkbVpUUVVjelRrOUlVbFZ5U0M5WFowdFlaRXRsZEZSNmFtWnhkelJuVjFKSFNWTmhSRzQxTDNOMGExUmtTV2d5WmpGbVZsVm1aVE5rY21JNE9GVk9LM0J2T1RWalZVNVFiMUZMZDB4cUlpd2liV0ZqSWpvaVpEWm1ZbUk1TXpFek9EUTBNbUl3TTJFNE4ySTBOekk1TnpZelpUVXpNakUxTmpkaFpEZ3hNR1F3WVRGa05HUTBNRFZqTlRSaE5qWTJOR0l5T1RNM1lTSXNJblJoWnlJNklpSjk=', 1753161191),
('i8ef5jnZwaCwIQ3lPZFRm8yWwq23k0b0V4lx5qnS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJalF2YkhZM2MxZEdSRVppYVVSSVowcFpTMkphWTNjOVBTSXNJblpoYkhWbElqb2ljek56UVc5eE5tTnFkRWRqTWt4aFdFNDRTM05RU0d0NVFreEJiRkZ0TkVNMVQzWktaM1JQTW5wSFZITlRTWGNyWlZGWGRGWkZRWGs1Y0dscmRtOUdTSEYxUTFWclEyRXlTMGRyTm5ad2QzVjFZMkZsVmtGTVowNHpWVXhpVTA4eE9XWXZieXRHTTA5V2N5OVpaMmg1WkdKMmNGQnhORTFDTW5CRFVYaFpXRVYzTld0VFQzcG1aMUoyTWs4eEswdFJlSEZqYzBZMlVFd3ZSbTVFUkZGdVkyWlNkRk4xTmxFMlJIUkZWSEJxT1VkeU1VaFNVVnA1TUhsNGNUWTNlRTh5VlcxbFYya3dZM2RFVEUwNVR5c3JlbmxxVlVSWmFqVkJZek5OYW01WGFWTndkMHhzTVhnd2VFUkxTSEJNTVVNMVVHbDJaUzl5V0UxYVVVazJOMHBYYmtoek1qSmpSVEV2UkhGVWRHUXdUbkJ4Ym5WeU9XSXhTa05LYVhWSGFVaFdaVlJpUVdveVdrRmxMMmhzUzFwWFNrWjViRlIyUmtkdlpYQklWVkV5YkZOalRYQjJkRmd4UkcwNVVrcHhXbTgxUlZGc2RFcHNkM0JYZDBoUFZHVlNVa1pyTVVaU1IwdDFTVVZCUFNJc0ltMWhZeUk2SWprMVl6TXdaVEZoT1dJME5tVTROakk0TmpZeFlqVTRNV0prWW1FeFlXUXhZemxoT0RFNE5UazFOalF4TURrMllUWmxZbVF3TVRJMU1EUTVaREZqTldZaUxDSjBZV2NpT2lJaWZRPT0=', 1753413704),
('SHgFwFwXeCobRoVtzOW6zjGbLJF62V2yhKcOBoNl', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbFZZWlZoVlNtd3lVVXg1TWxOd2IwSlBSbmM1V21jOVBTSXNJblpoYkhWbElqb2lVRTl4VUZNd2JIUm1XamROTUhsdFZrbE1iMHhYVFc1UFpuZFBZelpaUnk4eGJYVjNjelFyYVRWYU5WbGpVRE5TZUhoSFVXcG5MMDVYTkZneFR6SnNhWFZSY1c4eWIwVnRUa1J0TW5ZM2RYTTRXVVpJUlhwNE1WTkhUek5yUlZsd2FIbGlWbTFvTlVGUlFWWnFUbU4xZDJwQmQxSlJObEUyUzBoVGRYTlpMME52TVVoSlJ6Tm9aR3N3Vm1aMk5FbG1VRXgxVmtGYWFWUnFRbVpSTmtWaVRsWkRNR3BFY0V0Q1EyeFNWM1pqT1hGQ1VqUXpUbXRDYW5wMFpGQnRVVmdyTjB4bWJFVnhMMVV4UjJKU1RFeDVObFJQZUd4S09VbEllV0pJSzJoMloyb3Zha1JPVmxGbGVrWkdkbVIxYm01clNYWTFSRUZpTnpoVE4zUlVZMDAzUkdKU1VXeENkSFIxUTFJeGRsUjBUVE5JWmxNNVMwMVBjbFV4Y0daWVF6ZEpUbTVwT0RkNmNVTktlRFZVT0dWVVNFSTRiM2RuZGtSTU1sSnVWVTExYWtkNVlsWlJNRTgzWTA5alVUWjJUemR5ZGpWelJDOW9jRFJoZFVsSVptUnhNWGx6VUV0aFdVTlBTM2RGUFNJc0ltMWhZeUk2SWpWaFlqazNaakZtWTJFeFptRXlaRGN6T0dNeFl6WXlZelF3TWpWaU5qa3pObVprWmpVelpUYzFNR1U1WkdNMU56RTROams0T0RjM1ltRTBNRFkwT1RnaUxDSjBZV2NpT2lJaWZRPT0=', 1753244903);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_actividad_economica`
--

CREATE TABLE `tbl_actividad_economica` (
  `Id_Actividad` int(11) NOT NULL,
  `Id_Beneficiario` int(11) NOT NULL,
  `Tipo` enum('Agrícola','No Agrícola') NOT NULL,
  `Numero` smallint(6) NOT NULL,
  `Rubro` varchar(100) NOT NULL,
  `Unidad_Medida` enum('Manzanas','Lempiras') NOT NULL,
  `Cantidad` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_actividad_economica`
--

INSERT INTO `tbl_actividad_economica` (`Id_Actividad`, `Id_Beneficiario`, `Tipo`, `Numero`, `Rubro`, `Unidad_Medida`, `Cantidad`) VALUES
(7, 8, 'Agrícola', 1, 'Maiz', 'Manzanas', 8.00),
(8, 9, 'No Agrícola', 1, 'Tomates', 'Manzanas', 58.00),
(10, 10, 'Agrícola', 1, 'Piña', 'Manzanas', 23.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_aldea`
--

CREATE TABLE `tbl_aldea` (
  `Id_Aldea` int(11) NOT NULL,
  `Id_Municipio` int(11) NOT NULL,
  `Nombre_Aldea` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_aldea`
--

INSERT INTO `tbl_aldea` (`Id_Aldea`, `Id_Municipio`, `Nombre_Aldea`) VALUES
(1, 1, 'Aldea El Chimbo'),
(2, 2, 'Aldea El Carmen'),
(3, 1, 'La cañada'),
(4, 278, 'Aldea el triunfo'),
(5, 110, 'Valle de Angeles'),
(6, 115, 'la pequeña'),
(7, 31, 'san jeronimo'),
(8, 132, 'santa lucia'),
(9, 134, 'Aldea nueva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_anexo_respuesta`
--

CREATE TABLE `tbl_anexo_respuesta` (
  `Id_Anexo_Respuesta` int(11) NOT NULL,
  `Id_Documento` int(11) NOT NULL,
  `Id_Organizacion` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_anexo_respuesta`
--

INSERT INTO `tbl_anexo_respuesta` (`Id_Anexo_Respuesta`, `Id_Documento`, `Id_Organizacion`, `Id_Usuario`, `Fecha`) VALUES
(1, 1, 1, 2, '2025-07-01 21:02:05'),
(2, 1, 1, 2, '2025-07-01 21:02:06'),
(3, 1, 1, 2, '2025-07-01 21:02:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_beneficiario`
--

CREATE TABLE `tbl_beneficiario` (
  `Id_Beneficiario` int(11) NOT NULL,
  `Id_Organizacion` int(11) NOT NULL,
  `Nombre_Beneficiario` varchar(60) NOT NULL,
  `DNI` varchar(15) NOT NULL,
  `Tipo_Cargo` varchar(50) DEFAULT NULL,
  `Tipo_De_Socio` varchar(45) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `genero` char(1) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `estado_civil` varchar(50) DEFAULT NULL,
  `etnia` varchar(100) DEFAULT NULL,
  `nivel_educativo` varchar(100) DEFAULT NULL,
  `medio_comunicacion` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `comunidad` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_beneficiario`
--

INSERT INTO `tbl_beneficiario` (`Id_Beneficiario`, `Id_Organizacion`, `Nombre_Beneficiario`, `DNI`, `Tipo_Cargo`, `Tipo_De_Socio`, `categoria`, `Telefono`, `genero`, `fecha_nacimiento`, `edad`, `estado_civil`, `etnia`, `nivel_educativo`, `medio_comunicacion`, `departamento`, `municipio`, `comunidad`, `direccion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'Edgard Samir Lanza Izaguirress', '0801-2000-17207', 'Presidente(a)', 'Socio', 'todaaaaaas', '9716-6764', 'M', '2000-08-25', 24, 'Viudo(a)', 'Garífuna', 'Educación superior', 'Teléfono', 'Francisco Morazán', 'Tegucigalpa', 'Comayagüela', 'Col.carrizal #1', 0, '2025-07-06 04:43:56', '2025-07-22 04:52:08'),
(2, 2, 'samir samuel', '0801-2001-18207', 'Junta de Vigilancia Vocal', 'Socio', 'ededed', '9828-8979', 'F', '2000-02-23', 24, 'Soltero(a)', 'Negro de habla inglesa o Creole', 'Sin estudios', 'Teléfono', 'Olancho', 'Juticalpa', 'Catacamas', 'eeded', 0, '2025-07-09 02:59:44', '2025-07-22 04:52:20'),
(3, 3, 'Jose Armando Trejo Valladares', '0801200301901', NULL, 'Socio', NULL, '9394-7057', 'M', '2004-01-17', 45, 'Casado(a)', 'Mestizo', 'Educación superior', 'Teléfono', 'Francisco Morazán', 'Tegucigalpa', 'La cañada', NULL, 1, '2025-07-14 00:18:03', '2025-07-25 09:20:31'),
(4, 3, 'Jose Armando Trejo Garciaa', '0801203301901', 'Vicepresidente(a)', 'Cliente', 'sdsdf', '9394-7057', 'M', '2008-12-10', 26, 'Viudo(a)', 'Mestizo', 'Educación superior', 'Teléfono', 'Francisco Morazán', 'Tegucigalpa', 'La cañada', 'Reparto Por Arriba', 1, '2025-07-14 01:21:51', '2025-07-25 08:44:34'),
(5, 3, 'Trejo V', '0801-2033-02011', NULL, NULL, NULL, '9397-4857', 'M', '2016-01-07', 23, 'Soltero(a)', 'Mestizo', 'Educación superior', 'Computadora', NULL, NULL, NULL, NULL, 1, '2025-07-14 01:29:08', '2025-07-14 01:29:08'),
(6, 3, 'ANAEL', '0108-2033-20125', NULL, NULL, NULL, '9371-2584', 'F', '2009-12-29', 25, 'Casado(a)', 'Mestizo', 'Educación media', 'Teléfono', NULL, NULL, NULL, NULL, 1, '2025-07-14 01:31:10', '2025-07-14 01:31:10'),
(7, 3, 'Hamlet Valldaressds', '0801-2003-20214', 'Vicepresidente(a)', 'ssdds', 'sdsd', '9349-2147', 'M', '2025-07-01', 15, 'Soltero(a)', 'Mestizo', 'Educación media', 'Teléfono', 'Francisco Morazán', 'Tegucigalpa', 'La cañada', 'Reparto Por Bajo', 0, '2025-07-14 01:34:53', '2025-07-14 02:26:47'),
(8, 1, 'Ahser Santiago Trejo', '0201-2025-20154', 'Presidente(a)', 'efesdf', 'sdfdf', '6454-5256', 'M', '2012-01-31', 25, 'Casado(a)', NULL, 'Educación superior', 'Teléfono', 'Francisco Morazán', 'Tegucigalpa', 'Aldea El Chimbo', 'La caseta', 1, '2025-07-14 02:46:36', '2025-07-14 02:46:52'),
(9, 4, 'Jose Luis Peralessdfsdf', '0801203220150', 'Secretario(a)', NULL, 'sdffsd', '9555-3026', 'M', '2004-01-06', 21, 'Casado(a)', 'Lenca', NULL, 'Teléfono', 'SANTA BARBARA', 'NUEVA FRONTERA', 'Aldea el triunfo', 'Reparto Por Bajo', 1, '2025-07-14 04:11:08', '2025-07-22 10:18:51'),
(10, 5, 'Osiris Valladaress', '0201-2015-10244', 'Secretario(a)', 'sdsdf', 'sdfsdf', '6354-9854', 'F', '1996-12-31', 28, 'Unión Libre', 'Tolupan', 'Educación superior', 'Teléfono', 'FRANCISCO MORAZAN', 'DISTRITO CENTRAL', 'Valle de Angeles', 'Calle santa elena', 1, '2025-07-14 04:17:43', '2025-07-14 04:18:15'),
(11, 1, 'Angie Zambrano', '0201-5258-52454', 'Vicepresidente(a)', 'sdasd', 'asdas', '9394-7057', 'F', '1999-01-05', 26, 'Soltero(a)', 'Mestizo', 'Educación básica', 'Teléfono', 'ATLANTIDA', 'LA CEIBA', 'Aldea El Chimbo', 'dfsdfsdf', 1, '2025-07-14 04:32:49', '2025-07-14 04:36:32'),
(12, 6, 'samuel sm', '0801200017208', 'Tesorero(a)', 'Socio', '1', '9829-6768', 'M', '1999-02-18', 26, 'Casado(a)', 'Miskito', 'Educación básica', 'Tablet', 'FRANCISCO MORAZAN', 'GUAIMACA', 'la pequeña', 'colonia #1', 1, '2025-07-18 05:46:33', '2025-07-23 09:44:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_capacitacion`
--

CREATE TABLE `tbl_capacitacion` (
  `Id_Capacitacion` int(11) NOT NULL,
  `Id_Tecnico` int(11) NOT NULL,
  `Tema` varchar(45) DEFAULT NULL,
  `Fecha_Inicio` date DEFAULT NULL,
  `Fecha_Fin` date DEFAULT NULL,
  `Material_Referencia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_coordenadas_municipio`
--

CREATE TABLE `tbl_coordenadas_municipio` (
  `Id` int(11) NOT NULL,
  `Id_Departamento` int(11) NOT NULL,
  `Id_Municipio` int(11) NOT NULL,
  `coordenada_x` decimal(10,8) NOT NULL,
  `coordenada_y` decimal(10,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_coordenadas_municipio`
--

INSERT INTO `tbl_coordenadas_municipio` (`Id`, `Id_Departamento`, `Id_Municipio`, `coordenada_x`, `coordenada_y`) VALUES
(1, 3, 31, -87.61063734, 14.62743758),
(2, 8, 132, -87.10167520, 14.11833060),
(3, 8, 134, -87.10110605, 14.01493173);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_datos`
--

CREATE TABLE `tbl_datos` (
  `Id_Dato` int(11) NOT NULL,
  `Id_Documento` int(11) NOT NULL,
  `nombre_dato` varchar(30) DEFAULT NULL,
  `Tipo_Dato` enum('texto','numero','decimal','fecha') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_datos`
--

INSERT INTO `tbl_datos` (`Id_Dato`, `Id_Documento`, `nombre_dato`, `Tipo_Dato`) VALUES
(1, 1, 'Nombre de la Caja Rural', 'texto'),
(2, 1, 'Departamento', 'texto'),
(3, 1, 'Municipio', 'texto'),
(4, 1, 'Comunidad', 'texto'),
(5, 1, 'Nombre y Apellidos del Socio(a', 'texto'),
(6, 1, 'Etnia', 'texto'),
(7, 1, 'Fecha de Nacimiento', 'fecha'),
(8, 1, 'Edad', 'numero'),
(9, 1, 'No. de Identidad', 'texto'),
(10, 1, 'Cargo', 'texto'),
(11, 1, 'No. de Telefono', 'texto'),
(12, 1, 'Nombre del Técnico que Asiste', 'texto'),
(13, 1, 'Nombre del Proyecto Actual', 'texto'),
(14, 1, 'Socios(as) H', 'numero'),
(15, 1, 'Socios(as) M', 'numero'),
(16, 1, 'Jóvenes H', 'numero'),
(17, 1, 'Jóvenes M', 'numero'),
(18, 1, 'Acciones (L)', 'decimal'),
(19, 1, 'Ahorros (L)', 'decimal'),
(20, 1, 'Saldo de Préstamos Vigentes (L', 'decimal'),
(21, 1, 'Actividad Agrícola 1 Rubro', 'texto'),
(22, 1, 'Actividad Agrícola 1 Area Manz', 'decimal'),
(23, 1, 'Actividad Agrícola 2 Rubro', 'texto'),
(24, 1, 'Actividad Agrícola 2 Area Manz', 'decimal'),
(25, 1, 'Actividad Agrícola 3 Rubro', 'texto'),
(26, 1, 'Actividad Agrícola 3 Area Manz', 'decimal'),
(27, 1, 'Actividad No Agrícola 1 Rubro', 'texto'),
(28, 1, 'Actividad No Agrícola 1 Ventas', 'decimal'),
(29, 1, 'Actividad No Agrícola 2 Rubro', 'texto'),
(30, 1, 'Actividad No Agrícola 2 Ventas', 'decimal'),
(31, 2, 'Nombre de la Caja Rural', 'texto'),
(32, 2, 'Departamento', 'texto'),
(33, 2, 'Municipio', 'texto'),
(34, 2, 'Comunidad', 'texto'),
(35, 2, 'Nombre Completo', 'texto'),
(36, 2, 'Etnia', 'texto'),
(37, 2, 'Fecha de Nacimiento', 'fecha'),
(38, 2, 'Edad', 'numero'),
(39, 2, 'No. de Identidad', 'texto'),
(40, 2, 'No. de Telefono', 'texto'),
(41, 2, 'Clientes Adultos H', 'numero'),
(42, 2, 'Clientes Adultos M', 'numero'),
(43, 2, 'Jóvenes H', 'numero'),
(44, 2, 'Jóvenes M', 'numero'),
(45, 2, 'Niños H', 'numero'),
(46, 2, 'Niños M', 'numero'),
(47, 2, 'Ahorros (L)', 'decimal'),
(48, 2, 'Préstamos (L)', 'decimal'),
(49, 2, 'Actividad Agrícola 1 Rubro', 'texto'),
(50, 2, 'Actividad Agrícola 1 Area Manz', 'decimal'),
(51, 2, 'Actividad Agrícola 2 Rubro', 'texto'),
(52, 2, 'Actividad Agrícola 2 Area Manz', 'decimal'),
(53, 2, 'Actividad Agrícola 3 Rubro', 'texto'),
(54, 2, 'Actividad Agrícola 3 Area Manz', 'decimal'),
(55, 2, 'Actividad No Agrícola 1 Rubro', 'texto'),
(56, 2, 'Actividad No Agrícola 1 Ventas', 'decimal'),
(57, 2, 'Actividad No Agrícola 2 Rubro', 'texto'),
(58, 2, 'Actividad No Agrícola 2 Ventas', 'decimal'),
(59, 3, 'Nombre de la Caja Rural', 'texto'),
(60, 3, 'Departamento', 'texto'),
(61, 3, 'Municipio', 'texto'),
(62, 3, 'Comunidad', 'texto'),
(63, 3, 'Nombre y Apellidos del Socio(a', 'texto'),
(64, 3, 'Etnia', 'texto'),
(65, 3, 'Fecha de Nacimiento', 'fecha'),
(66, 3, 'Edad', 'numero'),
(67, 3, 'No. de Identidad', 'texto'),
(68, 3, 'Cargo', 'texto'),
(69, 3, 'Tiempo de Ser socio (meses)', 'numero'),
(70, 3, 'Nombre del Proyecto Actual', 'texto'),
(71, 3, 'Socios(as) H', 'numero'),
(72, 3, 'Socios(as) M', 'numero'),
(73, 3, 'Jóvenes H', 'numero'),
(74, 3, 'Jóvenes M', 'numero'),
(75, 3, 'Clientes Adultos H', 'numero'),
(76, 3, 'Clientes Adultos M', 'numero'),
(77, 3, 'Jóvenes Clientes H', 'numero'),
(78, 3, 'Jóvenes Clientes M', 'numero'),
(79, 3, 'Niños Clientes H', 'numero'),
(80, 3, 'Niños Clientes M', 'numero'),
(81, 3, 'Acciones (L)', 'decimal'),
(82, 3, 'Promedio de Acciones Mensual', 'decimal'),
(83, 3, 'Ahorros (L)', 'decimal'),
(84, 3, 'Promedio de Ahorros Mensual', 'decimal'),
(85, 3, 'Préstamo Otorgado (Lps)', 'decimal'),
(86, 3, 'Fecha de Otorgado', 'fecha'),
(87, 3, 'Vigencia (Numero de meses)', 'numero'),
(88, 3, 'Tipo de Garantia', 'texto'),
(89, 3, 'Tasa de interes', 'decimal'),
(90, 3, 'Rubro a financiar', 'texto'),
(91, 3, 'Pago saldado a la fecha', 'decimal'),
(92, 3, 'Tiene Mora? Cantidad', 'numero'),
(93, 3, 'Tiempo de morosidad (dias)', 'numero'),
(94, 3, 'Saldo de Préstamos Vigentes (L', 'decimal'),
(95, 3, '% de mora', 'decimal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_departamento`
--

CREATE TABLE `tbl_departamento` (
  `Id_Departamento` int(11) NOT NULL,
  `Nombre_Departamento` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_departamento`
--

INSERT INTO `tbl_departamento` (`Id_Departamento`, `Nombre_Departamento`) VALUES
(1, 'ATLANTIDA'),
(2, 'COLON'),
(3, 'COMAYAGUA'),
(4, 'COPAN'),
(5, 'CORTES'),
(6, 'CHOLUTECA'),
(7, 'EL PARAISO'),
(8, 'FRANCISCO MORAZAN'),
(9, 'GRACIAS A DIOS'),
(10, 'INTIBUCA'),
(11, 'ISLAS DE LA BAHIA'),
(12, 'LA PAZ'),
(13, 'LEMPIRA'),
(14, 'OCOTEPEQUE'),
(15, 'OLANCHO'),
(16, 'SANTA BARBARA'),
(17, 'VALLE'),
(18, 'YORO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_detalle_respuesta`
--

CREATE TABLE `tbl_detalle_respuesta` (
  `Id_Detalle_Respuesta` int(11) NOT NULL,
  `Id_Dato` int(11) NOT NULL,
  `Id_Anexo_Respuesta` int(11) NOT NULL,
  `Valor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_detalle_respuesta`
--

INSERT INTO `tbl_detalle_respuesta` (`Id_Detalle_Respuesta`, `Id_Dato`, `Id_Anexo_Respuesta`, `Valor`) VALUES
(1, 1, 1, 'Caja Rural El Progreso'),
(2, 2, 1, 'Francisco Morazán'),
(3, 3, 1, 'Tegucigalpa'),
(4, 4, 1, 'Col. Centro'),
(5, 5, 1, 'Juan Pérez'),
(6, 6, 1, 'Lenca'),
(7, 7, 1, '1980-05-10'),
(8, 8, 1, '45'),
(9, 9, 1, '0801198012345'),
(10, 10, 1, 'Presidente'),
(11, 11, 1, '98765432'),
(12, 12, 1, 'Pedro López'),
(13, 13, 1, 'Proyecto A'),
(14, 14, 1, '10'),
(15, 15, 1, '12'),
(16, 16, 1, '3'),
(17, 17, 1, '2'),
(18, 18, 1, '5000'),
(19, 19, 1, '2000'),
(20, 20, 1, '10000'),
(21, 21, 1, 'Maíz'),
(22, 22, 1, '2'),
(23, 23, 1, 'Frijol'),
(24, 24, 1, '1'),
(25, 25, 1, 'Café'),
(26, 26, 1, '1.5'),
(27, 27, 1, 'Tienda'),
(28, 28, 1, '3000'),
(29, 29, 1, 'Panadería'),
(30, 30, 1, '1500'),
(31, 1, 2, 'Caja Rural La Esperanza'),
(32, 2, 2, 'Intibucá'),
(33, 3, 2, 'La Esperanza'),
(34, 4, 2, 'Barrio Abajo'),
(35, 5, 2, 'María López'),
(36, 6, 2, 'Lenca'),
(37, 7, 2, '1992-08-15'),
(38, 8, 2, '33'),
(39, 9, 2, '1001199209876'),
(40, 10, 2, 'Secretaria'),
(41, 11, 2, '99887766'),
(42, 12, 2, 'Ana Torres'),
(43, 13, 2, 'Proyecto B'),
(44, 14, 2, '8'),
(45, 15, 2, '15'),
(46, 16, 2, '2'),
(47, 17, 2, '3'),
(48, 18, 2, '4000'),
(49, 19, 2, '2500'),
(50, 20, 2, '8000'),
(51, 21, 2, 'Caña'),
(52, 22, 2, '1'),
(53, 23, 2, 'Arroz'),
(54, 24, 2, '0.5'),
(55, 25, 2, 'Plátano'),
(56, 26, 2, '2'),
(57, 27, 2, 'Pulpería'),
(58, 28, 2, '2000'),
(59, 29, 2, 'Tortillería'),
(60, 30, 2, '1000'),
(61, 1, 3, 'Caja Rural El Futuro'),
(62, 2, 3, 'Lempira'),
(63, 3, 3, 'Gracias'),
(64, 4, 3, 'Col. Nueva'),
(65, 5, 3, 'Carlos Martínez'),
(66, 6, 3, 'Mestizo'),
(67, 7, 3, '1975-12-20'),
(68, 8, 3, '49'),
(69, 9, 3, '1101197512345'),
(70, 10, 3, 'Tesorero'),
(71, 11, 3, '91234567'),
(72, 12, 3, 'Luis Gómez'),
(73, 13, 3, 'Proyecto C'),
(74, 14, 3, '12'),
(75, 15, 3, '10'),
(76, 16, 3, '4'),
(77, 17, 3, '1'),
(78, 18, 3, '6000'),
(79, 19, 3, '3000'),
(80, 20, 3, '12000'),
(81, 21, 3, 'Sorgo'),
(82, 22, 3, '3'),
(83, 23, 3, 'Ajonjolí'),
(84, 24, 3, '2'),
(85, 25, 3, 'Cacao'),
(86, 26, 3, '0.8'),
(87, 27, 3, 'Farmacia'),
(88, 28, 3, '5000'),
(89, 29, 3, 'Ferretería'),
(90, 30, 3, '2500');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_documento`
--

CREATE TABLE `tbl_documento` (
  `Id_Documento` int(11) NOT NULL,
  `Nombre` varchar(60) NOT NULL,
  `Descripcion` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_documento`
--

INSERT INTO `tbl_documento` (`Id_Documento`, `Nombre`, `Descripcion`) VALUES
(1, 'LISTADO DE SOCIOS', 'Listado de socios de la caja rural'),
(2, 'LISTADO DE CLIENTES PARTICULARES', 'Listado de clientes particulares de la caja rural'),
(3, 'INDICADORES FINANCIEROS', 'Indicadores financieros de la caja rural');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_emprendimiento`
--

CREATE TABLE `tbl_emprendimiento` (
  `Id_Emprendimiento` int(11) NOT NULL,
  `Caja_Rural` varchar(100) NOT NULL,
  `Id_Municipio` int(11) NOT NULL,
  `Comunidad` varchar(100) DEFAULT NULL,
  `Fecha_Inicio_Operaciones` date DEFAULT NULL,
  `Socios_Hombres` int(11) DEFAULT 0,
  `Socios_Mujeres` int(11) DEFAULT 0,
  `Tipo_Negocio` text DEFAULT NULL,
  `Ventas_Trimestrales` decimal(15,2) DEFAULT 0.00,
  `Empleos_Hombres` int(11) DEFAULT 0,
  `Empleos_Mujeres` int(11) DEFAULT 0,
  `Id_Tecnico` int(11) DEFAULT NULL,
  `Fecha_Levantamiento` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_evaluacion`
--

CREATE TABLE `tbl_evaluacion` (
  `Id_Evaluacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_financiera`
--

CREATE TABLE `tbl_financiera` (
  `Id_Financiera` int(11) NOT NULL,
  `Id_Organizacion` int(11) NOT NULL,
  `Ahorros` decimal(10,0) DEFAULT NULL,
  `Prestamos` decimal(10,0) DEFAULT NULL,
  `Aportaciones` decimal(10,0) DEFAULT NULL,
  `Fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ms_bitacora`
--

CREATE TABLE `tbl_ms_bitacora` (
  `Id_Bitacora` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Id_Objeto` int(11) NOT NULL,
  `Fecha` datetime NOT NULL,
  `Accion` varchar(45) NOT NULL,
  `Descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ms_bitacora`
--

INSERT INTO `tbl_ms_bitacora` (`Id_Bitacora`, `Id_Usuario`, `Id_Objeto`, `Fecha`, `Accion`, `Descripcion`) VALUES
(668, 2, 4, '2025-07-23 01:51:46', 'Ingreso', 'El usuario accedió a la bitácora.'),
(669, 2, 4, '2025-07-23 01:52:17', 'Ingreso', 'El usuario accedió a la bitácora.'),
(670, 2, 3, '2025-07-23 01:53:26', 'Ingreso', 'El usuario ingresó a la gestión de roles'),
(671, 2, 13, '2025-07-23 02:01:19', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(672, 2, 14, '2025-07-23 02:01:20', 'Ingreso', 'El usuario ingresó a la gestión de emprendimientos'),
(673, 2, 4, '2025-07-23 02:01:27', 'Ingreso', 'El usuario accedió a la bitácora.'),
(674, 2, 14, '2025-07-23 02:01:50', 'Ingreso', 'El usuario ingresó a la gestión de emprendimientos'),
(675, 2, 2, '2025-07-23 02:01:55', 'Ingreso', 'El usuario ingresó a la gestión de usuarios'),
(676, 2, 4, '2025-07-23 02:01:58', 'Ingreso', 'El usuario accedió a la bitácora.'),
(677, 2, 13, '2025-07-23 02:02:26', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(678, 2, 4, '2025-07-23 02:02:32', 'Ingreso', 'El usuario accedió a la bitácora.'),
(679, 2, 4, '2025-07-23 02:03:35', 'Ingreso', 'El usuario accedió a la bitácora.'),
(680, 2, 4, '2025-07-23 02:09:37', 'Ingreso', 'El usuario accedió a la bitácora.'),
(681, 2, 4, '2025-07-23 02:09:52', 'Ingreso', 'El usuario accedió a la bitácora.'),
(682, 2, 4, '2025-07-23 02:10:16', 'Ingreso', 'El usuario accedió a la bitácora.'),
(683, 2, 13, '2025-07-23 02:10:19', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(684, 2, 14, '2025-07-23 02:10:42', 'Ingreso', 'El usuario ingresó a la gestión de emprendimientos'),
(685, 2, 4, '2025-07-23 02:10:56', 'Ingreso', 'El usuario accedió a la bitácora.'),
(686, 2, 4, '2025-07-23 02:22:10', 'Ingreso', 'El usuario accedió a la bitácora.'),
(687, 2, 5, '2025-07-23 02:22:12', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(688, 2, 12, '2025-07-23 02:22:17', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(689, 2, 4, '2025-07-23 02:22:21', 'Ingreso', 'El usuario accedió a la bitácora.'),
(690, 2, 12, '2025-07-23 02:22:30', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(691, 2, 4, '2025-07-23 02:22:35', 'Ingreso', 'El usuario accedió a la bitácora.'),
(692, 2, 4, '2025-07-23 02:22:40', 'Ingreso', 'El usuario accedió a la bitácora.'),
(693, 2, 13, '2025-07-23 02:22:45', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(694, 2, 5, '2025-07-23 02:22:54', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(695, 2, 4, '2025-07-23 02:23:01', 'Ingreso', 'El usuario accedió a la bitácora.'),
(696, 2, 4, '2025-07-23 02:26:43', 'Ingreso', 'El usuario accedió a la bitácora.'),
(697, 2, 11, '2025-07-23 02:26:45', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(698, 2, 4, '2025-07-23 02:26:49', 'Ingreso', 'El usuario accedió a la bitácora.'),
(699, 2, 13, '2025-07-23 02:27:39', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(700, 2, 13, '2025-07-23 02:30:38', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(701, 2, 10, '2025-07-23 02:30:48', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(702, 2, 10, '2025-07-23 02:35:35', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(703, 2, 13, '2025-07-23 02:40:12', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(704, 2, 5, '2025-07-23 02:40:17', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(705, 2, 5, '2025-07-23 02:41:23', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(706, 2, 11, '2025-07-23 02:52:49', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(707, 2, 1, '2025-07-23 03:33:38', 'Ingreso', 'El usuario ha iniciado sesión.'),
(708, 2, 11, '2025-07-23 03:33:38', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(709, 2, 11, '2025-07-23 03:37:18', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(710, 2, 11, '2025-07-23 03:40:19', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(711, 2, 11, '2025-07-23 03:43:14', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(712, 2, 11, '2025-07-23 03:43:34', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Valladares'),
(713, 2, 11, '2025-07-23 03:43:34', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(714, 2, 11, '2025-07-23 03:43:59', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Garcia'),
(715, 2, 11, '2025-07-23 03:43:59', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(716, 2, 11, '2025-07-23 03:44:14', 'Update', 'Actualizó el socio/cliente: samuel sm'),
(717, 2, 11, '2025-07-23 03:44:14', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(718, 2, 3, '2025-07-23 03:44:46', 'Ingreso', 'El usuario ingresó a la gestión de roles'),
(719, 2, 10, '2025-07-23 03:44:49', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(720, 2, 5, '2025-07-23 03:44:51', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(721, 2, 11, '2025-07-23 03:45:00', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(722, 2, 12, '2025-07-23 03:45:04', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(723, 2, 5, '2025-07-23 03:46:15', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(724, 2, 14, '2025-07-23 03:46:16', 'Ingreso', 'El usuario ingresó a la gestión de emprendimientos'),
(725, 2, 12, '2025-07-23 03:46:18', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(726, 2, 14, '2025-07-23 03:46:28', 'Ingreso', 'El usuario ingresó a la gestión de emprendimientos'),
(727, 2, 14, '2025-07-23 03:47:16', 'Ingreso', 'El usuario ingresó a la gestión de emprendimientos'),
(728, 2, 13, '2025-07-23 03:47:18', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(729, 2, 13, '2025-07-23 03:51:48', 'Nuevo', 'Creó un nuevo ahorro'),
(730, 2, 13, '2025-07-23 03:51:48', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(731, 2, 13, '2025-07-23 03:51:58', 'Nuevo', 'Creó un nuevo ahorro'),
(732, 2, 13, '2025-07-23 03:51:58', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(733, 2, 5, '2025-07-23 03:52:16', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(734, 2, 11, '2025-07-23 03:52:17', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(735, 2, 11, '2025-07-23 03:52:30', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Valladares'),
(736, 2, 11, '2025-07-23 03:52:30', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(737, 2, 11, '2025-07-23 03:52:39', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Garcia'),
(738, 2, 11, '2025-07-23 03:52:39', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(739, 2, 13, '2025-07-23 03:52:42', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(740, 2, 5, '2025-07-23 03:52:56', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(741, 2, 11, '2025-07-23 03:53:00', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(742, 2, 11, '2025-07-23 03:53:10', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Valladares'),
(743, 2, 11, '2025-07-23 03:53:10', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(744, 2, 13, '2025-07-23 03:53:16', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(745, 2, 3, '2025-07-23 03:56:05', 'Ingreso', 'El usuario ingresó a la gestión de roles'),
(746, 2, 11, '2025-07-23 03:56:16', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(747, 2, 5, '2025-07-23 03:56:19', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(748, 2, 2, '2025-07-23 03:56:33', 'Ingreso', 'El usuario ingresó a la gestión de usuarios'),
(749, 2, 4, '2025-07-23 03:56:43', 'Ingreso', 'El usuario accedió a la bitácora.'),
(750, 2, 10, '2025-07-23 03:56:50', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(751, 2, 3, '2025-07-23 03:56:55', 'Ingreso', 'El usuario ingresó a la gestión de roles'),
(752, 2, 10, '2025-07-23 03:56:58', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(753, 2, 10, '2025-07-23 04:05:09', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(754, 2, 5, '2025-07-23 04:07:57', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(755, 2, 2, '2025-07-23 04:08:02', 'Salida', 'El usuario ha cerrado sesión.'),
(756, 15, 1, '2025-07-23 04:08:06', 'Ingreso', 'El usuario ha iniciado sesión.'),
(757, 15, 5, '2025-07-23 04:09:52', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(758, 15, 5, '2025-07-23 04:10:54', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(759, 15, 5, '2025-07-23 04:10:56', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(760, 15, 5, '2025-07-23 04:17:56', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(761, 2, 1, '2025-07-25 02:35:48', 'Ingreso', 'El usuario ha iniciado sesión.'),
(762, 2, 2, '2025-07-25 02:35:57', 'Ingreso', 'El usuario ingresó a la gestión de usuarios'),
(763, 2, 10, '2025-07-25 02:36:14', 'Ingreso', 'El usuario ingresó a la gestión de objetos'),
(764, 2, 3, '2025-07-25 02:36:17', 'Ingreso', 'El usuario ingresó a la gestión de roles'),
(765, 2, 3, '2025-07-25 02:36:26', 'Ingreso', 'El usuario ingresó a la gestión de roles'),
(766, 2, 2, '2025-07-25 02:36:35', 'Ingreso', 'El usuario ingresó a la gestión de usuarios'),
(767, 2, 5, '2025-07-25 02:36:37', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(768, 2, 12, '2025-07-25 02:36:47', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(769, 2, 13, '2025-07-25 02:36:53', 'Ingreso', 'El usuario ingresó a la gestión de ahorros'),
(770, 2, 12, '2025-07-25 02:42:01', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(771, 2, 11, '2025-07-25 02:44:03', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(772, 2, 11, '2025-07-25 02:44:34', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Garciaa'),
(773, 2, 11, '2025-07-25 02:44:35', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(774, 2, 5, '2025-07-25 02:47:58', 'Ingreso', 'El usuario ingresó a la gestión de organizaciones'),
(775, 2, 12, '2025-07-25 02:50:10', 'Ingreso', 'El usuario ingresó a la gestión de créditos'),
(776, 2, 1, '2025-07-25 03:19:51', 'Ingreso', 'El usuario ha iniciado sesión.'),
(777, 2, 11, '2025-07-25 03:20:08', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(778, 2, 11, '2025-07-25 03:20:31', 'Update', 'Actualizó el socio/cliente: Jose Armando Trejo Valladares'),
(779, 2, 11, '2025-07-25 03:20:32', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes'),
(780, 2, 11, '2025-07-25 03:20:55', 'Ingreso', 'El usuario ingresó a la gestión de socios/clientes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ms_hist_contraseña`
--

CREATE TABLE `tbl_ms_hist_contraseña` (
  `Id_Historial` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Contraseña` varchar(255) DEFAULT NULL,
  `Fecha_Creacion` datetime DEFAULT NULL,
  `Creado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ms_hist_contraseña`
--

INSERT INTO `tbl_ms_hist_contraseña` (`Id_Historial`, `Id_Usuario`, `Contraseña`, `Fecha_Creacion`, `Creado_Por`, `Fecha_Modificacion`) VALUES
(1, 2, '$2y$12$EGOuFJ2WjMZf0ks8P8s0SeRRGAeu/yrwj1dfHZOYBvmlYdUw8aMOm', '2025-06-23 04:24:20', 'system', NULL),
(2, 33, '$2y$12$PWsI9cOOQe2sMiST3JxX4.av4CWlISolzhCzq1CNGQ3NzI0ltko36', '2025-06-30 02:56:10', 'system', NULL),
(3, 44, '$2y$12$zqMe7dainfcyKcaR5Sn08eTVtCFD8Z05mPeixF6efFMlPWlsi4MQy', '2025-06-30 05:13:13', 'system', NULL),
(4, 47, '$2y$12$RzNDug5NAV649pOrqsb3zeZ2fDkyEFRQOHyVe6q2cXb3zCQ6f6ysK', '2025-06-30 17:31:36', 'system', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ms_objeto`
--

CREATE TABLE `tbl_ms_objeto` (
  `Id_Objeto` int(11) NOT NULL,
  `Objeto` varchar(100) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Tipo_Objeto` varchar(60) NOT NULL,
  `Creado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Creacion` date DEFAULT NULL,
  `Modificado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Modificacion` date DEFAULT NULL,
  `Estado` varchar(20) DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ms_objeto`
--

INSERT INTO `tbl_ms_objeto` (`Id_Objeto`, `Objeto`, `Descripcion`, `Tipo_Objeto`, `Creado_Por`, `Fecha_Creacion`, `Modificado_Por`, `Fecha_Modificacion`, `Estado`) VALUES
(1, 'Dashboard', 'Pantalla principal del sistema', 'Pantalla', 'admin', '2025-06-21', NULL, NULL, 'ACTIVO'),
(2, 'Usuarios', 'Gestión de usuarios', 'Pantalla', 'admin', '2025-06-21', NULL, NULL, 'ACTIVO'),
(3, 'Roles', 'Gestión de roles y permisos', 'Pantalla', 'admin', '2025-06-21', NULL, NULL, 'ACTIVO'),
(4, 'Bitácora', 'Registro de actividades', 'Pantalla', 'admin', '2025-06-21', NULL, NULL, 'ACTIVO'),
(5, 'Organizaciones', 'Gestión de organizaciones', 'Pantalla', 'admin', '2025-06-21', NULL, NULL, 'ACTIVO'),
(6, 'Seguridad', 'Opciones de seguridad del sistema', 'Menú', 'admin', '2025-07-05', NULL, NULL, 'ACTIVO'),
(7, 'Mantenimiento', 'Opciones de mantenimiento del sistema', 'Menú', 'admin', '2025-07-05', NULL, NULL, 'ACTIVO'),
(8, 'Administracion', 'Opciones de administración del sistema', 'Menú', 'admin', '2025-07-05', NULL, NULL, 'ACTIVO'),
(9, 'Gestion de base', 'Opcion del menu lateral', 'PANTALLA', NULL, NULL, NULL, NULL, 'ACTIVO'),
(10, 'Objetos', 'Gestión de objetos del sistema', 'Pantalla', 'admin', '2025-07-17', NULL, NULL, 'ACTIVO'),
(11, 'Socios / Clientes', 'Gestión de socios y clientes', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(12, 'Créditos', 'Gestión de créditos', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(13, 'Ahorros', 'Gestión de ahorros', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(14, 'Emprendimientos', 'Gestión de emprendimientos', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(15, 'Cargos Directivos', 'Gestión de cargos directivos', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(16, 'Indicadores de Género', 'Gestión de indicadores de género', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(17, 'Reportes', 'Visualización de reportes', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO'),
(18, 'Auditoría', 'Gestión de auditoría', 'Pantalla', 'admin', '2025-07-22', NULL, NULL, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ms_rol`
--

CREATE TABLE `tbl_ms_rol` (
  `Id_Rol` int(11) NOT NULL,
  `Rol` varchar(30) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Creado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Creacion` date DEFAULT NULL,
  `Modificado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Modificacion` date DEFAULT NULL,
  `Estado` varchar(20) DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ms_rol`
--

INSERT INTO `tbl_ms_rol` (`Id_Rol`, `Rol`, `Descripcion`, `Creado_Por`, `Fecha_Creacion`, `Modificado_Por`, `Fecha_Modificacion`, `Estado`) VALUES
(1, 'ADMINISTRADOR', 'Rol con acceso total al sistema', 'sistema', '2025-06-22', NULL, NULL, 'ACTIVO'),
(2, 'TECNICO DE CAMPO', 'Rol para técnicos de campo', 'sistema', '2025-06-22', NULL, NULL, 'ACTIVO'),
(3, 'AUTO-REGISTRO', 'Rol para usuarios auto-registrados', 'sistema', '2025-06-22', NULL, NULL, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ms_roles_objeto`
--

CREATE TABLE `tbl_ms_roles_objeto` (
  `Id_Roles_Objeto` int(11) NOT NULL,
  `Id_Rol` int(11) NOT NULL,
  `Id_Objeto` int(11) NOT NULL,
  `Permiso_Insercion` tinyint(1) DEFAULT NULL,
  `Permiso_Eliminacion` tinyint(1) DEFAULT NULL,
  `Permiso_Actualizacion` tinyint(1) DEFAULT NULL,
  `Permiso_Consultar` tinyint(1) DEFAULT NULL,
  `Creado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Creacion` date DEFAULT NULL,
  `Modificado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Modificacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ms_roles_objeto`
--

INSERT INTO `tbl_ms_roles_objeto` (`Id_Roles_Objeto`, `Id_Rol`, `Id_Objeto`, `Permiso_Insercion`, `Permiso_Eliminacion`, `Permiso_Actualizacion`, `Permiso_Consultar`, `Creado_Por`, `Fecha_Creacion`, `Modificado_Por`, `Fecha_Modificacion`) VALUES
(1, 1, 6, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(12, 1, 2, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(13, 1, 3, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(14, 1, 4, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(15, 1, 7, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(16, 1, 8, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(17, 1, 9, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(18, 1, 10, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(19, 1, 5, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(20, 1, 11, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(21, 1, 12, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(22, 1, 13, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(23, 1, 14, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(24, 1, 17, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(25, 1, 15, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(26, 2, 5, 1, 1, 1, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ms_usuario`
--

CREATE TABLE `tbl_ms_usuario` (
  `Id_Usuario` int(11) NOT NULL,
  `Id_Rol` int(11) NOT NULL,
  `Usuario` varchar(60) NOT NULL,
  `Nombre_Usuario` varchar(100) NOT NULL,
  `Estado_Usuario` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Fecha_Ultima_Conexion` datetime DEFAULT NULL,
  `Primer_Ingreso` int(11) NOT NULL,
  `Fecha_Vencimiento` date DEFAULT NULL,
  `Correo_Electronico` varchar(60) DEFAULT NULL,
  `Creado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Creacion` date DEFAULT NULL,
  `Modificado_Por` varchar(100) DEFAULT NULL,
  `Fecha_Modificacion` date DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `Intentos_Fallidos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_ms_usuario`
--

INSERT INTO `tbl_ms_usuario` (`Id_Usuario`, `Id_Rol`, `Usuario`, `Nombre_Usuario`, `Estado_Usuario`, `Contraseña`, `Fecha_Ultima_Conexion`, `Primer_Ingreso`, `Fecha_Vencimiento`, `Correo_Electronico`, `Creado_Por`, `Fecha_Creacion`, `Modificado_Por`, `Fecha_Modificacion`, `otp_code`, `otp_expires_at`, `email_verified_at`, `Intentos_Fallidos`) VALUES
(2, 1, 'SUPER-ADMIN', 'ADMIN', 'ACTIVO', '$2y$12$IKTrXqX2vu..vBGjCHYgx.WgRLb8W28ptNDqH9F0fPlg0iK7kkR1W', NULL, 0, '2025-06-23', 'armandoarmanditooo79@gmail.com', NULL, '2025-06-23', 'SISTEMA', '2025-06-23', '339201', '2025-06-30 11:12:38', '2025-06-27 02:19:59', 0),
(3, 2, 'JOSET', 'JOSE TREJO', 'NUEVO', '$2y$12$X8IAekFyeveSdA/IqpMP6.PnLjiIXJTKnsFnWstdBmMMiqEihtoum', NULL, 1, '2026-06-18', 'jjuniortwrfsdfrejo@gmail.com', NULL, '2025-06-23', NULL, NULL, NULL, NULL, NULL, 0),
(4, 2, 'LUIST', 'LUIS MIGUEL', 'ACTIVO', '$2y$12$dyGdapkNUYXE96FiRtgEQ.rEL9pEWkuW5RngRCT122DGtzIM2P9o.', NULL, 0, '2026-06-18', 'jjuniortrrejo@gmail.com', NULL, '2025-06-23', NULL, NULL, NULL, NULL, NULL, 0),
(5, 2, 'YUYU', 'CLAHS', 'ACTIVO', '$2y$12$QIhVNI0WO31AcCF..25nauy6xv.dyQ6hRdgcbDH78go7AN5EjJPh6', NULL, 1, '2026-06-18', 'jjuniortrejosss@gmail.com', NULL, '2025-06-23', NULL, NULL, NULL, NULL, NULL, 0),
(6, 3, 'KEREN', 'KEREN MALDONADO', 'NUEVO', '$2y$12$FyZEDcsRrNWJVBQVl4kmYOoxTajX4ecTNnZHEKfol1LJfXBRmqgvK', NULL, 1, '2026-06-25', 'keSSren@gmail.com', NULL, '2025-06-23', NULL, NULL, NULL, NULL, NULL, 0),
(7, 1, 'JT', 'JOSE ARMANDO TREJO V', 'ACTIVO', '$2y$12$FPPlnD.s9jGuCy5FsFsCCOqTO/lPBD4tJuYFwAgAXH7oi2j61xpw6', NULL, 1, '2026-06-22', 'armandoarmasadfndito79@gmail.com', NULL, '2025-06-27', NULL, NULL, '544005', '2025-06-28 08:04:11', NULL, 0),
(8, 1, 'PALMA', 'PAL7', 'ACTIVO', '$2y$12$UsbGY700BoCalj6LINsJwuteS6bpZ7V4loRBrPWU2H.nY0V1nOpem', NULL, 0, '2026-06-22', 'armandoarsdfsdfmandito79@gmail.com', NULL, '2025-06-27', NULL, NULL, NULL, NULL, NULL, 0),
(9, 1, 'JOH', 'JUAN', 'ACTIVO', '$2y$12$pkXLVi76lV7EYT0bim0KFOH5JifisO75fydCyZFRaXN6Dzl2swwyi', NULL, 0, '2026-06-23', 'armandoaffffffffffrmandito79@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(10, 1, 'JOH', 'JUAN', 'ACTIVO', '$2y$12$pkXLVi76lV7EYT0bim0KFOH5JifisO75fydCyZFRaXN6Dzl2swwyi', NULL, 0, '2026-06-23', 'armandoarmsdfsarasdvandito79@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(11, 2, 'MESSI', 'MMMM', 'ACTIVO', '$2y$12$9xIzWWnKBgnCTsdGOVaDTuIaIrDYsCvKkUILMnUbOCnoePZF9AKbW', NULL, 0, '2026-06-23', 'armandoarmssdsdasdQDW<SC<Candito79@gmail.com', NULL, '2025-06-28', NULL, NULL, '153664', '2025-06-28 08:48:36', NULL, 0),
(12, 3, 'HAMLETGAY', 'JAFET', 'NUEVO', '$2y$12$P.8cb1iTf2wYNQEpFIf5geyVnmDuSvaAZCdJAf1mEFpJlj9v.Hr32', NULL, 1, '2026-06-23', 'daniel@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(13, 2, 'JAFETBISE', 'KKK', 'ACTIVO', '$2y$12$WoIZHCUiNFa1.w9ai6J9COTnY/zSuWvSl0.71eYaF3wwBvFmOGsFK', NULL, 1, '2026-06-23', 'edagard@gmail.com', NULL, '2025-06-28', NULL, NULL, '723282', '2025-06-28 08:43:10', NULL, 0),
(14, 1, 'TTT', 'ANGIE MICHELLE', 'NUEVO', '$2y$12$H.97CsdwFGCjxtEhDsPazuq05TQf6ZW7d7qu0oNIg47AgXRZXwpIm', NULL, 1, '2026-06-23', 'armandoarmaEFASFSDCndito79@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(15, 2, 'WWW', 'SSSS', 'ACTIVO', '$2y$12$YKyfBRqpLvbRp1Is5UWxiud1AcgTQMqNCoTWBuESjgTZwsIKB8jam', NULL, 0, '2026-06-23', 'armandoarmsdsffdfgfggfgfandito79@gmail.com', NULL, '2025-06-28', NULL, NULL, '867013', '2025-06-28 10:31:26', NULL, 0),
(16, 2, 'NUEVOaSASAS', 'ASDASDAS', 'ACTIVO', '$2y$12$amAuQYLnagZcpEbg8XEe4Oce2ziOhagOHh6MjSsQjxQwJAHaRvnz.', NULL, 0, '2026-06-23', 'armandoarasasdasdadmandito79@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(17, 2, 'NUEVO', 'ASDAFSFDASFAERFSD', 'ACTIVO', '$2y$12$/PULwQAL8jVW2I3JnL7Lh.k7dL5SzI2Aq73nfVfSWX1f/WuyIgnMS', NULL, 0, '2026-06-23', 'armandoarmandito79@gmail.com', NULL, '2025-06-28', NULL, NULL, '312346', '2025-06-28 10:38:11', NULL, 0),
(18, 2, 'ILI', 'SDFDSGDSFG', 'NUEVO', '$2y$12$wpiWRTNwLaIwQzRCSGhZFe7fDboEQTpQuwzVzDZvuoA1VvFG0fLw6', NULL, 0, '2026-06-23', 'jjunioasdfsdfsdfrtrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '793009', '2025-06-28 10:49:51', NULL, 0),
(19, 2, 'PUCA', 'PUCAAA', 'ACTIVO', '$2y$12$gKhkLFy5wtGnE3lqpyUO7evwFsIoxUj.kd6T/zT4Eq4jVXvlMyijK', NULL, 0, '2026-06-23', 'jjuniortreEDSjo@gmail.com', NULL, '2025-06-28', NULL, NULL, '932514', '2025-06-28 11:17:18', NULL, 0),
(20, 2, 'ROYAL', 'MONTA', 'NUEVO', '$2y$12$BMGfe7h25orrG7c4bEWR9ud8W6cikA//XeTgogzwlySP4Qfvf6.rW', NULL, 1, '2026-06-23', 'jjuniortrsejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '714021', '2025-06-28 11:21:16', NULL, 0),
(21, 2, 'VEGA', 'ALEXYVEGA', 'ACTIVO', '$2y$12$k4q8pQb.O5dQRZyTLs77C.NY8yMlP69jLpU44VTxlCu2pDmyObJEu', NULL, 0, '2026-06-23', 'jjuniorsdasdasdasdasdtrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '323915', '2025-06-29 03:33:26', NULL, 0),
(22, 2, 'BUBA', 'BUBAAA', 'ACTIVO', '$2y$12$bYnvk5qckWRW.OtmPo311.I1uzQ57QqhdvVQYw3JKn/3xxD1KkRyO', NULL, 0, '2026-06-23', 'jjuniorSEFSEDFSDFSDtrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '812557', '2025-06-29 03:44:47', NULL, 0),
(23, 2, 'KAKA', 'KAKAAAA', 'ACTIVO', '$2y$12$QRWmA51U.aDWdaZWxQ8a3OyQ61Taas9LCQ7OIyhuRu1MxnqIn.qXO', NULL, 0, '2026-06-23', 'jjuniSEFSAFSADFASDFSADortrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '407353', '2025-06-29 03:49:56', NULL, 0),
(24, 2, 'YUPI', 'DFSDFSDFSDF', 'ACTIVO', '$2y$12$Ot8TsezTPPPXJbdoK5KtUuKB2vjEfVr9z7s0n/S6GwR3cXTZBKNpy', NULL, 0, '2026-06-23', 'jjuSCZXCZXCniortrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '843926', '2025-06-29 04:15:19', NULL, 0),
(25, 3, 'JOSELUIS', 'PAL7ED', 'NUEVO', '$2y$12$FHJGWaPgMAqczY62SEy.nO4uRKDNsIDtk1ysy5GyDCneSFCOlgGu.', NULL, 1, '2026-06-23', 'jjuniortr<scasddWDejo@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(26, 2, 'NUEVOOO', 'JOJDSFJSDFJ', 'ACTIVO', '$2y$12$bdo.edqSJm.Iq3rMXFozDOu2mOHN48TRJtFpQKTARju4isgP.WhIO', NULL, 0, '2026-06-23', 'jjuniortreIADISDBFIUBSjo@gmail.com', NULL, '2025-06-28', NULL, NULL, '803889', '2025-06-29 05:25:06', NULL, 0),
(27, 2, 'PEKA', 'PEKAAA', 'ACTIVO', '$2y$12$8GdNHAtcPMRPOTTw.wom9eywIrg1kE2GLwfnI6q.2i4Yo2lvNMwK6', NULL, 1, '2026-06-23', 'jjuniorzxczcxztrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, NULL, NULL, NULL, 0),
(28, 2, 'REYNA', 'HDSHSDHDS', 'ACTIVO', '$2y$12$FHsYAUyb08l7V4M6oFPjLul8kjci7L/owz5setUxH6O.DRvcSRyNm', NULL, 0, '2026-06-23', 'jjunioradfsdfsdfdsfsdfdsdftrejo@gmail.com', NULL, '2025-06-28', NULL, NULL, '513709', '2025-06-29 05:37:17', NULL, 0),
(29, 2, 'PINEDA', 'ALEX PINEDA CHACON', 'NUEVO', '$2y$12$776q/kZo93pJjoMpEIcdYuHIoUSSM2UC.ErVOFOZnOb/2cQerqUdC', NULL, 0, '2026-06-24', 'jjuniddssdggsdsdgdsgdsgdsgortrejo@gmail.com', NULL, '2025-06-29', NULL, NULL, NULL, NULL, NULL, 0),
(30, 2, 'CANADA', 'MIEL DE MAPLE', 'ACTIVO', '$2y$12$zcZy7TL2G7u2At3Kd9Viq.nT1DNoMiaYG219mt37AuCONBE73Jbli', NULL, 0, '2026-06-24', 'jjuniortreSDFDSFDSSFDSDFFSDSDFFDSFDSFDSFSDjo@gmail.com', NULL, '2025-06-29', NULL, NULL, NULL, NULL, NULL, 0),
(31, 2, 'CHOCO', 'CHOCO LOZANO', 'BLOQUEADO', '$2y$12$14juKRrfx/CWq28wvrSgbOPec2umZK6gKOeguaUe2Qf4URteCSbva', NULL, 0, '2026-06-24', 'jjunisefsdfsfsdfsdfsdfortrejo@gmail.com', NULL, '2025-06-29', NULL, NULL, '515790', '2025-06-30 04:04:40', NULL, 3),
(32, 2, 'EDWIN', 'EL MERO QUESO', 'INACTIVO', '$2y$12$sYW.v7FFx2CR346KJxCJROSIIcxp6OsoFv3ZZnPjFKZGfN0t1hziS', NULL, 0, '2026-06-25', 'jjundsdfsfdfsiortrejo@gmail.com', NULL, '2025-06-29', NULL, NULL, '251479', '2025-06-30 04:18:06', NULL, 0),
(33, 2, 'QUIOTO', 'SADASDASDASD', 'BLOQUEADO', '$2y$12$ECaqNuDR1L4.zjrFVNzl1.GTx.KxxPS6Q7WvD0WZkbg/UVX46HlI2', NULL, 0, '2026-06-25', 'jjunioasdasdasswwwrtrejo@gmail.com', NULL, '2025-06-30', 'SISTEMA', '2025-06-30', '972091', '2025-06-30 09:05:36', NULL, 3),
(34, 3, 'SANTA', 'SANTAMARIA', 'NUEVO', '$2y$12$bHdkRdkQ0HwJSSh30OCCJuGodoe21dna1rktN4ILUk1nX0bWHAqjC', NULL, 1, '2026-06-25', 'jjuniortaaaarejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(35, 3, 'KEVIN', 'SCVSCCVX', 'NUEVO', '$2y$12$TALYuF1nHIuzcVg2MH9jd.4W97DtFtLT/EMPGloT/jlOSozqA646u', NULL, 1, '2026-06-25', 'jjunioSSDSDrtrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(36, 3, 'YIMY', 'SDFSDFSDF', 'NUEVO', '$2y$12$l7qptdsQjg07Y4jeuLBzPe.LmapX1Xzt004jsvX6Y.Y4azV/Ni7Se', NULL, 1, '2026-06-25', 'jjunissssssaaaaortrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(37, 3, 'DIEGO', 'SDASASD', 'NUEVO', '$2y$12$MIo/rWPCxEFDXFHNbNknCeiMHvfZryhGvZKHImMGi/9RSd4PR7CrC', NULL, 1, '2026-06-25', 'jjaaauniortrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(38, 2, 'SAUL', 'SDASASDddSDFSDF', 'ACTIVO', '$2y$12$EQNgym1qLozwNtwVHtKf6.x5/RSq5jBnqiK5MUMC1j7etpguPA2/y', NULL, 1, '2026-06-25', 'jjaaauSSniortrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, '350618', '2025-06-30 09:30:52', NULL, 0),
(39, 1, 'PRUEBA', 'JEJEJE', 'INACTIVO', '$2y$12$nLLI1XiYr/pgn3GGyeSipeQqrF9RY3Ic8BPhA2ltV50dxAzVDDXD6', NULL, 1, '2026-06-25', 'jjuniortreSDSDSDSDjo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(40, 2, 'KIT', 'SDSSssDSD', 'INACTIVO', '$2y$12$dpr/zTuyEaPbCKSaWhghEeYKXVpKoikf8bNA2xaSEmd1G6hnAuAc6', NULL, 1, '2026-06-25', 'jjuniSSSSsSortrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(41, 3, 'IOIOI', 'JIJIJSIAA', 'NUEVO', '$2y$12$OdEJWOLdXziyk0C/XPVCVOLLwjD/RrP1JIrQB0TZHm4YK8LzdIxvm', NULL, 1, '2026-06-25', 'jjuniortrejo@jose.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(42, 2, 'TITA', 'OSIRISdd', 'INACTIVO', '$2y$12$0TlmZT2enAZIhHgiPfnaLOmDJmo8fs48dSkEHutW61Hm7aS1uSAbO', NULL, 1, '2026-06-25', 'jjuniorssssdsstrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(43, 1, 'VIVENTE', 'FERNANDEZ', 'INACTIVO', '$2y$12$Uo9t5giU9e/DLNOlO8b65e20z0QUHxcF1NDxjgprxGaFPNEnkHDJ2', NULL, 1, '2026-06-25', 'jjjjiununiortrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, NULL, NULL, NULL, 0),
(44, 2, 'DENIL', 'KILLER', 'ACTIVO', '$2y$12$47R6aqPdffkloXtWAjuQGedmapQPISRIHBnWNQcCBHRzgMDX4UGYC', NULL, 0, '2026-06-25', 'wwwjjuniortrejo@gmail.com', NULL, '2025-06-30', 'SISTEMA', '2025-06-30', '901483', '2025-06-30 11:22:16', NULL, 3),
(45, 2, 'SANTAMARIA', 'PROMESA', 'INACTIVO', '$2y$12$sxK.AdOjLcQ8VhlpptC6LuyuFQt3YR2ygZvAERpfEK2cNXY.AZu8O', NULL, 1, '2026-06-25', 'armandoarmssddffandito79@gmail.com', NULL, '2025-06-30', NULL, NULL, '899817', '2025-06-30 11:21:37', NULL, 0),
(46, 2, 'VITINA', 'PSG', 'ACTIVO', '$2y$12$zRZgE6lZUwObsIB5P3u.8OxKRtmrpt2BFsODTHix.vDnjb7tG07Ca', NULL, 0, '2026-06-25', 'aaaaaaaaajjuniortrejo@gmail.com', NULL, '2025-06-30', NULL, NULL, '716102', '2025-06-30 23:04:49', NULL, 0),
(47, 2, 'KILIAN', 'MBAPE', 'INACTIVO', '$2y$12$/./gNxW0t9ontwBYRBjhL.zBkLf9SM0zIo70LhkFpEKy99kHRHPly', NULL, 0, '2026-06-26', 'jjuniortrejo@gmail.com', NULL, '2025-06-30', 'SISTEMA', '2025-06-30', '342097', '2025-06-30 23:40:57', NULL, 3),
(48, 3, 'SAMIR', 'SAMI', 'NUEVO', '$2y$12$6rKf.pLTayB0j1gCgLs1oeWk4YbYfe7S.xRW185gJDud2kWjEIIH2', NULL, 1, '2026-07-03', 'edgat521@gmail.com', NULL, '2025-07-08', NULL, NULL, NULL, NULL, NULL, 0),
(49, 3, 'EDGARD521', 'EDGARD LANZA', 'NUEVO', '$2y$12$gAl3pFcPdXOH1m3/bT72t.n4s.Vf7orgUWJSbSuJxfOY/2Y8wICcC', NULL, 1, '2026-07-03', 'samibmx25@gmail.com', NULL, '2025-07-08', NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_municipio`
--

CREATE TABLE `tbl_municipio` (
  `Id_Municipio` int(11) NOT NULL,
  `Id_Departamento` int(11) NOT NULL,
  `Nombre_Municipio` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_municipio`
--

INSERT INTO `tbl_municipio` (`Id_Municipio`, `Id_Departamento`, `Nombre_Municipio`) VALUES
(1, 1, 'LA CEIBA'),
(2, 1, 'EL PORVENIR'),
(3, 1, 'ESPARTA'),
(4, 1, 'JUTIAPA'),
(5, 1, 'LA MASICA'),
(6, 1, 'SAN FRANCISCO'),
(7, 1, 'TELA'),
(8, 1, 'ARIZONA'),
(9, 2, 'TRUJILLO'),
(10, 2, 'BALFATE'),
(11, 2, 'IRIONA'),
(12, 2, 'LIMON'),
(13, 2, 'SABA'),
(14, 2, 'SANTA FE'),
(15, 2, 'SANTA ROSA AGUAN'),
(16, 2, 'SONAGUERA'),
(17, 2, 'TOCOA'),
(18, 2, 'BONITO ORIENTAL'),
(19, 3, 'COMAYAGUA'),
(20, 3, 'AJUTERIQUE'),
(21, 3, 'EL ROSARIO'),
(22, 3, 'ESQUIAS'),
(23, 3, 'HUMUYA'),
(24, 3, 'LA LIBERTAD'),
(25, 3, 'LAMANI'),
(26, 3, 'LA TRINIDAD'),
(27, 3, 'LEJAMANI'),
(28, 3, 'MEAMBAR'),
(29, 3, 'MINAS DE ORO'),
(30, 3, 'OJOS DE AGUA'),
(31, 3, 'SAN JERONIMO'),
(32, 3, 'SAN JOSE DE COMAYAGUA'),
(33, 3, 'SAN JOSE DEL POTRERO'),
(34, 3, 'SAN LUIS'),
(35, 3, 'SAN SEBASTIAN'),
(36, 3, 'SIGUATEPEQUE'),
(37, 3, 'VILLA DE SAN ANTONIO'),
(38, 3, 'LAS LAJAS'),
(39, 3, 'TAULABE'),
(40, 4, 'SANTA ROSA DE COPAN'),
(41, 4, 'CABAÑAS'),
(42, 4, 'CONCEPCION'),
(43, 4, 'COPAN RUINAS'),
(44, 4, 'CORQUIN'),
(45, 4, 'CUCUYAGUA'),
(46, 4, 'DOLORES'),
(47, 4, 'DULCE NOMBRE'),
(48, 4, 'EL PARAISO'),
(49, 4, 'FLORIDA'),
(50, 4, 'LA JIGUA'),
(51, 4, 'LA UNION'),
(52, 4, 'NUEVA ARCADIA'),
(53, 4, 'SAN AGUSTIN'),
(54, 4, 'SAN ANTONIO'),
(55, 4, 'SAN JERONIMO'),
(56, 4, 'SAN JOSE'),
(57, 4, 'SAN JUAN DE OPOA'),
(58, 4, 'SAN NICOLAS'),
(59, 4, 'SAN PEDRO'),
(60, 4, 'SANTA RITA'),
(61, 4, 'TRINIDAD'),
(62, 4, 'VERACRUZ'),
(63, 5, 'SAN PEDRO SULA'),
(64, 5, 'CHOLOMA'),
(65, 5, 'OMOA'),
(66, 5, 'PIMIENTA'),
(67, 5, 'POTRERILLOS'),
(68, 5, 'PUERTO CORTES'),
(69, 5, 'SAN ANTONIO DE CORTES'),
(70, 5, 'SAN FRANCISCO DE YOJOA'),
(71, 5, 'SAN MANUEL'),
(72, 5, 'SANTA CRUZ DE YOJOA'),
(73, 5, 'VILLANUEVA'),
(74, 5, 'LA LIMA'),
(75, 6, 'CHOLUTECA'),
(76, 6, 'APACILAGUA'),
(77, 6, 'CONCEPCION DE MARIA'),
(78, 6, 'DUYURE'),
(79, 6, 'EL CORPUS'),
(80, 6, 'EL TRIUNFO'),
(81, 6, 'MARCOVIA'),
(82, 6, 'MOROLICA'),
(83, 6, 'NAMASIGUE'),
(84, 6, 'OROCUINA'),
(85, 6, 'PESPIRE'),
(86, 6, 'SAN ANTONIO DE FLORES'),
(87, 6, 'SAN ISIDRO'),
(88, 6, 'SAN JOSE'),
(89, 6, 'SAN MARCOS DE COLON'),
(90, 6, 'SANTA ANA DE YUSGUARE'),
(91, 7, 'YUSCARAN'),
(92, 7, 'ALAUCA'),
(93, 7, 'DANLI'),
(94, 7, 'EL PARAISO'),
(95, 7, 'GUINOPE'),
(96, 7, 'JACALEAPA'),
(97, 7, 'LIURE'),
(98, 7, 'MOROCELI'),
(99, 7, 'OROPOLI'),
(100, 7, 'POTRERILLOS'),
(101, 7, 'SAN ANTONIO DE FLORES'),
(102, 7, 'SAN LUCAS'),
(103, 7, 'SAN MATIAS'),
(104, 7, 'SOLEDAD'),
(105, 7, 'TEUPASENTI'),
(106, 7, 'TEXIGUAT'),
(107, 7, 'VADO ANCHO'),
(108, 7, 'YAUYUPE'),
(109, 7, 'TROJES'),
(110, 8, 'DISTRITO CENTRAL'),
(111, 8, 'ALUBAREN'),
(112, 8, 'CEDROS'),
(113, 8, 'CURAREN'),
(114, 8, 'EL PORVENIR'),
(115, 8, 'GUAIMACA'),
(116, 8, 'LA LIBERTAD'),
(117, 8, 'LA VENTA'),
(118, 8, 'LEPATERIQUE'),
(119, 8, 'MARAITA'),
(120, 8, 'MARALE'),
(121, 8, 'NUEVA ARMENIA'),
(122, 8, 'OJOJONA'),
(123, 8, 'ORICA'),
(124, 8, 'REITOCA'),
(125, 8, 'SABANAGRANDE'),
(126, 8, 'SAN ANTONIO DE ORIENTE'),
(127, 8, 'SAN BUENAVENTURA'),
(128, 8, 'SAN IGNACIO'),
(129, 8, 'CANTARRANAS'),
(130, 8, 'SAN MIGUELITO'),
(131, 8, 'SANTA ANA'),
(132, 8, 'SANTA LUCIA'),
(133, 8, 'TALANGA'),
(134, 8, 'TATUMBLA'),
(135, 8, 'VALLE DE ANGELES'),
(136, 8, 'VILLA SAN FRANCISCO'),
(137, 8, 'VALLECILLOS'),
(138, 9, 'PUERTO LEMPIRA'),
(139, 9, 'BRUS LAGUNA'),
(140, 9, 'AHUAS'),
(141, 9, 'JUAN FRANCISCO BULNES'),
(142, 9, 'VILLEDA MORALES'),
(143, 9, 'WAMPUSIRPI'),
(144, 10, 'LA ESPERANZA'),
(145, 10, 'CAMASCA'),
(146, 10, 'COLOMONCAGUA'),
(147, 10, 'CONCEPCION'),
(148, 10, 'DOLORES'),
(149, 10, 'INTIBUCA'),
(150, 10, 'JESUS DE OTORO'),
(151, 10, 'MAGDALENA'),
(152, 10, 'MASAGUARA'),
(153, 10, 'SAN ANTONIO'),
(154, 10, 'SAN ISIDRO'),
(155, 10, 'SAN JUAN'),
(156, 10, 'SAN MARCOS SIERRA'),
(157, 10, 'SAN MIGUELITO'),
(158, 10, 'SANTA LUCIA'),
(159, 10, 'YAMARANGUILA'),
(160, 10, 'SAN FRANCISCO DE OPALACA'),
(161, 11, 'ROATAN'),
(162, 11, 'GUANAJA'),
(163, 11, 'JOSE SANTOS GUARDIOLA'),
(164, 11, 'UTILA'),
(165, 12, 'LA PAZ'),
(166, 12, 'AGUANQUETERIQUE'),
(167, 12, 'CABAÑAS'),
(168, 12, 'CANE'),
(169, 12, 'CHINACLA'),
(170, 12, 'GUAJIQUIRO'),
(171, 12, 'LAUTERIQUE'),
(172, 12, 'MARCALA'),
(173, 12, 'MERCEDES DE ORIENTE'),
(174, 12, 'OPATORO'),
(175, 12, 'SAN ANTONIO DEL NORTE'),
(176, 12, 'SAN JOSE'),
(177, 12, 'SAN JUAN'),
(178, 12, 'SAN PEDRO DE TUTULE'),
(179, 12, 'SANTA ANA'),
(180, 12, 'SANTA ELENA'),
(181, 12, 'SANTA MARIA'),
(182, 12, 'SANTIAGO DE PURINGLA'),
(183, 12, 'YARULA'),
(184, 13, 'GRACIAS'),
(185, 13, 'BELEN'),
(186, 13, 'CANDELARIA'),
(187, 13, 'COLOLACA'),
(188, 13, 'ERANDIQUE'),
(189, 13, 'GUALCINCE'),
(190, 13, 'GUARITA'),
(191, 13, 'LA CAMPA'),
(192, 13, 'LA IGUALA'),
(193, 13, 'LAS FLORES'),
(194, 13, 'LA UNION'),
(195, 13, 'LA VIRTUD'),
(196, 13, 'LEPAERA'),
(197, 13, 'MAPULACA'),
(198, 13, 'PIRAERA'),
(199, 13, 'SAN ANDRES'),
(200, 13, 'SAN FRANCISCO'),
(201, 13, 'SAN JUAN GUARITA'),
(202, 13, 'SAN MANUEL COLOHETE'),
(203, 13, 'SAN RAFAEL'),
(204, 13, 'SAN SEBASTIAN'),
(205, 13, 'SANTA CRUZ'),
(206, 13, 'TALGUA'),
(207, 13, 'TAMBLA'),
(208, 13, 'TOMALA'),
(209, 13, 'VALLADOLID'),
(210, 13, 'VIRGINIA'),
(211, 13, 'SAN MARCOS DE CAIQUIN'),
(212, 14, 'OCOTEPEQUE'),
(213, 14, 'BELEN GUALCHO'),
(214, 14, 'CONCEPCION'),
(215, 14, 'DOLORES MERENDON'),
(216, 14, 'FRATERNIDAD'),
(217, 14, 'LA ENCARNACION'),
(218, 14, 'LA LABOR'),
(219, 14, 'LUCERNA'),
(220, 14, 'MERCEDES'),
(221, 14, 'SAN FERNANDO'),
(222, 14, 'SAN FRANCISCO DEL VALLE'),
(223, 14, 'SAN JORGE'),
(224, 14, 'SAN MARCOS'),
(225, 14, 'SANTA FE'),
(226, 14, 'SENSENTI'),
(227, 14, 'SINUAPA'),
(228, 15, 'JUTICALPA'),
(229, 15, 'CAMPAMENTO'),
(230, 15, 'CATACAMAS'),
(231, 15, 'CONCORDIA'),
(232, 15, 'DULCE NOMBRE DE CULMI'),
(233, 15, 'EL ROSARIO'),
(234, 15, 'ESQUIPULAS DEL NORTE'),
(235, 15, 'GUALACO'),
(236, 15, 'GUARIZAMA'),
(237, 15, 'GUATA'),
(238, 15, 'GUAYAPE'),
(239, 15, 'JANO'),
(240, 15, 'LA UNION'),
(241, 15, 'MANGULILE'),
(242, 15, 'MANTO'),
(243, 15, 'SALAMA'),
(244, 15, 'SAN ESTEBAN'),
(245, 15, 'SAN FRANCISCO DE BECERRA'),
(246, 15, 'SAN FRANCISCO DE LA PAZ'),
(247, 15, 'SANTA MARIA DEL REAL'),
(248, 15, 'SILCA'),
(249, 15, 'YOCON'),
(250, 15, 'PATUCA'),
(251, 16, 'SANTA BARBARA'),
(252, 16, 'ARADA'),
(253, 16, 'ATIMA'),
(254, 16, 'AZACUALPA'),
(255, 16, 'CEGUACA'),
(256, 16, 'SAN JOSE DE COLINAS'),
(257, 16, 'CONCEPCION DEL NORTE'),
(258, 16, 'CONCEPCION DEL SUR'),
(259, 16, 'CHINDA'),
(260, 16, 'EL NISPERO'),
(261, 16, 'GUALALA'),
(262, 16, 'ILAMA'),
(263, 16, 'MACUELIZO'),
(264, 16, 'NARANJITO'),
(265, 16, 'NUEVO CELILAC'),
(266, 16, 'PETOA'),
(267, 16, 'PROTECCION'),
(268, 16, 'QUIMISTAN'),
(269, 16, 'SAN FRANCISCO DE OJUERA'),
(270, 16, 'SAN LUIS'),
(271, 16, 'SAN MARCOS'),
(272, 16, 'SAN NICOLAS'),
(273, 16, 'SAN PEDRO DE ZACAPA'),
(274, 16, 'SANTA RITA'),
(275, 16, 'SAN VICENTE CENTENARIO'),
(276, 16, 'TRINIDAD'),
(277, 16, 'LAS VEGAS'),
(278, 16, 'NUEVA FRONTERA'),
(279, 17, 'NACAOME'),
(280, 17, 'ALIANZA'),
(281, 17, 'AMAPALA'),
(282, 17, 'ARAMECINA'),
(283, 17, 'CARIDAD'),
(284, 17, 'GOASCORAN'),
(285, 17, 'LANGUE'),
(286, 17, 'SAN FRANCISCO DE CORAY'),
(287, 17, 'SAN LORENZO'),
(288, 18, 'YORO'),
(289, 18, 'ARENAL'),
(290, 18, 'EL NEGRITO'),
(291, 18, 'EL PROGRESO'),
(292, 18, 'JOCON'),
(293, 18, 'MORAZAN'),
(294, 18, 'OLANCHITO'),
(295, 18, 'SANTA RITA'),
(296, 18, 'SULACO'),
(297, 18, 'VICTORIA'),
(298, 18, 'YORITO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_organizacion`
--

CREATE TABLE `tbl_organizacion` (
  `Id_Organizacion` int(11) NOT NULL,
  `Id_Aldea` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Nombre_Organizacion` varchar(45) DEFAULT NULL,
  `Estado_Organizacion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_organizacion`
--

INSERT INTO `tbl_organizacion` (`Id_Organizacion`, `Id_Aldea`, `Id_Usuario`, `Nombre_Organizacion`, `Estado_Organizacion`) VALUES
(1, 1, 2, 'Caja Rural El Progresoo', 'ACTIVO'),
(2, 2, 2, 'Caja Rural La Esperanza', 'ACTIVO'),
(3, 3, 2, 'Caja rural la esperanza', 'ACTIVO'),
(4, 4, 2, 'Carja rura el triunfo', 'ACTIVO'),
(5, 5, 2, 'Caja Rural La Tusa', 'ACTIVO'),
(6, 6, 2, 'cerro grande', 'ACTIVO'),
(7, 7, 2, 'caja san jeromino', 'ACTIVO'),
(8, 8, 2, 'caja de santa lucia', 'INACTIVO'),
(9, 9, 2, 'Caja rural Tatumbla', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_pagos`
--

CREATE TABLE `tbl_pagos` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pagado` decimal(12,2) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_pagos`
--

INSERT INTO `tbl_pagos` (`id`, `prestamo_id`, `fecha_pago`, `monto_pagado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-17', 1000.00, 'primer pago', '2025-07-18 05:50:26', '2025-07-18 05:50:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_parametros`
--

CREATE TABLE `tbl_parametros` (
  `Id_Parametro` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Nombre_Parametro` varchar(100) DEFAULT NULL,
  `Valor` varchar(45) DEFAULT NULL,
  `Fecha_Creacion` datetime DEFAULT NULL,
  `Fecha_Modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_parametros`
--

INSERT INTO `tbl_parametros` (`Id_Parametro`, `Id_Usuario`, `Nombre_Parametro`, `Valor`, `Fecha_Creacion`, `Fecha_Modificacion`) VALUES
(1, 2, 'ADMIN_INTENTOS_INVALIDOS', '3', '2025-06-07 17:37:37', NULL),
(2, 2, 'ADMIN_DIAS_VIGENCIA', '360', '2025-06-07 17:37:37', NULL),
(3, 2, 'LONGITUD_MIN_PASSWORD', '8', '2025-06-07 17:37:37', '2025-07-23 02:45:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_prestamos`
--

CREATE TABLE `tbl_prestamos` (
  `id` int(11) NOT NULL,
  `socio_id` int(11) NOT NULL,
  `nombre_caja_rural` varchar(255) NOT NULL,
  `monto_solicitado` decimal(10,2) NOT NULL,
  `plazo_meses` int(11) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `tipo_credito` varchar(255) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `porcentaje_mora_caja` decimal(5,2) DEFAULT NULL,
  `intereses_cobrados` decimal(12,2) DEFAULT NULL,
  `capital_social` decimal(12,2) DEFAULT NULL,
  `capital_trabajo` decimal(12,2) DEFAULT NULL,
  `reservas` decimal(12,2) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','desembolsado','rechazado') NOT NULL DEFAULT 'pendiente',
  `puntaje` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_prestamos`
--

INSERT INTO `tbl_prestamos` (`id`, `socio_id`, `nombre_caja_rural`, `monto_solicitado`, `plazo_meses`, `destino`, `tipo_credito`, `fecha_solicitud`, `porcentaje_mora_caja`, `intereses_cobrados`, `capital_social`, `capital_trabajo`, `reservas`, `estado`, `puntaje`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 6, 'principal', 2000.00, 12, 'banca', 'prestamo', '2025-07-02', 0.10, 0.03, 12000.00, 1000.00, 10000.00, 'pendiente', 30, NULL, '2025-07-18 05:49:46', '2025-07-18 05:49:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tecnicos`
--

CREATE TABLE `tbl_tecnicos` (
  `Id_Tecnico` int(11) NOT NULL,
  `Nombre_Tecnico` varchar(80) NOT NULL,
  `Apellido_Tecnico` varchar(80) NOT NULL,
  `Correo_Electronico` varchar(50) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ahorros`
--
ALTER TABLE `ahorros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_organizacion` (`id_organizacion`),
  ADD KEY `id_beneficiario` (`id_beneficiario`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `indicador_generos`
--
ALTER TABLE `indicador_generos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tbl_actividad_economica`
--
ALTER TABLE `tbl_actividad_economica`
  ADD PRIMARY KEY (`Id_Actividad`),
  ADD KEY `Id_Beneficiario` (`Id_Beneficiario`);

--
-- Indices de la tabla `tbl_aldea`
--
ALTER TABLE `tbl_aldea`
  ADD PRIMARY KEY (`Id_Aldea`,`Id_Municipio`),
  ADD KEY `fk_TBL_ALDEA_TBL_MUNICIPIO1_idx` (`Id_Municipio`);

--
-- Indices de la tabla `tbl_anexo_respuesta`
--
ALTER TABLE `tbl_anexo_respuesta`
  ADD PRIMARY KEY (`Id_Anexo_Respuesta`,`Id_Documento`,`Id_Organizacion`,`Id_Usuario`),
  ADD KEY `fk_tbl_anexo_respuesta_TBL_DOCUMENTO1_idx` (`Id_Documento`),
  ADD KEY `fk_TBL_ANEXO_RESPUESTA_TBL_ORGANIZACION1_idx` (`Id_Organizacion`),
  ADD KEY `fk_TBL_ANEXO_RESPUESTA_TBL_MS_USUARIO1_idx` (`Id_Usuario`);

--
-- Indices de la tabla `tbl_beneficiario`
--
ALTER TABLE `tbl_beneficiario`
  ADD PRIMARY KEY (`Id_Beneficiario`,`Id_Organizacion`),
  ADD KEY `fk_TBL_BENEFICIARIO_TBL_ORGANIZACION1_idx` (`Id_Organizacion`);

--
-- Indices de la tabla `tbl_capacitacion`
--
ALTER TABLE `tbl_capacitacion`
  ADD PRIMARY KEY (`Id_Capacitacion`,`Id_Tecnico`),
  ADD KEY `fk_TBL_CAPACITACION_TBL_TECNICOS1_idx` (`Id_Tecnico`);

--
-- Indices de la tabla `tbl_coordenadas_municipio`
--
ALTER TABLE `tbl_coordenadas_municipio`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `coord_unique` (`Id_Departamento`,`Id_Municipio`),
  ADD KEY `Id_Municipio` (`Id_Municipio`);

--
-- Indices de la tabla `tbl_datos`
--
ALTER TABLE `tbl_datos`
  ADD PRIMARY KEY (`Id_Dato`,`Id_Documento`),
  ADD KEY `fk_TBL_DATOS_TBL_DOCUMENTO1_idx` (`Id_Documento`);

--
-- Indices de la tabla `tbl_departamento`
--
ALTER TABLE `tbl_departamento`
  ADD PRIMARY KEY (`Id_Departamento`);

--
-- Indices de la tabla `tbl_detalle_respuesta`
--
ALTER TABLE `tbl_detalle_respuesta`
  ADD PRIMARY KEY (`Id_Detalle_Respuesta`,`Id_Dato`,`Id_Anexo_Respuesta`),
  ADD KEY `fk_TBL_DETALLE_RESPUESTA_tbl_anexo_respuesta1_idx` (`Id_Anexo_Respuesta`),
  ADD KEY `fk_TBL_DETALLE_RESPUESTA_TBL_DATOS1_idx` (`Id_Dato`);

--
-- Indices de la tabla `tbl_documento`
--
ALTER TABLE `tbl_documento`
  ADD PRIMARY KEY (`Id_Documento`);

--
-- Indices de la tabla `tbl_emprendimiento`
--
ALTER TABLE `tbl_emprendimiento`
  ADD PRIMARY KEY (`Id_Emprendimiento`),
  ADD KEY `fk_emprendimiento_municipio` (`Id_Municipio`),
  ADD KEY `fk_emprendimiento_tecnico` (`Id_Tecnico`);

--
-- Indices de la tabla `tbl_evaluacion`
--
ALTER TABLE `tbl_evaluacion`
  ADD PRIMARY KEY (`Id_Evaluacion`);

--
-- Indices de la tabla `tbl_financiera`
--
ALTER TABLE `tbl_financiera`
  ADD PRIMARY KEY (`Id_Financiera`,`Id_Organizacion`),
  ADD KEY `fk_TBL_FINANCIERA_TBL_ORGANIZACION1_idx` (`Id_Organizacion`);

--
-- Indices de la tabla `tbl_ms_bitacora`
--
ALTER TABLE `tbl_ms_bitacora`
  ADD PRIMARY KEY (`Id_Bitacora`,`Id_Usuario`,`Id_Objeto`),
  ADD KEY `fk_TBL_MS_BITACORA_TBL_MS_USUARIO1_idx` (`Id_Usuario`),
  ADD KEY `fk_TBL_MS_BITACORA_TBL_MS_OBJETO1_idx` (`Id_Objeto`);

--
-- Indices de la tabla `tbl_ms_hist_contraseña`
--
ALTER TABLE `tbl_ms_hist_contraseña`
  ADD PRIMARY KEY (`Id_Historial`,`Id_Usuario`),
  ADD KEY `fk_TBL_MS_HIST_CONTRASEÑA_TBL_MS_USUARIO1_idx` (`Id_Usuario`);

--
-- Indices de la tabla `tbl_ms_objeto`
--
ALTER TABLE `tbl_ms_objeto`
  ADD PRIMARY KEY (`Id_Objeto`);

--
-- Indices de la tabla `tbl_ms_rol`
--
ALTER TABLE `tbl_ms_rol`
  ADD PRIMARY KEY (`Id_Rol`);

--
-- Indices de la tabla `tbl_ms_roles_objeto`
--
ALTER TABLE `tbl_ms_roles_objeto`
  ADD PRIMARY KEY (`Id_Roles_Objeto`,`Id_Rol`,`Id_Objeto`),
  ADD KEY `fk_TBL_MS_ROLES_OBJETO_TBL_MS_OBJETO1_idx` (`Id_Objeto`),
  ADD KEY `fk_TBL_MS_ROLES_OBJETO_TBL_MS_ROL1` (`Id_Rol`);

--
-- Indices de la tabla `tbl_ms_usuario`
--
ALTER TABLE `tbl_ms_usuario`
  ADD PRIMARY KEY (`Id_Usuario`,`Id_Rol`),
  ADD UNIQUE KEY `Correo_Electronico_UNIQUE` (`Correo_Electronico`),
  ADD KEY `fk_TBL_MS_USUARIO_TBL_MS_ROL1_idx` (`Id_Rol`);

--
-- Indices de la tabla `tbl_municipio`
--
ALTER TABLE `tbl_municipio`
  ADD PRIMARY KEY (`Id_Municipio`,`Id_Departamento`),
  ADD KEY `fk_TBL_MUNICIPIO_TBL_DEPARTAMENTO1_idx` (`Id_Departamento`);

--
-- Indices de la tabla `tbl_organizacion`
--
ALTER TABLE `tbl_organizacion`
  ADD PRIMARY KEY (`Id_Organizacion`,`Id_Aldea`,`Id_Usuario`),
  ADD KEY `fk_TBL_ORGANIZACION_TBL_ALDEA1_idx` (`Id_Aldea`),
  ADD KEY `fk_TBL_ORGANIZACION_TBL_MS_USUARIO1_idx` (`Id_Usuario`);

--
-- Indices de la tabla `tbl_pagos`
--
ALTER TABLE `tbl_pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prestamo_pago` (`prestamo_id`);

--
-- Indices de la tabla `tbl_parametros`
--
ALTER TABLE `tbl_parametros`
  ADD PRIMARY KEY (`Id_Parametro`,`Id_Usuario`),
  ADD KEY `fk_TBL_PARAMETROS_TBL_MS_USUARIO1_idx` (`Id_Usuario`);

--
-- Indices de la tabla `tbl_prestamos`
--
ALTER TABLE `tbl_prestamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_tecnicos`
--
ALTER TABLE `tbl_tecnicos`
  ADD PRIMARY KEY (`Id_Tecnico`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ahorros`
--
ALTER TABLE `ahorros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `indicador_generos`
--
ALTER TABLE `indicador_generos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_actividad_economica`
--
ALTER TABLE `tbl_actividad_economica`
  MODIFY `Id_Actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbl_aldea`
--
ALTER TABLE `tbl_aldea`
  MODIFY `Id_Aldea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tbl_anexo_respuesta`
--
ALTER TABLE `tbl_anexo_respuesta`
  MODIFY `Id_Anexo_Respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_beneficiario`
--
ALTER TABLE `tbl_beneficiario`
  MODIFY `Id_Beneficiario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tbl_capacitacion`
--
ALTER TABLE `tbl_capacitacion`
  MODIFY `Id_Capacitacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_coordenadas_municipio`
--
ALTER TABLE `tbl_coordenadas_municipio`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_datos`
--
ALTER TABLE `tbl_datos`
  MODIFY `Id_Dato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `tbl_departamento`
--
ALTER TABLE `tbl_departamento`
  MODIFY `Id_Departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tbl_detalle_respuesta`
--
ALTER TABLE `tbl_detalle_respuesta`
  MODIFY `Id_Detalle_Respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `tbl_documento`
--
ALTER TABLE `tbl_documento`
  MODIFY `Id_Documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_emprendimiento`
--
ALTER TABLE `tbl_emprendimiento`
  MODIFY `Id_Emprendimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_evaluacion`
--
ALTER TABLE `tbl_evaluacion`
  MODIFY `Id_Evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_financiera`
--
ALTER TABLE `tbl_financiera`
  MODIFY `Id_Financiera` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_ms_bitacora`
--
ALTER TABLE `tbl_ms_bitacora`
  MODIFY `Id_Bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=781;

--
-- AUTO_INCREMENT de la tabla `tbl_ms_hist_contraseña`
--
ALTER TABLE `tbl_ms_hist_contraseña`
  MODIFY `Id_Historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_ms_objeto`
--
ALTER TABLE `tbl_ms_objeto`
  MODIFY `Id_Objeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tbl_ms_rol`
--
ALTER TABLE `tbl_ms_rol`
  MODIFY `Id_Rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_ms_roles_objeto`
--
ALTER TABLE `tbl_ms_roles_objeto`
  MODIFY `Id_Roles_Objeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `tbl_ms_usuario`
--
ALTER TABLE `tbl_ms_usuario`
  MODIFY `Id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `tbl_municipio`
--
ALTER TABLE `tbl_municipio`
  MODIFY `Id_Municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT de la tabla `tbl_organizacion`
--
ALTER TABLE `tbl_organizacion`
  MODIFY `Id_Organizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tbl_pagos`
--
ALTER TABLE `tbl_pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_parametros`
--
ALTER TABLE `tbl_parametros`
  MODIFY `Id_Parametro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_prestamos`
--
ALTER TABLE `tbl_prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_tecnicos`
--
ALTER TABLE `tbl_tecnicos`
  MODIFY `Id_Tecnico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ahorros`
--
ALTER TABLE `ahorros`
  ADD CONSTRAINT `ahorros_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `tbl_organizacion` (`Id_Organizacion`),
  ADD CONSTRAINT `ahorros_ibfk_2` FOREIGN KEY (`id_beneficiario`) REFERENCES `tbl_beneficiario` (`Id_Beneficiario`);

--
-- Filtros para la tabla `tbl_actividad_economica`
--
ALTER TABLE `tbl_actividad_economica`
  ADD CONSTRAINT `tbl_actividad_economica_ibfk_1` FOREIGN KEY (`Id_Beneficiario`) REFERENCES `tbl_beneficiario` (`Id_Beneficiario`);

--
-- Filtros para la tabla `tbl_aldea`
--
ALTER TABLE `tbl_aldea`
  ADD CONSTRAINT `fk_TBL_ALDEA_TBL_MUNICIPIO1` FOREIGN KEY (`Id_Municipio`) REFERENCES `tbl_municipio` (`Id_Municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_anexo_respuesta`
--
ALTER TABLE `tbl_anexo_respuesta`
  ADD CONSTRAINT `fk_TBL_ANEXO_RESPUESTA_TBL_MS_USUARIO1` FOREIGN KEY (`Id_Usuario`) REFERENCES `tbl_ms_usuario` (`Id_Usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TBL_ANEXO_RESPUESTA_TBL_ORGANIZACION1` FOREIGN KEY (`Id_Organizacion`) REFERENCES `tbl_organizacion` (`Id_Organizacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_anexo_respuesta_TBL_DOCUMENTO1` FOREIGN KEY (`Id_Documento`) REFERENCES `tbl_documento` (`Id_Documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_beneficiario`
--
ALTER TABLE `tbl_beneficiario`
  ADD CONSTRAINT `fk_TBL_BENEFICIARIO_TBL_ORGANIZACION1` FOREIGN KEY (`Id_Organizacion`) REFERENCES `tbl_organizacion` (`Id_Organizacion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_capacitacion`
--
ALTER TABLE `tbl_capacitacion`
  ADD CONSTRAINT `fk_TBL_CAPACITACION_TBL_TECNICOS1` FOREIGN KEY (`Id_Tecnico`) REFERENCES `tbl_tecnicos` (`Id_Tecnico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_coordenadas_municipio`
--
ALTER TABLE `tbl_coordenadas_municipio`
  ADD CONSTRAINT `tbl_coordenadas_municipio_ibfk_1` FOREIGN KEY (`Id_Departamento`) REFERENCES `tbl_departamento` (`Id_Departamento`),
  ADD CONSTRAINT `tbl_coordenadas_municipio_ibfk_2` FOREIGN KEY (`Id_Municipio`) REFERENCES `tbl_municipio` (`Id_Municipio`);

--
-- Filtros para la tabla `tbl_datos`
--
ALTER TABLE `tbl_datos`
  ADD CONSTRAINT `fk_TBL_DATOS_TBL_DOCUMENTO1` FOREIGN KEY (`Id_Documento`) REFERENCES `tbl_documento` (`Id_Documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_detalle_respuesta`
--
ALTER TABLE `tbl_detalle_respuesta`
  ADD CONSTRAINT `fk_TBL_DETALLE_RESPUESTA_TBL_DATOS1` FOREIGN KEY (`Id_Dato`) REFERENCES `tbl_datos` (`Id_Dato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TBL_DETALLE_RESPUESTA_tbl_anexo_respuesta1` FOREIGN KEY (`Id_Anexo_Respuesta`) REFERENCES `tbl_anexo_respuesta` (`Id_Anexo_Respuesta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_emprendimiento`
--
ALTER TABLE `tbl_emprendimiento`
  ADD CONSTRAINT `fk_emprendimiento_municipio` FOREIGN KEY (`Id_Municipio`) REFERENCES `tbl_municipio` (`Id_Municipio`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_emprendimiento_tecnico` FOREIGN KEY (`Id_Tecnico`) REFERENCES `tbl_ms_usuario` (`Id_Usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tbl_financiera`
--
ALTER TABLE `tbl_financiera`
  ADD CONSTRAINT `fk_TBL_FINANCIERA_TBL_ORGANIZACION1` FOREIGN KEY (`Id_Organizacion`) REFERENCES `tbl_organizacion` (`Id_Organizacion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_ms_bitacora`
--
ALTER TABLE `tbl_ms_bitacora`
  ADD CONSTRAINT `fk_TBL_MS_BITACORA_TBL_MS_OBJETO1` FOREIGN KEY (`Id_Objeto`) REFERENCES `tbl_ms_objeto` (`Id_Objeto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TBL_MS_BITACORA_TBL_MS_USUARIO1` FOREIGN KEY (`Id_Usuario`) REFERENCES `tbl_ms_usuario` (`Id_Usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_ms_hist_contraseña`
--
ALTER TABLE `tbl_ms_hist_contraseña`
  ADD CONSTRAINT `fk_TBL_MS_HIST_CONTRASEÑA_TBL_MS_USUARIO1` FOREIGN KEY (`Id_Usuario`) REFERENCES `tbl_ms_usuario` (`Id_Usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_ms_roles_objeto`
--
ALTER TABLE `tbl_ms_roles_objeto`
  ADD CONSTRAINT `fk_TBL_MS_ROLES_OBJETO_TBL_MS_OBJETO1` FOREIGN KEY (`Id_Objeto`) REFERENCES `tbl_ms_objeto` (`Id_Objeto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TBL_MS_ROLES_OBJETO_TBL_MS_ROL1` FOREIGN KEY (`Id_Rol`) REFERENCES `tbl_ms_rol` (`Id_Rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_ms_usuario`
--
ALTER TABLE `tbl_ms_usuario`
  ADD CONSTRAINT `fk_TBL_MS_USUARIO_TBL_MS_ROL1` FOREIGN KEY (`Id_Rol`) REFERENCES `tbl_ms_rol` (`Id_Rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_municipio`
--
ALTER TABLE `tbl_municipio`
  ADD CONSTRAINT `fk_TBL_MUNICIPIO_TBL_DEPARTAMENTO1` FOREIGN KEY (`Id_Departamento`) REFERENCES `tbl_departamento` (`Id_Departamento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_organizacion`
--
ALTER TABLE `tbl_organizacion`
  ADD CONSTRAINT `fk_TBL_ORGANIZACION_TBL_ALDEA1` FOREIGN KEY (`Id_Aldea`) REFERENCES `tbl_aldea` (`Id_Aldea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TBL_ORGANIZACION_TBL_MS_USUARIO1` FOREIGN KEY (`Id_Usuario`) REFERENCES `tbl_ms_usuario` (`Id_Usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_pagos`
--
ALTER TABLE `tbl_pagos`
  ADD CONSTRAINT `fk_prestamo_pago` FOREIGN KEY (`prestamo_id`) REFERENCES `tbl_prestamos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tbl_parametros`
--
ALTER TABLE `tbl_parametros`
  ADD CONSTRAINT `fk_TBL_PARAMETROS_TBL_MS_USUARIO1` FOREIGN KEY (`Id_Usuario`) REFERENCES `tbl_ms_usuario` (`Id_Usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
