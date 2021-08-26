<?php
 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Procedures extends Migration
{
    public function up()
    {     
        /* funcion que obtiene la cantidad de meses a tomar para la evaluacion del historial de pagos */
            $count_historial = "CREATE OR REPLACE  FUNCTION count_historial (valuef int) 
                RETURNS int
                DETERMINISTIC
                BEGIN 
                DECLARE dist int default(0); 
                SET dist =(SELECT COUNT(*)  FROM par__productos__criterios pc, par__productos__parametros pp  WHERE pc.idprodparam=pp.idprodparam and pp.idfactor=valuef and pc.valornumerico BETWEEN 1 AND 59);
                RETURN dist;
                END "; 
            DB::unprepared($count_historial);

            /* funcion que obtiene la cantidad de veces que un socio fue garante */
            $count_historial_garante = "CREATE OR REPLACE FUNCTION valida_historial_garante (valuef int) 
            RETURNS int
            DETERMINISTIC
            BEGIN   
            RETURN(SELECT count(*) FROM  par__prestamos__garantes pg,par__prestamos p where pg.idprestamo=p.idprestamo and p.idestado IN (1, 2, 3) and pg.activo=1  and pg.idsocio=valuef); 
            END"; 
            DB::unprepared($count_historial_garante);


             /* funcion que inserta la fecha*/
            $setfecha = "CREATE OR REPLACE  PROCEDURE insertfecha(fecha text)
           BEGIN
            UPDATE par__fecha__sistemas SET activo = 0; 
            INSERT INTO par__fecha__sistemas(fechaSistema)values(fecha);
            END"; 
            DB::unprepared($setfecha);


            
              


            /* funcion que obtiene los dias de cada mes */
            $getfechas = "CREATE OR REPLACE  FUNCTION getfechas () 
            RETURNS TEXT
            DETERMINISTIC
            BEGIN  
              DECLARE puntero int default(0);
              DECLARE fecha text;
              SET fecha = (SELECT  fechaSistema  FROM par__fecha__sistemas WHERE activo=1);
              SET @valor = CONCAT('[\"',(SELECT LAST_DAY(fecha)),'\"'); 
            WHILE puntero<=420 DO
               SET puntero=puntero+1;
               SET @mes=(SELECT LAST_DAY(fecha + INTERVAL puntero MONTH)); 
               SET @valor=CONCAT(@valor,',\"',@mes,'\"');
               
            END WHILE;
               SET @valor = CONCAT(@valor,']'); 
              RETURN @valor;
            END";
             DB::unprepared($getfechas);

             /* funcion que obtiene la fecha actual del sistema */
             $getfecha="CREATE OR REPLACE FUNCTION getfecha() 
                    RETURNS TEXT
                    DETERMINISTIC
                    BEGIN 
                    
                    SET @valor = (SELECT  fechaSistema  FROM par__fecha__sistemas WHERE activo=1); 
                    SET @valor2 = (SELECT DATE_FORMAT(NOW( ), \"%H:%i:%S\" )); 
                    RETURN CONCAT(@valor,' ',@valor2);
                    END";
            DB::unprepared($getfecha);
          
            DB::unprepared("CREATE OR REPLACE FUNCTION getfecha2() 
            RETURNS TEXT
            DETERMINISTIC
            BEGIN 
            SET @valor = (SELECT  fechaSistema  FROM par__fecha__sistemas WHERE activo=1);  
            RETURN @valor;
            END");

            // funcion para obtener el numero papeleta en base al idsocio
            $getpapeleta = "CREATE OR REPLACE FUNCTION getpapeleta (entrada int) RETURNS VARCHAR(20)
                            BEGIN
                            set @valor = (SELECT numpapeleta FROM socios WHERE idsocio=entrada); 
                            RETURN @valor;
                            END";
            DB::unprepared($getpapeleta);

              /* funcion que obtiene el valor porcentual del monto solicitado */
              $valida_monto = "CREATE OR REPLACE  FUNCTION valida_monto (factor int,monto float,cuota float) 
              RETURNS text
              DETERMINISTIC
                BEGIN   
                DECLARE outdata float default(0);  
                set @valor=(SELECT pc.valorporcentual FROM par__productos__criterios pc, par__productos__parametros pp  WHERE pc.idprodparam=pp.idprodparam and pp.idf=3 and pp.idfactor=factor);
                set @final=(SELECT valorparametro  FROM par__productos__parametros WHERE idf=3 and idfactor=factor);
                set @dataout=0;
                IF(@valor>0 and monto>0)THEN
                set @dataout =(monto*@valor)/100;  

                    IF ABS(cuota)<=@dataout THEN
                        SET outdata=@final;
                    ELSE
                        SET @aux=cuota-@dataout;
                        SET outdata=0;
                    END IF; 

                END IF;       
                /* data= valor obtenido, value= cuanto es lo que deberia tener */
                   /*m= liquido pagable computable , d= maximo de cuota , c=cuota ingresada, por= porcentaje que aplica al liquido pagable*/
              RETURN concat('{\"data\":',outdata,',\"value\":',@final,',\"html\":{\"m\":',monto,',\"d\":',@dataout,',\"c\":',cuota,',\"por\":',@valor,'}}');
              END"; 
             DB::unprepared($valida_monto);


              $getidsmora = "CREATE OR REPLACE PROCEDURE getidsprestamosMora (INOUT listaids text, in id int)
              BEGIN
               
                DECLARE v_finished INTEGER DEFAULT 0;
                DECLARE id_prestamos_in int;
                DECLARE nombrepres varchar(191);

                DEClARE cursor_prestmos CURSOR FOR SELECT idprestamo,no_prestamo FROM par__prestamos WHERE idestado=3 and idsocio=id;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;

                    OPEN cursor_prestmos; 
                            puntero: LOOP 
                                    FETCH cursor_prestmos INTO id_prestamos_in,nombrepres;
                            
                            IF v_finished = 1 THEN 
                            LEAVE puntero;
                            END IF; 
                            
                            set @fechaini=(SELECT MIN(fp) FROM par__prestamos__plans WHERE idprestamo = id_prestamos_in and idestado BETWEEN 0 and 2 and idtransaccionC is NULL  ORDER by pe);
                            set @contador=(SELECT count(*) FROM par__prestamos__plans WHERE idprestamo = id_prestamos_in and idestado BETWEEN 0 and 2 and idtransaccionC is NULL and fp BETWEEN @fechaini and getfecha()  ORDER by pe);
                                    
                        if(CHAR_LENGTH(listaids)>0) then
                            SET listaids = CONCAT(listaids,',',nombrepres,'|',@contador);
                        else
                            SET listaids = CONCAT(nombrepres,'|',@contador);
                        end if;
                            
                            END LOOP puntero; 
                    CLOSE cursor_prestmos;
               
              END"; 
             DB::unprepared($getidsmora);

/* procedimiento que realiza el refinanciamiento*/
             DB::unprepared("CREATE OR REPLACE PROCEDURE refinanciar_prestamo1 (INOUT value text,in idsocio int,in producto int,in userid int,in prestamonew int)
             BEGIN
             DECLARE v_finished INTEGER DEFAULT 0;
                DECLARE sumout float DEFAULT 0;
                DECLARE idprestamoin int;
                DECLARE tasa_in float;

                DEClARE cursor_prestmos CURSOR FOR select pre.idprestamo,pro.tasa from par__prestamos pre,par__productos pro,par__monedas mo 
                                    where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pro.idproducto=producto  and pre.idejecucion!=2 and pre.idejecucion!=6  
                                    and pre.idsocio=idsocio and pre.idestado between 2 and 3;
                
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;

                    OPEN cursor_prestmos; 
                            puntero: LOOP 
                                    FETCH cursor_prestmos INTO idprestamoin,tasa_in;
                            
                            IF v_finished = 1 THEN 
                            LEAVE puntero;
                            END IF; 
                           			
                         
	                        set @tem=0;
	                       
	                        if tasa_in>0 then
	                        set @tem =(tasa_in/12)/100;
	                        end if;
                         
		                        SET @dias=0;
		                        set @montosolicitado=0;
		                       
		                        set @por_cobrar=(select sum(am) from par__prestamos__plans where idprestamo=idprestamoin and idestado=2);
		                        if @por_cobrar is null then set @por_cobrar=0; end if;
		                        
		                        set @cobrado=(select sum(am) from par__prestamos__plans where idprestamo=idprestamoin and idestado>9);
		                        if @cobrado is null then set @cobrado=0; end if;
		                       
		                        
		                        set @montosolicitado=(SELECT monto FROM par__prestamos  WHERE idprestamo=idprestamoin);
		                        
		                        if(ROUND(@por_cobrar+@cobrado,2)=ROUND(@montosolicitado,2)) then
		                        
		                                set @mesdesembolso=(select month(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
		                                set @diadesembolso=(select day(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
		                                set @tipocambio=(select mo.tipocambio from par__prestamos pre,par__productos pro,par__monedas mo where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pre.idprestamo=idprestamoin);
			                            
		                                SET @fecha = (SELECT  getfecha());
			                            SET @mesactual =(SELECT month(@fecha));
			                            SET @dias =(SELECT DAY(@fecha)); 
			                           
					                            if @mesactual=@mesdesembolso then
					                            SET @dias=(@dias-@diadesembolso)+1;
					                            end if;
			                            
			                            set @interes=((@por_cobrar*@tem*@dias)/30);
			                          
			                            set @indi_sum=(select sum(indi) from par__prestamos__plans where idprestamo=idprestamoin and idestado=2);
			                            if @indi_sum is null then set @indi_sum=0; end if;  
			                            set @cuota=@por_cobrar+@interes+@indi_sum; 
			                            set sumout=sumout+(@cuota*@tipocambio);
			                           
			                             set @manipular=(select idplan from par__prestamos__plans where idprestamo=idprestamoin and idestado=2 ORDER by pe asc limit 1);
			                             set @prestamos_nuevo=(SELECT no_prestamo FROM par__prestamos WHERE idprestamo=prestamonew);
			                            
			                             set @prestamos_tra=concat('R-',prestamonew,'-',idprestamoin);
			                         
			                           SELECT JSON_ARRAY_APPEND(value, '$.prestamos',idprestamoin)into value; 
			                            
			                      /*   UPDATE par__prestamos__plans SET `fp`=getfecha(),`indi`=@indi_sum,`di`=@dias,`am`=@por_cobrar,`in`=@interes,`car`=0,`cut`=@cuota,`ca`=0,`cuota`=@cuota,`fecharegistro`=getfecha(),
			                         `tipo`=3,`idestado`=13,`idtransaccionC`=@prestamos_tra,`numDocC`=@prestamos_nuevo,`glosa`='Liquidación del prestamo - automatico',`fechaCobranza`=getfecha(),
			                         `importe`=@cuota,`idusuario`=userid  WHERE idplan=@manipular and idprestamo=idprestamoin;*/
			                          
			                        INSERT INTO par__prestamos__plans (`idprestamo`,`pe`, `fp`, `di`, `am`, `in`, `indi`, `car`, `cut`, `ca`, `cuota`, `fecharegistro`,`tipo`, `idestado`, `idtransaccionC`, `numDocC`, `glosa`, `fechaCobranza`, `importe`, `idusuario`) 
									 VALUES (idprestamoin,500,getfecha(),@dias,@por_cobrar,@interes,@indi_sum,0,@cuota,0,@cuota,getfecha(),3,13,@prestamos_tra,@prestamos_nuevo,'Liquidación del prestamo - automatico',getfecha(),@cuota,userid);
									 

			                        set @valuecount=(SELECT count(*) FROM par__prestamos__plans where idprestamo=idprestamoin and idestado=10);
			                        if @valuecount=0 then 
			                          		UPDATE par__prestamos SET idejecucion=6,idref=prestamonew, idestado=5,idrefaux=concat(idrefaux,'|',prestamonew) WHERE idprestamo =idprestamoin;
			                           else
			                          		UPDATE par__prestamos SET idejecucion=6,idref=prestamonew,idrefaux=concat(idrefaux,'|',prestamonew) WHERE idprestamo =idprestamoin;
			                        end if;
			                        
			                 UPDATE par__prestamos__plans set `tipo`=0,`idestado`=13 WHERE idestado=2 and idprestamo=idprestamoin;
			                       /*DELETE FROM par__prestamos__plans WHERE idestado=2 and idprestamo=idprestamoin;
			                       DELETE FROM par__prestamos__plan__cargos WHERE idplan=@manipular; */ 
		                            
		                        else
		                            set sumout=-10; 
		                           LEAVE puntero;
		                        end if; 
                            
                            END LOOP puntero; 
                    CLOSE cursor_prestmos;
               
                   select sumout/mo.tipocambio from par__productos pro, par__monedas mo where pro.moneda=mo.idmoneda and pro.idproducto=producto into sumout;
               SELECT JSON_SET(value, '$.total',ROUND(sumout,6))into value;
             END");
 DB::unprepared("CREATE OR REPLACE PROCEDURE refinanciar_prestamo2 (INOUT value text,in idsocio int,in producto int,in userid int,in prestamonew int)
 BEGIN
 DECLARE v_finished INTEGER DEFAULT 0;
                DECLARE sumout float DEFAULT 0;
                DECLARE idprestamoin int;
                DECLARE tasa_in float;

                DEClARE cursor_prestmos CURSOR FOR select pre.idprestamo,pro.tasa from par__prestamos pre,par__productos pro,par__monedas mo 
                                    where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pro.tasa!=0  and pre.idejecucion!=2 and pre.idejecucion!=6 
                                    and pre.idsocio=idsocio and pre.idestado between 2 and 3;
                
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;

                    OPEN cursor_prestmos; 
                            puntero: LOOP 
                                    FETCH cursor_prestmos INTO idprestamoin,tasa_in;
                            
                            IF v_finished = 1 THEN 
                            LEAVE puntero;
                            END IF; 
                           			
                         
	                        set @tem=0;
	                        if tasa_in>0 then
	                        set @tem =(tasa_in/12)/100;
	                        end if;
                         
		                        SET @dias=0;
		                        set @montosolicitado=0;
		                       
		                        set @por_cobrar=(select sum(am) from par__prestamos__plans where idprestamo=idprestamoin and idestado=2);
		                        if @por_cobrar is null then set @por_cobrar=0; end if;
		                        
		                        set @cobrado=(select sum(am) from par__prestamos__plans where idprestamo=idprestamoin and idestado>9);
		                        if @cobrado is null then set @cobrado=0; end if;
		                       
		                        
		                        set @montosolicitado=(SELECT monto FROM par__prestamos  WHERE idprestamo=idprestamoin);
		                        
		                        if(ROUND(@por_cobrar+@cobrado,2)=ROUND(@montosolicitado,2)) then
		                        
		                                set @mesdesembolso=(select month(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
		                                set @diadesembolso=(select day(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
		                                set @tipocambio=(select mo.tipocambio from par__prestamos pre,par__productos pro,par__monedas mo where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pre.idprestamo=idprestamoin);
			                            
		                                SET @fecha = (SELECT  getfecha());
			                            SET @mesactual =(SELECT month(@fecha));
			                            SET @dias =(SELECT DAY(@fecha)); 
			                           
					                            if @mesactual=@mesdesembolso then
					                            SET @dias=(@dias-@diadesembolso)+1;
					                            end if;
			                            
			                            set @interes=((@por_cobrar*@tem*@dias)/30);
			                          
			                            set @indi_sum=(select sum(indi) from par__prestamos__plans where idprestamo=idprestamoin and idestado=2);
			                            if @indi_sum is null then set @indi_sum=0; end if;  
			                            set @cuota=@por_cobrar+@interes+@indi_sum; 
			                            set sumout=sumout+(@cuota*@tipocambio);
			                           
			                             set @manipular=(select idplan from par__prestamos__plans where idprestamo=idprestamoin and idestado=2 ORDER by pe asc limit 1);
			                             set @prestamos_nuevo=(SELECT no_prestamo FROM par__prestamos WHERE idprestamo=prestamonew);
			                            
			                              set @prestamos_tra=concat('R-',prestamonew,'-',idprestamoin);
			                         
			                           SELECT JSON_ARRAY_APPEND(value, '$.prestamos',idprestamoin)into value;  
			                          
			                    /*     UPDATE par__prestamos__plans SET `fp`=getfecha(),`indi`=@indi_sum,`di`=@dias,`am`=@por_cobrar,`in`=@interes,`car`=0,`cut`=@cuota,`ca`=0,`cuota`=@cuota,`fecharegistro`=getfecha(),
			                         `tipo`=3,`idestado`=13,`idtransaccionC`=@prestamos_tra,`numDocC`=@prestamos_nuevo,`glosa`='Liquidación del prestamo - automatico',`fechaCobranza`=getfecha(),
			                         `importe`=@cuota,`idusuario`=userid  WHERE idplan=@manipular and idprestamo=idprestamoin;
			                        */
			                          INSERT INTO par__prestamos__plans (`idprestamo`,`pe`, `fp`, `di`, `am`, `in`, `indi`, `car`, `cut`, `ca`, `cuota`, `fecharegistro`,`tipo`, `idestado`, `idtransaccionC`, `numDocC`, `glosa`, `fechaCobranza`, `importe`, `idusuario`) 
									 VALUES (idprestamoin,500,getfecha(),@dias,@por_cobrar,@interes,@indi_sum,0,@cuota,0,@cuota,getfecha(),3,13,@prestamos_tra,@prestamos_nuevo,'Liquidación del prestamo - automatico',getfecha(),@cuota,userid);
									  
			                          
			                        set @valuecount=(SELECT count(*) FROM par__prestamos__plans where idprestamo=idprestamoin and idestado=10);
			                        if @valuecount=0 then 
			                          		UPDATE par__prestamos SET idejecucion=6,idref=prestamonew, idestado=5,idrefaux=concat(idrefaux,'|',prestamonew) WHERE idprestamo =idprestamoin;
			                           else
			                          		UPDATE par__prestamos SET idejecucion=6,idref=prestamonew,idrefaux=concat(idrefaux,'|',prestamonew) WHERE idprestamo =idprestamoin;
			                        end if;
			                        
			                   UPDATE par__prestamos__plans set `tipo`=0,`idestado`=13 WHERE idestado=2 and idprestamo=idprestamoin;
			                      /* DELETE FROM par__prestamos__plans WHERE idestado=2 and idprestamo=idprestamoin;
			                       DELETE FROM par__prestamos__plan__cargos WHERE idplan=@manipular;  */ 
		                            
		                        else
		                            set sumout=-10; 
		                           LEAVE puntero;
		                        end if; 
                            
                            END LOOP puntero; 
                    CLOSE cursor_prestmos;
                   
                   select sumout/mo.tipocambio from par__productos pro, par__monedas mo where pro.moneda=mo.idmoneda and pro.idproducto=producto into sumout; 
              SELECT JSON_SET(value, '$.total',ROUND(sumout,6))into value;   
 END");
             /* funcion que obtiene el valor porcentual del estado del prestamo */
             $valida_estado="CREATE OR REPLACE  FUNCTION valida_estado (factor int,moneda int,socio int) 
             RETURNS text
            DETERMINISTIC
            BEGIN   
            DECLARE outdata float default(0); 
            /*  se debe implementar un query que obtenga las cuotas pagadas y el total de cuotas */
            set @idprestamo=(SELECT idprestamo FROM par__prestamos pre, par__productos p WHERE pre.idproducto=p.idproducto and pre.idprestamo=pre.idref and pre.idestado BETWEEN 1 and 3 and p.moneda=moneda and pre.idsocio=socio limit 1); 
           
           if(@idprestamo is NOT NULL) then
            set @cuotaspagadas=(SELECT count(*) FROM par__prestamos__plans  WHERE idprestamo=@idprestamo and idestado BETWEEN 7 and 10); 
            set @totalcuotas=(SELECT plazo FROM par__prestamos WHERE idprestamo=@idprestamo); 
           else
            set @cuotaspagadas=0; 
            set @totalcuotas=0; 
           set @idprestamo=0;
           end if;
             
           
            SET @aux=0;

            set @valor=(SELECT pc.valorporcentual FROM par__productos__criterios pc, par__productos__parametros pp  WHERE pc.idprodparam=pp.idprodparam and pp.idf=2 and pp.idfactor=factor); 
            set @final=(SELECT valorparametro  FROM par__productos__parametros WHERE idf=2 and idfactor=factor);

                if @totalcuotas>0 then
                    if @cuotaspagadas>0 then
                    SET @aux=(@cuotaspagadas*100)/@totalcuotas; 
                        if @aux >= @valor then 
                            set outdata=@final; 
                        end if; 
                    else
                    SET @aux=0; 
                    end if;
                else
                SET @aux=100; 
                set outdata=@final;
                end if; 
            /*cp= cuotas pagadas, tc= total de cuotas a pagar, pa= porcentaje sobre todas las cuotas pagadas 100%, pn= es el porcentaje minimo a cumplir*/
            RETURN concat('{\"data\":',outdata,',\"value\":',@final,',\"html\":{\"id\":',@idprestamo,',\"cp\":',@cuotaspagadas,',\"tc\":',@totalcuotas,',\"pa\":',@aux,',\"pn\":',@valor,'}}');
            END";
             DB::unprepared($valida_estado);
                
   
         /* funcion que de acuerdo ala cantidad historial de pagos obtiene la calificacion en un porcentaje de 0 a 100  */
            $valida_historial = "CREATE OR REPLACE  FUNCTION valida_historial (valuef int,prestamos text) 
            RETURNS text
            DETERMINISTIC
            BEGIN  
            
            DECLARE contador int default(0);
            DECLARE contadorporcentaje float default(0); 
								/* p0014|1,p0016|2,p0018|1,p0015|0 representa la cantidad de meses en mora de cada prestamo, si no tuviera prestamos poner valor por defecto 0 */			
                    SET @prestamosevalua = prestamos;
					SET @prestamosdetalle='';
                    SET @separator = ',';
                    SET @separatorcod = '|';
                    SET @separatorLength = CHAR_LENGTH(@separator);
                    SET @separatorLength2 = CHAR_LENGTH(@separatorcod);
					
					set @valorgeneral=(SELECT valorparametro  FROM par__productos__parametros WHERE idf=1 and idfactor=valuef);
                    WHILE @prestamosevalua  != '' > 0 DO
					
                        SET @prestamo = SUBSTRING_INDEX(@prestamosevalua , @separator, 1); 
						
						SET @codprestamo = SUBSTRING_INDEX(@prestamo , @separatorcod, 1);
						
                        SET @valorprestamo = SUBSTRING(@prestamo , CHAR_LENGTH(@codprestamo) + @separatorLength2 + 1);
						
                        SET @valordetalle=(SELECT pc.valorporcentual FROM par__productos__criterios pc, par__productos__parametros pp WHERE pc.idprodparam=pp.idprodparam and pp.idf=1 and pp.idfactor=valuef and pc.valornumerico=@valorprestamo); 
						  
					   IF (@valordetalle>0 and @valorgeneral>0) THEN
					            set @aux = (@valordetalle*@valorgeneral)/100;
								
								if CHAR_LENGTH(@prestamosdetalle)=0 then
								set @prestamosdetalle = concat('\"',@codprestamo ,'\":{\"m\":',@valorprestamo,',\"p\":',@aux,'}');
								else
								set @prestamosdetalle = concat(@prestamosdetalle,',\"',@codprestamo ,'\":{\"m\":',@valorprestamo,',\"p\":',@aux,'}');
								end if;
								 
								
                                set contadorporcentaje = contadorporcentaje +@aux;
                       ELSEIF(@valorprestamo=0)THEN
					           set @aux = (100*@valorgeneral)/100;
							   
							   if CHAR_LENGTH(@prestamosdetalle)=0 then
								set @prestamosdetalle = concat('\"',@codprestamo ,'\":{\"m\":',@valorprestamo,',\"p\":',@aux,'}');
								else
								set @prestamosdetalle = concat(@prestamosdetalle,',\"',@codprestamo ,'\":{\"m\":',@valorprestamo,',\"p\":',@aux,'}');
								end if;
								
                               set contadorporcentaje = contadorporcentaje +@aux;
						ELSE
						       set @aux =0;
							   
							   if CHAR_LENGTH(@prestamosdetalle)=0 then
								set @prestamosdetalle = concat('\"',@codprestamo ,'\":{\"m\":',@valorprestamo,',\"p\":',@aux,'}');
								else
							    set @prestamosdetalle = concat(@prestamosdetalle,',\"',@codprestamo ,'\":{\"m\":',@valorprestamo,',\"p\":',@aux,'}');
								end if;
								
                               set contadorporcentaje = contadorporcentaje +@aux;
						
                        END IF; 
							
                        SET @prestamosevalua  = SUBSTRING(@prestamosevalua , CHAR_LENGTH(@prestamo) + @separatorLength + 1);
                        set contador=contador+1;
                    END WHILE; 
			 
                set contadorporcentaje = contadorporcentaje / contador;   
            RETURN concat('{\"data\":',contadorporcentaje,',\"value\":',@valorgeneral,',\"html\":{',@prestamosdetalle,'}}');
            END";
             DB::unprepared($valida_historial);


             $getprestamosmora="CREATE OR REPLACE FUNCTION getPrestamosmora (id int) 
             RETURNS text
             DETERMINISTIC
             BEGIN  
             SET @moras = '';
              CALL getidsprestamosMora(@moras,id); 
                        if(CHAR_LENGTH(@moras)=0) then
                        SET @moras = '0|0';
                        end if;
            RETURN @moras;
             END";
             DB::unprepared($getprestamosmora);

            $getprestamos="CREATE OR REPLACE FUNCTION getPrestamos (id int,idpro int,tipo int) 
            RETURNS FLOAT
            DETERMINISTIC
            BEGIN  
            set @value=0;
		  
					 if tipo=0 or tipo=3 then  
					 
					 	set @value=(select sum(pre.cuota*mo.tipocambio) from par__prestamos pre,par__productos pro,par__monedas mo 
			            where pre.idproducto=pro.idproducto  
			            and pro.moneda=mo.idmoneda and pre.idsocio=id and pre.apro_conta not between 2 and 4 and pre.idestado between 2 and 3);
			           
                     elseif tipo=1 then 
                     
                        set @value=(select sum(pre.cuota*mo.tipocambio) from par__prestamos pre,par__productos pro,par__monedas mo 
			            where pre.idproducto=pro.idproducto and pro.idproducto!=idpro 
			            and pro.moneda=mo.idmoneda and pre.idsocio=id and pre.apro_conta not between 2 and 4 and pre.idestado between 2 and 3);
		                             
                     elseif tipo=2 then 
                        
                         set @value=(select sum(pre.cuota*mo.tipocambio) from par__prestamos pre,par__productos pro,par__monedas mo 
			            where pre.idproducto=pro.idproducto and pro.tasa=0 
			            and pro.moneda=mo.idmoneda and pre.idsocio=id and pre.apro_conta not between 2 and 4 and pre.idestado between 2 and 3); 
			           
                     else
                         SET @value=0;
                     end if; 
            if(@value is NULL) then
		            set @value=0; 
		           end if;
            RETURN @value;
            END";
             DB::unprepared($getprestamos);


          
              DB::unprepared('CREATE OR REPLACE FUNCTION refinanciar (id int,pro int,iduser int,prestamonew int,tipo int)
              RETURNS text
              DETERMINISTIC
              BEGIN  
              SET @moras=(SELECT JSON_OBJECT("total",0, "prestamos",Json_Array()));
                                    if tipo=1 then
                                        CALL refinanciar_prestamo1(@moras,id,pro,iduser,prestamonew);  
                                    elseif tipo=2 then 
                                        CALL refinanciar_prestamo2(@moras,id,pro,iduser,prestamonew);    
                                    end if;
                                  
                         RETURN @moras;
              END');


           
             DB::unprepared("CREATE OR REPLACE FUNCTION getmonto_refinciamiento (idprestamoin int) 
             RETURNS FLOAT
             DETERMINISTIC
             BEGIN  
             set @validaconta=1; 
                             set @validaconta=(select pre.apro_conta from par__prestamos pre,par__productos pro where pre.idproducto=pro.idproducto   and pre.idprestamo=idprestamoin);
                                
                         if @validaconta=1 then  
                                 set @mesdesembolso=(select month(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
                                 set @diadesembolso=(select day(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
                                 set @tipocambio=(select mo.tipocambio from par__prestamos pre,par__productos pro,par__monedas mo 
                                     where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pre.idprestamo=idprestamoin);
                                 
                                 set @tasa=(select pro.tasa from par__prestamos pre,par__productos pro where pre.idproducto=pro.idproducto   and pre.idprestamo=idprestamoin);   
                                 set @tem=0;
                                 if @tasa>0 then
                                 set @tem =(@tasa/12)/100;
                                 end if;
                                  
                                 SET @dias=0;
                                 set @por_cobrar=(select plan.ca_an from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                 set @fecha_plan=(select plan.fp from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                 set @dias_plan=(select plan.di from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                 set @dias_fecha_plan=(select day(@fecha_plan));
                                 set @mes_fecha_plan=(select month(@fecha_plan));
                                
                                 set @value=0; 
                                     if(@tem>0) then   
	                                     SET @fecha = (SELECT  fechaSistema  FROM par__fecha__sistemas WHERE activo=1);
	                                     SET @mesactual =(SELECT month(@fecha));
	                                     SET @dias =(SELECT DAY(@fecha));  
	                                    	 
                                             if @mesactual=@mesdesembolso then
		                                         SET @dias=(@dias-@diadesembolso)+1;
		                                     elseif @mes_fecha_plan<@mesactual  then
		                                         SET @dias=(@dias+@dias_plan)+1;
		                                     elseif @mes_fecha_plan=@mesactual  then
		                                         SET @dias=(@dias+(@dias_plan-@dias_fecha_plan))+1;
		                                     end if;
	                                     
	                                     set @interes=((@por_cobrar*@tem*@dias)/30);
	                                     set @value=@por_cobrar+@interes; 
	                                     set @value=@value*@tipocambio;  
                                     else
	                                     set @value=@por_cobrar; 
	                                     set @value=@value*@tipocambio; 
                                     end if;
                                  
                         else
                             set @value=-25; 
                         end if;  
                             RETURN @value;
             END");

            
             DB::unprepared("CREATE OR REPLACE FUNCTION getcapitaltotal (idsocioin int,producto int,cancelar int) 
             RETURNS FLOAT
             DETERMINISTIC
             BEGIN  SET @value=0; 
                                      set @validate1=(select count(*) FROM par__prestamos  where  idsocio=idsocioin  and idestado=1);
                                      set @validate2=(select count(*) from par__prestamos where idsocio=idsocioin and (apro_conta=0 or apro_conta=5) and idestado between 2 and 3);
                                     if @validate1>0 then
                                            set @value=-35;
                                          elseif @validate2>0 then 
                                              set @value=-25;
                                          elseif cancelar=1 then  
                                               CALL get_capitalTotal_0(@value,idsocioin,producto);  
                                               if @value is null then set @value=0; end if; 
                                          else
                                               set @value=0;
                                         end if;  
                                      RETURN @value;
             END");


             $checkcut="CREATE OR REPLACE  FUNCTION checkcut() RETURNS BOOL
             BEGIN
                 DECLARE isExist BOOL;
                 SET isExist = 0;
                 SET @fecha='';
             select getfecha() into @fecha;	
                 if (SELECT count(*) FROM par__fecha__sistemas WHERE (fechaSistema BETWEEN DATE_FORMAT(@fecha,'%Y-%m-01') AND LAST_DAY(@fecha)) and fechaCorte=1)>0 THEN 
                     SET isExist = 1;
                 ELSEIF (SELECT count(*) FROM par__fecha__sistemas WHERE (fechaSistema BETWEEN DATE_SUB(DATE_FORMAT(@fecha,'%Y-%m-01') , INTERVAL 1 MONTH) AND LAST_DAY(DATE_SUB(@fecha, INTERVAL 1 MONTH))) and fechaCorte=1)>1 THEN 
                     SET isExist = 1;
                 ELSE
                     SET isExist = 0;
                 END IF; 
                 RETURN isExist;
             END";
             DB::unprepared($checkcut);

             ///////////////////////////////////////////////////////////////////////////////////////////////////////////triggers//////////////////////
             DB::unprepared("CREATE OR REPLACE TRIGGER estado_conta_prestamo AFTER UPDATE ON con__asientomaestros
                            FOR EACH ROW BEGIN
                            set @countvalue=(SELECT count(*) FROM par__prestamos__plans WHERE FIND_IN_SET(new.idasientomaestro,idasiento)); 
	                         if @countvalue is null then set @countvalue=0; end if; 
	                        
	                        set @countvaluepre=(SELECT count(*) FROM par__prestamos WHERE FIND_IN_SET(new.idasientomaestro,idasiento)); 
	                         if @countvaluepre is null then set @countvaluepre=0; end if; 
	                         
	                        if @countvalue>0 then  
	                          UPDATE par__prestamos__plans SET apro_conta=new.estado,fecharde_apro_conta=getfecha() WHERE idasiento=new.idasientomaestro;
	                         elseif @countvaluepre>0 then
	                          UPDATE par__prestamos SET apro_conta=new.estado,fecharde_apro_conta=getfecha() WHERE idasiento=new.idasientomaestro;
	                        end if;
                            END");

                DB::unprepared("CREATE OR REPLACE  PROCEDURE get_capitalTotal_0 (INOUT value float,in idsocio int,in producto int)
                BEGIN
                DECLARE v_finished INTEGER DEFAULT 0;
                        DECLARE idprestamoin int; 
                        DEClARE cursor_prestmos CURSOR FOR select pre.idprestamo  from par__prestamos pre,par__productos pro,par__monedas mo 
									where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pro.cobranza_perfil_refi!=0
                                    and pre.idsocio=idsocio and pre.idestado between 2 and 3;
                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1; 
                                    OPEN cursor_prestmos; 
                                                puntero: LOOP 
                                                        FETCH cursor_prestmos INTO idprestamoin; 
                                                IF v_finished = 1 THEN 
                                                LEAVE puntero;
                                                END IF; 
                                                set @outsum=getmonto_refinciamiento(idprestamoin); 
                                                    if @outsum>=0 then
                                                    set value=value+@outsum;
                                                    else
                                                    set value=@outsum; 
                                                    LEAVE puntero;
                                                    end if;
                                            
                                                END LOOP puntero; 
                                CLOSE cursor_prestmos;  
                        if value>0 then
                                    set @tipocambio=(select mo.tipocambio from par__productos pro,par__monedas mo 
                                            where  pro.moneda=mo.idmoneda   and pro.idproducto=producto);
                                    set value=value/@tipocambio;
                        end if;
                END");
            DB::unprepared("CREATE OR REPLACE  PROCEDURE get_capitalTotal_1 (INOUT value float,in idsocio int,in producto int,in paso int)
            BEGIN
            DECLARE v_finished INTEGER DEFAULT 0;
                DECLARE idprestamoin int;
                DECLARE aproconta_in int;
                DECLARE tasa_in float; 
                DEClARE cursor_prestmos CURSOR FOR select pre.idprestamo,pro.tasa,pre.apro_conta from par__prestamos pre,par__productos pro,par__monedas mo 
                                    where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pro.idproducto=producto and pre.idejecucion!=2 and pre.idejecucion!=6
                                    and pre.idsocio=idsocio and pre.idestado between 2 and 3 ;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1; 
		                    OPEN cursor_prestmos; 
			                            puntero: LOOP 
			                                    FETCH cursor_prestmos INTO idprestamoin,tasa_in,aproconta_in;
			                            
			                            IF v_finished = 1 THEN 
			                            LEAVE puntero;
			                            END IF; 
			                           
			                             set @outsum=getmonto_refinciamiento(idprestamoin);
			                            /*set @outsum=getmonto_refinciamiento(idprestamoin,tasa_in,1);*/
			                             
			                                  if @outsum>=0 then
					                            set value=value+@outsum; 
					                         else
					                           set value=@outsum; 
					                           LEAVE puntero;
				                             end if; 
			                          
			                            
			                            END LOOP puntero; 
			              CLOSE cursor_prestmos;  
                if value>0 then
                            set @tipocambio=(select mo.tipocambio from par__productos pro,par__monedas mo 
                                    where  pro.moneda=mo.idmoneda   and pro.idproducto=producto);
                            set value=value/@tipocambio;
                end if;
            END");

            DB::unprepared("CREATE OR REPLACE  PROCEDURE get_capitalTotal_2 (INOUT value float,in idsocio int,in producto int,in paso int)
            BEGIN
            DECLARE v_finished INTEGER DEFAULT 0;
                DECLARE idprestamoin int;
                DECLARE aproconta_in int;
                DECLARE tasa_in float; 
                DEClARE cursor_prestmos CURSOR FOR select pre.idprestamo,pro.tasa,pre.apro_conta  from par__prestamos pre,par__productos pro,par__monedas mo 
                                    where pre.idproducto=pro.idproducto and pro.moneda=mo.idmoneda and pro.tasa!=0  and pre.idejecucion!=2 and pre.idejecucion!=6 
                                    and pre.idsocio=idsocio and pre.idestado between 2 and 3;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1; 
		                    OPEN cursor_prestmos; 
			                            puntero: LOOP 
			                                    FETCH cursor_prestmos INTO idprestamoin,tasa_in,aproconta_in;
			                            
			                            IF v_finished = 1 THEN 
			                            LEAVE puntero;
			                            END IF; 
			                             set @outsum=getmonto_refinciamiento(idprestamoin);
				                         /*set @outsum=getmonto_refinciamiento(idprestamoin,tasa_in,1);*/
                                          
			                                 if @outsum>=0 then
					                            set value=value+@outsum; 
					                         else
					                           set value=@outsum; 
					                           LEAVE puntero;
				                             end if; 
				                            
			                            END LOOP puntero; 
			              CLOSE cursor_prestmos;  
                if value>0 then
                            set @tipocambio=(select mo.tipocambio from par__productos pro,par__monedas mo 
                                    where  pro.moneda=mo.idmoneda   and pro.idproducto=producto);
                            set value=value/@tipocambio;
                end if; 
            END");

 
                DB::unprepared("CREATE OR REPLACE FUNCTION getasciisended(id text,tipo int) RETURNS text 
                DETERMINISTIC
                BEGIN  
                    SET @sumasjson='';
                    SET @sumas='';
                    set @prs='';
                    
                    select IFNULL(sum(plan.send_ascii),-1) ,GROUP_CONCAT(plan.idplan separator ',') INTO @sumas,@prs 
                        from socios s,par__prestamos pre,par__prestamos__plans plan 
                        where  pre.idprestamo=plan.idprestamo and plan.idestado=10 and pre.idsocio=s.idsocio and s.numpapeleta=id;
                        
                    if tipo=0 then
                    set @sumasjson=@prs;
                    else
                    set @sumasjson=@sumas;
                    end if;
                         RETURN @sumasjson;
                end");
   
   
   DB::unprepared("CREATE OR REPLACE FUNCTION getcuota(id int,cambio float) RETURNS float 
                 DETERMINISTIC
                 BEGIN  
                 return (select plan.cut*cambio from par__prestamos__plans plan where plan.idprestamo=id and plan.idestado=2 ORDER by plan.pe asc limit 1);
                 END");
DB::unprepared('CREATE OR REPLACE FUNCTION getmontoRefi2 (idprestamoin int)
RETURNS text
DETERMINISTIC
BEGIN   set @validaconta=1; 
                             set @validaconta=(select pre.apro_conta from par__prestamos pre,par__productos pro where pre.idproducto=pro.idproducto   and pre.idprestamo=idprestamoin);
							set @value=0; 
							SET @dias=0;
							set @indi_sum=0;
							set @interes=0;
							set @por_cobrar=0;
							set @periodo=0;
                         if @validaconta=1 then  
                                 set @indi_sum=(select sum(indi) from par__prestamos__plans where idprestamo=idprestamoin and (idestado=2 or idestado=10));
                                 set @mesdesembolso=(select month(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
                                 set @diadesembolso=(select day(fechardesembolso) from par__prestamos where idprestamo=idprestamoin);
                                 
                                 set @tasa=(select pro.tasa from par__prestamos pre,par__productos pro where pre.idproducto=pro.idproducto   and pre.idprestamo=idprestamoin);   
                                 set @tem=0;
			                                 if @tasa>0 then
			                                 set @tem =(@tasa/12)/100;
			                                 end if; 
                                 SET @dias=0;
                                 set @por_cobrar=(select plan.ca_an from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                set @periodo=(select plan.pe from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                 set @fecha_plan=(select plan.fp from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                 set @dias_plan=(select plan.di from par__prestamos__plans plan where plan.idprestamo=idprestamoin and (plan.idestado=2 or plan.idestado=10) ORDER by plan.pe asc limit 1);
                                 set @dias_fecha_plan=(select day(@fecha_plan));
                                 set @mes_fecha_plan=(select month(@fecha_plan));
                                
                                 set @value=0; 
                                     if(@tem>0) then   
	                                     SET @fecha = (SELECT  fechaSistema  FROM par__fecha__sistemas WHERE activo=1 limit 1);
	                                     SET @mesactual =(SELECT month(@fecha));
	                                     SET @dias =(SELECT DAY(@fecha)); 
		                     
	                                    	 if @mesactual=@mesdesembolso then
		                                     SET @dias=(@dias-@diadesembolso)+1;
		                                     elseif @mes_fecha_plan<@mesactual  then
		                                     SET @dias=(@dias+@dias_plan)+1;
		                                     elseif @mes_fecha_plan=@mesactual  then
		                                     SET @dias=(@dias+(@dias_plan-@dias_fecha_plan))+1;
		                                     end if;
	                                     /* obtener el total de papeleria que debe al momento de hacer el refinanciamiento que no se hace en este momento*/
	                                     set @interes=round(((@por_cobrar*@tem*@dias)/30),2);
	                                     set @value=round((@por_cobrar+@interes+@indi_sum),2) ;  
                                     else
                                         set @value_total=0;
	                                     set @value=@por_cobrar;  
                                     end if;
                                  
                         else
                             set @value=-25; 
                         end if;  
                         RETURN JSON_OBJECT("values",JSON_OBJECT("dias",@dias,"indi",ifnull(round(@indi_sum,2),0)),"datas",JSON_OBJECT("am",ifnull(@por_cobrar,0),"in",ifnull(@interes,0),"cuota",ifnull(@value,0)));
END');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS valida_historial;');
        DB::unprepared('DROP FUNCTION IF EXISTS valida_historial_garante;');
        DB::unprepared('DROP FUNCTION IF EXISTS getPrestamos;');
        DB::unprepared('DROP FUNCTION IF EXISTS getcuota;');
        DB::unprepared('DROP FUNCTION IF EXISTS getmonto_refinciamiento;');
        DB::unprepared('DROP FUNCTION IF EXISTS valida_monto;');
        DB::unprepared('DROP FUNCTION IF EXISTS valida_estado;');
        DB::unprepared('DROP FUNCTION IF EXISTS getfechas;');
        DB::unprepared('DROP FUNCTION IF EXISTS getfecha;');
        DB::unprepared('DROP FUNCTION IF EXISTS getfecha2;');
        DB::unprepared('DROP FUNCTION IF EXISTS checkcut;');
        DB::unprepared('DROP FUNCTION IF EXISTS getasciisended;');
        DB::unprepared('DROP FUNCTION IF EXISTS refinanciar;');
        DB::unprepared('DROP PROCEDURE IF EXISTS insertfecha;'); 
        DB::unprepared('DROP PROCEDURE IF EXISTS getidsprestamosMora;'); 
        DB::unprepared('DROP PROCEDURE IF EXISTS refinanciar_prestamo1;'); 
        DB::unprepared('DROP PROCEDURE IF EXISTS refinanciar_prestamo2;'); 
        DB::unprepared('DROP PROCEDURE IF EXISTS get_capitalTotal_0;'); 
        DB::unprepared('DROP PROCEDURE IF EXISTS get_capitalTotal_1;'); 
        DB::unprepared('DROP PROCEDURE IF EXISTS get_capitalTotal_2;'); 
        DB::unprepared('DROP TRIGGER IF EXISTS estado_conta_prestamo');
    }
}
