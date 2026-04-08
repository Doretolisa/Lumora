-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql313.infinityfree.com
-- Tempo de geração: 08/04/2026 às 11:33
-- Versão do servidor: 11.4.10-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `if0_41611802_lumora`
--
CREATE DATABASE IF NOT EXISTS `if0_41611802_lumora` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `if0_41611802_lumora`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_acessos`
--

CREATE TABLE `log_acessos` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `log_acessos`
--

INSERT INTO `log_acessos` (`id`, `usuario_id`, `ip`, `user_agent`, `criado_em`) VALUES
(1, 4, '189.29.151.108', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 08:26:41'),
(2, 4, '189.29.151.108', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 08:30:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `ativo`, `criado_em`, `atualizado_em`) VALUES
(1, 'Admin Lumora', 'admin@lumora.com', '$2y$12$K8N1zJqW3vXmP7oL9cRdYeHsTtUiOpQwErTyUiOpAsdfGhJkLzXcV', 1, '2026-04-07 09:23:50', '2026-04-07 09:23:50'),
(2, 'Monalisa', 'lisa@lumora.com', '$2y$10$ChP/V9cZjwOWPvRp4AK.3umCVVwIQKLulzbQBp.In9L5Dd4EuDFC2', 1, '2026-04-07 09:57:15', '2026-04-07 09:57:15'),
(3, 'Isabella', 'isabella@allebasi.com', '$2y$10$eoNliDcAgT6G7of3IE9e9uL8ZRWKOun3a8Q1ET8DT4U5zZDY46oIW', 1, '2026-04-07 10:00:22', '2026-04-07 10:00:22'),
(4, 'Monalisa Doreto', 'monalisadoreto.a@gmail.com', '$2y$10$UakKKzs86vgj6gUqLxGl8ukqKcNSnFjSM039AyixbIv/8rgQUAXBq', 1, '2026-04-08 08:26:34', '2026-04-08 08:26:34');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `log_acessos`
--
ALTER TABLE `log_acessos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `log_acessos`
--
ALTER TABLE `log_acessos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `log_acessos`
--
ALTER TABLE `log_acessos`
  ADD CONSTRAINT `log_acessos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
