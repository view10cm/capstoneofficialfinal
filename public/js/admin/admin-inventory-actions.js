// Global variables
let currentEditId = null;
const editModal = document.createElement('div');
let isJsPDFLoaded = false;
let isHtml2CanvasLoaded = false;

// Initialize edit modal
function initializeEditModal() {
    editModal.id = 'editProductModal';
    editModal.className = 'fixed inset-0 bg-transparent bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50';
    editModal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 w-full max-w-md">
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-200 shadow-2xl rounded-2xl">
                <div class="px-6 py-5 bg-gradient-to-r from-orange-500 to-amber-500 rounded-t-2xl">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">Edit Ingredient</h3>
                        <button onclick="closeEditModal()" 
                                class="text-white hover:text-orange-100 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <form id="editIngredientForm" class="space-y-5">
                        <div class="space-y-2">
                            <label for="editProductName" class="block text-sm font-semibold text-orange-900">
                                Ingredient Name *
                            </label>
                            <input type="text" 
                                   id="editProductName" 
                                   name="name"
                                   required
                                   class="w-full px-4 py-3 border-2 border-orange-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-orange-300"
                                   placeholder="Enter product name">
                        </div>
                        <div class="space-y-2">
                            <label for="editQuantity" class="block text-sm font-semibold text-orange-900">
                                Quantity *
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="editQuantity" 
                                       name="quantity"
                                       required
                                       min="0"
                                       step="1"
                                       class="w-full px-4 py-3 border-2 border-orange-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-orange-300 pr-12"
                                       placeholder="0"
                                       oninput="updateEditAvailabilityStatus()">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-orange-600 font-medium text-sm">units</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="editProductCategory" class="block text-sm font-semibold text-orange-900">
                                Category *
                            </label>
                            <select id="editProductCategory" 
                                    name="category"
                                    required
                                    class="w-full px-4 py-3 border-2 border-orange-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-orange-300 appearance-none">
                                <!-- Categories will be populated dynamically -->
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-orange-900">
                                Availability
                            </label>
                            <input type="text" 
                                   id="editAvailabilityStatus"
                                   readonly
                                   class="w-full px-4 py-3 border-2 border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 text-gray-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner">
                        </div>
                        <div class="flex justify-end space-x-3 pt-6 border-t border-orange-200">
                            <button type="button"
                                    onclick="closeEditModal()"
                                    class="px-5 py-2.5 border-2 border-orange-300 text-orange-700 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl font-semibold hover:from-orange-100 hover:to-amber-100 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-300 shadow-sm hover:shadow">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Update Ingredient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(editModal);
    
    // Setup edit form submission
    setupEditIngredientForm();
}

// Setup edit form submission
function setupEditIngredientForm() {
    const editForm = document.getElementById('editIngredientForm');
    if (!editForm) return;
    
    editForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!currentEditId) return;
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Updating...
        `;
        
        try {
            const response = await fetch(`/admin/inventory/${currentEditId}/update`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    quantity: formData.get('quantity'),
                    category: formData.get('category')
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Ingredient updated successfully!', 'success');
                closeEditModal();
                
                // Refresh the inventory table
                await refreshInventoryTable();
            } else {
                showNotification(data.message || 'Failed to update ingredient', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        } finally {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Edit ingredient function
async function editIngredient(id) {
    try {
        // Fetch ingredient details
        const response = await fetch(`/admin/inventory/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.ingredient) {
                currentEditId = id;
                openEditModal(data.ingredient);
            } else {
                showNotification('Failed to load ingredient details', 'error');
            }
        }
    } catch (error) {
        console.error('Error loading ingredient:', error);
        showNotification('Failed to load ingredient details', 'error');
    }
}

