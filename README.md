# 🏬 Proyecto ALCENTRO: Sistema de Gestión de Alquileres

**ALCENTRO** es una plataforma administrativa integral diseñada para la gestión de locales en centros comerciales. El sistema permite el control de inventario inmobiliario, recaudación de rentas, cobros de servicios prepagados y supervisión financiera para inversionistas.

---

## 🚀 Descripción del Proyecto

El objetivo es digitalizar la administración de la plaza, permitiendo al **Administrador** gestionar la operación diaria y a los **Inversionistas** (Supervisores) monitorear estados de pago y flujos de caja en tiempo real.

### Stack Tecnológico
- **Framework**: [Laravel 12.x](https://laravel.com/)
- **Frontend Interactivo**: [Livewire 3.x](https://livewire.laravel.com/) & Alpine.js
- **Estilos**: [Tailwind CSS 4.x](https://tailwindcss.com/)
- **UI Kit**: Metronic Tailwind (Integración de Demo1 y Demo2)
- **Build Tool**: Vite 6.x

---

## 🏗️ Estructura del Sistema

El proyecto sigue el patrón de carpetas de Metronic (estilo Symfony) para separar las experiencias de usuario:


### 🗄️ Arquitectura de Datos (Módulos Core)
1. **Locales**: Gestión por M2 y estatus de disponibilidad.
2. **Contratos**: Configuración de renta base + 10% mantenimiento + 10% publicidad.
3. **Cuentas por Cobrar**: Generación de cargos mensuales (rentas) y semestrales (agua/aseo).
4. **Pagos**: Registro diferenciado por método (Efectivo vs. Transferencia fiscalizada).

---

## ✨ Características Principales

### ✅ Lógica de Cobros
- **Servicios Semestrales**: Cálculo automático basado en los metros cuadrados ($m^2$) del local.
- **Diferenciación Fiscal**: Switch lógico para aplicar o no IVA según el método de pago (Efectivo/Transferencia).
- **Gestión de Moras**: Recargo automático del 10% mensual si el pago excede los 5 días de la fecha de corte.

### ✅ Reportes Financieros
- Visualización de montos pendientes agrupados por arrendatario y concepto.
- Estados de cuenta detallados para transparencia con los inversionistas.

---

## 🛠️ Instalación y Puesta en Marcha

Requisitos previos: PHP 8.2+, Composer, Node 20+, npm, y una base de datos MySQL o PostgreSQL configurada.

1. Clonar el repositorio y entrar al proyecto:

```bash
git clone <url-del-repo>
cd alcentro_app
```

2. Instalar dependencias de backend y frontend:

```bash
composer install
npm install
```

3. Copiar variables de entorno y generar la clave de aplicación:

```bash
cp .env.example .env  # En PowerShell: copy .env.example .env
php artisan key:generate
```

4. Configurar credenciales de base de datos en `.env` y levantar el esquema con datos iniciales:

```bash
php artisan migrate --seed
```

5. Levantar el frontend y el backend en desarrollo:

```bash
npm run dev
php artisan serve
```

6. Para compilar activos para producción:

```bash
npm run build
```

---

## 📂 Estructura de Carpetas

```text
resources/views/
├── layouts/
│   ├── demo1/          # Panel Administrativo (Sidebar)
│   └── demo2/          # Panel Inversionista (Vertical)
├── livewire/
│   ├── gestion/        # CRUDs de locales y contratos
│   ├── pagos/          # Registro de cobros y facturación
│   └── reportes/       # Dashboards para supervisores
app/Livewire/
├── Shared/             # Componentes comunes (ThemeMode, Search)
├── Demo1/              # Lógica específica de administración
└── Demo2/              # Lógica de visualización ejecutiva
```
