CREATE TYPE statusagendamento AS ENUM ('Aguardando Atendimento','Em Atendimento','Atendido', 'Agendado');
CREATE TYPE statusatendimento AS ENUM ('Pendente','Finalizado');

-- PARAMETRO GERAL
CREATE TABLE desen.parametro_geral
(
  id bigserial NOT NULL,
  usuario_versao_id int8,
  tempo_consulta int8,
  maior_invalido_anterior character varying(8000) NOT NULL,
  maior_invalido_partir character varying(8000) NOT NULL,
  aposentadoria_invalidez_integral character varying(8000) NOT NULL,
  aposentadoria_invalidez_proporcional character varying(8000) NOT NULL,
  isencao_imposto_renda_temporaria character varying(8000) NOT NULL,
  isencao_imposto_renda_definitiva character varying(8000) NOT NULL,
  isencao_contribuicao_previdenciaria_temporaria character varying(8000) NOT NULL,
  isencao_contribuicao_previdenciaria_definitiva character varying(8000) NOT NULL,
  ativo boolean NOT NULL,
  data_alteracao timestamp without time zone,
  data_exclusao timestamp without time zone,
  data_inclusao timestamp without time zone,
  primary key (id)
);

alter table desen.parametro_geral 
        add constraint FKCPLTFASW11B0915 
        foreign key (usuario_versao_id) 
        references desen.usuario;

CREATE SEQUENCE desen.protocolo_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE desen.atendimento
(
    id bigserial NOT NULL,
    usuario_id int8,
    agendamento_id int8,
    dependente_id int8,
    atedimento_pai_id int8,
    invalidez_fisica_id int8,
    incap_atos_vida_civil_id int8,
    historico_doenca_atual character varying(8000),
    antecedentes_pessoais_familiares character varying(8000),
    altura character varying,
    peso character varying, 
    temperatura character varying,
    faceis character varying (255),
    estado_nutricao character varying (255),
    mucoses_visiveis character varying (255),
    status_atendimento statusatendimento DEFAULT 'Pendente',
    atitude character varying (255),
    tecido_celular_subcutaneo character varying (255),
    pele_faneros character varying (255),
    defeitos_fisicos character varying (8000),
    tensao_arterial character varying (10),
    pulso character varying (3),
    observacoes character varying (4000),
    observacoes_exigencias character varying (1000),
    observacoes_cid character varying (1000),
    procedimento_exames character varying (1000),
    aparelho_respiratorio character varying (8000),
    aparelho_digestivo character varying (8000),
    aparelho_linfo_hemopoetico character varying (8000),
    aparelho_genitor_urinario character varying (8000),
    aparelho_osteo_articular character varying (8000),
    exame_neuro_psiquiatrico character varying (8000),
    sensibilidade_geral_especial character varying (8000),
    exames_complementares character varying (8000),
    diagnostico character varying (8000),
    aposentado boolean,
    pensionista boolean,
    dependente_maior_invalido boolean,
    patologia_remonta_lc boolean,
    data_parecer date,
    duracao int8,
    data_dependente_invalido date,
    data_dependente_inc_atos_vida date,
    data_insencao_temporaria date,
    isencao_id int8,
    situacao_id int8,
    cid_id int8,
    parecer character varying (8000),
    ativo boolean NOT NULL,
    usuario_versao_id int8,
    data_emissao_laudo timestamp without time zone,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    primary key (id)
);

CREATE TABLE desen.sit_parecer_tec
(
    id bigserial NOT NULL,
    ativo boolean not null,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) not null,
    primary key (id)
);

CREATE TABLE desen.tipo_invalidez_fisica
(
    id bigserial NOT NULL,
    ativo boolean not null,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) not null,
    primary key (id)
);

CREATE TABLE desen.tipo_isencao
(
    id bigserial NOT NULL,
    ativo boolean not null,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) not null,
    primary key (id)
);

CREATE TABLE desen.requisicao_disponivel
(
    id bigserial NOT NULL,
    ativo boolean not null,
    data_alteracao timestamp without time zone,
    data_exclusao timestamp without time zone,
    data_inclusao timestamp without time zone,
    usuario_versao_id int8,
    nome character varying(255) not null,
    primary key (id)
);

