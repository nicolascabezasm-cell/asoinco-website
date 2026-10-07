# ASOINCO — Sitio Web

Repositorio del código personalizado del sitio [asoinco.com](https://asoinco.com).

## Estructura

```
theme/          → Tema WordPress personalizado (asoinco)
mu-plugins/     → Must-use plugins con la lógica de negocio
```

## Stack

- **CMS**: WordPress 6.x
- **Hosting**: cPanel compartido — blue200.dnsmisitio.net
- **Dominio**: asoinco.com
- **PHP**: 8.x
- **DB**: MySQL — prefijo de tablas `wphn_`

## Tema (`theme/`)

| Archivo | Descripción |
|---|---|
| `front-page.php` | Página de inicio |
| `page-noticias.php` | Listado de noticias |
| `single.php` | Artículo individual (con imagen en hero + texto justificado) |
| `page-convenios.php` | Club de Beneficios con lightbox |
| `page-directorio.php` | Directorio de socios |
| `page-expo.php` | Expo ASOINCO |
| `page-hazte-socio.php` | Formulario de afiliación |
| `page-quienes-somos.php` | Quiénes somos |
| `page-contacto.php` | Contacto |
| `page-publica-noticia.php` | Formulario de publicación para socios |
| `functions.php` | Funciones y enqueue de assets |
| `header.php` / `footer.php` | Cabecera y pie de página |
| `style.css` | Hoja de estilos principal + variables CSS |

## Must-Use Plugins (`mu-plugins/`)

| Archivo | Descripción |
|---|---|
| `asoinco-setup.php` | Configuración base (menús, imágenes, etc.) |
| `asoinco-noticias.php` | CPT "noticia" + campos personalizados |
| `asoinco-socios.php` | CPT "socio" + taxonomía por rubro |
| `asoinco-convenios.php` | CPT "convenio" (Club de Beneficios) |
| `asoinco-expo.php` | CPT "expositor" para Expo ASOINCO |
| `asoinco-forms.php` | Formulario de afiliación |
| `asoinco-admin.php` | Personalizaciones del panel de administración |
| `asoinco-cleanup.php` | Limpieza de funciones por defecto de WordPress |

## Instalación local

1. Instalar [Local by Flywheel](https://localwp.com/)
2. Crear un sitio WordPress nuevo
3. Reemplazar `wp-content/themes/` con la carpeta `theme/` (renombrarla a `asoinco`)
4. Copiar los archivos de `mu-plugins/` a `wp-content/mu-plugins/`
5. Importar la base de datos de producción (solicitar al equipo Pulso21)

## Contacto técnico

Desarrollo: Pulso21 — [pulso21.com](https://pulso21.com)