// Open edit modal
function openEditModal(ingredient) {
    // Initialize modal if not already initialized
    if (!document.getElementById('editProductModal')) {
        initializeEditModal();
    }
    
    // Populate form fields
    document.getElementById('editProductName').value = ingredient.ingredientName;
    document.getElementById('editQuantity').value = ingredient.ingredientQuantity;
    
    // Load categories and set the current one
    loadCategoriesForEdit(ingredient.ingredientCategory);
    
    // Update availability status
    updateEditAvailabilityStatus();
    
    // Show modal
    editModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Close edit modal
function closeEditModal() {
    editModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentEditId = null;
    
    // Reset form
    const editForm = document.getElementById('editIngredientForm');
    if (editForm) {
        editForm.reset();
    }
}

// Load categories for edit modal
async function loadCategoriesForEdit(currentCategoryId) {
    try {
        const response = await fetch('/admin/categories/list', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                updateEditCategoryDropdown(data.categories, currentCategoryId);
            }
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Update edit category dropdown
function updateEditCategoryDropdown(categories, currentCategoryId) {
    const categorySelect = document.getElementById('editProductCategory');
    if (!categorySelect) return;
    
    // Clear existing options
    categorySelect.innerHTML = '';
    
    // Add categories
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.ingredientCategoryName;
        option.selected = (category.id == currentCategoryId);
        categorySelect.appendChild(option);
    });
}

// Update edit availability status
function updateEditAvailabilityStatus() {
    const quantityInput = document.getElementById('editQuantity');
    const availabilityStatus = document.getElementById('editAvailabilityStatus');
    const quantity = parseInt(quantityInput.value) || 0;
    
    if (quantity === 0) {
        availabilityStatus.value = 'Out of Stock';
        availabilityStatus.className = 'w-full px-4 py-3 border-2 border-red-200 bg-gradient-to-r from-red-50 to-pink-50 text-red-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner';
    } else if (quantity <= 10) {
        availabilityStatus.value = 'Low Stock';
        availabilityStatus.className = 'w-full px-4 py-3 border-2 border-yellow-200 bg-gradient-to-r from-yellow-50 to-orange-50 text-yellow-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner';
    } else {
        availabilityStatus.value = 'Available';
        availabilityStatus.className = 'w-full px-4 py-3 border-2 border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 text-green-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner';
    }
}

// Handle Add Product form submission
function setupAddIngredientForm() {
    const addIngredientForm = document.getElementById('addIngredientForm');
    if (!addIngredientForm) return;

    addIngredientForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Adding...
        `;
        
        try {
            const response = await fetch('/admin/inventory/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Ingredient added successfully!', 'success');
                closeAddProductModal();
                
                // Refresh the inventory table
                await refreshInventoryTable();
            } else {
                showNotification(data.message || 'Failed to add ingredient', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        } finally {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Refresh inventory table with data from server
async function refreshInventoryTable() {
    try {
        const response = await fetch('/admin/inventory/list', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.ingredients) {
                updateInventoryTable(data.ingredients);
            }
        }
    } catch (error) {
        console.error('Error refreshing inventory:', error);
        showNotification('Failed to refresh inventory', 'error');
    }
}

// Update inventory table with data
function updateInventoryTable(ingredients) {
    const tableContainer = document.querySelector('.overflow-x-auto');
    const emptyState = document.querySelector('.py-12.px-6.text-center');
    const tableBody = document.querySelector('tbody');
    
    if (!tableBody || !tableContainer || !emptyState) return;
    
    if (ingredients.length === 0) {
        // Show empty state
        tableContainer.classList.add('hidden');
        emptyState.classList.remove('hidden');
        return;
    }
    
    // Hide empty state and show table
    emptyState.classList.add('hidden');
    tableContainer.classList.remove('hidden');
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    // Add new rows
    ingredients.forEach(ingredient => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-200';
        
        // Determine availability badge classes
        let availabilityClass = '';
        let availabilityText = ingredient.ingredientAvailability;
        
        switch(ingredient.ingredientAvailability) {
            case 'Available':
                availabilityClass = 'bg-green-100 text-green-800';
                availabilityText = 'Available';
                break;
            case 'Low Stock':
                availabilityClass = 'bg-yellow-100 text-yellow-800';
                availabilityText = 'Low Stock';
                break;
            case 'Out of Stock':
                availabilityClass = 'bg-red-100 text-red-800';
                availabilityText = 'Out of Stock';
                break;
        }
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex justify-center">
                    <input type="checkbox" 
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                ${ingredient.id}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                ${escapeHtml(ingredient.ingredientName)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                ${escapeHtml(ingredient.ingredientCategory)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                ${ingredient.ingredientQuantity}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${availabilityClass}">
                    ${availabilityText}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <div class="flex justify-center space-x-3">
                    <button onclick="editIngredient('${ingredient.id}')" 
                            class="text-amber-600 hover:text-amber-900 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <!-- Delete button has been removed as requested -->
                </div>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

// Export inventory to PDF functionality
async function exportInventoryToPDF() {
    try {
        showNotification('Generating PDF...', 'info');
        
        // Get table data
        const response = await fetch('/admin/inventory/list');
        const data = await response.json();
        
        if (!data.success || !data.ingredients || data.ingredients.length === 0) {
            showNotification('No data to export', 'error');
            return;
        }
        
        // Load jsPDF library
        if (!window.jspdf) {
            await loadJsPDF();
        }
        
        // Create PDF document
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');
        
        // Add title
        const title = "Caffe Arabica - Inventory Report";
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Add header
        doc.setFontSize(18);
        doc.text(title, 14, 15);
        doc.setFontSize(10);
        doc.text(`Generated on: ${currentDate}`, 14, 22);
        
        // Create table data
        const tableData = data.ingredients.map(item => [
            item.id,
            item.ingredientName,
            item.ingredientCategory,
            item.ingredientQuantity.toString(),
            item.ingredientAvailability
        ]);
        
        // Define table headers
        const headers = [
            ['ID', 'Ingredient Name', 'Category', 'Quantity', 'Availability']
        ];
        
        // Create table using jsPDF autoTable
        if (typeof doc.autoTable !== 'undefined') {
            doc.autoTable({
                head: headers,
                body: tableData,
                startY: 30,
                theme: 'grid',
                headStyles: {
                    fillColor: [245, 158, 11], // Amber color
                    textColor: [255, 255, 255],
                    fontSize: 10,
                    fontStyle: 'bold'
                },
                bodyStyles: {
                    fontSize: 9
                },
                columnStyles: {
                    0: { cellWidth: 25 }, // ID
                    1: { cellWidth: 60 }, // Name
                    2: { cellWidth: 40 }, // Category
                    3: { cellWidth: 25 }, // Quantity
                    4: { cellWidth: 30 }  // Availability
                },
                didDrawPage: function (data) {
                    // Footer
                    doc.setFontSize(8);
                    doc.text(
                        'Page ' + doc.internal.getNumberOfPages(),
                        data.settings.margin.left,
                        doc.internal.pageSize.height - 10
                    );
                }
            });
        } else {
            // Fallback: Create simple table if autoTable is not available
            createSimpleTable(doc, headers, tableData);
        }
        
        // Add summary statistics
        const finalY = doc.lastAutoTable ? doc.lastAutoTable.finalY + 10 : 100;
        
        // Calculate statistics
        const totalItems = data.ingredients.length;
        const inStock = data.ingredients.filter(item => item.ingredientAvailability === 'Available').length;
        const lowStock = data.ingredients.filter(item => item.ingredientAvailability === 'Low Stock').length;
        const outOfStock = data.ingredients.filter(item => item.ingredientAvailability === 'Out of Stock').length;
        
        doc.setFontSize(12);
        doc.text('Summary Statistics', 14, finalY);
        doc.setFontSize(10);
        doc.text(`Total Items: ${totalItems}`, 14, finalY + 8);
        doc.text(`In Stock: ${inStock}`, 14, finalY + 16);
        doc.text(`Low Stock: ${lowStock}`, 14, finalY + 24);
        doc.text(`Out of Stock: ${outOfStock}`, 14, finalY + 32);
        
        // Add current time
        const currentTime = new Date().toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
        doc.setFontSize(8);
        doc.text(`Report generated at: ${currentTime}`, 14, finalY + 40);
        
        // Save PDF
        const fileName = `inventory_report_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error exporting PDF:', error);
        showNotification('Failed to export PDF. Please try again.', 'error');
    }
}

