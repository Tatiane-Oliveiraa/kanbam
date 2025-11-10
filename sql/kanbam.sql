-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/10/2025 às 04:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE kanbam;
USE kanbam;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `kanbam`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas`
--

CREATE TABLE `perguntas` (
  `id` int(11) NOT NULL,
  `pergunta` text NOT NULL,
  `alternativa_a` varchar(255) DEFAULT NULL,
  `alternativa_b` varchar(255) DEFAULT NULL,
  `alternativa_c` varchar(255) DEFAULT NULL,
  `alternativa_d` varchar(255) DEFAULT NULL,
  `resposta_correta` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perguntas`
--

INSERT INTO `perguntas` (`id`, `pergunta`, `alternativa_a`, `alternativa_b`, `alternativa_c`, `alternativa_d`, `resposta_correta`) VALUES
(1, 'Qual linguagem é usada para desenvolver aplicações Android nativas?', 'Python', 'Java', 'Swift', 'C#', 'B'),
(2, 'O que significa HTML?', 'HyperText Markup Language', 'HighText Machine Language', 'HyperTool Multi Language', 'None', 'A'),
(3, 'Qual desses é um framework JavaScript?', 'Laravel', 'Django', 'React', 'Flask', 'C'),
(4, 'Qual comando Git cria uma nova branch?', 'git branch', 'git init', 'git commit', 'git push', 'A'),
(5, 'Qual linguagem é mais usada para ciência de dados?', 'C++', 'Java', 'Python', 'Ruby', 'C'),
(6, 'O que é um loop for?', 'Uma função', 'Uma estrutura de repetição', 'Um tipo de variável', 'Um operador lógico', 'B'),
(7, 'Qual destes é um banco de dados relacional?', 'MongoDB', 'Redis', 'MySQL', 'Elasticsearch', 'C'),
(8, 'Qual palavra-chave declara uma constante em JavaScript?', 'let', 'var', 'const', 'static', 'C'),
(9, 'O que significa CSS?', 'Cascading Style Sheets', 'Computer Style Syntax', 'Creative Style System', 'Central Style Sheet', 'A'),
(10, 'Qual destes é um sistema de controle de versão?', 'Docker', 'Git', 'Nginx', 'Jenkins', 'B'),
(11, 'Em Python, qual comando imprime algo na tela?', 'echo', 'print', 'console.log', 'write', 'B'),
(12, 'Qual linguagem é tipicamente usada para scripts em páginas web?', 'PHP', 'Java', 'JavaScript', 'C++', 'C'),
(13, 'O que faz o comando \"git clone\"?', 'Cria um repositório', 'Remove arquivos', 'Copia um repositório', 'Atualiza o repositório', 'C'),
(14, 'Qual destes é um operador lógico?', '==', '&&', '++', '--', 'B'),
(15, 'Qual extensão de arquivo é usada para folhas de estilo?', '.html', '.js', '.css', '.php', 'C');

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `resposta_dada` char(1) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `status` enum('todo','in-progress','done') NOT NULL,
  `categoria` enum('trabalho','pessoal','estudos') NOT NULL,
  `descricao` text DEFAULT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `data_nascimento` date NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `cpf` varchar(14) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pergunta` (`id_pergunta`);

--
-- Índices de tabela `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `perguntas`
--
ALTER TABLE `perguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `respostas`
--
ALTER TABLE `respostas`
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `respostas_ibfk_2` FOREIGN KEY (`id_pergunta`) REFERENCES `perguntas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;