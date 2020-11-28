CREATE TABLE desen.agen_aten_tip
(
    tipologia_id int8 not null,
    agen_aten_id int8 not null
);

alter table desen.agen_aten_tip 
	add constraint FK3078FTG46AE19926
	foreign key (tipologia_id) 
	references desen.tipologia;

alter table desen.agen_aten_tip 
	add constraint FK3078SSEB6AE19926
	foreign key (agen_aten_id) 
	references desen.agenda_atendimento;

alter table desen.agenda_atendimento DROP COLUMN tipologia_id;

ALTER TABLE desen.dependente ALTER COLUMN nome TYPE character varying(255);
ALTER TABLE desen.dependente ALTER COLUMN nome_pai TYPE character varying(255);
ALTER TABLE desen.dependente ALTER COLUMN nome_mae TYPE character varying(255);

update desen.agenda_atendimento set ativo = false;

CREATE TABLE desen.ger_sala_tip
(
    tipologia_id int8 not null,
    ger_sala_id int8 not null
);

alter table desen.ger_sala_tip 
	add constraint FK3078FTG46AE19926
	foreign key (tipologia_id) 
	references desen.tipologia;

alter table desen.ger_sala_tip 
	add constraint FK3078SSEB6AE19926
	foreign key (ger_sala_id) 
	references desen.gerenciamento_sala;

alter table desen.gerenciamento_sala DROP COLUMN tipologia_id;

update desen.gerenciamento_sala set ativo = false;