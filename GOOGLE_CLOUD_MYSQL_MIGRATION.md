# 🚀 Migración a PlanetScale MySQL - Guía Completa

## 📋 Resumen
Esta guía te llevará paso a paso para migrar tu base de datos local de XAMPP a PlanetScale (MySQL en la nube), permitiendo acceso desde aplicaciones web, móviles y de escritorio.

## 🎯 Beneficios de esta migración
- ✅ **100% GRATUITO** - Sin tarjeta de crédito
- ✅ Acceso desde cualquier dispositivo/aplicación
- ✅ Base de datos disponible 24/7
- ✅ Respaldos automáticos
- ✅ 10GB gratis para desarrollo
- ✅ MySQL compatible (sin cambios en tu código)
- ✅ Escalabilidad para tu proyecto universitario
- ✅ Resuelve problemas de conectividad entre apps

---

## 📂 FASE 1: Preparación y Respaldo Local

### 1.1 Crear respaldo de tu base de datos actual
```bash
# Desde tu terminal en el directorio del proyecto
mysqldump -u root -p sacramentify > backup_sacramentify_local.sql
```

### 1.2 Verificar estado actual de migraciones
```bash
php artisan migrate:status
```

### 1.3 Crear archivo de configuración adicional
Crear `.env.planetscale` para mantener configuraciones separadas:
```env
# Configuración para PlanetScale MySQL
DB_CONNECTION=mysql
DB_HOST=[HOST_DE_PLANETSCALE]
DB_PORT=3306
DB_DATABASE=[DATABASE_NAME]
DB_USERNAME=[USERNAME_PLANETSCALE]
DB_PASSWORD=[PASSWORD_PLANETSCALE]
DB_SSLMODE=require
```

---

## ☁️ FASE 2: Configuración en PlanetScale

