<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/Cabras.php';
require_once __DIR__ . '/../models/Parto.php';
require_once __DIR__ . '/../models/EventoReproductivo.php';
require_once __DIR__ . '/../models/HistorialPropiedad.php';
require_once __DIR__ . '/../models/ControlSanitario.php';
require_once __DIR__ . '/../models/DocumentosCabras.php';

class PDFService
{
    private $db;
    private $pdf;

    // Paleta de colores cafés y tierras moderna
    private $colorCafePrimario = [101, 67, 33];       // Café oscuro elegante
    private $colorCafeSecundario = [139, 90, 43];     // Café medio cálido
    private $colorTierra = [160, 116, 78];            // Tierra clara
    private $colorCrema = [245, 238, 227];            // Crema suave
    private $colorMarron = [62, 39, 35];              // Marrón muy oscuro para texto
    private $colorNaranja = [191, 87, 0];             // Naranja terroso
    private $colorVerde = [76, 106, 60];              // Verde oliva
    private $colorRojo = [140, 49, 49];               // Rojo óxido
    private $colorDorado = [184, 134, 11];            // Dorado mostaza
    private $colorBeige = [250, 248, 240];            // Beige muy claro

    public function __construct($db)
    {
        $this->db = $db;
    }

    private function configurarPDF()
    {
        $this->pdf = new \FPDF();
        $this->pdf->AddPage();
        $this->pdf->SetMargins(20, 20, 20);
        $this->pdf->SetAutoPageBreak(true, 35);

        // Fondo sutil para toda la página
        $this->pdf->SetFillColor($this->colorBeige[0], $this->colorBeige[1], $this->colorBeige[2]);
        $this->pdf->Rect(0, 0, 210, 297, 'F');
    }

    private function dibujarEncabezado($nombreCabra)
    {
        // Gradiente de fondo usando múltiples rectángulos
        $this->dibujarGradiente(0, 0, 210, 45, $this->colorCafePrimario, $this->colorCafeSecundario);

        // Línea decorativa dorada
        $this->pdf->SetDrawColor($this->colorDorado[0], $this->colorDorado[1], $this->colorDorado[2]);
        $this->pdf->SetLineWidth(2);
        $this->pdf->Line(20, 38, 190, 38);

        // Título principal con sombra
        $this->pdf->SetTextColor($this->colorCrema[0], $this->colorCrema[1], $this->colorCrema[2]);
        $this->pdf->SetFont('Arial', 'B', 24);
        $this->pdf->SetXY(20, 10);
        $this->pdf->Cell(0, 12, 'HOJA DE VIDA CAPRINA', 0, 1, 'L');

        // Subtítulo elegante
        $this->pdf->SetFont('Arial', '', 16);
        $this->pdf->SetXY(20, 24);
        $this->pdf->Cell(0, 8, strtoupper($nombreCabra), 0, 1, 'L');

        // Resetear configuración
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->SetLineWidth(0.2);
        $this->pdf->SetY(55);
    }

    private function dibujarGradiente($x, $y, $w, $h, $color1, $color2)
    {
        $steps = 20;
        $stepHeight = $h / $steps;

        for ($i = 0; $i < $steps; $i++) {
            $ratio = $i / $steps;
            $r = $color1[0] + ($color2[0] - $color1[0]) * $ratio;
            $g = $color1[1] + ($color2[1] - $color1[1]) * $ratio;
            $b = $color1[2] + ($color2[2] - $color1[2]) * $ratio;

            $this->pdf->SetFillColor($r, $g, $b);
            $this->pdf->Rect($x, $y + ($i * $stepHeight), $w, $stepHeight, 'F');
        }
    }