// Simple table creation fallback
function createSimpleTable(doc, headers, tableData) {
    let y = 30;
    const lineHeight = 7;
    const colWidths = [25, 60, 40, 25, 30];
    const startX = 14;
    
    // Draw headers
    headers[0].forEach((header, i) => {
        doc.setFillColor(245, 158, 11); // Amber
        doc.rect(startX + colWidths.slice(0, i).reduce((a, b) => a + b, 0), y, colWidths[i], 10, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.text(header, startX + colWidths.slice(0, i).reduce((a, b) => a + b, 0) + 2, y + 7);
    });
    
    y += 10;
    
    // Draw data rows
    tableData.forEach((row, rowIndex) => {
        if (y > 190) { // Start new page if near bottom
            doc.addPage();
            y = 20;
        }
        
        row.forEach((cell, i) => {
            doc.setFillColor(255, 255, 255);
            doc.rect(startX + colWidths.slice(0, i).reduce((a, b) => a + b, 0), y, colWidths[i], 10);
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');
            doc.text(cell, startX + colWidths.slice(0, i).reduce((a, b) => a + b, 0) + 2, y + 7);
        });
        
        y += 10;
    });
}

// Alternative: Export current table view to PDF (captures exactly what's on screen)
async function exportTableViewToPDF() {
    try {
        showNotification('Generating PDF from table view...', 'info');
        
        // Get the table container
        const tableContainer = document.querySelector('.overflow-x-auto');
        if (!tableContainer || tableContainer.classList.contains('hidden')) {
            showNotification('No table data to export', 'error');
            return;
        }
        
        // Load html2canvas library
        if (!window.html2canvas) {
            await loadHtml2Canvas();
        }
        
        // Create a temporary container for the PDF
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'absolute';
        tempContainer.style.left = '-9999px';
        tempContainer.style.top = '0';
        tempContainer.style.width = '800px';
        tempContainer.style.backgroundColor = 'white';
        tempContainer.style.padding = '20px';
        
        // Clone the table and add additional info
        const tableClone = tableContainer.cloneNode(true);
        
        // Create header for PDF
        const header = document.createElement('div');
        header.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="color: #d97706; margin: 0;">Caffe Arabica</h1>
                <h2 style="color: #333; margin: 5px 0 10px 0;">Inventory Report</h2>
                <p style="color: #666; margin: 0;">
                    Generated on: ${new Date().toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    })}
                </p>
                <p style="color: #666; margin: 0;">
                    Generated at: ${new Date().toLocaleTimeString('en-US', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    })}
                </p>
            </div>
        `;
        
        tempContainer.appendChild(header);
        tempContainer.appendChild(tableClone);
        document.body.appendChild(tempContainer);
        
        // Use html2canvas to capture the table
        const canvas = await html2canvas(tempContainer, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        
        // Remove temporary container
        document.body.removeChild(tempContainer);
        
        // Load jsPDF if not already loaded
        if (!window.jspdf) {
            await loadJsPDF();
        }
        
        // Create PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        
        const imgWidth = 190;
        const imgHeight = canvas.height * imgWidth / canvas.width;
        
        // Add image to PDF
        doc.addImage(canvas.toDataURL('image/png'), 'PNG', 10, 10, imgWidth, imgHeight);
        
        // Save PDF
        const fileName = `inventory_table_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);
        
        showNotification('PDF exported successfully!', 'success');
        
    } catch (error) {
        console.error('Error exporting table to PDF:', error);
        showNotification('Failed to export PDF. Please try again.', 'error');
    }
}

