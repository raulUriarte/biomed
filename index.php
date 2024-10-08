<?php
require('fpdf/fpdf.php'); 


//********************************************************************** */ 

  

//********************************************************************** */     
    
    // Función para rotar (puedes modificarla si es necesario)
    

//********************************************************************** */ 

// Conexión a la base de datos




// Conexión a la base de datos
$conexion = new PDO("sqlsrv:server=siga_nube.mssql.somee.com; database=siga_nube", "SQLrauluriate_hbc", "10635015ch1t0");

// Establecer directamente el código de barras a buscar
$codigo_barra = 'P001042';

// Asegúrate de sanitizar el input para prevenir SQL Injection
$consulta = $conexion->prepare("SELECT * FROM dbo.diem WHERE codigo_barra = :codigo_barra");
$consulta->bindParam(':codigo_barra', $codigo_barra);      
$consulta->execute();
$datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

// Manejar los datos
if ($datos) {
    foreach ($datos as $fila) {
        // Aquí puedes procesar cada fila de resultados
        print_r($fila);
    }
} else {
    echo "No se encontraron resultados para el código de barras: " . $codigo_barra;
}








//********************************************************************** */         

// Crear el PDF
        $fpdf = new PDF();
        $fpdf->AddPage(); // Página 1
//********************************************************************** */         

// Fondo de la hoja

            $fpdf->SetFont('Arial', '', 13);    
            $fpdf->Image('image/certificado.jpg', 8, 8, 193, 282, 'JPG', '');
//********************************************************************** */ 

// Título
            $fpdf->SetFont('Arial', 'B', 13);  
            $fpdf->SetTextColor(255, 0, 12); 
            $fpdf->setY(75);
            $fpdf->setX(47);
            $fpdf->MultiCell(115, 5, utf8_decode("CERTIFICADO DEL EQUIPO ACTIVO ESTRATÉGICO"), 0, 'C');
//********************************************************************** */ 

