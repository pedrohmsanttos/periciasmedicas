CREATE TYPE diasemana AS ENUM ('Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado');

CREATE TABLE desen.cargo
(
  id bigserial NOT NULL,
  ativo boolean NOT NULL,
  data_alteracao timestamp without time zone,
  data_exclusao timestamp without time zone,
  data_inclusao timestamp without time zone,
  usuario_versao_id int8,
  nome character varying(255) NOT NULL,
  sigla character varying(10),
  codigo_cargo_sad int8,
  primary key (id)
);


CREATE TABLE desen.usuario
(
    id bigserial NOT NULL,
    usuario_versao_id int8,
    tipo_usuario_id int8,
    unidade_atendimento_id int8,
    sexo_id int8,
    estado_civil_id int8,
    endereco_id int8,
    empresa_id int8,
    email varchar(100) not null,
    nome character varying(100),
    cpf character varying(14),
    rg character varying(15),
    orgao_expedidor character varying(15),
    numero_registro character varying(15),
    senha character varying(255),
    telefone character varying(12),
    telefone_trabalho character varying(12),
    telefone_celular character varying(12),
    data_nascimento date,
    data_obito date,
    data_admissao_pericia date,
    ativo boolean,
    ativado boolean,
    chefe_perito boolean,
    expirar_senha timestamp without time zone,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    primary key (id)
);

CREATE TABLE desen.perfil
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(50),
    ativado boolean,
    primary key (id)
);

CREATE TABLE desen.sexo
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(100),
    primary key (id)
);

CREATE TABLE desen.endereco
(
    id bigserial NOT NULL,
    estado_id int8,
    municipio_id int8,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    logradouro character varying(400),
    cep character varying(10),
    numero character varying(10),
    complemento character varying(255),
    bairro character varying(255),
    primary key (id)
);

CREATE TABLE desen.estado
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(100),
    sigla character varying(2),
    primary key (id)
);

CREATE TABLE desen.tipologia
(
    id bigserial NOT NULL,
    ativo boolean not null,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) not null,
    legislacao character varying(8000),
    primary key (id)
);

CREATE TABLE desen.agenda_atendimento
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    usuario_id int8,
    unidade_atendimento_id int8,
    tipologia_id int8,
    dia_semana diasemana,
    hora_inicial time,
    hora_final time,
    primary key (id)
);

CREATE TABLE desen.municipio
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    estado_id int8,
    nome character varying(100),
    ativado boolean,
    primary key (id)
);

CREATE TABLE desen.estado_civil
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(100),
    primary key (id)
);

CREATE TABLE desen.empresa
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    endereco_id int8,
    nome character varying(255) NOT NULL,
    nome_responsavel character varying(255),
    telefone_responsavel character varying(11),
    cnpj character varying(14),
    ativado boolean,
    primary key (id)
);

CREATE TABLE desen.unidade_atendimento
(
    id bigserial NOT NULL,
    usuario_versao_id int8,
    endereco_id int8,
    nome character varying(255),
    cnpj character varying(18),
    responsavel_id int8,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    ativo boolean,
    primary key (id)
);

CREATE TABLE desen.tipo_usuario
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(100),
    primary key (id)
);

CREATE TABLE desen.vinculo
(
    id bigserial NOT NULL,
    orgao_origem_id int8,
    cargo_id int8,
    usuario_versao_id int8,
    usuario_id int8,
    nome character varying(100),
    matricula character varying(10),
    data_admissao_servidor date,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    ativo boolean,
    primary key (id)
);

CREATE TABLE desen.lotacao
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    endereco_id int8,
    orgao_origem_id int8,
    nome character varying(255),
    telefone character varying(15),
    primary key (id)
);

CREATE TABLE desen.especialidade
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255),
    primary key (id)
);

CREATE TABLE desen.qualidade
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255),
    primary key (id)
);

CREATE TABLE desen.dependente
(
    id bigserial NOT NULL,
    usuario_versao_id int8,
    qualidade_id int8,
    endereco_id int8,
    usuario_id int8,
    data_nascimento date,
    endereco_servidor boolean,
    nome character varying(100),
    nome_pai character varying(100),
    nome_mae character varying(100),
    cpf character varying(11),
    rg character varying(8),
    inscricao_funape character varying(8),
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    ativo boolean,
    primary key (id)
);

