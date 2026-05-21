<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login.php");
    exit;
}

$titulo = "Gestión de Inventario - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Header de Inventario -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Inventario</h2>
            <p class="text-gray-600 mt-1">Control de insumos, herramientas y equipos</p>
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
            <button onclick="openModal('modalFiltrosInventario')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                <i class="fas fa-filter mr-2"></i>Filtros
            </button>
            <button onclick="openModal('modalInventario')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Agregar Item
            </button>
        </div>
    </div>
</div>

<!-- Estadísticas de Inventario -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Items</p>
                <p class="text-2xl font-bold text-gray-800">342</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-boxes text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Stock Bajo</p>
                <p class="text-2xl font-bold text-red-600">18</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Valor Total</p>
                <p class="text-2xl font-bold text-gray-800">$458K</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">En Tránsito</p>
                <p class="text-2xl font-bold text-yellow-600">12</p>
            </div>
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-truck text-yellow-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs para categorías -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <button onclick="switchTabInventario('todos')" id="tab-todos" class="px-6 py-3 border-b-2 border-green-500 text-green-600 font-medium text-sm">
                Todos (342)
            </button>
            <button onclick="switchTabInventario('insumos')" id="tab-insumos" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Insumos (156)
            </button>
            <button onclick="switchTabInventario('herramientas')" id="tab-herramientas" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Herramientas (89)
            </button>
            <button onclick="switchTabInventario('equipos')" id="tab-equipos" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Equipos (67)
            </button>
            <button onclick="switchTabInventario('semillas')" id="tab-semillas" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Semillas (30)
            </button>
        </nav>
    </div>
    
    <!-- Tabla de Inventario -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Unit.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="inventario-table-body">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INS001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-flask text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Fertilizante NPK 15-15-15</div>
                                <div class="text-sm text-gray-500">Fertilizante completo</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Insumos</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">850</span>
                            <span class="text-sm text-gray-500 ml-1">kg</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">kg</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$2.50</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$2,125</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Normal
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="verDetallesInventario('INS001')" class="text-green-600 hover:text-green-900 mr-2">Ver</button>
                        <button onclick="editarInventario('INS001')" class="text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                        <button onclick="ajustarStock('INS001')" class="text-yellow-600 hover:text-yellow-900">Ajustar</button>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">SEM001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-seedling text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Semillas de Maíz Híbrido</div>
                                <div class="text-sm text-gray-500">Variedad premium</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Semillas</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-red-600">45</span>
                            <span class="text-sm text-gray-500 ml-1">kg</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">kg</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$8.50</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$382.50</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Stock Bajo
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="verDetallesInventario('SEM001')" class="text-green-600 hover:text-green-900 mr-2">Ver</button>
                        <button onclick="editarInventario('SEM001')" class="text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                        <button onclick="reordenar('SEM001')" class="text-orange-600 hover:text-orange-900">Reordenar</button>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">HERR001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-hammer text-gray-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Machetes Agrícolas</div>
                                <div class="text-sm text-gray-500">Acero de alta calidad</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Herramientas</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">24</span>
                            <span class="text-sm text-gray-500 ml-1">unid</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">unid</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$15.00</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$360</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Normal
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="verDetallesInventario('HERR001')" class="text-green-600 hover:text-green-900 mr-2">Ver</button>
                        <button onclick="editarInventario('HERR001')" class="text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                        <button onclick="ajustarStock('HERR001')" class="text-yellow-600 hover:text-yellow-900">Ajustar</button>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">EQUIP001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-tractor text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Tractor Agrícola</div>
                                <div class="text-sm text-gray-500">John Deere 5075E</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Equipos</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">2</span>
                            <span class="text-sm text-gray-500 ml-1">unid</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">unid</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$45,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$90,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Operativo
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="verDetallesInventario('EQUIP001')" class="text-green-600 hover:text-green-900 mr-2">Ver</button>
                        <button onclick="editarInventario('EQUIP001')" class="text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                        <button onclick="mantenimiento('EQUIP001')" class="text-purple-600 hover:text-purple-900">Mantenimiento</button>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INS002</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-spray-can text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Herbicida Selectivo</div>
                                <div class="text-sm text-gray-500">Control de malezas</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Insumos</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">120</span>
                            <span class="text-sm text-gray-500 ml-1">L</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">L</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$18.00</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$2,160</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            En Tránsito
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="verDetallesInventario('INS002')" class="text-green-600 hover:text-green-900 mr-2">Ver</button>
                        <button onclick="editarInventario('INS002')" class="text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                        <button onclick="rastrear('INS002')" class="text-blue-600 hover:text-blue-900">Rastrear</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Mostrando <span class="font-medium">1</span> a <span class="font-medium">5</span> de <span class="font-medium">342</span> resultados
        </div>
        <div class="flex gap-2">
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Anterior</button>
            <button class="px-3 py-1 bg-green-600 text-white rounded-md text-sm">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Siguiente</button>
        </div>
    </div>
