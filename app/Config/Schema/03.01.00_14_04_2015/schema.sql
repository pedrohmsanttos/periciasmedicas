CREATE TYPE modoatendimento AS ENUM ('Inicial','Prorrogação');
ALTER TABLE desen.atendimento ADD COLUMN modo modoatendimento;   
ALTER TABLE desen.atendimento ADD COLUMN data_limite_exigencia date;   