CREATE TABLE desen.cid
(
    id bigserial NOT NULL,
    ativo boolean not null,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) not null,
    descricao character varying(8000),
    primary key (id)
);

create table desen.unidade_atendimento_municipio (
    unidade_atendimento_id int8 not null,
    municipio_id int8 not null
);

create table desen.unidade_atendimento_cid (
    unidade_atendimento_id int8 not null,
    cid_id int8 not null
);

create table desen.vinculo_funcao (
    vinculo_id int8 not null,
    funcao_id int8 not null
);

create table desen.vinculo_lotacao (
    vinculo_id int8 not null,
    lotacao_id int8 not null
);

create table desen.usuario_tipologia (
    usuario_id int8 not null,
    tipologia_id int8 not null
);

create table desen.cid_especialidade (
    cid_id int8 not null,
    especialidade_id int8 not null
);

create table desen.funcionalidade (
    id bigserial not null,
    ativo bool,
    data_alteracao timestamp,
    data_exclusao timestamp,
    data_inclusao timestamp,
    nome varchar(255) not null,
    id_tipo_funcionalidade varchar(1) not null,
    usuario_versao_id int8,
    id_funcionalidade_pai int8,
    primary key (id),
    unique (nome, data_exclusao)
);

CREATE TABLE desen.funcao
(
    id bigserial NOT NULL,
    ativo boolean NOT NULL,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) NOT NULL,
    sigla character varying(10),
    codigo_funcao_sad int8,
    primary key (id)
);

CREATE TABLE desen.orgao_origem
(
    id bigserial NOT NULL,
    ativo boolean,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    orgao_origem character varying(255),
    sigla character varying(10),
    cnpj character varying(14),
    primary key (id)
);

 create table desen.usuario_perfil (
    perfil_id int8 not null,
    usuario_id int8 not null
);

create table desen.perfil_funcionalidade (
    perfil_id int8 not null,
    funcionalidade_id int8 not null
);

alter table desen.perfil 
        add constraint FKC4E369CCC91B0761 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.perfil_funcionalidade 
	add constraint FK307867EB6AE19955
	foreign key (perfil_id) 
	references desen.perfil;

alter table desen.perfil_funcionalidade 
	add constraint FK307867EBDAC89954
	foreign key (funcionalidade_id) 
	references desen.funcionalidade;

alter table desen.usuario_perfil 
	add constraint FK307867EB6AE18595 
	foreign key (perfil_id) 
	references desen.perfil;

alter table desen.usuario_perfil 
	add constraint FK307867EBDAC8A715 
	foreign key (usuario_id) 
	references desen.usuario;

alter table desen.funcionalidade 
        add constraint FKCB11B5189E1D9930
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.funcionalidade 
	add constraint FKCB11B5184DB34CEA 
	foreign key (id_funcionalidade_pai) 
	references desen.funcionalidade;

alter table desen.usuario 
        add constraint FKF814F32EC91B0761 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.cargo 
        add constraint FKC4E369CCC91B1110 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.orgao_origem 
        add constraint FKC4E369CCC91B1110 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.usuario 
        add constraint FKC4E369CCC91B1034 
        foreign key (tipo_usuario_id) 
        references desen.tipo_usuario;

alter table desen.usuario 
        add constraint FKC4E369CCC91B1045 
        foreign key (unidade_atendimento_id) 
        references desen.unidade_atendimento;

alter table desen.usuario 
        add constraint FKC4E369CCC91B1134 
        foreign key (sexo_id) 
        references desen.sexo;

alter table desen.usuario 
        add constraint FKC4E369CCC91B1234 
        foreign key (estado_civil_id) 
        references desen.estado_civil;

alter table desen.vinculo 
        add constraint FKC4E369KIUY1B1256 
        foreign key (orgao_origem_id) 
        references desen.orgao_origem;

alter table desen.vinculo 
        add constraint FKC4EKJI2CC91B1056 
        foreign key (cargo_id) 
        references desen.cargo;

alter table desen.vinculo 
        add constraint FKC4E3KJU8C91B1056 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.vinculo_funcao 
        add constraint FKC4E369CC541B1256 
        foreign key (vinculo_id) 
        references desen.vinculo;

alter table desen.vinculo_funcao 
        add constraint FKC4E369CC8Q1B1056 
        foreign key (funcao_id) 
        references desen.funcao;

