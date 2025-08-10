# Configuración de Google OAuth para Sacramentify

## Paso 1: Crear un proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. En el nombre del proyecto, usa algo como "Sacramentify Auth"

## Paso 2: Habilitar Google+ API

1. En el panel de navegación, ve a "APIs y servicios" > "Biblioteca"
2. Busca "Google+ API" y habilítala
3. También busca y habilita "Google People API" (opcional pero recomendado)

## Paso 3: Configurar la pantalla de consentimiento OAuth

1. Ve a "APIs y servicios" > "Pantalla de consentimiento OAuth"
2. Selecciona "Externo" como tipo de usuario
3. Completa la información requerida:
   - Nombre de la aplicación: "Sistema de Gestión de Sacramentos"
   - Email de soporte del usuario: tu email
   - Dominio autorizado: localhost (para desarrollo)
   - Email de contacto del desarrollador: tu email

## Paso 4: Crear credenciales OAuth 2.0

1. Ve a "APIs y servicios" > "Credenciales"
2. Haz clic en "Crear credenciales" > "ID de cliente OAuth 2.0"
3. Selecciona "Aplicación web" como tipo de aplicación
4. Configura los campos:
   - Nombre: "Sacramentify Web Client"
   - Orígenes de JavaScript autorizados: 
     ```
     http://localhost
     http://localhost:8000
     http://127.0.0.1:8000
     ```
   - URI de redirección autorizados:
     ```
     http://localhost/sacramentify/public/auth/google/callback
     http://localhost:8000/auth/google/callback
     http://127.0.0.1:8000/auth/google/callback
     ```

## Paso 5: Configurar las variables de entorno

1. Copia el "ID de cliente" y "Secreto del cliente" que acabas de crear
2. Abre el archivo `.env` en tu proyecto Laravel
3. Actualiza las siguientes líneas con tus credenciales:

```env
# Google OAuth
GOOGLE_CLIENT_ID=tu_client_id_aqui
GOOGLE_CLIENT_SECRET=tu_client_secret_aqui
GOOGLE_REDIRECT_URL=http://localhost/sacramentify/public/auth/google/callback
```

**Importante:** Ajusta la URL de redirección según tu configuración local.

## Paso 6: Configurar el entorno local

### Opción A: XAMPP (Recomendado para tu setup actual)
Si usas XAMPP, la URL debería ser:
```env
GOOGLE_REDIRECT_URL=http://localhost/sacramentify/public/auth/google/callback
```

### Opción B: Artisan Serve
Si usas `php artisan serve`, la URL debería ser:
```env
GOOGLE_REDIRECT_URL=http://127.0.0.1:8000/auth/google/callback
```

## Paso 7: Probar la configuración

1. Asegúrate de que tu servidor web esté ejecutándose
2. Ve a la página de login: `http://localhost/sacramentify/public/login`
3. Deberías ver el botón "Continuar con Google"
4. Haz clic en él para probar la autenticación

## Funcionalidades Implementadas

### 🔐 Autenticación Segura
- Inicio de sesión con Google OAuth 2.0
- Creación automática de usuarios nuevos
- Vinculación de cuentas existentes

### 👤 Gestión de Usuarios
- Avatar de Google automático en la interfaz
- Campos adicionales: `google_id`, `avatar`
- Verificación automática de email para usuarios de Google

### 🔒 Seguridad
- Passwords aleatorios para usuarios OAuth
- Verificación de email automática
- Permisos de administrador preservados
- **Control de super administrador**: Solo `adrianagm291104@gmail.com` puede promocionar otros usuarios como administradores
- **Middleware de protección**: Rutas administrativas sensibles protegidas por middleware especializado

## Solución de Problemas

### Error: "redirect_uri_mismatch"
- Verifica que la URL de redirección en Google Console coincida exactamente con la de tu `.env`
- Asegúrate de incluir `http://` o `https://`
- No olvides incluir el puerto si es necesario

### Error: "Client ID not found"
- Verifica que hayas copiado correctamente el Client ID
- Asegúrate de que no haya espacios extra en el archivo `.env`

### Error: "Access blocked"
- Asegúrate de haber configurado la pantalla de consentimiento OAuth
- Verifica que tu email esté agregado como usuario de prueba (si es necesario)

## Próximos Pasos Sugeridos

1. **Producción**: Configura un dominio real para producción
2. **Seguridad**: Implementa verificación en dos pasos
3. **UX**: Agrega más providers OAuth (Facebook, Microsoft)
4. **Roles**: Configura roles automáticos para usuarios de Google de tu organización

## Notas Importantes

- **Desarrollo**: En desarrollo, Google permite hasta 100 usuarios de prueba
- **Producción**: Para producción necesitarás verificar la aplicación con Google
- **Privacidad**: Revisa las políticas de privacidad para el manejo de datos de Google
- **HTTPS**: En producción, Google requiere HTTPS para OAuth

---

¡La implementación está completa y lista para usar! 🎉
