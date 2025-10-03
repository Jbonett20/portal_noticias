<?php
// Script para insertar noticias de prueba
require_once 'config/config.php';
require_once 'config/Database.php';

$db = Database::getInstance();

// Noticias de prueba
$noticias = [
    [
        'title' => 'Nuevo restaurante japonés abre sus puertas en la Zona Rosa',
        'content' => 'Un nuevo restaurante especializado en comida japonesa tradicional ha abierto sus puertas en la exclusiva Zona Rosa de la ciudad. El establecimiento, llamado "Sakura Sushi", promete ofrecer una experiencia culinaria auténtica con ingredientes frescos importados directamente desde Japón.

El chef principal, Hiroshi Tanaka, tiene más de 15 años de experiencia en restaurantes de Tokio y ha traído consigo recetas tradicionales que han sido transmitidas por generaciones en su familia.

El menú incluye una amplia variedad de sushi, sashimi, ramen y otros platos típicos japoneses. También cuentan con una selección especial de sake y té verde premium.

El restaurante está ubicado en la Avenida Principal #123 y estará abierto de martes a domingo de 12:00 PM a 10:00 PM.',
        'summary' => 'Sakura Sushi, un nuevo restaurante japonés con chef tradicional de Tokio, abre en la Zona Rosa ofreciendo auténtica cocina japonesa.',
        'slug' => 'nuevo-restaurante-japones-zona-rosa',
        'author_id' => 1,
        'is_published' => 1,
        'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'title' => 'Pizza Express lanza servicio de delivery 24 horas',
        'content' => 'La popular cadena de pizzerías Pizza Express ha anunciado el lanzamiento de su nuevo servicio de entrega a domicilio las 24 horas del día, los 7 días de la semana.

Este nuevo servicio está disponible en toda la zona metropolitana y promete entregar pizzas frescas y calientes en un tiempo máximo de 30 minutos.

"Entendemos que nuestros clientes pueden tener antojos de pizza a cualquier hora del día o de la noche", comentó María González, gerente regional de Pizza Express. "Por eso hemos decidido expandir nuestros horarios para satisfacer esta demanda".

El servicio incluye todo el menú regular de la pizzería, desde las pizzas clásicas hasta las especialidades gourmet, además de entrantes, ensaladas y postres.

Para celebrar el lanzamiento, Pizza Express está ofreciendo un 20% de descuento en todas las órdenes realizadas entre las 10:00 PM y las 6:00 AM durante el mes de octubre.',
        'summary' => 'Pizza Express introduce servicio de delivery 24/7 en la zona metropolitana con promoción de 20% de descuento nocturno.',
        'slug' => 'pizza-express-delivery-24-horas',
        'author_id' => 1,
        'is_published' => 1,
        'published_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ],
    [
        'title' => 'Café Central gana premio al mejor café de especialidad',
        'content' => 'El reconocido establecimiento Café Central ha sido galardonado con el premio "Mejor Café de Especialidad 2025" en la competencia nacional de cafeterías.

El premio reconoce la excelencia en la preparación de café, la calidad de los granos utilizados y la experiencia general del cliente. Café Central compitió contra más de 200 establecimientos de todo el país.

"Estamos muy emocionados de recibir este reconocimiento", expresó Carlos Mendoza, propietario de Café Central. "Hemos trabajado muy duro durante los últimos cinco años para perfeccionar nuestras técnicas de preparación y seleccionar los mejores granos de café de la región".

El café utiliza granos orgánicos cultivados en las montañas locales y emplea métodos de preparación artesanales que incluyen pour-over, prensa francesa y espresso tradicional.

Como parte de la celebración, Café Central estará ofreciendo degustaciones gratuitas de su café ganador todos los fines de semana del mes de octubre.',
        'summary' => 'Café Central recibe el premio nacional "Mejor Café de Especialidad 2025" por su excelencia en preparación y calidad de granos orgánicos.',
        'slug' => 'cafe-central-premio-mejor-cafe-especialidad',
        'author_id' => 1,
        'is_published' => 1,
        'published_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s')
    ]
];

try {
    foreach ($noticias as $noticia) {
        $result = $db->insert('news', $noticia);
        if ($result) {
            echo "✅ Noticia creada: " . $noticia['title'] . "\n";
        } else {
            echo "❌ Error creando: " . $noticia['title'] . "\n";
        }
    }
    echo "\n🎉 Noticias de prueba creadas exitosamente!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>