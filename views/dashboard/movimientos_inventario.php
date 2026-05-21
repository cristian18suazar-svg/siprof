<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Movimientos de Inventario - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Header de Movimientos de Inventario -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Movimientos de Inventario</h2>
            <p class="text-gray-600 mt-1">Control de entradas y salidas de materiales</p>
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
            <button onclick="openModal('modalFiltrosMovimientos')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                <i class="fas fa-filter mr-2"></i>Filtros
            </button>
            <button onclick="openModal('modalMovimiento')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i class="fas fa-exchange-alt mr-2"></i>Nuevo Movimiento
            </button>
            <button onclick="generarReporteMovimientos()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Exportar
            </button>
        </div>
    </div>
</div>

<!-- Estadísticas de Movimientos -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Movimientos Hoy</p>
                <p class="text-2xl font-bold text-gray-800">24</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>+8 vs ayer
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exchange-alt text-green-800 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Entradas Hoy</p>
                <p class="text-2xl font-bold text-gray-800">15</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-down mr-1"></i>Recepción
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-down text-green-800 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Salidas Hoy</p>
                <p class="text-2xl font-bold text-gray-800">9</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>Despacho
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-up text-green-800 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Valor Total Hoy</p>
                <p class="text-2xl font-bold text-gray-800">$3.2M</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-dollar-sign mr-1"></i>COP
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-wallet text-green-800 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs de Movimientos -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <button onclick="cambiarTab('todos')" class="tab-btn px-4 py-3 text-sm font-medium text-green-600 border-b-2 border-green-600" data-tab="todos">
                Todos
            </button>
            <button onclick="cambiarTab('entradas')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="entradas">
                Entradas
            </button>
            <button onclick="cambiarTab('salidas')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="salidas">
                Salidas
            </button>
            <button onclick="cambiarTab('transferencias')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="transferencias">
                Transferencias
            </button>
            <button onclick="cambiarTab('ajustes')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="ajustes">
                Ajustes
            </button>
        </nav>
    </div>
</div>

<!-- Tabla de Movimientos -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Registro de Movimientos</h3>
        <div class="flex items-center gap-2">
            <input type="search" placeholder="Buscar movimiento..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Todos los materiales</option>
                <option value="MAT-001">Fertilizante NPK</option>
                <option value="MAT-002">Semillas Maíz</option>
                <option value="MAT-003">Mangueras</option>
                <option value="MAT-004">Insecticida</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Origen/Destino</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-sm text-gray-600">13/04/2026 08:30</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full flex items-center w-fit">
                            <i class="fas fa-arrow-down mr-1"></i>Entrada
                        </span>
                    </td>
                    <td class="p-3 text-sm font-medium text-gray-900">Fertilizante NPK 15-15-15</td>
                    <td class="p-3 text-sm font-semibold text-green-600">+200 kg</td>
                    <td class="p-3 text-sm text-gray-600">Proveedor: Agrofertil S.A.</td>
                    <td class="p-3 text-sm text-gray-600">Carlos Rodríguez</td>
                    <td class="p-3 text-sm text-gray-600">Compra ordinaria #ORD-2026-042</td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesMovimiento('MOV-001')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="imprimirComprobante('MOV-001')" class="text-green-600 hover:text-green-800 mr-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </button>
                        <button onclick="anularMovimiento('MOV-001')" class="text-red-600 hover:text-red-800" title="Anular">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-sm text-gray-600">13/04/2026 09:15</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full flex items-center w-fit">
                            <i class="fas fa-arrow-up mr-1"></i>Salida
                        </span>
                    </td>
                    <td class="p-3 text-sm font-medium text-gray-900">Semillas de Maíz Híbrido</td>
                    <td class="p-3 text-sm font-semibold text-red-600">-5 sacos</td>
                    <td class="p-3 text-sm text-gray-600">Lote: LOT-001</td>
                    <td class="p-3 text-sm text-gray-600">María García</td>
                    <td class="p-3 text-sm text-gray-600">Siembra 5 hectáreas</td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesMovimiento('MOV-002')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="imprimirComprobante('MOV-002')" class="text-green-600 hover:text-green-800 mr-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </button>
                        <button onclick="anularMovimiento('MOV-002')" class="text-red-600 hover:text-red-800" title="Anular">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-sm text-gray-600">13/04/2026 10:45</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full flex items-center w-fit">
                            <i class="fas fa-arrow-up mr-1"></i>Salida
                        </span>
                    </td>
                    <td class="p-3 text-sm font-medium text-gray-900">Manguera de Riego 50m</td>
                    <td class="p-3 text-sm font-semibold text-red-600">-3 unidades</td>
                    <td class="p-3 text-sm text-gray-600">Lote: LOT-002</td>
                    <td class="p-3 text-sm text-gray-600">José Martínez</td>
                    <td class="p-3 text-sm text-gray-600">Instalación sistema de riego</td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesMovimiento('MOV-003')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="imprimirComprobante('MOV-003')" class="text-green-600 hover:text-green-800 mr-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </button>
                        <button onclick="anularMovimiento('MOV-003')" class="text-red-600 hover:text-red-800" title="Anular">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-sm text-gray-600">13/04/2026 11:20</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full flex items-center w-fit">
                            <i class="fas fa-exchange-alt mr-1"></i>Transferencia
                        </span>
                    </td>
                    <td class="p-3 text-sm font-medium text-gray-900">Insecticida Orgánico</td>
                    <td class="p-3 text-sm font-semibold text-purple-600">-10 L</td>
                    <td class="p-3 text-sm text-gray-600">Bodega A -> Bodega B</td>
                    <td class="p-3 text-sm text-gray-600">Ana López</td>
                    <td class="p-3 text-sm text-gray-600">Reorganización de almacenamiento</td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesMovimiento('MOV-004')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="imprimirComprobante('MOV-004')" class="text-green-600 hover:text-green-800 mr-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </button>
                        <button onclick="anularMovimiento('MOV-004')" class="text-red-600 hover:text-red-800" title="Anular">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="p-3 text-sm text-gray-600">13/04/2026 12:00</td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full flex items-center w-fit">
                            <i class="fas fa-cog mr-1"></i>Ajuste
                        </span>
                    </td>
                    <td class="p-3 text-sm font-medium text-gray-900">Pala de Jardinero</td>
                    <td class="p-3 text-sm font-semibold text-yellow-600">-2 unidades</td>
                    <td class="p-3 text-sm text-gray-600">Bodega A</td>
                    <td class="p-3 text-sm text-gray-600">Carlos Rodríguez</td>
                    <td class="p-3 text-sm text-gray-600">Merma por deterioro</td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesMovimiento('MOV-005')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="imprimirComprobante('MOV-005')" class="text-green-600 hover:text-green-800 mr-2" title="Imprimir">
                            <i class="fas fa-print"></i>
                        </button>
                        <button onclick="anularMovimiento('MOV-005')" class="text-red-600 hover:text-red-800" title="Anular">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Movimiento -->
