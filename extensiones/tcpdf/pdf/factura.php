<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura{

public $codigo;

public function traerImpresionFactura(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$itemVenta = "codigo";
$valorVenta = $this->codigo;

$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$neto = number_format($respuestaVenta["neto"],2);
$impuesto = number_format($respuestaVenta["impuesto"],2);
$total = number_format($respuestaVenta["total"],2);
$metodo_pago=$respuestaVenta["metodo_pago"];

//TRAEMOS LA INFORMACIÓN DEL CLIENTE

$itemCliente = "id";
$valorCliente = $respuestaVenta["id_cliente"];

$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

$itemVendedor = "id";
$valorVendedor = $respuestaVenta["id_vendedor"];

$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->startPageGroup();

$pdf->AddPage();

// ---------------------------------------------------------

$bloque1 = <<<EOF

	<table>
		
		<tr>
			
			<td style="width:150px"><img src="images/logo.jpg"></td>

			<td style="background-color:white; width:240px">
				
				<div style="font-size:8.5px; text-align:center; line-height:15px;">
					
					<br>
				  <b>FARMACIA EMMANUEL</b>								
					<br>
					Horcones, Santa Catarina Mita, Jutiapa C.A.<br>
					Telefono: +(502) 78420810<br>
					Email: grupo2@gmail.com

				</div>

			</td>

			<td style="background-color:white; width:110px; text-align:center; color:black"><br><br>FACTURA N.<br>$valorVenta</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

// ---------------------------------------------------------

$bloque2 = <<<EOF

	<table>
		
		<tr>
			
			<td style="width:540px"><img src="images/back.jpg"></td>
		
		</tr>
		<tr>
			
		<td></td>
	
	</tr>

	</table>

	<table style="font-size:10px; padding:5px 10px;">
	
		<tr>
		<td style="border: 1px solid #666;  background-color:#2c3e50; width:390px">

		<p color="white"> FACTURAR A</p>

		</td>
		</tr>

		<tr>
		<td style="background-color:white; width:390px">
		<p color="black">$respuestaCliente[nombre]</p>
		</td>
		</tr>

		<tr>
		<td style="background-color:white; width:390px">
		<p color="black">$respuestaCliente[direccion]</p>
		</td>
		</tr>

		<tr>
		<td style="background-color:white; width:390px">
		<p color="black">$respuestaCliente[telefono]</p>
		</td>
		</tr>

		<tr>
		<td style="background-color:white; width:390px">
		<p color="black">$respuestaCliente[email]</p>
		</td>
		</tr>

		<tr>
		<td style="background-color:#2c3e50; width:220">
		<p color="white">VENDEDOR</p>
		</td>
		<td style="background-color:#2c3e50; width:120">
		<p color="white">FECHA</p>
		</td>
		<td style="background-color:#2c3e50; width:150">
		<p color="white">FORMA DE PAGO</p>
		</td>
		</tr>
		<tr>
		<td style="width:220">$respuestaVendedor[nombre]</td>
		
		<td style="width:120">	$fecha</td>

		<td style="width:170">$metodo_pago</td>
		</tr>
		
		<tr>
		<td></td>
		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');

// ---------------------------------------------------------

$bloque3 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
		
		<td style=" background-color:#2c3e50; width:80px; text-align:center"><p color="white">CANTIDAD</p></td>
		<td style="background-color:#2c3e50; width:260px; text-align:center"><p color="white">PRODUCTO</p></td>
		<td style="background-color:#2c3e50; width:100px; text-align:center"><p color="white">PRECIO UNIT.</p></td>
		<td style="background-color:#2c3e50; width:100px; text-align:center"><p color="white">PRECIO TOTAL</p></td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');

// ---------------------------------------------------------

$nums=1;
foreach ($productos as $key => $item) {

$itemProducto = "descripcion";
$valorProducto = $item["descripcion"];
$orden = null;

$respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);

$valorUnitario = number_format($respuestaProducto["precio_venta"], 2);

$precioTotal = number_format($item["total"], 2);
if ($nums%2==0){
$bloque4 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
			
		<td style=" color:#333; background-color:#ecf0f1; width:80px; text-align:center">
				$item[cantidad]
			</td>

			<td style=" color:#333; background-color:#ecf0f1; width:260px; text-align:center">
				$item[descripcion]
			</td>

			<td style=" color:#333; background-color:#ecf0f1; width:100px; text-align:center">Q. 
				$valorUnitario
			</td>

			<td style=" color:#333; background-color:#ecf0f1; width:100px; text-align:center">Q.	 
				$precioTotal
			</td>


		</tr>

	</table>


EOF;
}else {
	$bloque4 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>
			
		<td style=" color:#333; background-color:white; width:80px; text-align:center">
				$item[cantidad]
			</td>

			<td style=" color:#333; background-color:white; width:260px; text-align:center">
				$item[descripcion]
			</td>

			<td style=" color:#333; background-color:white; width:100px; text-align:center">Q. 
				$valorUnitario
			</td>

			<td style=" color:#333; background-color:white; width:100px; text-align:center">Q.	 
				$precioTotal
			</td>


		</tr>

	</table>


EOF;	
}
$pdf->writeHTML($bloque4, false, false, false, false, '');
$nums++;
}

// ---------------------------------------------------------

$bloque5 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="background-color:white; width:100px; text-align:center"></td>

			<td style="color:#333; background-color:white; width:100px; text-align:center"></td>

		</tr>
		
		<tr>
		
			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="background-color:white; width:100px; text-align:center">
				SUBTOTAL Q. :
			</td>

			<td style="color:#333; background-color:white; width:100px; text-align:center">
				Q $neto
			</td>

		</tr>

		<tr>

			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="background-color:white; width:100px; text-align:center">
				IVA(12%):
			</td>
		
			<td style="color:#333; background-color:white; width:100px; text-align:center">
				Q. $impuesto
			</td>

		</tr>

		<tr>
		
			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="background-color:white; width:100px; text-align:center">
				Total:
			</td>
			
			<td style=" background-color:white; width:100px; text-align:center">
				 Q. $total
			</td>

		</tr>
		<tr><td></td></tr>
	</table>
	<div style="font-size:11pt;text-align:center;font-weight:bold">Gracias por su compra!</div>
EOF;

$pdf->writeHTML($bloque5, false, false, false, false, '');



// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 

$pdf->Output('factura.pdf', 'D');

}

}

$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();

?>