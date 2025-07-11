<?php
// index.php convertido desde index.html para procesar correctamente PHP
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OPREC</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="css_proyecto/stilos.css">
  <link rel="stylesheet" href="css_proyecto/responsive.css">
  <script>
    /*  PARALLAX  */
    window.addEventListener('scroll', function() {
      const header = document.querySelector('.header');
      const scrolled = window.scrollY;
      const headerHeight = header.offsetHeight;
      if (scrolled < headerHeight) {
        const scrollProgress = scrolled / headerHeight;
        const waveOffset = Math.sin(scrolled * 0.01) * 15;
        const parallaxSpeed = scrolled * 0.5;
        header.style.backgroundPosition = `center ${parallaxSpeed + waveOffset}px`;
        const maxRotation = 8;
        const rotationX = scrollProgress * maxRotation;
        header.style.transform = `perspective(1000px) rotateX(${rotationX}deg)`;
        const overlayOpacity = Math.min(0.3 + (scrollProgress * 0.3), 0.6);
        header.style.setProperty('--overlay-opacity', overlayOpacity);
      } else {
        header.style.backgroundPosition = `center ${headerHeight * 0.5}px`;
        header.style.transform = `perspective(1000px) rotateX(8deg)`;
        header.style.setProperty('--overlay-opacity', 0.6);
      }
    });
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.image-container img').forEach(function(img) {
        if (img.closest('.imagen-full')) return;
        img.addEventListener('mouseenter', function() {
          img.style.transform = 'translateY(-24px) scale(1.04)';
          img.style.boxShadow = '0 12px 32px rgba(21,101,192,0.18), 0 2px 12px rgba(0,0,0,0.10)';
        });
        img.addEventListener('mouseleave', function() {
          img.style.transform = 'none';
          img.style.boxShadow = '0 2px 12px rgba(0,0,0,0.08)';
        });
      });
    });
  </script>
</head>
<body>
  <div class="menu">
    <a href="Desarrollo/creacion.php" class="action-button">
      <p>Crear cuenta</p>
    </a>
    <a href="Desarrollo/iniciosesion.php" class="action-button">
      <p>Iniciar sesión</p>
    </a>
  </div>
  <div class="header">
    <div class="header-content">
      <a class="logo">
        <i class="fas fa-leaf"></i>
        <h1>OPREC</h1>
      </a>
      <p>Transformando para un</p>
      <p>mundo nuevo</p>
    </div>
  </div>
  <div class="main-section">
    <div class="image-container">
      <img src="imagenes/img_petic_1.jpg" alt="Reciclaje">
    </div>
    <div class="text-section">
      <h2>¿Que es OPREC?</h2>
      <p>
        OPREC es un proyecto educativo que busca fomentar la conciencia ambiental y el reciclaje en las escuelas. A través de actividades prácticas y recursos educativos, OPREC promueve la sostenibilidad y el cuidado del medio ambiente entre los estudiantes, formando líderes comprometidos con el planeta.
      </p>
    </div>
  </div>
  <div class="main-section imagen-full">
    <div class="image-container">
      <img src="imagenes/img_petic_2.jpg" alt="Reciclaje">
      <div class="text-overlay">
        <h1>VISÓN DE OPREC</h1>
        <p>
          Ser una comunidad educativa comprometida con el medio ambiente, en la que el reciclaje sea una práctica habitual que fomente la conciencia ecológica, la responsabilidad social y la sostenibilidad, formando estudiantes líderes en el cuidado del planeta desde su entorno escolar.
        </p>
      </div>
    </div>
  </div>
  <div class="main-section imagen-full spacing-top">
    <div class="image-container">
      <img src="imagenes/img_petic_3.jpg" alt="Misión PETIC">
      <div class="text-overlay-left">
        <h1>MISIÓN DE OPREC</h1>
        <p>
          Promover la educación ambiental y el reciclaje en la comunidad estudiantil, proporcionando herramientas y recursos educativos que faciliten la adopción de prácticas sostenibles, desarrollando conciencia ecológica y formando ciudadanos responsables con el medio ambiente.
        </p>
      </div>
    </div>
  </div>
  <footer class="footer-petic">
    <div class="footer-container">
      <div class="footer-column">
        <h3>Examinar</h3>
        <ul>
          <li><a href="#">Actividades escolares</a></li>
          <li><a href="#">Proyectos de reciclaje</a></li>
          <li><a href="#">Talleres ambientales</a></li>
          <li><a href="#">Recursos educativos</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>Recursos</h3>
        <ul>
          <li><a href="#">Noticias de OPREC</a></li>
          <li><a href="#">Soporte técnico</a></li>
          <li><a href="#">Comentarios</a></li>
          <li><a href="#">Estadísticas de la comunidad</a></li>
          <li><a href="#">Advertencia sobre residuos peligrosos</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>Comunidad OPREC</h3>
        <ul>
          <li><a href="#">Cuenta de estudiante</a></li>
          <li><a href="#">Soporte técnico</a></li>
          <li><a href="#">Devoluciones</a></li>
          <li><a href="#">Seguimiento de proyectos</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>Para educadores</h3>
        <ul>
          <li><a href="#">Guías pedagógicas</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-bottom-left">
        <div class="language-selector">
          <i class="fas fa-globe"></i>
          <span>Español (México)</span>
        </div>
        <div class="privacy-toggle">
          <label class="switch">
            <input type="checkbox" checked>
            <span class="slider"></span>
          </label>
          <span>Tus opciones de privacidad</span>
        </div>
      </div>
      <div class="footer-bottom-right">
        <a href="#">Ponte en contacto con OPREC</a>
        <a href="#">Privacidad y cookies</a>
        <a href="#">Aviso legal</a>
        <a href="#">Marcas Registradas</a>
        <a href="#">Acerca de terceros</a>
        <a href="#">Sobre nuestra publicidad</a>
        <span>© OPREC 2025</span>
      </div>
    </div>
  </footer>
</body>
</html>