<div id="modalMovimiento" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Nuevo Movimiento</h3>
            <button onclick="closeModal('modalMovimiento')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Movimiento *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" onchange="actualizarFormularioMovimiento(this.value)">
                        <option value="">Seleccionar...</option>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="ajuste">Ajuste</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora *</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Seleccionar...</option>
                        <option value="MAT-001">Fertilizante NPK 15-15-15</option>
                        <option value="MAT-002">Semillas de Maíz Híbrido</option>
                        <option value="MAT-003">Manguera de Riego 50m</option>
                        <option value="MAT-004">Insecticida Orgánico</option>
                        <option value="MAT-005">Pala de Jardinero</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                    <input type="number" step="0.01" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <!-- Campos dinámicos según tipo de movimiento -->
            <div id="camposEntrada" class="hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Agrofertil S.A.</option>
                            <option value="2">Semillas del Campo</option>
                            <option value="3">Herramientas Agrícolas Ltda.</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Documento Referencia</label>
                        <input type="text" placeholder="Factura #123" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>
            
            <div id="camposSalida" class="hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccionar...</option>
                            <option value="LOT-001">Lote LOT-001</option>
                            <option value="LOT-002">Lote LOT-002</option>
                            <option value="LOT-003">Lote LOT-003</option>
                            <option value="LOT-004">Lote LOT-004</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trabajador Asignado</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccionar...</option>
                            <option value="1">Carlos Rodríguez</option>
                            <option value="2">María García</option>
                            <option value="3">José Martínez</option>
                            <option value="4">Ana López</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div id="camposTransferencia" class="hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Origen</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccionar...</option>
                            <option value="BODEGA-A">Bodega A</option>
                            <option value="BODEGA-B">Bodega B</option>
                            <option value="ALMACEN-1">Almacén 1</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccionar...</option>
                            <option value="BODEGA-A">Bodega A</option>
                            <option value="BODEGA-B">Bodega B</option>
                            <option value="ALMACEN-1">Almacén 1</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div id="camposAjuste" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Ajuste</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Seleccionar...</option>
                        <option value="merma">Merma</option>
                        <option value="deterioro">Deterioro</option>
                        <option value="correccion">Corrección de inventario</option>
                        <option value="devolucion">Devolución</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Responsable *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Seleccionar...</option>
                        <option value="1">Carlos Rodríguez - Mayordomo</option>
                        <option value="2">María García - Trabajador</option>
                        <option value="3">José Martínez - Trabajador</option>
                        <option value="4">Ana López - Trabajador</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Autorizado por</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Seleccionar...</option>
                        <option value="1">Administrador</option>
                        <option value="2">Mayordomo</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo/Descripción *</label>
                <textarea rows="3" placeholder="Describe el motivo del movimiento..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('modalMovimiento')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg">
                    Registrar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Filtros -->
