-- Migration para adicionar a tabela de configurações e mover as configurações de tema para o banco de dados.
-- Por favor, execute este script no seu banco de dados MySQL.

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `setting_value` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Limpa a tabela antes de inserir para garantir que não haja duplicatas se o script for executado novamente.
TRUNCATE TABLE `settings`;

-- Insere os valores padrão do antigo THEME_CONFIG para evitar que o site quebre.
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('header_logo_url', 'https://via.placeholder.com/150x50.png?text=Sua+Logo'),
('header_cor_fundo', '#FFFFFF'),
('header_cor_letra', '#000000'),
('footer_cor_fundo', '#F8F9FA'),
('footer_cor_letra', '#6C757D');