</div>

<!-- Modal Nuevo Item -->
<div id="modalInventario" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Agregar Item al Inventario</h3>
            <button onclick="closeModal('modalInventario')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                    <input type="text" placeholder="Ej: INS001" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccione...</option>
                        <option>Insumos</option>
                        <option>Herramientas</option>
                        <option>Equipos</option>
                        <option>Semillas</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto *</label>
                    <input type="text" placeholder="Ej: Fertilizante NPK" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <input type="text" placeholder="Descripción breve" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Inicial *</label>
                    <input type="number" placeholder="Ej: 100" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unidad *</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Seleccione...</option>
                        <option>kg</option>
                        <option>L</option>
                        <option>unid</option>
                        <option>caja</option>
                        <option>saco</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo</label>
                    <input type="number" placeholder="Ej: 20" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Unitario *</label>
                    <input type="number" step="0.01" placeholder="Ej: 25.50" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                    <input type="text" placeholder="Nombre del proveedor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                <input type="text" placeholder="Ej: Bodega A - Estante 3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea rows="3" placeholder="Observaciones adicionales..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('modalInventario')" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg shadow transition-colors">Agregar Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTabInventario(tab) {
        // Reset all tabs
        document.querySelectorAll('[id^="tab-"]').forEach(t => {
            t.classList.remove('border-green-500', 'text-green-600');
            t.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Activate selected tab
        const selectedTab = document.getElementById('tab-' + tab);
        selectedTab.classList.remove('border-transparent', 'text-gray-500');
        selectedTab.classList.add('border-green-500', 'text-green-600');
        
        console.log('Switching to inventory tab:', tab);
    }
    
    function verDetallesInventario(codigo) {
        Swal.fire({
            title: 'Detalles del Item',
            html: `
                <div class="text-left">
                    <p><strong>Código:</strong> ${codigo}</p>
                    <p><strong>Producto:</strong> Fertilizante NPK 15-15-15</p>
                    <p><strong>Categoría:</strong> Insumos</p>
                    <p><strong>Stock actual:</strong> 850 kg</p>
                    <p><strong>Stock mínimo:</strong> 100 kg</p>
                    <p><strong>Valor unitario:</strong> $2.50</p>
                    <p><strong>Valor total:</strong> $2,125</p>
                    <p><strong>Proveedor:</strong> AgroSuministros S.A.</p>
                    <p><strong>Última compra:</strong> 01/04/2024</p>
                    <p><strong>Ubicación:</strong> Bodega A - Estante 3</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    }
    
    function editarInventario(codigo) {
        Swal.fire({
            title: 'Editar Item',
            text: `¿Deseas editar el item ${codigo}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, editar',
            cancelButtonText: 'Cancelar'
        });
    }
    
    function ajustarStock(codigo) {
        Swal.fire({
            title: 'Ajustar Stock',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                        <input type="text" value="${codigo}" readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Actual</label>
                        <input type="text" value="850 kg" readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nuevo Stock</label>
                        <input type="number" placeholder="Ingrese nueva cantidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo del ajuste</label>
                        <textarea rows="3" placeholder="Describa el motivo..." class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                    </div>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ajustar',
            cancelButtonText: 'Cancelar'
        });
    }
    
    function reordenar(codigo) {
        Swal.fire({
            title: 'Reordenar Item',
            text: `¿Deseas generar una orden de compra para ${codigo}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, reordenar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Orden Generada', 'La orden de compra ha sido generada exitosamente', 'success');
            }
        });
    }
    
    function mantenimiento(codigo) {
        Swal.fire({
            title: 'Programar Mantenimiento',
            text: `¿Deseas programar mantenimiento para ${codigo}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Programar',
            cancelButtonText: 'Cancelar'
        });
    }
    
    function rastrear(codigo) {
        Swal.fire({
            title: 'Rastrear Envío',
            html: `
                <div class="text-left">
                    <p><strong>Número de guía:</strong> #TRK001234</p>
                    <p><strong>Estado:</strong> En tránsito</p>
                    <p><strong>Fecha estimada de entrega:</strong> 20/04/2024</p>
                    <p><strong>Transportista:</strong> Transportes Agrícolas S.A.</p>
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm">Bodega Origen</span>
                            <span class="text-xs text-gray-500">15/04/2024</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Destino</span>
                            <span class="text-xs text-gray-500">20/04/2024</span>
                        </div>
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