### 2.1 Crear cuenta en PlanetScale
1. Ir a [PlanetScale](https://planetscale.com/)
2. **Sign up** con GitHub, Google, o email
3. ✅ **100% Gratis** - No necesitas tarjeta de crédito
4. ✅ **10GB de storage gratis**
5. ✅ **1 billón de lecturas gratis por mes**

### 2.2 Crear base de datos
1. En el dashboard, hacer clic en **New database**
2. **Database name**: `sacramentify`
3. **Region**: Seleccionar la más cercana (ej: `us-east`)
4. **Plan**: Hobby (Gratuito)
5. Hacer clic en **Create database**

### 2.3 Crear branch principal
1. PlanetScale creará automáticamente un branch `main`
2. Este será tu entorno de producción
3. ✅ **Nota**: PlanetScale usa branches como Git para la base de datos

### 2.4 Obtener credenciales de conexión
1. En tu base de datos > **Connect**
2. Seleccionar **General** como framework
3. Copiar las credenciales:
   - **Host**: `aws.connect.psdb.cloud`
   - **Username**: `[generated-username]`
   - **Password**: `[generated-password]`
   - **Database**: `sacramentify`

---

## 📊 FASE 3: Migración de Datos

### 3.1 Actualizar configuración local
Actualizar tu `.env` con los datos de PlanetScale:
```env
# PlanetScale MySQL Configuration
DB_CONNECTION=mysql
DB_HOST=aws.connect.psdb.cloud
DB_PORT=3306
DB_DATABASE=sacramentify
DB_USERNAME=[TU_USERNAME_PLANETSCALE]
DB_PASSWORD=[TU_PASSWORD_PLANETSCALE]
DB_SSLMODE=require
```

### 3.2 Instalar certificado SSL (si es necesario)
PlanetScale requiere SSL, pero Laravel ya lo maneja automáticamente.

### 3.3 Probar conexión
```bash
# Probar conexión a PlanetScale
php artisan migrate:status
```

### 3.4 Ejecutar migraciones en PlanetScale
```bash
# Crear todas las tablas en PlanetScale
php artisan migrate:fresh
```

### 3.5 Migrar datos existentes (si tienes datos importantes)
**Opción 1: Usar seeders (Recomendado)**
```bash
php artisan db:seed
```

**Opción 2: Importar desde backup local**
⚠️ **Nota**: PlanetScale no permite importación directa via MySQL CLI. 
Usar método alternativo:
```bash
# 1. Crear script de migración temporal
php artisan make:command MigrateLocalData

# 2. En el comando, leer datos locales y insertarlos
# 3. Ejecutar el comando una vez
```

---

## 🧪 FASE 4: Verificación y Pruebas

### 4.1 Verificar migración exitosa
```bash
# Verificar que todas las migraciones están aplicadas
php artisan migrate:status

# Verificar conexión
php artisan tinker
# En tinker ejecutar:
# DB::connection()->getPdo();
# User::count();
```

### 4.2 Probar funcionalidad de la aplicación
```bash
# Iniciar servidor local
php artisan serve
```
- Probar login/registro
- Verificar que los datos se guardan correctamente
- Probar funcionalidades principales

### 4.3 Configurar SSL (Automático)
PlanetScale maneja SSL automáticamente. No necesitas configuración adicional.

### 4.4 Verificar performance
```bash
# Probar velocidad de consultas
php artisan tinker
# En tinker:
# use Illuminate\Support\Facades\DB;
# $start = microtime(true);
# User::count();
# echo "Tiempo: " . (microtime(true) - $start) . " segundos";
```

---

## 🛡️ FASE 5: Seguridad y Optimización

### 5.1 Configurar backup automático
✅ **PlanetScale hace backups automáticos** - No necesitas configurar nada
- Backups diarios automáticos
- Retención de 7 días en plan gratuito
- Restauración con un clic

### 5.2 Configurar monitoreo
1. En el dashboard de PlanetScale revisar métricas:
   - **Queries per second**
   - **Storage usage**
   - **Connection count**
2. ✅ **Alertas automáticas** incluidas

### 5.3 Optimizar performance
- ✅ **Auto-scaling** incluido
- ✅ **Connection pooling** automático
- ✅ **Query optimization** automática
- **Nota**: PlanetScale maneja todo automáticamente

---

## 📱 FASE 6: Preparación para Apps Móviles/Escritorio

### 6.1 Configurar variables de entorno para múltiples apps
Tu configuración actual funcionará para:
- ✅ **Web App Laravel**: Acceso directo
- ✅ **Mobile App**: Via API endpoints
- ✅ **Desktop App**: Via API o conexión directa

### 6.2 Crear endpoints API para móvil (cuando sea necesario)
```php
// routes/api.php - Ya preparado para futuras apps
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Aquí agregarás endpoints para móvil
});
```

---

## 💰 Estimación de Costos

### Desarrollo/Universidad (Plan Hobby - GRATUITO)
- **Storage**: 10GB = $0/mes 
- **Queries**: 1 billón lecturas = $0/mes
- **Backups**: 7 días retención = $0/mes
- **Conexiones**: Ilimitadas = $0/mes
- **Total**: **$0/mes** 🎉

### Si necesitas más (Plan Scaler)
- **Storage adicional**: $2.50/GB/mes
- **Queries adicionales**: $1/millón
- **Total estimado**: $10-25/mes (solo si creces mucho)

---

## 🚨 Solución de Problemas Comunes

### Error de conexión SSL
```env
# Asegurar SSL en .env
DB_SSLMODE=require
```

### Error "too many connections"
```env
# PlanetScale tiene connection pooling automático
# Si persiste, contactar soporte (responden rápido)
```

### Error de sintaxis SQL
```bash
# PlanetScale usa MySQL 8.0 compatible
# Verificar que las migraciones sean compatibles
php artisan migrate:status --pretend
```

### Lentitud en conexión
```env
# Verificar región más cercana en dashboard
# PlanetScale optimiza automáticamente las rutas
```

---

## ✅ Checklist Final

- [ ] Cuenta PlanetScale creada
- [ ] Base de datos `sacramentify` creada
- [ ] Credenciales de conexión obtenidas
- [ ] `.env` actualizado con credenciales PlanetScale
- [ ] SSL configurado automáticamente
- [ ] Migraciones ejecutadas exitosamente
- [ ] Datos migrados (si aplicable)
- [ ] Aplicación web funcionando
- [ ] Performance verificada
- [ ] Monitoreo revisado en dashboard

---

## 📞 Próximos Pasos

Una vez completada la migración:
1. **Desarrollar API endpoints** para aplicaciones móviles
2. **Crear aplicación móvil** con React Native/Expo
3. **Desarrollar aplicación de escritorio** con Electron o similar
4. **Implementar autenticación compartida** entre apps
5. **Configurar notificaciones push** para móvil

---

## 📚 Recursos Adicionales

- [PlanetScale Documentation](https://planetscale.com/docs)
- [PlanetScale Laravel Guide](https://planetscale.com/docs/tutorials/laravel-quickstart)
- [Laravel Database Configuration](https://laravel.com/docs/10.x/database)
- [PlanetScale CLI](https://planetscale.com/docs/concepts/planetscale-cli) (opcional)

---

**¿Listo para comenzar? ¡Empecemos con la FASE 1!** 🚀

### 🎯 Ventajas de PlanetScale sobre otras opciones:
- ✅ **100% Gratuito** para desarrollo
- ✅ **Sin tarjeta de crédito**
- ✅ **MySQL nativo** (tu código funciona sin cambios)
- ✅ **Branching** como Git para bases de datos
- ✅ **Backups automáticos**
- ✅ **Scaling automático**
- ✅ **Support 24/7** incluido
