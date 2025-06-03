CREATE PROC carga_cliente AS 

--crear tabla temporal
SELECT top 0 * INTO #tmpCliente FROM usuarios  
    
--realizar la carga masiva a la tabla temporal 
BULK INSERT #tmpCliente
FROM 'D:\Proyecto_PHP\archivo.txt'
with(
 rowterminator = '\n',
 fieldterminator = ','
)

--obtener los clientes nuevos 
SELECT a.*
INTO #tmpClienteNuevos
FROM #tmpCliente a left join usuarios b ON a.email = b.email 
where b.email is null 

--obtener los clinetes repetidos 
SELECT a.*
INTO #tmpClienteRepetidos
FROM #tmpCliente a inner join usuarios b ON a.email = b.email 

--actualizar los clientes repetidos (campos) 
UPDATE a
set a.direccion = b.direccion 
from usuarios a inner join #tmpClienteRepetidos b ON a.email = b.email 

--insertar los clientes nuevos 
INSERT INTO usuarios 
SELECT * 
from #tmpClienteNuevos

--eliminar tablas temporales 
DROP TABLE #tmpCliente
DROP TABLE #tmpClienteNuevos
DROP TABLE #tmpClienteRepetidos