alter table desen.vinculo_lotacao 
        add constraint FKC4ETR9CC541B1256 
        foreign key (vinculo_id) 
        references desen.vinculo;

alter table desen.vinculo_lotacao 
        add constraint FKC4ETY9CCUI7B1056 
        foreign key (lotacao_id) 
        references desen.lotacao;

alter table desen.endereco 
        add constraint FKC4ETYPCC8Q1B1056 
        foreign key (estado_id) 
        references desen.estado;

alter table desen.municipio 
        add constraint FKC4ETYPCC8Q1B1018 
        foreign key (estado_id) 
        references desen.estado;

alter table desen.endereco 
        add constraint FKC4ETY9HG6Q1B1056 
        foreign key (municipio_id) 
        references desen.municipio;

alter table desen.usuario 
        add constraint FKC4ETYOO14Q1B1056 
        foreign key (endereco_id) 
        references desen.endereco;

alter table desen.usuario 
        add constraint FKC4ETYKO04Q1B1056 
        foreign key (empresa_id) 
        references desen.empresa;

alter table desen.usuario_tipologia 
        add constraint FKC4ETY9TY4Q1B1056 
        foreign key (usuario_id) 
        references desen.usuario;

alter table desen.usuario_tipologia 
        add constraint FKC4ETJU764Q1B1056 
        foreign key (tipologia_id) 
        references desen.tipologia;

alter table desen.agenda_atendimento 
        add constraint FKC4UY55TY4Q1B1056 
        foreign key (tipologia_id) 
        references desen.tipologia;

alter table desen.dependente 
        add constraint FKC4UY5IO87Q1B1056 
        foreign key (qualidade_id) 
        references desen.qualidade;

alter table desen.dependente 
        add constraint FKC4UYKI767Q1B1056 
        foreign key (endereco_id) 
        references desen.endereco;

alter table desen.agenda_atendimento 
        add constraint FKC4UYJKJ881B1056 
        foreign key (unidade_atendimento_id) 
        references desen.unidade_atendimento;

alter table desen.especialidade 
        add constraint FKC4UYJKJ881B1056 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.empresa 
        add constraint FKC4UYJKJ881B0914 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.empresa 
        add constraint FKC4UYJKJ881B0915 
        foreign key (endereco_id) 
        references desen.endereco;

alter table desen.cid_especialidade 
        add constraint FKC4UYUOY781B0914 
        foreign key (cid_id) 
        references desen.cid;

alter table desen.cid_especialidade 
        add constraint FKC4ULPO0881B0915 
        foreign key (especialidade_id) 
        references desen.especialidade;

alter table desen.lotacao 
        add constraint FKC4KLJ0Y781B0914 
        foreign key (endereco_id) 
        references desen.endereco;

alter table desen.lotacao 
        add constraint FKCPPOIO0881B0915 
        foreign key (orgao_origem_id) 
        references desen.orgao_origem;

alter table desen.unidade_atendimento 
        add constraint FKCPPOKKJ981B0915 
        foreign key (endereco_id) 
        references desen.endereco;

alter table desen.unidade_atendimento_municipio 
        add constraint FKCPPOUY8781B0915 
        foreign key (unidade_atendimento_id) 
        references desen.unidade_atendimento;

alter table desen.unidade_atendimento_municipio 
        add constraint FKCPPLJY1981B0915 
        foreign key (municipio_id) 
        references desen.municipio;

alter table desen.unidade_atendimento_cid 
        add constraint FKCPPPJUA781B0915 
        foreign key (unidade_atendimento_id) 
        references desen.unidade_atendimento;

alter table desen.unidade_atendimento_cid 
        add constraint FKCPPLLMKJ81B0915 
        foreign key (cid_id) 
        references desen.cid;

alter table desen.vinculo 
        add constraint FKCPLKSRRT11B0915 
        foreign key (usuario_id) 
        references desen.usuario;

alter table desen.dependente 
        add constraint FKCPOI91RT11B0915 
        foreign key (usuario_id) 
        references desen.usuario;

alter table desen.unidade_atendimento 
        add constraint FKCPOI91RT11B0904 
        foreign key (responsavel_id) 
        references desen.usuario;

alter table desen.agenda_atendimento 
        add constraint FKCPLTWQ1T11B0915 
        foreign key (usuario_id) 
        references desen.usuario;