<div id="modalFiltrosMovimientos" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Filtros de Movimientos</h3>
            <button onclick="closeModal('modalFiltrosMovimientos')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Movimiento</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos los tipos</option>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="ajuste">Ajuste</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos los materiales</option>
                    <option value="MAT-001">Fertilizante NPK</option>
                    <option value="MAT-002">Semillas Maíz</option>
                    <option value="MAT-003">Mangueras</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos los responsables</option>
                    <option value="1">Carlos Rodríguez</option>
                    <option value="2">María García</option>
                    <option value="3">José Martínez</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
                <div class="flex gap-2">
                    <input type="date" placeholder="Desde" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="date" placeholder="Hasta" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button onclick="limpiarFiltros()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    Limpiar
                </button>
                <button onclick="aplicarFiltros()" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg">
                    Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cambiarTab(tab) {
    // Actualizar estilos de tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-green-600', 'border-green-600');
        btn.classList.add('text-gray-500', 'border-transparent');
    });
    
    const activeTab = document.querySelector(`[data-tab="${tab}"]`);
    activeTab.classList.remove('text-gray-500', 'border-transparent');
    activeTab.classList.add('text-green-600', 'border-green-600');
    
    console.log('Cambiando a tab:', tab);
}

function actualizarFormularioMovimiento(tipo) {
    // Ocultar todos los campos dinámicos
    document.getElementById('camposEntrada').classList.add('hidden');
    document.getElementById('camposSalida').classList.add('hidden');
    document.getElementById('camposTransferencia').classList.add('hidden');
    document.getElementById('camposAjuste').classList.add('hidden');
    
    // Mostrar campos según el tipo de movimiento
    if (tipo === 'entrada') {
        document.getElementById('camposEntrada').classList.remove('hidden');
    } else if (tipo === 'salida') {
        document.getElementById('camposSalida').classList.remove('hidden');
    } else if (tipo === 'transferencia') {
        document.getElementById('camposTransferencia').classList.remove('hidden');
    } else if (tipo === 'ajuste') {
        document.getElementById('camposAjuste').classList.remove('hidden');
    }
}

function verDetallesMovimiento(codigo) {
    Swal.fire({
        title: 'Detalles del Movimiento ' + codigo,
        html: `
            <div class="text-left">
                <p><strong>Tipo:</strong> Entrada</p>
                <p><strong>Fecha/Hora:</strong> 13/04/2026 08:30</p>
                <p><strong>Material:</strong> Fertilizante NPK 15-15-15</p>
                <p><strong>Cantidad:</strong> 200 kg</p>
                <p><strong>Proveedor:</strong> Agrofertil S.A.</p>
                <p><strong>Documento:</strong> Factura #ORD-2026-042</p>
                <p><strong>Responsable:</strong> Carlos Rodríguez</p>
                <p><strong>Autorizado por:</strong> Administrador</p>
                <p><strong>Valor Unitario:</strong> $12,500</p>
                <p><strong>Valor Total:</strong> $2,500,000</p>
                <p class="mt-2"><strong>Motivo:</strong> Compra ordinaria para reposición de stock</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

function imprimirComprobante(codigo) {
    Swal.fire({
        title: 'Imprimir Comprobante',
        html: `
            <div class="text-left">
                <p><strong>Movimiento:</strong> ${codigo}</p>
                <p><strong>Tipo:</strong> Comprobante de entrada</p>
                <p class="mt-3 text-green-600">¿Generar comprobante PDF para impresión?</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Imprimir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Comprobante generado', 'El PDF ha sido enviado a impresión', 'success');
        }
    });
}

function anularMovimiento(codigo) {
    Swal.fire({
        title: 'Anular Movimiento',
        text: '¿Está seguro de anular este movimiento? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Movimiento anulado', 'El movimiento ha sido anulado exitosamente', 'success');
        }
    });
}

function generarReporteMovimientos() {
    Swal.fire({
        title: 'Exportar Movimientos',
        html: `
            <div class="text-left">
                <p><strong>Formato:</strong> Excel</p>
                <p><strong>Período:</strong> Últimos 30 días</p>
                <p><strong>Total registros:</strong> 156 movimientos</p>
                <p class="mt-3 text-green-600">¿Generar archivo Excel?</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Generar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Archivo generado', 'El reporte ha sido descargado', 'success');
        }
    });
}

function limpiarFiltros() {
    document.querySelectorAll('#modalFiltrosMovimientos select, #modalFiltrosMovimientos input').forEach(element => {
        element.value = '';
    });
}

function aplicarFiltros() {
    closeModal('modalFiltrosMovimientos');
    Swal.fire('Filtros aplicados', '', 'success');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