    private function dibujarSeccion($titulo, $icono = '')
    {
        $this->verificarNuevaPagina(15);

        $y = $this->pdf->GetY();

        // Fondo principal de la sección
        $this->pdf->SetFillColor($this->colorCafeSecundario[0], $this->colorCafeSecundario[1], $this->colorCafeSecundario[2]);
        $this->pdf->Rect(20, $y, 170, 10, 'F');

        // Banda decorativa izquierda
        $this->pdf->SetFillColor($this->colorDorado[0], $this->colorDorado[1], $this->colorDorado[2]);
        $this->pdf->Rect(20, $y, 4, 10, 'F');

        // Título de la sección
        $this->pdf->SetTextColor($this->colorCrema[0], $this->colorCrema[1], $this->colorCrema[2]);
        $this->pdf->SetFont('Arial', 'B', 13);
        $this->pdf->SetXY(28, $y + 2);
        $this->pdf->Cell(0, 6, $icono . ' ' . $titulo, 0, 1, 'L');

        // Línea inferior decorativa
        $this->pdf->SetDrawColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
        $this->pdf->Line(20, $y + 10, 190, $y + 10);

        // Resetear configuración
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->SetY($y + 15);
    }

    private function dibujarCampo($etiqueta, $valor, $ancho = 85)
    {
        $y = $this->pdf->GetY();

        // Fondo alternado con bordes redondeados (simulado)
        $this->pdf->SetFillColor($this->colorCrema[0], $this->colorCrema[1], $this->colorCrema[2]);
        $this->pdf->Rect(20, $y, 170, 7, 'F');

        // Borde sutil
        $this->pdf->SetDrawColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->Rect(20, $y, 170, 7, 'D');

        // Etiqueta con estilo
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
        $this->pdf->SetXY(25, $y + 1);
        $this->pdf->Cell($ancho, 5, $etiqueta . ':', 0, 0, 'L');

        // Valor con estilo
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->Cell(0, 5, $valor, 0, 1, 'L');

        $this->pdf->SetY($y + 9);
        $this->pdf->SetLineWidth(0.2);
    }

    private function dibujarCampoDoble($etiqueta1, $valor1, $etiqueta2, $valor2)
    {
        $y = $this->pdf->GetY();

        // Fondo elegante
        $this->pdf->SetFillColor($this->colorCrema[0], $this->colorCrema[1], $this->colorCrema[2]);
        $this->pdf->Rect(20, $y, 170, 7, 'F');

        // Borde sutil
        $this->pdf->SetDrawColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
        $this->pdf->SetLineWidth(0.1);
        $this->pdf->Rect(20, $y, 170, 7, 'D');

        // Divisor central
        $this->pdf->SetDrawColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
        $this->pdf->Line(105, $y, 105, $y + 7);

        // Primera columna
        $this->pdf->SetXY(25, $y + 1);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
        $this->pdf->Cell(40, 5, $etiqueta1 . ':', 0, 0, 'L');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->Cell(40, 5, $valor1, 0, 0, 'L');

        // Segunda columna
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
        $this->pdf->Cell(40, 5, $etiqueta2 . ':', 0, 0, 'L');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->Cell(40, 5, $valor2, 0, 1, 'L');

        $this->pdf->SetY($y + 9);
        $this->pdf->SetLineWidth(0.2);
    }

