<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meilisearch Index Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .index-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .index-card h5 {
            color: #495057;
            margin-bottom: 15px;
        }
        .btn-action {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Meilisearch Index Management</h1>
        
        <div class="alert-container" id="alertContainer"></div>

        <!-- Create Index Section -->
        <div class="form-section">
            <h3>Create New Index</h3>
            <form id="createIndexForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="indexUid" class="form-label">Index UID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="indexUid" name="uid" required placeholder="e.g., products">
                        <small class="form-text text-muted">Unique identifier for the index</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="primaryKey" class="form-label">Primary Key</label>
                        <input type="text" class="form-control" id="primaryKey" name="primaryKey" placeholder="id (default)">
                        <small class="form-text text-muted">Primary key field name (defaults to 'id')</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create Index</button>
            </form>
        </div>

        <!-- Index Operations Section -->
        <div class="form-section">
            <h3>Index Operations</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="indexName" class="form-label">Index Name</label>
                    <input type="text" class="form-control" id="indexName" placeholder="products" value="products">
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-info btn-action" onclick="getIndexInfo()">Get Index Info</button>
                    <button type="button" class="btn btn-danger btn-action" onclick="deleteIndex()">Delete Index</button>
                </div>
            </div>
        </div>

        <!-- Index Products from CSV Section -->
        <div class="form-section">
            <h3>Index Products from CSV</h3>
            <div class="alert alert-info">
                <strong>Note:</strong> This will index products from <code>products/product_feed.csv</code> file.
                This process may take several minutes depending on the file size.
            </div>
            <button type="button" class="btn btn-primary btn-lg" id="indexProductsBtn" onclick="indexProductsFromCsv()">
                <span id="indexProductsSpinner" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                Index Products from CSV
            </button>
            <div id="indexProductsOutput" class="mt-3" style="display: none;">
                <pre class="bg-light p-3 rounded" id="indexProductsOutputContent"></pre>
            </div>
        </div>

        <!-- Index Pages Section -->
        <div class="form-section">
            <h3>Index Pages</h3>
            <div class="alert alert-info">
                <strong>Note:</strong> This will index:
                <ul class="mb-0 mt-2">
                    <li>Static Pages (gb_static_pages index)</li>
                    <li>Education Pages (gb_education_pages index)</li>
                    <li>Support Articles from Gladly API (gladly_support_pages index)</li>
                </ul>
                This process may take several minutes.
            </div>
            <button type="button" class="btn btn-success btn-lg" id="indexPagesBtn" onclick="indexPages()">
                <span id="indexPagesSpinner" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                Index Pages
            </button>
            <div id="indexPagesOutput" class="mt-3" style="display: none;">
                <pre class="bg-light p-3 rounded" id="indexPagesOutputContent"></pre>
            </div>
        </div>

        <!-- Index Settings Section -->
        <div class="form-section">
            <h3>Index Settings</h3>
            
            <!-- Pagination Settings -->
            <div class="index-card">
                <h5>Pagination Settings</h5>
                <form id="paginationForm">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="indexName" id="paginationIndexName" value="products">
                            <label for="maxTotalHits" class="form-label">Max Total Hits</label>
                            <input type="number" class="form-control" id="maxTotalHits" name="maxTotalHits" value="1500" min="1">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-success">Update Pagination</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Filterable Attributes -->
            <div class="index-card">
                <h5>Filterable Attributes</h5>
                <form id="filterableForm">
                    <input type="hidden" name="indexName" id="filterableIndexName" value="products">
                    <div class="mb-3">
                        <label for="filterableAttributes" class="form-label">Attributes (JSON Array)</label>
                        <textarea class="form-control" id="filterableAttributes" name="attributes" rows="4" placeholder='["plpCode", "productName", "price", "metalType", "carat", "shape"]'<!--["plpCode","productName","objectID","productFullName","category","price","categoryIds","metalType","carat","color","stone","shape","gender","maincat","subcat","sideStone","stoneFilter","productStyle","metalTypeFilter","searchKeyword","department","hideOnSite","inStock","caratFraction","diamondQuality","erpSKU","upcNumber"]-->["badge","carat","caratFraction","caseSize","category.lvl0","category.lvl1","categoryIds","color","colorOptions","dialColor","dialColorOptions","diamondQuality","gender","hideOnSite","inStock","maincat","metalOptions","metalType","metalTypeFilter","metalTypeFilterOptions","metalWidth","metalWidthOptions","newarrival","plpCode","price","productStyle","shape","shapeOptions","stone","stoneFilter","stoneFilterOptions","stoneOptions","storeIds","subcat","totalRevenue","watchModel"]</textarea>
                        <small class="form-text text-muted">Enter attributes as a JSON array</small>
                    </div>
                    <button type="submit" class="btn btn-success">Update Filterable Attributes</button>
                </form>
            </div>

            <!-- Searchable Attributes -->
            <div class="index-card">
                <h5>Searchable Attributes</h5>
                <form id="searchableForm">
                    <input type="hidden" name="indexName" id="searchableIndexName" value="products">
                    <div class="mb-3">
                        <label for="searchableAttributes" class="form-label">Attributes (JSON Array)</label>
                        <textarea class="form-control" id="searchableAttributes" name="attributes" rows="4" placeholder='["productName", "description"]'>["caratFraction","metalType","shape","productName","carat","category.lvl0","color","upcNumber","objectID","productFullName","shortName","searchKeyword","erpSKU","upcNumber"]</textarea>
                        <small class="form-text text-muted">Enter attributes as a JSON array</small>
                    </div>
                    <button type="submit" class="btn btn-success">Update Searchable Attributes</button>
                </form>
            </div>

            <!-- Sortable Attributes -->
            <div class="index-card">
                <h5>Sortable Attributes</h5>
                <form id="sortableForm">
                    <input type="hidden" name="indexName" id="sortableIndexName" value="products">
                    <div class="mb-3">
                        <label for="sortableAttributes" class="form-label">Attributes (JSON Array)</label>
                        <textarea class="form-control" id="sortableAttributes" name="attributes" rows="4" placeholder='["price", "created_at"]'>["totalGroupRevenue","displayRank","qualityTier","diamondQuality","id","totalSold","ratingCount","inStock","price","newarrival","created_date"]</textarea>
                        <small class="form-text text-muted">Enter attributes as a JSON array</small>
                    </div>
                    <button type="submit" class="btn btn-success">Update Sortable Attributes</button>
                </form>
            </div>
			
			<!-- Custom Ranking (customRanking) -->
            <div class="index-card">
                <h5>Custom Ranking</h5>
                <form id="customRankingForm">
                    <input type="hidden" name="indexName" id="customRankingIndexName" value="products">
                    <div class="mb-3">
                        <label for="customRankingRules" class="form-label">Custom Ranking Rules (JSON Array)</label>
                        <textarea
                            class="form-control"
                            id="customRankingRules"
                            name="rules"
                            rows="6"
                            placeholder='["desc(totalGroupRevenue)", "asc(displayRank)", ...]'
                        >["sort"]</textarea>
                        <small class="form-text text-muted">Enter custom ranking rules as a JSON array, in order of priority.</small>
                    </div>
                    <button type="submit" class="btn btn-success">Update Custom Ranking</button>
                </form>
            </div>
        </div>

        <!-- Index Info Display -->
        <div id="indexInfoDisplay" class="index-card" style="display: none;">
            <h5>Index Information</h5>
            <pre id="indexInfoContent" class="bg-light p-3 rounded"></pre>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE = 'http://27.109.8.106:8245/74/gbthemesearch';
        
        // Get CSRF token
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // Show alert
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Create Index
        document.getElementById('createIndexForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = {
                uid: formData.get('uid'),
                primaryKey: formData.get('primaryKey') || 'id'
            };

            try {
                const response = await fetch(`${API_BASE}/indexes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message || 'Index created successfully!');
                    e.target.reset();
                } else {
                    showAlert('danger', result.message || 'Failed to create index');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        });

        // Get Index Info
        async function getIndexInfo() {
            const indexName = document.getElementById('indexName').value || 'products';
            
            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/info`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('indexInfoContent').textContent = JSON.stringify(result, null, 2);
                    document.getElementById('indexInfoDisplay').style.display = 'block';
                    showAlert('success', 'Index info retrieved successfully!');
                } else {
                    showAlert('danger', result.message || 'Failed to get index info');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        }

        // Delete Index
        async function deleteIndex() {
            const indexName = document.getElementById('indexName').value || 'products';
            
            if (!confirm(`Are you sure you want to delete index "${indexName}"? This action cannot be undone!`)) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message || 'Index deleted successfully!');
                } else {
                    showAlert('danger', result.message || 'Failed to delete index');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        }

        // Update Pagination
        document.getElementById('paginationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const indexName = formData.get('indexName') || 'products';
            const data = {
                maxTotalHits: parseInt(formData.get('maxTotalHits'))
            };

            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/settings/pagination`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message || 'Pagination settings updated!');
                } else {
                    showAlert('danger', result.message || 'Failed to update pagination');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        });

        // Update Filterable Attributes
        document.getElementById('filterableForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const indexName = formData.get('indexName') || 'products';
            let attributes;
            
            try {
                attributes = JSON.parse(formData.get('attributes'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for attributes');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/settings/filterable-attributes`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(attributes)
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message || 'Filterable attributes updated!');
                } else {
                    showAlert('danger', result.message || 'Failed to update filterable attributes');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        });

        // Update Searchable Attributes
        document.getElementById('searchableForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const indexName = formData.get('indexName') || 'products';
            let attributes;
            
            try {
                attributes = JSON.parse(formData.get('attributes'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for attributes');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/settings/searchable-attributes`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(attributes)
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message || 'Searchable attributes updated!');
                } else {
                    showAlert('danger', result.message || 'Failed to update searchable attributes');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        });

        // Update Sortable Attributes
        document.getElementById('sortableForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const indexName = formData.get('indexName') || 'products';
            let attributes;
            
            try {
                attributes = JSON.parse(formData.get('attributes'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for attributes');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/settings/sortable-attributes`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(attributes)
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message || 'Sortable attributes updated!');
                } else {
                    showAlert('danger', result.message || 'Failed to update sortable attributes');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        });
		
		// Update Custom Ranking (customRanking)
        document.getElementById('customRankingForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const indexName = formData.get('indexName') || 'products';
            let rules;

            try {
                rules = JSON.parse(formData.get('rules'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for custom ranking rules');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${indexName}/settings/custom-ranking`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(rules)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message || 'Custom ranking rules updated!');
                } else {
                    showAlert('danger', result.message || 'Failed to update custom ranking rules');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        });

        // Index Products from CSV
        async function indexProductsFromCsv() {
            const btn = document.getElementById('indexProductsBtn');
            const spinner = document.getElementById('indexProductsSpinner');
            const outputDiv = document.getElementById('indexProductsOutput');
            const outputContent = document.getElementById('indexProductsOutputContent');
            
            if (!confirm('This will index all products from the CSV file. This may take several minutes. Continue?')) {
                return;
            }
            
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            outputDiv.style.display = 'block';
            outputContent.textContent = 'Starting indexing process...\n';
            
            try {
                const response = await fetch(`${API_BASE}/index-products-csv`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    outputContent.textContent = result.output || result.message;
                    showAlert('success', result.message || 'Products indexed successfully!');
                } else {
                    outputContent.textContent = result.message || 'Failed to index products';
                    showAlert('danger', result.message || 'Failed to index products');
                }
            } catch (error) {
                outputContent.textContent = 'Error: ' + error.message;
                showAlert('danger', 'Error: ' + error.message);
            } finally {
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        }

        // Index Pages
        async function indexPages() {
            const btn = document.getElementById('indexPagesBtn');
            const spinner = document.getElementById('indexPagesSpinner');
            const outputDiv = document.getElementById('indexPagesOutput');
            const outputContent = document.getElementById('indexPagesOutputContent');
            
            if (!confirm('This will index all pages (static, education, and support articles). This may take several minutes. Continue?')) {
                return;
            }
            
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            outputDiv.style.display = 'block';
            outputContent.textContent = 'Starting indexing process...\n';
            
            try {
                const response = await fetch(`${API_BASE}/index-pages`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    let outputText = result.output || result.message;
                    if (result.results) {
                        outputText += '\n\nResults:\n';
                        outputText += `- Static Pages: ${result.results.static_pages}\n`;
                        outputText += `- Education Pages: ${result.results.education_pages}\n`;
                        outputText += `- Support Pages: ${result.results.support_pages}\n`;
                    }
                    outputContent.textContent = outputText;
                    showAlert('success', result.message || 'Pages indexed successfully!');
                } else {
                    outputContent.textContent = result.message || 'Failed to index pages';
                    showAlert('danger', result.message || 'Failed to index pages');
                }
            } catch (error) {
                outputContent.textContent = 'Error: ' + error.message;
                showAlert('danger', 'Error: ' + error.message);
            } finally {
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        }
    </script>
</body>
</html>

