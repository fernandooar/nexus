CREATE DATABASE rede_social;

USE rede_social;

-- Tabela usuarios
CREATE TABLE usuarios (
    id_usuario INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME NOT NULL,
    INDEX (email)
);

-- Tabela amizades
CREATE TABLE amizades (
    id_amizade INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_usuario1 INT(11) NOT NULL,
    id_usuario2 INT(11) NOT NULL,
    status ENUM('pendente', 'aceita', 'recusada') NOT NULL,
    data_interacao DATETIME NOT NULL,
    FOREIGN KEY (id_usuario1) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_usuario2) REFERENCES usuarios(id_usuario)
);

-- Tabela seguidores
CREATE TABLE seguidores (
    id_seguidor INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_usuario_seguido INT(11) NOT NULL,
    id_usuario_seguidor INT(11) NOT NULL,
    data_seguimento DATETIME NOT NULL,
    FOREIGN KEY (id_usuario_seguido) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_usuario_seguidor) REFERENCES usuarios(id_usuario)
);

-- Tabela posts
CREATE TABLE posts (
    id_post INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(11) NOT NULL,
    conteudo TEXT NOT NULL,
    data_publicacao DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabela curtidas
CREATE TABLE curtidas (
    id_curtida INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_post INT(11) NOT NULL,
    id_usuario INT(11) NOT NULL,
    data_curtida DATETIME NOT NULL,
    FOREIGN KEY (id_post) REFERENCES posts(id_post),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabela comentarios
CREATE TABLE comentarios (
    id_comentario INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_post INT(11) NOT NULL,
    id_usuario INT(11) NOT NULL,
    conteudo TEXT NOT NULL,
    data_comentario DATETIME NOT NULL,
    FOREIGN KEY (id_post) REFERENCES posts(id_post),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabela grupos
CREATE TABLE grupos (
    id_grupo INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    id_criador INT(11) NOT NULL,
    data_criacao DATETIME NOT NULL,
    FOREIGN KEY (id_criador) REFERENCES usuarios(id_usuario)
);

-- Tabela membros_grupo
CREATE TABLE membros_grupo (
    id_membro_grupo INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_grupo INT(11) NOT NULL,
    id_usuario INT(11) NOT NULL,
    data_entrada DATETIME NOT NULL,
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabela mensagens_privadas
CREATE TABLE mensagens_privadas (
    id_mensagem INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_remetente INT(11) NOT NULL,
    id_destinatario INT(11) NOT NULL,
    conteudo TEXT NOT NULL,
    data_envio DATETIME NOT NULL,
    FOREIGN KEY (id_remetente) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_destinatario) REFERENCES usuarios(id_usuario)
);

-- Tabela notificacoes
CREATE TABLE notificacoes (
    id_notificacao INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(11) NOT NULL,
    tipo ENUM('curtida', 'comentario', 'amizade', 'seguimento', 'mensagem') NOT NULL,
    id_referencia INT(11) NOT NULL,
    data_notificacao DATETIME NOT NULL,
    lida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabela midia
CREATE TABLE midia (
    id_midia INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(11) NOT NULL,
    tipo ENUM('foto', 'video') NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    data_upload DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabela tags
CREATE TABLE tags (
    id_tag INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    UNIQUE (nome)
);

-- Tabela post_tags
CREATE TABLE post_tags (
    id_post_tag INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_post INT(11) NOT NULL,
    id_tag INT(11) NOT NULL,
    FOREIGN KEY (id_post) REFERENCES posts(id_post),
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag)
);

-- Tabela mencoes
CREATE TABLE mencoes (
    id_mencao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_mencionador INT NOT NULL,
    id_usuario_mencionado INT NOT NULL,
    id_referencia INT NOT NULL, -- Pode ser um id_post ou id_comentario
    tipo_mencao ENUM('post', 'comentario') NOT NULL, -- Define o tipo
    FOREIGN KEY (id_usuario_mencionador) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_usuario_mencionado) REFERENCES usuarios(id_usuario)
);


--Tabela de sessões Isso ajuda a armazenar tokens de login e manter os usuários autenticados.
CREATE TABLE sessoes (
    id_sessao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    token VARCHAR(128) UNIQUE NOT NULL,
    data_expiracao DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);


-- Adiciona o campo foto_perfil na tabela usuarios. O valor padrão será "default.png", caso o usuário não tenha enviado uma imagem.
ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) DEFAULT 'default.png';


/*
1. Tabela usuarios

    Senha: Você está armazenando a senha como VARCHAR(255), o que é bom para hashes de senha. Certifique-se de usar algoritmos de hash seguros como bcrypt ou Argon2.

    Índice: O índice no campo email é uma boa prática, já que ele será frequentemente usado para login e verificação de unicidade.

2. Tabela amizades

    Status: O uso de ENUM para o status da amizade é uma boa escolha, mas lembre-se de que ENUM pode ser menos flexível se você precisar adicionar novos status no futuro. Uma alternativa seria usar uma tabela separada para os status.

    Chaves estrangeiras: As chaves estrangeiras estão corretamente definidas, mas você pode querer adicionar um índice composto em (id_usuario1, id_usuario2) para consultas mais rápidas.

3. Tabela seguidores

    Índices: Considere adicionar um índice composto em (id_usuario_seguido, id_usuario_seguidor) para otimizar consultas que verificam se um usuário segue outro.

4. Tabela posts

    Conteúdo: O campo conteudo é do tipo TEXT, o que é adequado para postagens longas. Se você espera postagens muito grandes, pode considerar usar LONGTEXT.

5. Tabela curtidas

    Índices: Adicione um índice composto em (id_post, id_usuario) para otimizar consultas que verificam se um usuário já curtiu um post.

6. Tabela comentarios

    Índices: Similar à tabela de curtidas, um índice composto em (id_post, id_usuario) pode ser útil.

7. Tabela grupos

    Descrição: O campo descricao é do tipo TEXT, o que é bom para descrições longas. Se você espera descrições muito grandes, pode considerar usar LONGTEXT.

8. Tabela membros_grupo

    Índices: Considere adicionar um índice composto em (id_grupo, id_usuario) para otimizar consultas que verificam a membresia de um usuário em um grupo.

9. Tabela mensagens_privadas

    Índices: Adicione um índice composto em (id_remetente, id_destinatario) para otimizar consultas que buscam mensagens entre dois usuários.

10. Tabela notificacoes

    Tipo: O uso de ENUM para o tipo de notificação é uma boa escolha, mas, como mencionado anteriormente, pode ser menos flexível. Uma alternativa seria usar uma tabela separada para os tipos de notificação.

    Índices: Adicione um índice em (id_usuario, lida) para otimizar consultas que buscam notificações não lidas de um usuário.

11. Tabela midia

    Caminho: O campo caminho é do tipo VARCHAR(255), o que é adequado para caminhos de arquivos. Certifique-se de que o caminho seja seguro e não permita injeção de diretórios.

12. Tabela tags

    Nome: O campo nome é único, o que é bom para evitar tags duplicadas.

13. Tabela post_tags

    Índices: Adicione um índice composto em (id_post, id_tag) para otimizar consultas que buscam tags associadas a um post.

14. Tabela mencoes

    Tipo de menção: O uso de ENUM para o tipo de menção é uma boa escolha, mas, como mencionado anteriormente, pode ser menos flexível. Uma alternativa seria usar uma tabela separada para os tipos de menção.

    Índices: Adicione um índice composto em (id_usuario_mencionado, tipo_mencao) para otimizar consultas que buscam menções a um usuário.

15. Tabela sessoes

    Token: O campo token é único, o que é essencial para evitar conflitos de sessão.

    Data de expiração: Certifique-se de que o campo data_expiracao seja sempre atualizado para manter a segurança das sessões.

Considerações Finais:

    Normalização: O esquema está bem normalizado, mas em alguns casos, como nas tabelas de notificações e menções, você pode querer considerar a desnormalização para melhorar o desempenho, dependendo do volume de dados.

    Backup e Segurança: Certifique-se de implementar backups regulares e medidas de segurança, como sanitização de inputs e uso de prepared statements para evitar SQL injection.

    Escalabilidade: Se você espera um grande volume de dados, considere o uso de técnicas de sharding ou replicação para melhorar a escalabilidade.