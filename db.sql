-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_menu`
--

CREATE TABLE IF NOT EXISTS `blog_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog_post`
--

CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `corpo` longtext NOT NULL,
  `min_desc` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `hora` varchar(255) NOT NULL,
  `capa` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentario`
--

CREATE TABLE IF NOT EXISTS `comentario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idpost` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comentario` longtext NOT NULL,
  `data` varchar(255) NOT NULL,
  `hora` varchar(255) NOT NULL,
  `autorpost` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `comentario`
--

INSERT INTO `comentario` (`id`, `idpost`, `nome`, `email`, `comentario`, `data`, `hora`, `autorpost`) VALUES
(2, '6', 'Gabriel', 'gcdesign@kmaill.com.br', '<p>testando cadastro de comentarios</p>', '01/08/2013 às', '00:25', 'spark'),
(3, '6', 'Corel Draw', 'gcdesign@kmaill.com.br', '<p>adsdasd</p>', '01/08/2013 às', '00:26', 'spark'),
(4, '5', 'Seu Nome', 'gcdesign@kmaill.com.br', '<p>asdasd</p>', '01/08/2013 às', '00:26', 'spark'),
(5, '5', 'Seu', 'test@test.com', '<p>testando a atualiza&ccedil;&atilde;o do coment&aacute;rio</p>', '31/07/2013 às', '21:37', 'spark');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comunicado`
--

CREATE TABLE IF NOT EXISTS `comunicado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `corpo` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `comunicado`
--

INSERT INTO `comunicado` (`id`, `corpo`) VALUES
(1, '<p>Aten&ccedil;&atilde;o! Nem Todos os Recursos do Painel de Controle est&atilde;o funcionando 100%.<br />Se voc&ecirc; encontar algum&nbsp;<span style="color: #ff0000;">ERRO&nbsp;</span>informe atrav&eacute;s de Nossa&nbsp;<a href="http://www.jambull.com.br" target="_blank">P&aacute;gina de Contato</a>&nbsp;com o Assunto "Bug encontrado na p&aacute;gina: ''pagina onde o erro foi encontrado''".</p>\n<p>Assim poderemos transformar nosso sistema, em um dos melhores do ramo.</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `config_site`
--

CREATE TABLE IF NOT EXISTS `config_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto`
--

CREATE TABLE IF NOT EXISTS `projeto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autor` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `capa` varchar(255) NOT NULL,
  `demo` varchar(255) NOT NULL,
  `download` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `projeto`
--

INSERT INTO `projeto` (`id`, `autor`, `username`, `categoria`, `titulo`, `descricao`, `capa`, `demo`, `download`) VALUES
(4, 'Gabriel Costa', 'spark', '4', 'testando Atualização', '<p>sdsadsa</p>', 'ee9647f373a494d7af677780171e6b6a.jpg', 'http://localhost/jambull', 'http://url-do-projeto.com/projeto'),
(5, 'Gabriel Costa', 'spark', '4', 'A Nova Tendência do Flat Design. O que? Por que?', '<p>sadasdsad</p>', '6d862a81bdf87b6eaaa798e32dc6812b.jpg', 'http://localhost/jambull', 'http://url-do-projeto.com/projeto');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projetos_menu`
--

CREATE TABLE IF NOT EXISTS `projetos_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autor` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `capa` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `projetos_menu`
--

INSERT INTO `projetos_menu` (`id`, `autor`, `titulo`, `capa`, `desc`) VALUES
(1, 'spark', 'Objet C#', '', 'Livre redistribuição: Sua licença não pode restringir ninguém, proibindo que se venda ou doe o software a terceiros;\nCódigo-fonte: O programa precisa obrigatoriamente incluir código-fonte e permitir a distribuição tanto do código-fonte quanto do programa '),
(2, 'spark', 'PHP', 'dacd0c0da6f6a96b10a636f3d8fbcab0.jpg', 'Livre redistribuição: Sua licença não pode restringir ninguém, proibindo que se venda ou doe o software a terceiros;\r\nCódigo-fonte: O programa precisa obrigatoriamente incluir código-fonte e permitir a distribuição tanto do código-fonte quanto do programa já compilado;\r\nObras derivadas:'),
(3, 'spark', 'JQuery', '', ''),
(4, 'spark', 'JavaScript', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_imagem`
--

CREATE TABLE IF NOT EXISTS `projeto_imagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projeto` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `titulo_img` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `username`, `foto`, `email`, `senha`, `nivel`) VALUES
(1, 'Gabriel Costa', 'spark', 'c9573d6ba3e1acd82061e168aeff0cf0.jpg', 'gcdesign@kmaill.com.br', 'MzQ0MzQ1NDU=', 'admin');
