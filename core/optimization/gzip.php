<?php
	/** BROWSER CACHE EXPIRES & GZIP COMPRESSION **/
	function quecodig_htaccess() {
		// We get the main WordPress .htaccess filepath.
		$ruta_htaccess = get_home_path() . '.htaccess'; // https://codex.wordpress.org/Function_Reference/get_home_path !
		$lineas = array();
		$lineas[] = '<IfModule mod_expires.c>';
		$lineas[] = '# Activar caducidad de contenido';
		$lineas[] = 'ExpiresActive On';
		$lineas[] = '# Directiva de caducidad por defecto';
		$lineas[] = 'ExpiresDefault "access plus 1 month"';
		$lineas[] = '# Para el favicon';
		$lineas[] = 'ExpiresByType image/x-icon "access plus 1 year"';
		$lineas[] = '# Imagenes';
		$lineas[] = 'ExpiresByType image/gif "access plus 1 month"';
		$lineas[] = 'ExpiresByType image/png "access plus 1 month"';
		$lineas[] = 'ExpiresByType image/jpg "access plus 1 month"';
		$lineas[] = 'ExpiresByType image/jpeg "access plus 1 month"';
		$lineas[] = '# CSS';
		$lineas[] = 'ExpiresByType text/css "access 1 month"';
		$lineas[] = '# Javascript';
		$lineas[] = 'ExpiresByType application/javascript "access plus 1 year"';
		$lineas[] = '</IfModule>';
		$lineas[] = '<IfModule mod_deflate.c>';
		$lineas[] = '# Activar compresión de contenidos estáticos';
		$lineas[] = 'AddOutputFilterByType DEFLATE text/plain text/html';
		$lineas[] = 'AddOutputFilterByType DEFLATE text/xml application/xml application/xhtml+xml application/xml-dtd';
		$lineas[] = 'AddOutputFilterByType DEFLATE application/rdf+xml application/rss+xml application/atom+xml image/svg+xml';
		$lineas[] = 'AddOutputFilterByType DEFLATE text/css text/javascript application/javascript application/x-javascript';
		$lineas[] = 'AddOutputFilterByType DEFLATE font/otf font/opentype application/font-otf application/x-font-otf';
		$lineas[] = 'AddOutputFilterByType DEFLATE font/ttf font/truetype application/font-ttf application/x-font-ttf';
		$lineas[] = '</IfModule>';
		insert_with_markers( $ruta_htaccess, 'Qué Código WP', $lineas ); // https://developer.wordpress.org/reference/functions/insert_with_markers/ !
	}
	function quecodig_delete_htaccess() {
		// We get the mail WordPress .htaccess filepath.
		$ruta_htaccess = get_home_path() . '.htaccess'; // https://codex.wordpress.org/Function_Reference/get_home_path !
		$lineas = array();
		$lineas[] = '# Optimizaciones eliminadas al desactivar el plugin';
		insert_with_markers( $ruta_htaccess, 'Qué Código WP', $lineas ); // https://developer.wordpress.org/reference/functions/insert_with_markers/ !
	}