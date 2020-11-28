/* -------------------- ATENÇÃO -----------------------
   |												   |
   | 	Manter a organização dos comentários           |
   |    Manter a numeração das sequeências atualizadas |
   |___________________________________________________| */

-- ################## FUNCIONALIDADE ################### 

-- FUNCIONALIDADES REFERENTE AOS MENUS ( AOS PAIS DE CADA FUNCIONALIDADE ) --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(1,'Menu.Usuario',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(2,'Menu.Perfil',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(3,'Menu.Cargo',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(19,'Menu.Tipologia',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(25,'Menu.Especialidade',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(31,'Menu.Funcao',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(37,'Menu.Empresa',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(43,'Menu.OrgaoOrigem',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(49,'Menu.Cid',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(55,'Menu.Lotacao',true,0);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE) VALUES(61,'Menu.UnidadeAtendimento',true,0);

-- FUNCIONALIDADES REFERENTE A Usuario --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(4,'Usuario.adicionar',true,1, 1);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(5,'Usuario.deletar',true,1, 1);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(6,'Usuario.editar',true,1, 1);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(7,'Usuario.visualizar',true,1, 1);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(8,'Usuario.consultar',true,1, 1);

-- FUNCIONALIDADES REFERENTE A PERFIL --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(9,'Perfil.adicionar',true,1, 2);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(10,'Perfil.deletar',true,1, 2);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(11,'Perfil.editar',true,1, 2);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(12,'Perfil.visualizar',true,1, 2);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(13,'Perfil.consultar',true,1, 2);

-- FUNCIONALIDADES REFERENTE A CARGOS --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(14,'Cargo.adicionar',true,1, 3);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(15,'Cargo.deletar',true,1, 3);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(16,'Cargo.editar',true,1, 3);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(17,'Cargo.visualizar',true,1, 3);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(18,'Cargo.consultar',true,1, 3);

-- FUNCIONALIDADES REFERENTE A TIPOLOGIA --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(20,'Tipologia.adicionar',true,1, 19);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(21,'Tipologia.deletar',true,1, 19);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(22,'Tipologia.editar',true,1, 19);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(23,'Tipologia.visualizar',true,1, 19);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(24,'Tipologia.consultar',true,1, 19);

-- FUNCIONALIDADES REFERENTE A TIPOLOGIA --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(26,'Especialidade.adicionar',true,1, 25);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(27,'Especialidade.deletar',true,1, 25);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(28,'Especialidade.editar',true,1, 25);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(29,'Especialidade.visualizar',true,1, 25);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(30,'Especialidade.consultar',true,1, 25);

-- FUNCIONALIDADES REFERENTE A FUNÇÃO --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(32,'Funcao.adicionar',true,1, 31);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(33,'Funcao.deletar',true,1, 31);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(34,'Funcao.editar',true,1, 31);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(35,'Funcao.visualizar',true,1, 31);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(36,'Funcao.consultar',true,1, 31);

-- FUNCIONALIDADES REFERENTE A EMPRESA --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(38,'Empresa.adicionar',true,1, 37);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(39,'Empresa.deletar',true,1, 37);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(40,'Empresa.editar',true,1, 37);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(41,'Empresa.visualizar',true,1, 37);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(42,'Empresa.consultar',true,1, 37);

-- FUNCIONALIDADES REFERENTE A ORGÃO DE ORIGEM --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(44,'OrgaoOrigem.adicionar',true,1, 43);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(45,'OrgaoOrigem.deletar',true,1, 43);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(46,'OrgaoOrigem.editar',true,1, 43);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(47,'OrgaoOrigem.visualizar',true,1, 43);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(48,'OrgaoOrigem.consultar',true,1, 43);

-- FUNCIONALIDADES REFERENTE A CID --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(50,'Cid.adicionar',true,1, 49);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(51,'Cid.deletar',true,1, 49);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(52,'Cid.editar',true,1, 49);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(53,'Cid.visualizar',true,1, 49);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(54,'Cid.consultar',true,1, 49);

-- FUNCIONALIDADES REFERENTE A CID --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(56,'Lotacao.adicionar',true,1, 55);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(57,'Lotacao.deletar',true,1, 55);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(58,'Lotacao.editar',true,1, 55);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(59,'Lotacao.visualizar',true,1, 55);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(60,'Lotacao.consultar',true,1, 55);

-- FUNCIONALIDADES REFERENTE A CID --
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(62,'UnidadeAtendimento.adicionar',true,1, 61);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(63,'UnidadeAtendimento.deletar',true,1, 61);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(64,'UnidadeAtendimento.editar',true,1, 61);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(65,'UnidadeAtendimento.visualizar',true,1, 61);
INSERT INTO desen.FUNCIONALIDADE(ID,NOME,ATIVO,ID_TIPO_FUNCIONALIDADE, id_funcionalidade_pai) VALUES(66,'UnidadeAtendimento.consultar',true,1, 61);

-- Ajustando valor da sequencia
SELECT setval('desen.funcionalidade_id_seq', 67, true);

-- ################## PERFIL ###################
INSERT INTO desen.PERFIL(ID, NOME, ATIVADO, ATIVO) VALUES(1,'Administrador', true, true);
-- Ajustando valor da sequencia
SELECT setval('desen.perfil_id_seq', 2, true);

-- ################## Usuario ###################
INSERT INTO desen.USUARIO(ID,NOME, CPF, SENHA, EMAIL,ATIVO, ATIVADO)VALUES(1,'Administrador', '00000000000', '$2a$10$4UZY5bbBXsxryXG5gSGa5eFal6m1X2GChCcp7egcJodcl95.EE3tW', 'raphael.borborema@banksystem.com.br', true, true);


-- ################## Tipo do Usuário ###################
INSERT INTO desen.TIPO_USUARIO(ID,NOME, ATIVO)VALUES(1,'Perito Credenciado',true);
INSERT INTO desen.TIPO_USUARIO(ID,NOME, ATIVO)VALUES(2,'Perito Servidor',true);
INSERT INTO desen.TIPO_USUARIO(ID,NOME, ATIVO)VALUES(3,'Interno',true);
INSERT INTO desen.TIPO_USUARIO(ID,NOME, ATIVO)VALUES(4,'Servidor',true);

SELECT setval('desen.usuario_id_seq', 2, true);

-- USUARIO_PERFIL
INSERT INTO desen.usuario_perfil(PERFIL_ID, USUARIO_ID) VALUES (1, 1);

-- ################## Qualidade ###################
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(1 ,'Companheiro (a)',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(2 ,'Credor (a) de alimentos ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(3 ,'Curador (a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(4 ,'Curatelado ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(5 ,'Enteado(a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(6 ,'Filho (a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(7 ,'Filho (a) maior até 21 anos ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(8 ,'Filho (a) maior inválido ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(9 ,'Filho menor ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(10,'Genitor assistido(a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(11,'Genitor (a)',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(12,'Herdeiro (a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(13,'Menor sob guarda ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(14,'Pensionista ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(15,'Pretenso pensionista ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(16,'Militar reformado ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(17,'Representante legal(procurador etc) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(18,'Separada judicialmente com pensão',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(19,'Tutelado(a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(20,'Viúvo (a) ',true);
INSERT INTO desen.qualidade(ID,NOME, ATIVO)VALUES(21,'Outros',true);

SELECT setval('desen.qualidade_id_seq', 22, true);

-- ################## Sexo ###################
INSERT INTO desen.sexo(ID,NOME, ATIVO)VALUES(1 ,'Masculino',true);
INSERT INTO desen.sexo(ID,NOME, ATIVO)VALUES(2 ,'Feminino ',true);

SELECT setval('desen.sexo_id_seq', 3, true);

-- ################## Estado Civil ###################
-- INSERT INTO desen.estado_civil(ID,NOME, ATIVO)VALUES(1 ,'Solteiro(a)',true);
-- INSERT INTO desen.estado_civil(ID,NOME, ATIVO)VALUES(2 ,'Casado(a)',true);
-- INSERT INTO desen.estado_civil(ID,NOME, ATIVO)VALUES(3 ,'Viúvo(a)',true);
-- INSERT INTO desen.estado_civil(ID,NOME, ATIVO)VALUES(4 ,'Divorciado(a)',true);

INSERT INTO desen.ESTADO_CIVIL (ID, ATIVO, DATA_INCLUSAO, DATA_ALTERACAO, NOME) VALUES (1, TRUE, NOW(), NOW(), 'CASADO(A)');
INSERT INTO desen.ESTADO_CIVIL (ID, ATIVO, DATA_INCLUSAO, DATA_ALTERACAO, NOME) VALUES (2, TRUE, NOW(), NOW(), 'SOLTEIRO(A)');
INSERT INTO desen.ESTADO_CIVIL (ID, ATIVO, DATA_INCLUSAO, DATA_ALTERACAO, NOME) VALUES (3, TRUE, NOW(), NOW(), 'MARITAL');
INSERT INTO desen.ESTADO_CIVIL (ID, ATIVO, DATA_INCLUSAO, DATA_ALTERACAO, NOME) VALUES (4, TRUE, NOW(), NOW(), 'DESQUITADO(A)/DIVORCIADO(A)');
INSERT INTO desen.ESTADO_CIVIL (ID, ATIVO, DATA_INCLUSAO, DATA_ALTERACAO, NOME) VALUES (5, TRUE, NOW(), NOW(), 'VIUVO(A)');
SELECT setval('desen.estado_civil_id_seq', 6, true);