// Verificamos si hay datos
if (count($datos) > 0) 
{
    foreach ($datos as $dato) 
        {   // Aquí se agregan los detalles del activo al PDF...

         // Descripción
             $fpdf->SetFont('Arial', 'B', 12);
             $fpdf->SetTextColor(0, 0, 0); 
             $fpdf->setY(87);
             $fpdf->setX(47);
             $fpdf->MultiCell(115, 5, utf8_decode($dato['descripcion_del_bien']), 0, 'C');
         //********************************************************************** */ 

         // Foto   

            
         //********************************************************************** */ 

         // Marca
         $fpdf->SetFont('Arial', '', 11);
         $fpdf->setY(102);
         $fpdf->setX(108);       
         $fpdf->MultiCell(51, 5, utf8_decode('' . $dato['marca']), 0, 0);
         //********************************************************************** */ 

         // Modelo
         $fpdf->SetFont('Arial', '', 11);
         $fpdf->setY(112);
         $fpdf->setX(108);
         $fpdf->MultiCell(51, 5, utf8_decode('' . $dato['modelo']), 0, 0);
         //********************************************************************** */ 

         // Serie
         $fpdf->SetFont('Arial', '', 9);
         $fpdf->setY(122);
         $fpdf->setX(108);
         $fpdf->MultiCell(51, 5, utf8_decode('' . $dato['numero_serie']), 0, 0);
         //********************************************************************** */ 

         // Estado
         $fpdf->SetFont('Arial', '', 11);
         $fpdf->setY(130);
         $fpdf->setX(108);
         $fpdf->write(0, utf8_decode("ESTADO      : " . $dato['estado']));
         //********************************************************************** */ 

         // Pecosa        
         $fpdf->SetFont('Arial', '', 11);
         $fpdf->setY(135);
         $fpdf->setX(108);
         $pecosaEntero = intval($dato['numero_pecosa']);
         $fpdf->write(0, "PECOSA      : " . $pecosaEntero);               
         //********************************************************************** */ 

         // Fecha
         $fechaCompra = new DateTime($dato['fecha_compra']);
         $fechaFormateada = $fechaCompra->format('d-m-Y'); // 18-06-2009        
         $fpdf->setY(140);
         $fpdf->setX(108);
         $fpdf->write(0, utf8_decode("FECHA         : " . $fechaFormateada));
         //********************************************************************** */ 

         // Edad
         $fechaCompra = new DateTime($dato['fecha_compra']);
         $hoy = new DateTime();
         $intervalo = $hoy->diff($fechaCompra);
         $edadEquipo = $intervalo->y; // Esto te da la cantidad de años

         // Mostrar la edad en el PDF
         $fpdf->setY(145);
         $fpdf->setX(108);
         $fpdf->write(0, utf8_decode("EDAD           : " . $edadEquipo . " AÑOS"));
         //********************************************************************** */

         // Vida útil
         $fpdf->setY(150);
         $fpdf->setX(108);
         $vidautilEntero = intval($dato['vida_util']);
         $fpdf->write(0, utf8_decode("VIDA ÚTIL    : " . $vidautilEntero . " AÑOS"));          
         //********************************************************************** */

         // Costo de compra
         $fpdf->setY(155);
         $fpdf->setX(108);
         $costoFormateado = number_format($dato['costo_de_compra'], 2, '.', ',');
         $fpdf->write(0, utf8_decode("COSTO        : " . $costoFormateado));
         //********************************************************************** */ 

         /// Codigo Margesi
         $fpdf->SetFont('courier','B',12.5);
         $fpdf->setY(165);
         $fpdf->setX(63); 
         $codigoActivoEntero = intval($dato['codigo_activo']);
         $fpdf->write(0,"". $codigoActivoEntero);                
         //********************************************************************** */  

         /// Codigo Barra
         $fpdf->SetFont('courier','',12);
         $fpdf->setY(170); ////ARRIBA ABAJO
         $fpdf->setX(69);  ///IZQUI   
         $fpdf->write(0, utf8_decode("" . $dato['codigo_barra']));
         /********************************************************************** */ 

         /// Concepto
         $fpdf->SetFont('Arial','',9);
         $fpdf->setY(160); ////ARRIBA ABAJO
         $fpdf->setX(108); ///IZQUI  
         $fpdf->write(0, utf8_decode("********* CONCEPTO *********"));
         $fpdf->SetFont('Arial','',8);
         $fpdf->setY(162); ////ARRIBA ABAJO
         $fpdf->setX(108); ///IZQUI  
         $fpdf->MultiCell(55, 5, utf8_decode("" . $dato['definicion']),0,0);
         //********************************************************************** */

         /// Grupo
         $fpdf->SetFont('helvetica','I',8);
         $fpdf->setY(172); ////ARRIBA ABAJO
         $fpdf->setX(47); ///IZQUI
         $fpdf->MultiCell(60, 4, utf8_decode("" . $dato['grupo']),0,0);
         //$fpdf->write    (0, utf8_decode("".$dato['grupo']));
         //********************************************************************** */

         /// Clase
         $fpdf->SetFont('helvetica','I',8);
         $fpdf->setY(180.5); ////ARRIBA ABAJO
         $fpdf->setX(47); ///IZQUI
         $fpdf->MultiCell(60, 4, utf8_decode("" . $dato['clase']),0,0);                
         //********************************************************************** */

         /// Familia
         $fpdf->SetFont('helvetica','I',8);
         $fpdf->setY(185); ////ARRIBA ABAJO
         $fpdf->setX(47); ///IZQUI
         $fpdf->MultiCell(60, 4, utf8_decode("" . $dato['familia']),0,0);  
         //$fpdf->write  (0, utf8_decode("".$dato['familia']));
         //********************************************************************** */

         /// Items
         $fpdf->SetFont('helvetica','I',8);
         $fpdf->setY(193.5); ////ARRIBA ABAJO
         $fpdf->setX(47); ///IZQUI        
         $fpdf->MultiCell(60, 4, utf8_decode("" . $dato['items']),0,0);                
         //********************************************************************** */

         /// Provedor
         $fpdf->SetFont('times','',10);
         $fpdf->setY(203); ////ARRIBA ABAJO
         $fpdf->setX(47); ///IZQUI 
         //Cell se   (ancho, alto, texto, bordes, ?, alineacion, rellenar, link)
         $fpdf->cell(115,5,utf8_decode(""  . $dato['proveedor']),0, 0,'I', false); 
         //********************************************************************** */

         /// Observacion
         $fpdf->SetFont('times','',10);
         $fpdf->setY(215); ///ARRIBA ABAJO
         $fpdf->setX(47); ///IZQUI
         $fpdf->write(0, utf8_decode("Observación: ". $dato['observaciones']));
         //********************************************************************** */

         /// Establecimiento
         $fpdf->SetFont('times','',11);
         $fpdf->setY(225); ///DERECHA
         $fpdf->setX(47); ///IZQUI
         $fpdf->MultiCell(115, 3, utf8_decode("". $dato['establecimiento']),0,0);
         //********************************************************************** */

         /// Dependencia
         $fpdf->SetFont('times','',11);
         $fpdf->setY(230); ///DERECHA
         $fpdf->setX(47); ///IZQUI
         $fpdf->MultiCell(115, 3, utf8_decode("". $dato['dependencia']),0,0);
         //********************************************************************** */

         /// Servicio
         $fpdf->SetFont('times','',11);
         $fpdf->setY(237); ///DERECHA
         $fpdf->setX(47); ///IZQUI
         $fpdf->MultiCell(115, 3, utf8_decode("". $dato['servicio']),0,0);
         //********************************************************************** */

         ///**van */
         $fpdf->SetFont('times','I',11);
         $fpdf->setY(242); ///DERECHA
         $fpdf->setX(47); ///IZQUI
         //Cell se   (ancho, alto, texto, bordes, ?, alineacion, rellenar, link)
         $fpdf->cell(115,5,'*** VAN ***',0, 0,'C', false); 
         //********************************************************************** */

        
         // Fecha      
        
         // Ajustar la posición para imprimir la fecha rotada
         $fpdf->setY(242);
         $fpdf->setX(50);
        
         // Imprimir la fecha rotada 90 grados
         $fpdf->RotatedText(108, 140, utf8_decode("FECHA         : " . $fechaFormateada), 90);


         // Continúa con el resto del código para imprimir otros detalles...    }

                } 

                else 
                {
         // Manejar caso sin datos
                $fpdf->SetFont('Arial', 'B', 15);
                $fpdf->SetTextColor(255, 0, 0); 
                $fpdf->setY(100);
                $fpdf->setX(48);   
                $fpdf->MultiCell(115, 10, utf8_decode("Todavia no se ha subido la informacion:"),0,'C');
                $fpdf->ln();

                $fpdf->SetFont('Arial', 'B', 15);
                $fpdf->SetTextColor(255, 0, 0); 
                $fpdf->setY(120);
                $fpdf->setX(48);
                //$fpdf->MultiCell(115, 5, utf8_decode("Todavia no se ha subido la informacion:"),0,'C');
                $fpdf->MultiCell(115, 10, utf8_decode("del :".$codigo_barra),0,'C');

         // Código para manejar ausencia de datos...
         // Muestra las paginas
         

        }
 
        $fpdf->Output();
?>
