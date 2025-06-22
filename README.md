# Arzuaga CRUD Generator for Laravel

**Generador artesanal de CRUDs para Laravel**. Este paquete te permite generar de forma rápida y limpia modelos, controladores, requests, factories, tests, migraciones y rutas para cualquier entidad.

---

## 🚀 Instalación

Requiere Laravel **8.x a 12.x** y PHP **>= 8.0**

```bash
composer require arzuaga/crud-generator:^1.1.4
```
📦 Publicar los stubs
Si deseas utilizar las plantillas por defecto, utiliza los archivos generados:
```bash
php artisan vendor:publish --tag=crud-generator-stubs
```
Esto copiará los archivos a resources/stubs, donde podés personalizarlos.

## ⚙️ Uso
```bash
php artisan crud:generator Company
```
Siendo Company el nombre del modelo. Esto generará, por defecto los siguientes elementos:
1. Modelo (m)
2. Controlador (c)
3. Resource (r)
4. Factory (f)
5. Migration (i)
4. (no por defecto) Test (t)

Pero estos parámetros son editables. Con la propiedad --create={} puedes generar lo que necesites.
### 📄 Ejemplos
```angular2html
php artisan crud:generator Company --create=mrc
```
El ejemplo anterior solo generará el Modelo, Resource y Controlador.

Por defecto siempre se agregará a la ruta (routes/api.php) del proyecto en formato de Resource.
## Cualquier duda o sugerencia, escribir a aarzuagat@gmail.com