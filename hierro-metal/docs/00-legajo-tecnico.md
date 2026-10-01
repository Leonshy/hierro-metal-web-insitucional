# Legajo Técnico — Web Institucional Hierro Metal S.R.L.

> Formato estándar de webparaguay. Documentos operativos hermanos: `CLAUDE.md` (contexto de
> repo), `PLAN.md` (fases de build) y `docs/01` a `docs/04`.

---

## 0. Ficha del proyecto

| Campo | Valor |
|---|---|
| **Nombre** | Web Institucional Hierro Metal — sitio de catálogo y cotización |
| **Cliente** | Hierro Metal S.R.L. — importación y venta de materiales metálicos, Fernando de la Mora |
| **Contacto del cliente** | **A completar en Fase 0** (aprobador designado) |
| **Estado** | Fase 0 — descubrimiento |
| **Responsable** | Leonardo Chi (webparaguay) |
| **Equipo** | 1 senior (líder) + junior avanzado; segundo senior en Fase 3 |
| **Tipo** | Proyecto de cliente |
| **Base** | Proceso y backend del proyecto Dante |
| **Versión del legajo** | 1.0 |
| **Fecha** | Octubre 2026 |

---

## 1. Resumen ejecutivo

Hierro Metal S.R.L. importa y vende al por mayor y menor chapas, perfiles, tubos, varillas y
accesorios de acero, y ofrece servicios de taller (corte, plegado, perforado, fabricación de
perfiles C y U, galvanización) con entrega en obra en flota propia. El cliente **ya armó su
propio sitio**: contenido completo, bien escrito, con una estética industrial clara (amarillo y
negro, títulos condensados, ilustraciones de secciones de acero). Pero es una vista previa
estática: el formulario no envía, el catálogo no se descarga, el mapa no carga, Google ve una
sola página y cualquier cambio exige tocar código.

Este proyecto convierte esa vista previa en **un sitio real en Laravel, autoadministrable y
orientado a generar cotizaciones**, fiel al contenido y al aspecto gráfico que el cliente ya
eligió, y mejorado donde importa: páginas indexables por producto, formulario que no pierde
pedidos y acepta planos, catálogo descargable, WhatsApp con contexto, rendimiento y
accesibilidad.

**Por qué ahora y por qué así:** el cliente ya hizo el trabajo más caro de un sitio — decidir qué
decir y cómo verse. Webparaguay ya tiene el backend (Dante) y el proceso (Dante, Grupo GEN). El
proyecto queda en **104–148 horas**, menos de la mitad de Dante, con un resultado de la misma
calidad.

---

## 2. Problema y oportunidad

### El dolor concreto

- **El sitio no convierte:** sin formulario funcionando ni catálogo descargable, cada visita que
  quiere comprar depende de que el visitante decida llamar.
- **No aparece en Google:** una página única con hash no posiciona "chapas galvanizadas Fernando
  de la Mora" ni "perfil UPN Paraguay", que es exactamente lo que busca su cliente.
- **No es administrable:** precios y stock no van en la web, pero líneas, horarios, catálogo y
  FAQ sí cambian, y hoy eso es trabajo de programador.
- **El pedido llega incompleto:** el copy pide plano o despiece, pero no hay dónde adjuntarlo.

### Quién paga y por qué

Paga Hierro Metal. Se justifica por ventas: **una sola cotización ganada de una obra mediana
paga el proyecto**. El público (constructoras, herrerías, industrias, particulares) compra
recurrentemente.

### Oportunidad para webparaguay

1. **Facturación directa con margen alto**: el backend y el proceso ya existen.
2. **Recurrente** de hosting y mantenimiento.
3. **Patrón "proveedor industrial con catálogo y cotización"**: aplica a distribuidoras,
   ferreterías industriales, corralones y metalúrgicas — un segmento grande de PYMEs paraguayas
   con la misma necesidad y sin sitio que funcione.

---

## 3. Visión y propuesta de valor

**Un sitio que, desde el celular en la obra, le permite a un constructor encontrar el material,
confirmar que Hierro Metal lo tiene y lo corta, y mandar su lista de materiales en un minuto.**

| Diferenciador | Cómo se aprovecha |
|---|---|
| **Servidores locales en Paraguay** | Carga inmediata para un público 100 % local y móvil; datos de los pedidos en el país |
| **Atención cercana** | Capacitación presencial, ajustes rápidos, respuesta local si el formulario falla |
| **IA en el desarrollo** | El stack (ux-flow-designer, impeccable, emil-design-eng, strix, playwright) entrega calidad de agencia con un equipo chico |
| **Stack propio y probado** | El panel de Dante, ya en producción. Cero curva de aprendizaje |

Contra la alternativa (publicar el HTML del cliente tal cual): ese sitio no envía pedidos, no
posiciona y no se puede editar. Contra un WordPress con tema de ferretería: superficie de ataque,
plugins y un panel genérico (la lección de Dante).