    private function dibujarTarjeta($contenido, $colorBorde = null, $icono = '')
    {
        if ($colorBorde === null) {
            $colorBorde = $this->colorCafeSecundario;
        }

        $y = $this->pdf->GetY();
        $alturaBase = 30;

        // Sombra sutil
        $this->pdf->SetFillColor(200, 200, 200);
        $this->pdf->Rect(22, $y + 1, 170, $alturaBase, 'F');

        // Fondo principal de la tarjeta
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->Rect(20, $y, 170, $alturaBase, 'F');

        // Barra lateral colorida
        $this->pdf->SetFillColor($colorBorde[0], $colorBorde[1], $colorBorde[2]);
        $this->pdf->Rect(20, $y, 5, $alturaBase, 'F');

        // Borde superior decorativo
        $this->pdf->SetFillColor($colorBorde[0], $colorBorde[1], $colorBorde[2]);
        $this->pdf->Rect(25, $y, 165, 2, 'F');

        // Borde completo
        $this->pdf->SetDrawColor($colorBorde[0], $colorBorde[1], $colorBorde[2]);
        $this->pdf->SetLineWidth(0.3);
        $this->pdf->Rect(20, $y, 170, $alturaBase, 'D');

        // Contenido con mejor espaciado
        $this->pdf->SetXY(28, $y + 4);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->MultiCell(155, 4, $contenido, 0, 'L');

        // Icono si se proporciona
        if ($icono) {
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetXY(175, $y + 2);
            $this->pdf->SetTextColor($colorBorde[0], $colorBorde[1], $colorBorde[2]);
            $this->pdf->Cell(10, 6, $icono, 0, 0, 'C');
        }

        $this->pdf->SetY($y + $alturaBase + 4);
        $this->pdf->SetLineWidth(0.2);
    }

    private function dibujarFotografia($fotoPath, $x, $y, $ancho, $alto)
    {
        if (file_exists($fotoPath)) {
            // Marco decorativo
            $this->pdf->SetFillColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
            $this->pdf->Rect($x - 2, $y - 2, $ancho + 4, $alto + 4, 'F');

            // Marco dorado interior
            $this->pdf->SetFillColor($this->colorDorado[0], $this->colorDorado[1], $this->colorDorado[2]);
            $this->pdf->Rect($x - 1, $y - 1, $ancho + 2, $alto + 2, 'F');

            // Imagen
            $this->pdf->Image($fotoPath, $x, $y, $ancho, $alto);

            // Borde final elegante
            $this->pdf->SetDrawColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Rect($x - 2, $y - 2, $ancho + 4, $alto + 4, 'D');
        }
    }

    private function verificarNuevaPagina($alturaRequerida = 30)
    {
        if ($this->pdf->GetY() + $alturaRequerida > 250) {
            $this->pdf->AddPage();

            // Aplicar fondo a la nueva página
            $this->pdf->SetFillColor($this->colorBeige[0], $this->colorBeige[1], $this->colorBeige[2]);
            $this->pdf->Rect(0, 0, 210, 297, 'F');

            $this->pdf->SetY(30);
        }
    }

