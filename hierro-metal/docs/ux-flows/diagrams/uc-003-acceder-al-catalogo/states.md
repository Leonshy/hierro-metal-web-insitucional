# UC-003 — Acceder al catálogo PDF — estados

Estados de la interfaz.

```mermaid
stateDiagram-v2
    [*] --> BotonVisible
    BotonVisible --> Descargando: toca
    Descargando --> Descargado: 200 PDF
    Descargando --> Error: 404 o red caída
    Error --> BotonVisible: reintenta
    Descargado --> [*]
```
