-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Servidor: bssmsfpa.mysql.db
-- Tiempo de generación: 31-05-2023 a las 18:05:49
-- Versión del servidor: 5.7.42-log
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bssmsfpa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bebidas`
--

CREATE TABLE `bebidas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` enum('alcoholica','no_alcoholica','cafe') DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `bebidas`
--

INSERT INTO `bebidas` (`id`, `nombre`, `tipo`, `precio`, `stock`) VALUES
(1, 'Monster', 'no_alcoholica', '2.00', 29),
(3, 'Cortado', 'cafe', '1.60', 31),
(4, 'jagger', 'alcoholica', '16.00', 32),
(6, 'Coca cola', 'no_alcoholica', '2.00', 54),
(18, 'Redbul', 'no_alcoholica', '3.00', 52),
(19, 'Freeway siempre operativa', 'no_alcoholica', '1.20', 65);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historico_tickets`
--

CREATE TABLE `historico_tickets` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `mesa` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `pagado` tinyint(1) DEFAULT '0',
  `completado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `mesa`, `fecha_hora`, `pagado`, `completado`) VALUES
(41, 50, NULL, 0, 1),
(42, 345, NULL, 0, 1),
(43, 2, NULL, 0, 2),
(44, 1, NULL, 0, 2),
(45, 4, NULL, 0, 1),
(46, 345, NULL, 0, 1),
(47, 345, NULL, 0, 2),
(48, 2, NULL, 0, 1),
(49, 5000, NULL, 0, 2),
(50, 0, NULL, 0, 1),
(51, 345, NULL, 0, 1),
(52, 56, NULL, 0, 1),
(53, 4, NULL, 0, 1),
(54, 2, NULL, 0, 1),
(55, 34, NULL, 0, 2),
(56, 9, NULL, 0, 1),
(57, 9, NULL, 0, 2),
(58, 56, NULL, 0, 2),
(59, 1, NULL, 0, 1),
(60, 65, NULL, 0, 1),
(61, 67, NULL, 0, 2),
(62, 67, NULL, 0, 2),
(63, -2, NULL, 0, 1),
(64, 343, NULL, 0, 1),
(65, 343, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_detalle`
--

CREATE TABLE `pedidos_detalle` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `bebida_id` int(11) DEFAULT NULL,
  `plato_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pedidos_detalle`
--