CREATE TABLE desen.atendimento_perito
(
    atendimento_id int8 not null,
    usuario_id int8 not null
);

CREATE TABLE desen.atendimento_req_disp
(
    atendimento_id int8 not null,
    req_disp_id int8 not null
);

-- AGENDAMENTO
CREATE TABLE desen.agendamento
(
  id bigserial NOT NULL,
  usuario_versao_id int8,
  tempo_consulta int8,
  usuario_servidor_id int8,
  tipologia_id int8,
  atendimento_vigente_id int8,
  cid_id int8,
  qualidade_id int8,
  unidade_atendimento_id int8,
  acompanhado_cid_id int8,
  data_hora timestamp with time zone,
  chefe_imediato_um_id int8,
  chefe_imediato_um_orgao_origem_id int8,
  chefe_imediato_um_lotacao_id int8,
  chefe_imediato_dois_id int8,
  chefe_imediato_dois_orgao_origem_id int8,
  chefe_imediato_dois_lotacao_id int8,
  chefe_imediato_tres_id int8,
  chefe_imediato_tres_orgao_origem_id int8,
  chefe_imediato_tres_lotacao_id int8,
  protocolo int8 DEFAULT nextval('desen.protocolo_seq'::regclass),
  dia_semana diasemana,
  status_agendamento statusagendamento DEFAULT 'Agendado',
  sala character varying(30),
  nome_acompanhado_sem_abreviacao character varying(255),
  cpf_acompanhado character varying(14),
  outros character varying(100),
  rg_acompanhado character varying(10),
  orgao_expedidor_acompanhado character varying(11),
  nome_mae_acompanhado character varying(255),
  certidao_nascimento_acompanhado character varying(255),
  porque_assistencia_incompativel_cargo character varying(1000),
  porque_voce_unica_pessoa_cuidar character varying(1000),
  data_nascimento_acompanhado date,
  data_a_partir date,
  data_ate date,
  duracao int8,
  processo_administrativo boolean,
  agendamento_encaminhado_sala boolean,
  agendamento_confirmado boolean DEFAULT false,
  readaptacao_definitiva boolean,
  tratamento_fora_municipio boolean,
  assistencia_incompativel_cargo boolean,
  encaixe boolean,
  confirmar_divulgacao boolean,
  ativo boolean NOT NULL,
  data_alteracao timestamp without time zone,
  data_exclusao timestamp without time zone,
  data_inclusao timestamp without time zone,
  primary key (id)
);

CREATE TABLE desen.gerenciamento_sala
(
  id bigserial NOT NULL,
  usuario_versao_id int8,
  usuario_perito_id int8,
  unidade_atendimento_id int8,
  tipologia_id int8,
  sala character varying(30) NOT NULL,
  ativo boolean NOT NULL,
  data_alteracao timestamp without time zone,
  data_exclusao timestamp without time zone,
  data_inclusao timestamp without time zone,
  primary key (id)
);

ALTER TABLE desen.cid add column nome_doenca character varying(255) NOT NULL;

ALTER TABLE desen.orgao_origem add column email varchar(100);

alter table desen.atendimento 
	add constraint FK307867EB6AE19926
	foreign key (cid_id) 
	references desen.cid;

alter table desen.gerenciamento_sala 
	add constraint FK3078FTG46AE19926
	foreign key (unidade_atendimento_id) 
	references desen.unidade_atendimento;

alter table desen.gerenciamento_sala 
	add constraint FK3078SSEB6AE19926
	foreign key (tipologia_id) 
	references desen.tipologia;

alter table desen.atendimento 
	add constraint FK307867EB6AE19915
	foreign key (situacao_id) 
	references desen.sit_parecer_tec;

alter table desen.atendimento 
	add constraint FK307867EB6AE19924
	foreign key (isencao_id) 
	references desen.tipo_isencao;