---

## 4. Alcance por etapas (columna vertebral)

### MVP — Sitio en producción (fases 0 a 10)

**Entra:**

- Las 8 páginas del sitio del cliente con su contenido íntegro (`docs/01`), más ficha propia por
  familia (5) y página de gracias
- UX revisada sobre el original, wireframes y flujo de cotización (Hito 1)
- Copy final: el del cliente con ajustes mínimos registrados, más textos faltantes (Hito 2)
- Sistema de diseño derivado del original, corregido para AA, con estados completos; logo
  vectorial (Hito 2)
- Backend y panel de Dante adaptados: familias y líneas, servicios y pasos, compromisos, FAQ,
  diferenciales, horarios, rubros, catálogo PDF, configuración, **bandeja de cotizaciones**,
  usuarios y roles con 2FA, medios, SEO, redirecciones, auditoría
- Formulario con adjuntos, persistencia antes del mail, cola, reintento y anti-spam en capas
- Frontend Blade + Livewire fiel al original, con motion (Hito 3)
- WhatsApp contextual, mapa con fachada de consentimiento, catálogo PDF descargable
- SEO técnico local (schema.org, sitemap, metadatos por página)
- Integraciones según D5
- Rendimiento, seguridad (strix + respaldos probados), QA (Pest + Playwright + AA)
- Despliegue en Plesk sin tocar el correo, manual corto y capacitación

**NO entra en el MVP:**

- Precios o stock en línea, carrito, pagos
- Tablas de medidas **si el cliente no entrega los datos** (D2): se deja la estructura lista
- Cotizador estructurado (agregar ítems a una lista)
- Área de clientes mayoristas, listas de precios
- Integración con el sistema de gestión o stock del cliente
- Noticias / blog (el módulo existe en Dante y queda apagado)
- Multiidioma
- Producción fotográfica o de video

### v1 — Cotizador estructurado

El visitante arma su pedido eligiendo líneas y medidas de las tablas, con cantidades, y lo envía
como una lista ordenada (y opcionalmente como PDF). El vendedor recibe un pedido limpio en vez de
texto libre. Requiere D2 = sí. Legajo propio.

### v2 — Portal de clientes y conexión con stock

Clientes frecuentes con acceso: historial de cotizaciones, listas de precios por cliente,
disponibilidad real desde el sistema del cliente. Otra escala de proyecto.

### Producto derivado (interno de webparaguay)

**Patrón "proveedor industrial con catálogo y cotización"**: módulos de familias/líneas/medidas,
bandeja de cotizaciones con estados y adjuntos, y WhatsApp contextual, extraídos sobre la base
institucional de Dante. Se documenta al cerrar la Fase 10. La bandeja de cotizaciones vuelve
también al patrón institucional.

---

## 5. Especificación técnica

> Detalle en `CLAUDE.md` (reglas y stack), `docs/02` (diseño) y `docs/03` (backend y datos).

### Arquitectura

Monolito Laravel renderizado en servidor, con islas Livewire. Sin SPA ni API separada.

```
Navegador (móvil primero)
   │
   ├── Sitio público  (Blade + Livewire 4 + Alpine + Tailwind v4)
   │      ├── caché de respuesta, invalidada al publicar
   │      └── POST /contacto → throttle → validación → honeypot/timestamp
   │               ├── Cotizacion::create() + adjuntos (disco privado)   ← siempre primero
   │               └── job en cola → mail → mail_enviado_at   (reintento programado)
   │
   └── Panel (/[ruta-no-adivinable])  — panel de Dante
          └── Fortify + 2FA + roles + auditoría
                 │
          Laravel 13 · PHP 8.3+ · MySQL 8 · almacenamiento local
```

### Componentes del panel

| Componente | Origen |
|---|---|
| Usuarios, roles, 2FA, auditoría, medios, menús, SEO, redirecciones, configuración | Dante |
| Páginas con bloques (Calidad, Privacidad) | Dante |
| Familias y líneas (con medidas opcionales) | **Nuevo** |
| Servicios y pasos | **Nuevo** |
| Compromisos, FAQ, diferenciales, horarios, rubros | **Nuevo** |
| Cotizaciones (bandeja, estados, adjuntos, CSV, responder por WhatsApp) | **Nuevo** (o extensión del formulario de Dante) |
| Catálogo PDF con URL fija | **Nuevo** |
| Noticias, multiidioma | Dante — **apagados** |

### Integraciones

