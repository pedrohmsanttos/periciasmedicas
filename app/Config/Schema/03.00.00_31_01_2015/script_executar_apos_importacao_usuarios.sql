SELECT setval('desen.usuario_id_seq', ((SELECT MAX(ID) FROM DESEN.USUARIO)+1), true);
SELECT setval('desen.vinculo_id_seq', ((SELECT MAX(ID) FROM DESEN.vinculo)+1), true);
SELECT setval('desen.endereco_id_seq', ((SELECT MAX(ID) FROM DESEN.endereco)+1), true);