alter table desen.atendimento 
	add constraint FK307867EE6AE19916
	foreign key (usuario_id) 
	references desen.usuario;

alter table desen.atendimento 
	add constraint FK307867EB6AE19916
	foreign key (usuario_versao_id) 
	references desen.usuario;

alter table desen.sit_parecer_tec 
	add constraint FK307867EB6AE19914
	foreign key (usuario_versao_id) 
	references desen.usuario;

alter table desen.tipo_invalidez_fisica 
	add constraint FK307867EB6AE19915
	foreign key (usuario_versao_id) 
	references desen.usuario;

alter table desen.tipo_isencao 
	add constraint FK307867EB6AE58914
	foreign key (usuario_versao_id) 
	references desen.usuario;

alter table desen.atendimento_perito 
	add constraint FK307867EB6AE19911
	foreign key (atendimento_id) 
	references desen.atendimento;

alter table desen.atendimento_req_disp 
	add constraint FK307867EB6AE19905
	foreign key (atendimento_id) 
	references desen.atendimento;

alter table desen.atendimento_req_disp 
	add constraint FK307867EB6AE19906
	foreign key (req_disp_id) 
	references desen.requisicao_disponivel;

alter table desen.atendimento_perito 
	add constraint FK307867EB6AE19912
	foreign key (usuario_id) 
	references desen.usuario;

alter table desen.agendamento 
        add constraint FKCPLT58t231B0915 
        foreign key (acompanhado_cid_id) 
        references desen.cid;

alter table desen.agendamento 
        add constraint FKCPLT58t251B0846 
        foreign key (qualidade_id) 
        references desen.qualidade;

alter table desen.agendamento 
        add constraint FKCPLT58t251B0856 
        foreign key (atendimento_vigente_id) 
        references desen.atendimento;

alter table desen.agendamento 
        add constraint FKCPLTRTW231B0915 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.gerenciamento_sala 
        add constraint FKCPLKJU6231B0915 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.requisicao_disponivel 
        add constraint FKCPLKGF3231B0915 
        foreign key (usuario_versao_id) 
        references desen.usuario;

alter table desen.gerenciamento_sala 
        add constraint FKCPLKKI8931B0915 
        foreign key (usuario_perito_id) 
        references desen.usuario;

alter table desen.agendamento 
        add constraint FKCPLTGHD531B0915 
        foreign key (usuario_servidor_id) 
        references desen.usuario;

alter table desen.agendamento 
        add constraint FKCPLTGJKH81B0915 
        foreign key (tipologia_id) 
        references desen.tipologia;

alter table desen.agendamento 
        add constraint FKCPLKJU5H81B0915 
        foreign key (cid_id) 
        references desen.cid;

alter table desen.agendamento 
        add constraint FKCOKIUU5H81B0915 
        foreign key (unidade_atendimento_id) 
        references desen.unidade_atendimento;

alter table desen.agendamento 
        add constraint FKCOKFSA2681B0915 
        foreign key (chefe_imediato_um_id) 
        references desen.usuario;

alter table desen.agendamento 
        add constraint FKCASW7UH681B0915 
        foreign key (chefe_imediato_dois_id) 
        references desen.usuario;

alter table desen.agendamento 
        add constraint FKCASWR4H681B0915 
        foreign key (chefe_imediato_tres_id) 
        references desen.usuario;

alter table desen.atendimento 
        add constraint FKCASJJU8681B0915 
        foreign key (agendamento_id) 
        references desen.agendamento;

alter table desen.atendimento 
        add constraint FKCASJJU8681B1602 
        foreign key (atedimento_pai_id) 
        references desen.atendimento;

alter table desen.atendimento 
        add constraint FKCASJJU8681B0952 
        foreign key (dependente_id) 
        references desen.dependente;

alter table desen.atendimento 
	add constraint FK307867EB6AE19950
	foreign key (invalidez_fisica_id) 
	references desen.tipo_invalidez_fisica;

alter table desen.atendimento 
	add constraint FK307867EB6AE19952
	foreign key (incap_atos_vida_civil_id) 
	references desen.tipo_invalidez_fisica;