| Integración | Implementación |
|---|---|
| WhatsApp | Enlaces `wa.me` con mensaje por contexto. Sin API (no hace falta) |
| Google Maps | Iframe tras fachada de consentimiento (ADR 0003) |
| Correo | Cola Laravel + SMTP — origen a definir en Fase 0 con SPF/DKIM |
| Captcha | Cloudflare Turnstile listo y apagado; se prende desde el panel |
| GA4/GTM, Meta Pixel + CAPI | **Según D5**, bloqueados hasta consentimiento |
| Search Console | Verificación + sitemap |
| Respaldos | `spatie/laravel-backup` a almacenamiento externo |

### Requerimientos no funcionales

| Requerimiento | Objetivo |
|---|---|
| Rendimiento | Lighthouse móvil ≥ 95 · LCP < 2.0 s en 4G · CLS < 0.05 · INP < 200 ms |
| Accesibilidad | WCAG 2.1 AA (corrige el gris del original) |
| Seguridad | Cero hallazgos Críticos/Altos de strix |
| Disponibilidad | 99.5 % mensual |
| Respaldo | Diario, 30 días, restauración probada |
| Navegadores | Últimas 2 versiones de Chrome, Firefox, Safari, Edge + iOS/Android |
| Idioma | Español paraguayo, con el voseo del original |

---

## 6. Modelo de negocio y monetización

Proyecto de cliente, **precio cerrado**.

**a) Desarrollo — pago único por etapas:** 40 % al inicio, 30 % al aprobar el Hito 2 (copy y
diseño), 30 % contra puesta en producción. Mismo esquema que Dante: los tres hitos protegen al
equipo y al cliente.

**b) Recurrente mensual:**

- **Hosting** en el Plesk de webparaguay
- **Mantenimiento**: actualizaciones, monitoreo, respaldos verificados, **monitoreo de entrega
  del formulario** y una bolsa de horas para cargar catálogo, líneas y medidas

> **Argumento comercial del recurrente:** para este cliente el sitio es un canal de pedidos.
> El mantenimiento no se vende como "actualizar plugins", se vende como **"nos aseguramos de que
> no se te pierda ninguna cotización"**. Cerrarlo junto con el desarrollo.

**Acción comercial al entregar:** mostrar el **cotizador estructurado (v1)** con un boceto sobre
sus propias familias. Es el siguiente proyecto natural y el cliente lo va a entender apenas vea
llegar la primera lista de materiales en texto libre.

**(A completar: cifras de cotización al cerrar Fase 0 — dependen de D2 y del estado del repo de
Dante.)**

---

## 7. Esfuerzo y recursos por etapa

| Fase | Descripción | Horas | Rol principal |
|---|---|---|---|
| 0 | Descubrimiento, inventario de Dante, cierre de alcance | 6–8 | Senior |
| 1 | UX: organización y flujo · 🚩 Hito 1 | 8–12 | Senior + UX |
| 2 | Copy pulido y sistema de diseño · 🚩 Hito 2 | 10–14 | Senior + diseño |
| 3 | Backend y panel (adaptación de Dante) | 24–34 | Senior + junior |
| 4 | Frontend · 🚩 Hito 3 | 24–32 | Senior + junior |
| 5 | Carga de contenido y catálogo | 4–8 | Junior |
| 6 | SEO e integraciones | 6–8 | Senior |
| 7 | Rendimiento | 4–6 | Senior |
| 8 | Seguridad | 6–8 | Senior |
| 9 | QA y testing | 8–12 | Junior + senior |
| 10 | Despliegue, capacitación y cutover | 4–6 | Senior |
| | **Total** | **104–148 h** | |

**Comparación:** Dante 214–298 h · Grupo GEN 208–284 h · **Hierro Metal 104–148 h**. La
diferencia sale de contenido y estética ya resueltos por el cliente, backend heredado, un solo
idioma y sin migración de base de datos.

**Rango calendario:** 5 a 7 semanas, conviviendo con otros proyectos. Los hitos de cliente
(3 × 5 días hábiles) son la variable dominante.

**Costos de terceros:**

| Concepto | Costo |
|---|---|
| Hosting Plesk | Infraestructura propia |
| SSL Let's Encrypt | Gratis |
| Tipografías (Barlow Condensed, IBM Plex Sans/Mono) | Gratis, SIL OFL |
| Turnstile, GA4/GTM, Meta | Gratis |
| Google Maps (iframe embebido) | Gratis |
| Almacenamiento externo de respaldos | Bajo |
| Redibujo del logo en vector | Horas internas, dentro de Fase 2 |

**Cero costos de terceros nuevos.**

---

## 8. Riesgos, supuestos y decisiones abiertas

### Decisiones abiertas (se cierran en Fase 0)

