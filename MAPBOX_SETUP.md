# 🚀 Configuración Rápida de Mapbox

## Paso 1: Obtener Access Token

1. Ve a [mapbox.com](https://mapbox.com)
2. Haz clic en "Sign up" (Registrarse)
3. Completa el registro con tu email
4. **NO necesitas tarjeta de crédito**
5. Ve a tu Dashboard
6. Copia el "Default public token"

## Paso 2: Configurar en tu proyecto

Abre tu archivo `.env` y agrega esta línea:

```env
MAPBOX_ACCESS_TOKEN=pk.eyJ1IjoiTU9fSElKTyIsImEiOiJjbHNhNzBpNTEwNXFwMm1xZ2pqb3d2ODZ0IYN2J9.ejemplo_token_aqui
```

**Reemplaza** `pk.eyJ1...` con tu token real de Mapbox.

## Paso 3: Limpiar caché

Ejecuta en tu terminal:

```bash
php artisan config:cache
```

## ¡Ya está listo! 🎉

### ¿Qué tienes ahora con Mapbox?

✅ **50,000 cargas gratis por mes**
✅ **Mapas hermosos y modernos**
✅ **Búsqueda mundial precisa**
✅ **Geocodificación en español**
✅ **Marcadores arrastrables**
✅ **Controles de navegación**
✅ **Geolocalización automática**
✅ **Animaciones suaves**

### Características premium incluidas:

🗺️ **Estilos de mapa**: Streets, Satellite, Outdoors, etc.
📍 **Marcadores personalizables**: Colores, iconos, etc.
🔍 **Búsqueda inteligente**: Autocompletado y sugerencias
🌍 **Cobertura global**: Perfecto para México
📱 **Responsive**: Funciona en móviles
🎨 **UI moderna**: Interfaz elegante

## Ventajas sobre OpenStreetMap:

- ✅ Mapas más bonitos y profesionales
- ✅ Geocodificación más precisa
- ✅ Mejor rendimiento
- ✅ Controles avanzados incluidos
- ✅ Animaciones fluidas
- ✅ Soporte técnico oficial

## Ejemplo de tu Access Token:

```
pk.eyJ1IjoibWl1c3VhcmlvIiwiYSI6ImNrdGU2ejRrYzBhM20zMW9mNXBmZTBhY2oifQ.ejemplo123456789
```

Siempre empiezan con `pk.`

---

**Una vez que agregues tu token a `.env`, ¡todo funcionará perfectamente!** 🚀