    public function generarFichaCabra($id_cabra)
    {
        $cabraModel = new Cabra($this->db);
        $partoModel = new Parto($this->db);
        $eventoModel = new EventoReproductivo($this->db);
        $historialModel = new HistorialPropiedad($this->db);
        $controlModel = new ControlSanitario($this->db);
        $docModel = new DocumentosCabras($this->db);

        $cabra = $cabraModel->getByIdFull($id_cabra);
        $partos = $partoModel->getByCabra($id_cabra);
        $eventos = $eventoModel->getByCabra($id_cabra);
        $historial = $historialModel->getByCabra($id_cabra);
        $controles = $controlModel->getByCabra($id_cabra);
        $documentos = $docModel->getByCabra($id_cabra);

        $this->configurarPDF();
        $this->dibujarEncabezado($cabra['nombre']);

        // INFORMACIÓN BÁSICA
        $this->dibujarSeccion('INFORMACION BASICA');



        $this->dibujarCampoDoble('ID', $cabra['id_cabra'], 'Nombre', $cabra['nombre']);
        $this->dibujarCampoDoble('Sexo', $cabra['sexo'], 'Color', $cabra['color']);
        $this->dibujarCampoDoble('Raza', $cabra['raza_nombre'], 'Nacimiento', $cabra['fecha_nacimiento']);
        $this->dibujarCampoDoble('Estado', $cabra['estado'], 'Fecha Registro', $cabra['fecha_registro']);



        if (!empty($cabra['foto'])) {
            $fotoPath = __DIR__ . '/../../public/uploads/' . $cabra['foto'];

            $this->pdf->Ln(3);
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
            $this->pdf->Cell(0, 6, 'FOTOGRAFIA :', 0, 1, 'L');
            $this->dibujarFotografia($fotoPath, 80, $this->pdf->GetY() + 2, 40, 40);
            $this->pdf->Ln(40);
        }


        $this->pdf->Ln(8);



        // GENEALOGÍA
        $this->dibujarSeccion('GENEALOGIA');

        $this->dibujarCampoDoble('Madre', $cabra['nombre_madre'] ?? 'No definida', 'Padre', $cabra['nombre_padre'] ?? 'No definido');
        $this->dibujarCampo('Propietario Actual', $cabra['propietario_nombre'] ?? 'No definido');

        // Mostrar imágenes de la madre y padre si existen
        $yPadres = $this->pdf->GetY();

        if (!empty($cabra['foto_madre']) || !empty($cabra['foto_padre'])) {
            if (!empty($cabra['foto_madre'])) {
                $fotoMadre = __DIR__ . '/../../public/uploads/' . $cabra['foto_madre'];
                $this->dibujarFotografia($fotoMadre, 40, $yPadres, 40, 40);
                $this->pdf->SetXY(40, $yPadres + 42);
                $this->pdf->SetFont('Arial', 'I', 8);
                $this->pdf->Cell(40, 5, 'Madre', 0, 0, 'C');
            }
            if (!empty($cabra['foto_padre'])) {
                $fotoPadre = __DIR__ . '/../../public/uploads/' . $cabra['foto_padre'];
                $this->dibujarFotografia($fotoPadre, 130, $yPadres, 40, 40);
                $this->pdf->SetXY(130, $yPadres + 42);
                $this->pdf->SetFont('Arial', 'I', 8);
                $this->pdf->Cell(40, 5, 'Padre', 0, 0, 'C');
            }

            $this->pdf->SetY($yPadres + 50);
        }


        // EVENTOS REPRODUCTIVOS
        $this->dibujarSeccion('EVENTOS REPRODUCTIVOS');

        if (empty($eventos)) {
            $this->pdf->SetFont('Arial', 'I', 11);
            $this->pdf->SetTextColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
            $this->pdf->Cell(0, 10, 'No hay eventos reproductivos registrados.', 0, 1, 'C');
            $this->pdf->Ln(5);
        } else {
            foreach ($eventos as $e) {
                $semental = $e['nombre_semental'] ?? 'No definido';
                $observaciones = $e['observaciones'] ?? 'Sin observaciones';
                $registradoPor = $e['nombre_usuario'] ?? 'Desconocido';

                $contenido = "FECHA: {$e['fecha_evento']} | TIPO: {$e['tipo_evento']}\n";
                $contenido .= "SEMENTAL: $semental\n";
                $contenido .= "OBSERVACIONES: $observaciones\n";
                $contenido .= "REGISTRADO POR: $registradoPor";

                $this->dibujarTarjeta($contenido, $this->colorVerde,);
                $this->verificarNuevaPagina();
            }
        }

        // HISTORIAL DE PARTOS
        $this->dibujarSeccion('HISTORIAL DE PARTOS');

        if (empty($partos)) {
            $this->pdf->SetFont('Arial', 'I', 11);
            $this->pdf->SetTextColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
            $this->pdf->Cell(0, 10, 'No hay partos registrados.', 0, 1, 'C');
            $this->pdf->Ln(5);
        } else {
            foreach ($partos as $p) {
                $padre = $p['nombre_padre'] ?? 'Desconocido';
                $peso = $p['peso_total_crias'] ?? 'No registrado';
                $dificultad = $p['dificultad'] ?? 'No especificada';
                $observaciones = $p['observaciones'] ?? 'Sin observaciones';
                $registradoPor = $p['nombre_usuario'] ?? 'Desconocido';

                $contenido = "FECHA: {$p['fecha_parto']} | PADRE: $padre\n";
                $contenido .= "CRIAS: {$p['numero_crias']} ({$p['tipo_parto']}) | PESO TOTAL: $peso kg\n";
                $contenido .= "DIFICULTAD: $dificultad\n";
                $contenido .= "OBSERVACIONES: $observaciones\n";
                $contenido .= "REGISTRADO POR: $registradoPor";

                $colorTarjeta = ($dificultad === 'Alta') ? $this->colorRojo : $this->colorVerde;
                $this->dibujarTarjeta($contenido, $colorTarjeta,);
                $this->verificarNuevaPagina();
            }
        }

        // CONTROLES SANITARIOS
        $this->dibujarSeccion('CONTROLES SANITARIOS');

        if (empty($controles)) {
            $this->pdf->SetFont('Arial', 'I', 11);
            $this->pdf->SetTextColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
            $this->pdf->Cell(0, 10, 'No hay controles sanitarios registrados.', 0, 1, 'C');
            $this->pdf->Ln(5);
        } else {
            foreach ($controles as $c) {
                $this->verificarNuevaPagina(60);

                // Tarjeta principal del control
                $contenido = "FECHA: {$c['fecha_control']}\n";
                $contenido .= "PESO ACTUAL: {$c['peso_kg']} kg | PESO NACIMIENTO: {$c['peso_nacer_kg']} kg\n";
                $contenido .= "FAMACHA: {$c['famacha']} | DRACK SCORE: {$c['drack_score']} | COND. CORPORAL: {$c['c_corporal']}\n";
                $contenido .= "OBSERVACIONES: " . ($c['observaciones'] ?? 'Sin observaciones');

                $this->dibujarTarjeta($contenido, $this->colorNaranja,);

                // Tabla de detalles con mejor diseño
                $this->pdf->SetFont('Arial', 'B', 10);
                $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
                $this->pdf->Cell(0, 8, 'DETALLES DEL EXAMEN CLINICO:', 0, 1, 'L');

                // Fondo para la tabla
                $y = $this->pdf->GetY();
                $this->pdf->SetFillColor($this->colorCrema[0], $this->colorCrema[1], $this->colorCrema[2]);
                $this->pdf->Rect(20, $y, 170, 64, 'F');

                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);

                $detalles = [
                    ['Genitales', $c['genitales'], 'Ubre', $c['ubre']],
                    ['Mucosas', $c['mucosas'], 'Orejas', $c['orejas']],
                    ['Vitaminacion', $c['vitaminacion'], 'Purga', $c['purga']],
                    ['Cascos', $c['cascos'], 'E. Interdigital', $c['e_interdigital']],
                    ['Pinzas', $c['pinzas'], '1ros Medios', $c['primeros_medios']],
                    ['2dos Medios', $c['segundos_medios'], 'Extremos', $c['extremos']],
                    ['Desgaste', $c['desgaste'], 'Perdidas Dentales', $c['perdidas_dentales']],
                    ['Sin Muda', $c['sin_muda'], 'Cond. Especial', $c['condicion_especial']]
                ];

                foreach ($detalles as $i => $fila) {
                    $yPos = $y + ($i * 8) + 2;

                    // Alternar color de fondo
                    if ($i % 2 == 0) {
                        $this->pdf->SetFillColor($this->colorBeige[0], $this->colorBeige[1], $this->colorBeige[2]);
                        $this->pdf->Rect(20, $yPos, 170, 8, 'F');
                    }

                    $this->pdf->SetXY(25, $yPos + 1);
                    $this->pdf->SetFont('Arial', 'B', 8);
                    $this->pdf->Cell(40, 6, $fila[0] . ':', 0, 0, 'L');
                    $this->pdf->SetFont('Arial', '', 8);
                    $this->pdf->Cell(40, 6, $fila[1], 0, 0, 'L');
                    $this->pdf->SetFont('Arial', 'B', 8);
                    $this->pdf->Cell(40, 6, $fila[2] . ':', 0, 0, 'L');
                    $this->pdf->SetFont('Arial', '', 8);
                    $this->pdf->Cell(40, 6, $fila[3], 0, 1, 'L');
                }

                $this->pdf->SetY($y + 66);

                // Foto de ubre si existe
                if (!empty($c['foto_ubre'])) {
                    $fotoPath = __DIR__ . '/../../public/uploads/' . $c['foto_ubre'];
                    if (file_exists($fotoPath)) {
                        $this->pdf->Ln(3);
                        $this->pdf->SetFont('Arial', 'B', 10);
                        $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
                        $this->pdf->Cell(0, 6, 'FOTOGRAFIA DE UBRE:', 0, 1, 'L');
                        $this->dibujarFotografia($fotoPath, 25, $this->pdf->GetY() + 2, 45, 35);
                        $this->pdf->Ln(40);
                    }
                }

                $this->pdf->Ln(8);
            }
        }