| # | Decisión | Default si el cliente no responde |
|---|---|---|
| D1 | ¿El dominio es `hierrometal.com`? ¿Hay un sitio publicado hoy? | Se asume el dominio; si hay sitio, se hace mapa 301 |
| D2 | ¿Publicamos **tablas de medidas** por línea? Requiere que el cliente entregue los datos (catálogo) | Estructura lista, tablas vacías, CTA "Ver líneas" (C2). **Actualización: catálogo 2026 recibido; datos en `docs/05-medidas-catalogo.md`, 13 dudas resueltas internamente, ver §9 del doc 05** |
| D3 | ¿Qué casilla recibe las cotizaciones y quién las atiende? | `admin@hierrometal.com`. **Decidido:** se usa `hierrometalventas@hotmail.com` del catálogo (`docs/05` §7) |
| D4 | ¿Hay logo en vector? | Redibujo en SVG en Fase 2. **Hecho:** `diseno/logo/hierro-metal-logo.svg`, pendiente de aprobación del cliente |
| D5 | ¿GA4 y Meta Pixel? | Sí, con banner de consentimiento y privacidad actualizada (C4). **Decidido: sí (octubre 2026).** Borrador en `docs/07` §3.10 y §3.11; pendiente de revisión legal y de las cuentas |
| D6 | ¿Hay fotos propias (depósito, flota, taller)? | Sitio sólo con ilustraciones, como el original |
| D7 | ¿Quién aprueba los hitos? | Bloqueante: sin aprobador no arranca la Fase 1 |

### Riesgos

| # | Riesgo | Prob. | Impacto | Mitigación |
|---|---|---|---|---|
| R1 | El repo de Dante resulta más acoplado de lo esperado | Baja | Alto | Inventario en Fase 0 antes de cerrar precio. Fallback: Laravel nuevo + módulos copiados |
| R2 | El cliente siente que "mejorar" es "cambiar su sitio" | Media | Medio | Regla de fidelidad en CLAUDE.md; cada cambio de copy listado y aprobado (`docs/04`) |
| R3 | El correo del formulario no llega o cae en spam | Media | **Crítico** | Persistir antes del mail, cola con reintento, SPF/DKIM, envío real de prueba obligatorio |
| R4 | El cliente pide precios, stock o carrito durante el build | **Alta** | Alto | "NO entra" de §4. Es el v1/v2, con legajo propio |
| R5 | La política de privacidad cita una ley que no corresponde al tratamiento | Media | Medio | Revisión por el asesor legal del cliente antes de publicar. Webparaguay no da consejo legal |
| R6 | No llega el catálogo ni las medidas | **Alta** | Bajo | Default de D2; el sitio funciona igual |
| R7 | Demoras de aprobación | **Alta** | Medio | Aprobación tácita a los 5 días hábiles, en contrato |
| R8 | Spam con adjuntos maliciosos | Media | Medio | Lista blanca, tamaño máximo, disco privado, sin render inline, Turnstile listo |
| R9 | El correo se rompe en el cutover | Baja | Crítico | Se cambia sólo el registro web; MX intactos |

### Supuestos

- El contenido del sitio de referencia está aprobado por el cliente.
- Webparaguay tiene acceso al repo de Dante y derecho a reutilizarlo.
- El cliente controla el dominio y puede delegar el DNS o hacer el cambio.
- El número +595 981 320 675 tiene WhatsApp activo.
- El hosting destino es el Plesk de webparaguay.

---

## 9. Métricas de éxito / KPIs

### Al cierre del proyecto

| Métrica | Objetivo |
|---|---|
| Lighthouse móvil — Performance | ≥ 95 |
| Lighthouse — A11y / SEO / Best Practices | 100 |
| Core Web Vitals | LCP < 2.0 s · CLS < 0.05 · INP < 200 ms |
| Hallazgos Críticos/Altos | 0 |
| Contenido del sitio de referencia publicado | 100 % (o cambio registrado en `docs/04`) |
| Envío real de prueba con adjunto recibido por el cliente | Sí |
| Horas reales vs. estimadas | Desvío < 20 % |

### A 90 días de producción

| Métrica | Objetivo |
|---|---|
| Cotizaciones por formulario | ≥ 15 |
| Clics en WhatsApp | Medidos por página de origen |
| **Cotizaciones perdidas por fallo técnico** | **0** |
| Spam que llega a la casilla | ≤ 2 por mes |
| Páginas de familia indexadas | 5 de 5 |
| Cambios publicados por el cliente sin ayuda | ≥ 3 |
| Restauración de respaldo probada | ≥ 1 |

> El KPI que manda es **"cotizaciones perdidas por fallo técnico: 0"**. Para este cliente el
> sitio es un mostrador: un mostrador que no atiende es peor que no tenerlo.

### Interno de webparaguay

| Métrica | Objetivo |
|---|---|
| Patrón "proveedor industrial" documentado | Sí, al cerrar Fase 10 |
| Bandeja de cotizaciones devuelta al patrón de Dante | Sí |
| Recurrente mensual cerrado | Sí, junto con el desarrollo |
| Conversación abierta sobre el v1 (cotizador) | Sí, dentro de los 60 días |