INSERT INTO `pedidos_detalle` (`id`, `pedido_id`, `bebida_id`, `plato_id`, `cantidad`) VALUES
(178, 41, NULL, 5, 1),
(179, 41, NULL, 10, 1),
(180, 41, NULL, 3, 1),
(181, 41, 4, NULL, 1),
(182, 41, 6, NULL, 1),
(183, 41, 3, NULL, 1),
(184, 42, NULL, 5, 1),
(185, 42, NULL, 10, 1),
(186, 42, NULL, 3, 1),
(187, 42, 18, NULL, 1),
(188, 42, 3, NULL, 1),
(189, 43, NULL, 10, 1),
(190, 43, 4, NULL, 1),
(191, 43, 6, NULL, 1),
(192, 43, 3, NULL, 1),
(193, 44, NULL, 5, 1),
(194, 44, NULL, 3, 1),
(195, 44, 4, NULL, 1),
(196, 44, 1, NULL, 1),
(197, 44, 3, NULL, 1),
(198, 45, NULL, 5, 1),
(199, 45, NULL, 10, 1),
(200, 45, NULL, 3, 1),
(201, 45, 4, NULL, 1),
(202, 46, NULL, 5, 1),
(203, 46, NULL, 10, 1),
(204, 46, NULL, 3, 1),
(205, 46, 18, NULL, 1),
(206, 46, 3, NULL, 1),
(207, 47, NULL, 5, 1),
(208, 47, NULL, 10, 1),
(209, 47, NULL, 3, 1),
(210, 47, 1, NULL, 1),
(211, 47, 18, NULL, 1),
(212, 47, 3, NULL, 1),
(213, 48, NULL, 5, 1),
(214, 48, 4, NULL, 1),
(215, 48, 18, NULL, 1),
(216, 48, 3, NULL, 1),
(217, 49, NULL, 5, 1),
(218, 49, NULL, 10, 1),
(219, 49, NULL, 3, 1),
(220, 49, 4, NULL, 1),
(221, 49, 1, NULL, 1),
(222, 49, 3, NULL, 1),
(223, 51, NULL, 5, 1),
(224, 51, NULL, 10, 1),
(225, 51, NULL, 3, 1),
(226, 51, 1, NULL, 1),
(227, 51, 18, NULL, 1),
(228, 51, 3, NULL, 1),
(229, 52, NULL, 5, 1),
(230, 52, NULL, 10, 1),
(231, 52, NULL, 3, 1),
(232, 52, 1, NULL, 1),
(233, 52, 3, NULL, 1),
(234, 53, NULL, 5, 1),
(235, 53, NULL, 10, 1),
(236, 53, NULL, 3, 1),
(237, 53, 4, NULL, 1),
(238, 53, 1, NULL, 1),
(239, 53, 6, NULL, 1),
(240, 53, 18, NULL, 1),
(241, 53, 3, NULL, 1),
(242, 54, NULL, 5, 1),
(243, 54, NULL, 3, 1),
(244, 54, 1, NULL, 1),
(245, 55, NULL, 5, 1),
(246, 55, NULL, 10, 1),
(247, 55, NULL, 3, 1),
(248, 55, 4, NULL, 1),
(249, 55, 6, NULL, 1),
(250, 55, 3, NULL, 1),
(251, 56, NULL, 5, 1),
(252, 56, NULL, 10, 1),
(253, 56, 1, NULL, 1),
(254, 56, 3, NULL, 1),
(255, 57, NULL, 5, 1),
(256, 57, NULL, 10, 1),
(257, 57, 1, NULL, 1),
(258, 57, 19, NULL, 1),
(259, 58, NULL, 5, 1),
(260, 58, NULL, 13, 1),
(261, 58, NULL, 10, 1),
(262, 58, NULL, 12, 1),
(263, 58, NULL, 14, 1),
(264, 58, NULL, 3, 1),
(265, 58, 4, NULL, 1),
(266, 58, 1, NULL, 1),
(267, 58, 18, NULL, 1),
(268, 59, NULL, 5, 1),
(269, 59, NULL, 12, 1),
(270, 59, 4, NULL, 1),
(271, 59, 19, NULL, 1),
(272, 59, 3, NULL, 1),
(273, 60, NULL, 5, 1),
(274, 60, NULL, 13, 1),
(275, 60, NULL, 10, 1),
(276, 60, NULL, 12, 1),
(277, 60, NULL, 14, 1),
(278, 60, NULL, 3, 1),
(279, 60, 4, NULL, 1),
(280, 60, 1, NULL, 1),
(281, 60, 6, NULL, 1),
(282, 60, 18, NULL, 1),
(283, 60, 19, NULL, 1),
(284, 60, 3, NULL, 1),
(285, 64, NULL, 5, 1),
(286, 64, NULL, 13, 1),
(287, 64, NULL, 10, 1),
(288, 64, NULL, 12, 1),
(289, 64, NULL, 14, 1),
(290, 64, NULL, 3, 1),
(291, 64, 4, NULL, 1),
(292, 64, 19, NULL, 1),
(293, 65, NULL, 5, 1),
(294, 65, NULL, 13, 1),
(295, 65, NULL, 10, 1),
(296, 65, NULL, 12, 1),
(297, 65, NULL, 14, 1),
(298, 65, NULL, 3, 1),
(299, 65, 4, NULL, 1),
(300, 65, 19, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` enum('entrante','plato','postre') DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`id`, `nombre`, `tipo`, `precio`, `stock`) VALUES
(3, 'Tarta de queso', 'postre', '7.00', 15),
(5, 'Aceitunas del pacifico', 'entrante', '2.00', 16),
(10, 'atun', 'plato', '12.00', 53),
(12, 'Tortilla de patatas', 'plato', '7.00', 73),
(13, 'Mayonesa al corte', 'entrante', '3.00', 76),
(14, 'migas con bacalao', 'plato', '7.00', 76);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bebidas`
--
ALTER TABLE `bebidas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historico_tickets`
--
ALTER TABLE `historico_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `bebida_id` (`bebida_id`),
  ADD KEY `plato_id` (`plato_id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bebidas`
--
ALTER TABLE `bebidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `historico_tickets`
--
ALTER TABLE `historico_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historico_tickets`
--
ALTER TABLE `historico_tickets`
  ADD CONSTRAINT `historico_tickets_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`);

--
-- Filtros para la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD CONSTRAINT `pedidos_detalle_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedidos_detalle_ibfk_2` FOREIGN KEY (`bebida_id`) REFERENCES `bebidas` (`id`),
  ADD CONSTRAINT `pedidos_detalle_ibfk_3` FOREIGN KEY (`plato_id`) REFERENCES `platos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