        // HISTORIAL DE PROPIEDAD
        $this->dibujarSeccion('HISTORIAL DE PROPIEDAD');

        if (empty($historial)) {
            $this->pdf->SetFont('Arial', 'I', 11);
            $this->pdf->SetTextColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
            $this->pdf->Cell(0, 10, 'No hay historial de propiedad registrado.', 0, 1, 'C');
        } else {
            foreach ($historial as $h) {
                $hasta = $h['fecha_fin'] ?? 'ACTUAL';
                $motivo = !empty($h['motivo_cambio']) ? $h['motivo_cambio'] : 'No especificado';
                $precio = !empty($h['precio_transaccion']) ? '$' . number_format($h['precio_transaccion'], 2) : 'No registrado';

                $contenido = "PROPIETARIO: {$h['nombre_propietario']}\n";
                $contenido .= "PERIODO: {$h['fecha_inicio']} - $hasta\n";
                $contenido .= "MOTIVO: $motivo | PRECIO: $precio";

                $colorTarjeta = ($hasta === 'ACTUAL') ? $this->colorVerde : $this->colorTierra;
                $this->dibujarTarjeta($contenido, $colorTarjeta,);
                $this->verificarNuevaPagina();
            }
        }

        // PIE DE PÁGINA ELEGANTE
        $this->dibujarPiePagina($cabra['nombre']);

