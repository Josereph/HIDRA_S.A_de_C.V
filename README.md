# 💧 Sistema de Facturación para Empresa de Agua Potable

## 📌 Descripción General

Este proyecto consiste en el desarrollo de un sistema web administrativo en **PHP puro**, diseñado para la gestión integral de una empresa de agua potable.

El sistema tiene como finalidad centralizar y automatizar todos los procesos operativos y administrativos relacionados con:

- Gestión de clientes (naturales y jurídicos)
- Facturación mensual de servicios
- Registro de pagos y abonos
- Aplicación y control de moras
- Registro de lecturas de consumo
- Administración de sectores y conexiones
- Generación de reportes administrativos
- Configuración general del sistema

El sistema está diseñado bajo un enfoque de **simplicidad estructural, alto control y escalabilidad**, evitando el uso de frameworks para mantener una arquitectura ligera y completamente personalizable.

---

## 🎯 Objetivos del Sistema

### Objetivo General

Desarrollar una plataforma administrativa que permita gestionar de manera eficiente el servicio de agua potable, optimizando procesos de facturación, cobro y control de usuarios.

### Objetivos Específicos

- Automatizar la generación de facturación mensual
- Facilitar el registro de pagos y abonos
- Controlar de forma dinámica las moras de los usuarios
- Organizar usuarios por sectores geográficos
- Registrar y calcular el consumo de agua mediante lecturas
- Generar reportes detallados para análisis administrativo
- Permitir configuraciones flexibles del sistema (tarifas, mora, etc.)
- Diseñar una interfaz clara, simple y eficiente

---

## 🧠 Enfoque de Diseño

El sistema se basa en un principio clave:

> **Agrupar funcionalidades según el flujo real de trabajo, no por separación técnica.**

Por esta razón, múltiples procesos relacionados como:

- Facturación
- Pagos
- Moras
- Lecturas

Se integran dentro de un solo módulo lógico denominado **Operaciones**, facilitando la usabilidad y reduciendo la complejidad del sistema.

---

## 🧱 Arquitectura del Sistema

El proyecto utiliza una estructura basada en **PHP puro con separación por responsabilidades**, inspirada en el patrón MVC simplificado.

### Características principales:

- Sin uso de frameworks (Laravel, Symfony, etc.)
- Organización modular por carpetas
- Separación de lógica (controllers), datos (models) y vistas (views)
- Uso de helpers para funciones reutilizables
- Uso de Composer para dependencias externas

---

## 📁 Estructura de Carpetas

```bash
sistema_agua/
│
├── assets/
│   ├── css/
│   ├── img/
│   │   ├── logos/
│   │   ├── mapas/
│   │   ├── sectores/
│   │   └── usuarios/
│   └── js/
│
├── config/
│
├── controllers/
│
├── docs/
│   ├── manuales/
│   ├── diagramas/
│   └── sql/
│
├── helpers/
│
├── models/
│
├── src/
│
├── tmp/
│   └── mpdf/
│       └── ttfontdata/
│
├── uploads/
│   ├── facturas/
│   ├── recibos/
│   ├── reportes/
│   ├── comprobantes/
│   └── perfiles/
│
├── vendor/
│
└── views/
    └── layouts/