<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoAportesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TRIGGER total_aportes AFTER INSERT ON apo__aportes
                            FOR EACH ROW BEGIN
                            SET @maxobligatorios=0;
                            SET @maxjubilacion=0;
                            SET @reintegro=0;
                            SET @fechaincorp='';
                            SET @fechaaporte='';
                            
                            
                            INSERT INTO apo__idsaportes (idaporte,numpapeleta) VALUES(new.idaporte,new.numpapeleta);

                            SELECT fechaincorporacion FROM socios WHERE numpapeleta=new.numpapeleta INTO @fechaincorp;
                            IF(new.fechaaporte<@fechaincorp) THEN
                            	UPDATE socios SET fechaincorporacion=new.fechaaporte WHERE numpapeleta=new.numpapeleta;
                            END IF;
                            
                            
                            END");
                            /* SELECT valor FROM configs WHERE codigo='AO' INTO @maxobligatorios;
                            SELECT valor FROM configs WHERE codigo='AJ' INTO @maxjubilacion;
                            SELECT idtipoaporte FROM apo__tipoaportes WHERE descripcion='Reintegro' INTO @reintegro;
                            
                                IF (SELECT COUNT(numpapeleta) FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)=0 THEN 
                                    INSERT INTO apo__total_aportes(numpapeleta,cantobligados,totalobligados,created_at,updated_at,fecha_primeraporte_obligados,fecha_ultimoaporte_obligados)
                                                            VALUES(new.numpapeleta,1,new.aporte,new.created_at,new.updated_at,new.fechaaporte,new.fechaaporte);
                                ELSE
                                    IF(SELECT obligatorios FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)=0 THEN 
                                        IF(SELECT idtipoaporte FROM apo__aportes WHERE idaporte=new.idaporte)=@reintegro THEN
                                            UPDATE apo__total_aportes SET totalobligados=totalobligados+new.aporte,updated_at=new.created_at WHERE numpapeleta=new.numpapeleta;            	
                                        ELSE
                                            UPDATE apo__total_aportes SET cantobligados=cantobligados+1,totalobligados=totalobligados+new.aporte,updated_at=new.created_at WHERE numpapeleta=new.numpapeleta;
                                            
                                         -> IF new.fechaaporte<(SELECT fecha_primeraporte_obligados FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta) THEN
                                            	UPDATE apo__total_aportes SET fecha_primeraporte_obligados=new.fechaaporte where numpapeleta=new.numpapeleta;
                                            ELSE
                                            	IF (new.fechaaporte>(SELECT fecha_ultimoaporte_obligados FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)) THEN
                                                	UPDATE apo__total_aportes SET fecha_ultimoaporte_obligados=new.fechaaporte where numpapeleta=new.numpapeleta;
                                            	END IF;
                                            END IF;
                                            
                                        ->  IF(SELECT cantobligados FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)=@maxobligatorios THEN
                                            	UPDATE apo__total_aportes SET obligatorios=1,updated_at=new.created_at WHERE numpapeleta=new.numpapeleta;
                                            	UPDATE apo__idsaportes SET tipodevolucion=1 where numpapeleta=new.numpapeleta AND tipodevolucion=0;
                                        	END IF;
                                        END IF;
                                    ELSE
                                    ->    IF(SELECT jubilados FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)=0 THEN 
                                            IF(SELECT idtipoaporte FROM apo__aportes WHERE idaporte=new.idaporte)=@reintegro THEN
                                                UPDATE apo__total_aportes SET totaljubilacion=totaljubilacion+new.aporte,updated_at=new.created_at WHERE numpapeleta=new.numpapeleta;            	
                                            ELSE
                                                UPDATE apo__total_aportes SET cantjubilacion=cantjubilacion+1,totaljubilacion=totaljubilacion+new.aporte,updated_at=new.created_at WHERE numpapeleta=new.numpapeleta;
                                                IF (new.fechaaporte<(SELECT fecha_primeraporte_jubilacion FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)) THEN
                                            		UPDATE apo__total_aportes SET fecha_primeraporte_jubilacion=new.fechaaporte where numpapeleta=new.numpapeleta;
                                            	ELSE
                                            		IF new.fechaaporte>(SELECT fecha_ultimoaporte_jubilacion FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta) THEN
                                                		UPDATE apo__total_aportes SET fecha_ultimoaporte_jubilacion=new.fechaaporte where numpapeleta=new.numpapeleta;
                                            		END IF;
                                                END IF;
                                            END IF;
                                            IF(SELECT cantjubilacion FROM apo__total_aportes WHERE numpapeleta=new.numpapeleta)=@maxjubilacion THEN
                                                UPDATE apo__total_aportes SET jubilados=1,updated_at=new.created_at where numpapeleta=new.numpapeleta;
                                                UPDATE apo__idsaportes SET tipodevolucion=2 where numpapeleta=new.numpapeleta AND tipodevolucion=0;
                                            END IF;
                                        ELSE
                                            INSERT INTO apo__excedenteaportes (idaporte,numpapeleta,fechaaporte)
                                                                    VALUES(new.idaporte,new.numpapeleta,new.fechaaporte);
                                            UPDATE apo__idsaportes SET tipodevolucion=3 where idaporte=new.idaporte;
                                            -- DELETE FROM apo__aportes WHERE idaporte=new.idaporte;
                                        END IF;
                                    END IF;
                                END IF; */

        DB::unprepared("CREATE TRIGGER `cargar_aporte` AFTER INSERT ON `apo__carga_asciis`
        FOR EACH ROW BEGIN
       
       SET @olddestino=0;
      SET @newdestino=0;
      SET @idgradosocio=0;
      SET @idgradogrados=0;
      SET @oldespecialidad=0;
      SET @newespecialidad=0;
      
      SET @apaterno='';
      SET @amaterno='';
      SET @nombres='';
      SET @verificar='';
      
      SELECT DISTINCT(SUBSTRING_INDEX(new.nombres,' ',-2)) FROM apo__carga_asciis where numpapeleta=new.numpapeleta INTO @nombres;
      SELECT DISTINCT(SUBSTRING_INDEX(new.nombres,' ',1)) FROM apo__carga_asciis where numpapeleta=new.numpapeleta INTO @apaterno;
      SELECT DISTINCT(SUBSTRING_INDEX(SUBSTRING_INDEX(new.nombres,' ', 2),' ',-1)) FROM apo__carga_asciis where numpapeleta=new.numpapeleta into @amaterno;
      SELECT DISTINCT(SUBSTRING_INDEX(@nombres,' ',1)) INTO @verificar;
      
      IF @verificar=@amaterno THEN
            SELECT SUBSTRING_INDEX(@nombres,' ',-1) INTO @nombres;
      END IF;
                    INSERT INTO apo__aportes (numpapeleta, idtipoaporte,aporte,totalganado,fechaaporte,idperfilcuentamaestro,idlote,obsaporte,created_at,updated_at)
                    VALUES(new.numpapeleta,new.idtipoaporte,new.aporte,new.aporte*100/4,new.fechaaporte,new.idperfilcuentamaestro,new.idlote,new.observaciones,new.created_at,new.updated_at);
                    
                    IF (SELECT COUNT(coddestino) FROM par__destinos WHERE coddestino=TRIM(new.coddestino) and idfuerza=TRIM(new.codfuerza)) = 0 THEN BEGIN
                            INSERT INTO par__destinos (idfuerza,coddestino,nomdestino)
                            VALUES(TRIM(new.codfuerza),TRIM(new.coddestino),TRIM(new.destino));
                    END;
                    END IF;
                    
                    IF (new.especialidad<>'') THEN BEGIN
                        IF (SELECT COUNT(idespecialidad)FROM par_especialidades WHERE abrvesp=new.especialidad AND idfuerza=new.codfuerza)=0 THEN 
                                INSERT INTO par_especialidades (idfuerza,nomespecialidad,abrvesp,activo)
                                VALUES(new.codfuerza,new.especialidad,new.especialidad,1);
                        END IF;
                    END;
                    END IF;

                    IF (SELECT COUNT(numpapeleta) as numero FROM socios WHERE numpapeleta=new.numpapeleta) = 0 THEN BEGIN
                        INSERT INTO socios(numpapeleta,
                                            nombre,
                                            apaterno,
                                            amaterno,
                                            ci,
                                            iddepartamentoexpedido,
                                            idgrado,
                                            idfuerza,
                                            iddestino,
                                            idescalafon,
                                            idespecialidad,
                                            idestadocivil,
                                            idtiposocio,
                                            idusuarioregistro,
                                            idusuariomodificacion,
                                            fechaincorporacion,
                                            created_at)
                            VALUES(new.numpapeleta,
                                    @nombres,
                                   	@apaterno,
                                    @amaterno,
                                    (SELECT DISTINCT(SUBSTRING_INDEX(new.cisocio,' ',1)) FROM apo__carga_asciis where numpapeleta=new.numpapeleta),
                                    IFNULL((SELECT iddepartamento from par_departamentos where abrvdep=SUBSTRING_INDEX(new.cisocio,' ',-1) OR abrvdep2=SUBSTRING_INDEX(new.cisocio,' ',-1) OR abrvdep2=SUBSTRING_INDEX(new.cisocio,' ',-1)),1),
                                    (SELECT idgrado FROM par_grados WHERE nomgrado LIKE new.grado),
                                    new.codfuerza,
                                    IFNULL((SELECT iddestino FROM par__destinos where coddestino=new.coddestino),1),
                                    1,
                                    IFNULL((SELECT idespecialidad FROM par_especialidades where abrvesp=new.especialidad AND idfuerza=new.codfuerza),1),
                                    1,
                                    1,
                                    1,
                                    1,
                                    new.fechaaporte,
                                    new.created_at );
                        END;
                    ELSE
                        SELECT iddestino FROM socios WHERE numpapeleta=new.numpapeleta INTO @olddestino;
                        SELECT iddestino FROM par__destinos WHERE coddestino LIKE new.coddestino and idfuerza=new.codfuerza INTO @newdestino;
                        SELECT idgrado FROM socios WHERE numpapeleta=new.numpapeleta INTO @idgradosocio;
                        SELECT idgrado FROM par_grados WHERE nomgrado LIKE new.grado INTO @idgradogrados;
                        SELECT idespecialidad FROM socios WHERE numpapeleta=new.numpapeleta INTO @oldespecialidad;
                        SELECT idespecialidad FROM par_especialidades WHERE abrvesp=new.especialidad and idfuerza=new.codfuerza INTO @newespecialidad;
                        
                        IF(@newespecialidad<>'') THEN
                            IF @oldespecialidad<>@newespecialidad THEN
                                    UPDATE socios SET idespecialidad=@newespecialidad WHERE numpapeleta=new.numpapeleta;
                            END IF;
                        END IF;
                        IF @newdestino<>@olddestino THEN
                            UPDATE socios SET iddestino=@newdestino WHERE numpapeleta=new.numpapeleta;
                        END IF;
                        IF @idgradogrados<>@idgradosocio THEN
                            UPDATE socios SET idgrado=@idgradogrados WHERE numpapeleta=new.numpapeleta;
                        END IF;
                        UPDATE socios SET idestaportes=1 WHERE numpapeleta=new.numpapeleta AND idestaportes=2;
                        
                    END IF;
                END");
       DB::unprepared("CREATE TRIGGER actualizar_aporte AFTER INSERT ON apo__debitos
                            FOR EACH ROW BEGIN
                                -- DECLARE aporte decimal DEFAULT 0;
                            -- DECLARE debitos decimal DEFAULT 0;
                            set @aporte=0;
                            set @debitos=0;
                            set @obligat=0;
                            set @papeleta='';
                            -- set @fecharendimiento=0;
                            
                            SELECT numpapeleta FROM apo__aportes a JOIN apo__debitos b
                                                                    ON a.idaporte=b.idaporte
                                                                        WHERE b.idaporte=new.idaporte LIMIT 1 INTO @papeleta;
                            SELECT aporte FROM apo__aportes WHERE idaporte=new.idaporte INTO @aporte;
                            SELECT sum(monto) as total FROM apo__debitos WHERE idaporte=new.idaporte INTO @debitos;
                            SELECT obligatorios FROM apo__total_aportes WHERE numpapeleta=@papeleta INTO @obligat;
                            -- SELECT fecharendimiento FROM apo__aportes where idaporte=new.idaporte INTO @fecharendimiento;
                            
                            IF (@aporte-@debitos)>0 THEN
                                IF @obligat = 0 THEN
                                    UPDATE apo__total_aportes SET totalobligados=totalobligados-new.monto where numpapeleta=@papeleta;
                                ELSE
                                    UPDATE apo__total_aportes SET totaljubilacion=totaljubilacion-new.monto WHERE numpapeleta=@papeleta;
                                END IF;
                            ELSE
                                UPDATE apo__aportes SET activo=0 WHERE idaporte=new.idaporte;
                                IF @obligat = 0 THEN
                                    UPDATE apo__total_aportes SET totalobligados=totalobligados-new.monto, cantobligados=cantobligados-1 
                                            WHERE numpapeleta=@papeleta;
                                ELSE
                                    UPDATE apo__total_aportes SET totaljubilacion=totaljubilacion-new.monto, cantjubilacion=cantjubilacion-1
                                            WHERE numpapeleta=@papeleta;
                                END IF;
                            END IF;
                        END ");
        
         DB::unprepared("CREATE TRIGGER actualizar_observados AFTER INSERT ON apo__observados
            FOR EACH ROW BEGIN
        SET @montoaporte=0;
        SET @maxfechaaporte=0;
        
        select MAX(fechaaporte) FROM apo__aportes where numpapeleta=new.numpapeleta INTO @maxfechaaporte;
        SELECT aporte FROM apo__aportes WHERE numpapeleta=new.numpapeleta AND fechaaporte=@maxfechaaporte INTO @montoaporte;
        
        IF(SELECT obligatorios FROM apo__total_aportes where numpapeleta=new.numpapeleta)=0 THEN
            IF(SELECT count(numpapeleta) FROM apo__aportefaltantes WHERE numpapeleta=new.numpapeleta)=0 THEN
                INSERT INTO apo__aportefaltantes (numpapeleta,faltantes_obligatorio,aporte_ultimomes_obligatorio,suma_aportes_obligatorio) VALUES(new.numpapeleta,1,@montoaporte,@montoaporte);
            ELSE
                UPDATE apo__aportefaltantes SET faltantes_obligatorio=faltantes_obligatorio+1,aporte_ultimomes_obligatorio=@montoaporte,suma_aportes_obligatorio=suma_aportes_obligatorio+@montoaporte where numpapeleta=new.numpapeleta;
            END IF;
        ELSE
            IF(SELECT jubilados FROM apo__total_aportes where numpapeleta=new.numpapeleta)=0 THEN
                IF(SELECT count(numpapeleta) FROM apo__aportefaltantes WHERE numpapeleta=new.numpapeleta)=0 THEN
                    INSERT INTO apo__aportefaltantes (numpapeleta,faltantes_jubilacion,aporte_ultimomes_jubilacion,suma_aportes_jubilacion) VALUES(new.numpapeleta,1,@montoaporte,@montoaporte);
                ELSE
                    UPDATE apo__aportefaltantes SET faltantes_jubilacion=faltantes_jubilacion+1,aporte_ultimomes_jubilacion=@montoaporte,suma_aportes_jubilacion=suma_aportes_jubilacion+@montoaporte where numpapeleta=new.numpapeleta;
                END IF;
            END IF;
        END IF;
        END"); 


       /* DB::unprepared("CREATE TRIGGER socios_observados AFTER INSERT ON apo__observados
                            FOR EACH ROW BEGIN
                            UPDATE socios SET idestaportes = 2 
                            WHERE numpapeleta=new.numpapeleta;
                            END");*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS total_aportes');
        DB::statement('DROP TRIGGER IF EXISTS cargar_aporte');
        DB::statement('DROP TRIGGER IF EXISTS actualizar_aporte');
        DB::statement('DROP TRIGGER IF EXISTS socios_observados');
    }
}