        // Salida del PDF
        ob_clean();
        $this->pdf->Output('I', 'Ficha_' . $cabra['nombre'] . '.pdf');
        exit;
    }

    private function dibujarPiePagina($nombreCabra)
    {
        $this->pdf->SetY(-40);

        // Línea decorativa
        $this->pdf->SetDrawColor($this->colorDorado[0], $this->colorDorado[1], $this->colorDorado[2]);
        $this->pdf->SetLineWidth(1);
        $this->pdf->Line(20, $this->pdf->GetY(), 190, $this->pdf->GetY());

        $this->pdf->Ln(5);

        // Información del documento
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor($this->colorCafePrimario[0], $this->colorCafePrimario[1], $this->colorCafePrimario[2]);
        $this->pdf->Cell(0, 5, 'HOJA DE VIDA - ' . strtoupper($nombreCabra), 0, 1, 'C');

        // Fecha de generación
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetTextColor($this->colorTierra[0], $this->colorTierra[1], $this->colorTierra[2]);
        $this->pdf->Cell(0, 4, 'Generado el: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

        // Número de página
        $this->pdf->SetFont('Arial', 'I', 8);
        $this->pdf->Cell(0, 4, 'Pagina ' . $this->pdf->PageNo(), 0, 1, 'C');

        // Mensaje de autenticidad
        $this->pdf->SetFont('Arial', '', 7);
        $this->pdf->SetTextColor($this->colorMarron[0], $this->colorMarron[1], $this->colorMarron[2]);
        $this->pdf->Cell(0, 3, 'Documento generado automaticamente por el Sistema de Gestion Caprina', 0, 1, 'C');
    }
}
