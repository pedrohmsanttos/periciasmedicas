-- Caso nao esteja com a linguagem instalada no banco, executar o comando abaixo;
--CREATE  LANGUAGE  plpgsql;

CREATE OR REPLACE FUNCTION ft_cargaFuncionalidades ()
    RETURNS integer 
    AS $$   
	DECLARE codigo_perfil integer;
    DECLARE codigo_funcionalidade integer;        
    BEGIN		
	
	SELECT id INTO codigo_perfil FROM desen.perfil WHERE nome = 'Administrador';		
	
--Itera nas funcionalidades do sistema e associa ao perfil
	FOR codigo_funcionalidade IN SELECT id from desen.funcionalidade		
	LOOP	
	
	INSERT INTO desen.perfil_funcionalidade(PERFIL_ID,funcionalidade_id) VALUES(codigo_perfil,codigo_funcionalidade);
	    
	END LOOP;
	
	RETURN 0;
    END;
$$ LANGUAGE plpgsql;
-- Chamando a funcao acima
SELECT ft_cargaFuncionalidades();
-- Excluindo a funcao
DROP FUNCTION ft_cargaFuncionalidades();