// Load jsPDF library
async function loadJsPDF() {
    return new Promise((resolve, reject) => {
        if (window.jspdf) {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
        script.onload = () => {
            // Also load autoTable plugin
            const autoTableScript = document.createElement('script');
            autoTableScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js';
            autoTableScript.onload = resolve;
            autoTableScript.onerror = resolve; // Resolve even if autoTable fails
            document.head.appendChild(autoTableScript);
        };
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Load html2canvas library
async function loadHtml2Canvas() {
    return new Promise((resolve, reject) => {
        if (window.html2canvas) {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://html2canvas.hertzen.com/dist/html2canvas.min.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Export inventory functionality (main function)
async function exportInventory() {
    // Use the first method (structured PDF) by default
    await exportInventoryToPDF();
}

// Search inventory functionality
async function searchInventory() {
    const searchTerm = searchInput.value;
    
    if (!searchTerm.trim()) {
        // If search is empty, load all ingredients
        await refreshInventoryTable();
        return;
    }
    
    try {
        const response = await fetch(`/admin/inventory/search?search=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.ingredients) {
                updateInventoryTable(data.ingredients);
            }
        }
    } catch (error) {
        console.error('Search error:', error);
        showNotification('Failed to search inventory', 'error');
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show notification function
function showNotification(message, type = 'success') {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 
        type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
        'bg-blue-100 text-blue-800 border border-blue-200'
    }`;
    notification.textContent = message;
    
    // Add icon based on type
    const icon = document.createElement('span');
    icon.className = 'mr-2';
    if (type === 'success') {
        icon.innerHTML = '✓';
    } else if (type === 'error') {
        icon.innerHTML = '✗';
    } else {
        icon.innerHTML = 'ⓘ';
    }
    notification.prepend(icon);
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Get and display next ingredient ID
async function loadNextIngredientId() {
    try {
        const response = await fetch('/admin/inventory/next-id', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.next_id) {
                // Update modal title or add a display element
                const modalTitle = document.querySelector('#addProductModal h3');
                if (modalTitle) {
                    modalTitle.textContent = `Add Ingredients (${data.next_id})`;
                }
                
                // You could also add a small badge or text showing the ID
                addNextIdBadge(data.next_id);
            }
        }
    } catch (error) {
        console.error('Error loading next ID:', error);
    }
}

// Add a badge showing the next ID
function addNextIdBadge(nextId) {
    // Remove existing badge if any
    const existingBadge = document.querySelector('.next-id-badge');
    if (existingBadge) {
        existingBadge.remove();
    }
    
    // Create and add badge
    const badge = document.createElement('div');
    badge.className = 'next-id-badge absolute -top-2 -right-2 bg-amber-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg';
    badge.textContent = `ID: ${nextId}`;
    
    const modalHeader = document.querySelector('#addProductModal .px-6.py-5');
    if (modalHeader) {
        modalHeader.style.position = 'relative';
        modalHeader.appendChild(badge);
    }
}

// Update the openAddProductModal function (for main admin-inventory.js compatibility)
function openAddProductModal() {
    if (typeof addProductModal !== 'undefined') {
        addProductModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Load the next ID
        loadNextIngredientId();
    }
}

// Close add product modal (for compatibility)
function closeAddProductModal() {
    if (typeof closeAddProductModal !== 'undefined' && typeof addProductModal !== 'undefined') {
        addProductModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close edit modal when clicking outside or pressing Escape
document.addEventListener('click', function(e) {
    if (editModal && !editModal.classList.contains('hidden')) {
        if (e.target === editModal) {
            closeEditModal();
        }
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close edit modal if open
        if (editModal && !editModal.classList.contains('hidden')) {
            closeEditModal();
        }
        
        // Also handle closing add modals for compatibility
        if (typeof addCategoryModal !== 'undefined' && !addCategoryModal.classList.contains('hidden')) {
            closeAddCategoryModal();
        }
        
        if (typeof addProductModal !== 'undefined' && !addProductModal.classList.contains('hidden')) {
            closeAddProductModal();
        }
    }
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set up form submission
    setupAddIngredientForm();
    
    // Initialize edit modal
    initializeEditModal();
    
    // Load initial inventory data
    refreshInventoryTable();
    
    // Update export button functionality to use PDF export
    const exportBtn = document.querySelector('button[onclick="exportInventory()"]');
    if (exportBtn) {
        exportBtn.onclick = exportInventory;
        // Update icon to PDF icon
        const svg = exportBtn.querySelector('svg');
        if (svg) {
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
        }
        // Optional: Update button text
        const buttonText = exportBtn.querySelector('span');
        if (buttonText) {
            buttonText.textContent = 'Export PDF';
        }
    }
    
    // Update search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(searchInventory, 300));
    }
});