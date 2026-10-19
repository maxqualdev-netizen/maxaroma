<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Index Management</title>
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
        <h1 class="mb-4">Index Management</h1>
        
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
		
		<!-- Index Products from CSV Section -->
        <div class="form-section">
            <h3>Index Products from CSV in products Index</h3>
            <div class="alert alert-info">
                <strong>Note:</strong> This will index products from <code>/csv_download/product_feed.csv</code> file.
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
       <!-- <div class="form-section">
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
        </div> -->

        <div class="alert alert-secondary border">
            <strong>Active index</strong> — all actions below (except CSV / Index pages) use this Index ID.
            Change it after creating a new index or pick another index before saving settings.
            <div class="row g-2 align-items-end mt-2">
                <div class="col-md-4">
                    <form id="loadIndexSettingsForm">
                        <label for="targetIndexUid" class="form-label mb-0">Target index ID</label>
                        <input type="text" class="form-control" id="targetIndexUid" name="index" value="{{ request('index', config('global.CUSTOM_PRODUCT_INDEX', 'products_maxaroma')) }}" maxlength="128" pattern="[a-zA-Z0-9_-]+" placeholder="products_maxaroma" required>
                    </form>
                </div>
                <div class="col-md-8 d-flex align-items-end gap-2 flex-wrap">
                    <button type="submit" form="loadIndexSettingsForm" class="btn btn-primary btn-sm">Load index settings</button>
                    <!--<button type="button" class="btn btn-outline-primary btn-sm" onclick="syncTargetIndexToForms()">Sync hidden fields</button>-->
                </div>
            </div>
        </div>
        <!--<p class="text-muted small mb-4">Tip: Open this page as <code>?index=your_uid</code> to pre-select an index.</p>-->

        

        <!-- Index Operations Section -->
        <div class="form-section">
            <h3>Index Operations</h3>
            <p class="text-muted small">Uses the same <strong>Target index ID</strong> field at the top of the page.</p>
            <div class="row">
                <div class="col-md-6 mb-3 d-flex align-items-end gap-2 flex-wrap">
                    <button type="button" class="btn btn-info btn-action" onclick="getIndexInfo()">Get Index Info</button>
                    <button type="button" class="btn btn-danger btn-action" onclick="deleteIndex()">Delete Index</button>
                </div>
            </div>
        </div>

        

        <!-- Index Settings Section -->
        <div class="form-section">
            <h3>Index Settings</h3>
            <p class="text-muted small">Updates apply to the <strong>Target index ID</strong>. For a new index, <em>Paste your own JSON arrays.</p>
            
            <!-- Pagination Settings -->
            <div class="index-card">
                <h5>Pagination Settings</h5>
                <form id="paginationForm">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="indexName" id="paginationIndexName" value="products">
                            <label for="maxTotalHits" class="form-label">Max Total Hits</label>
                            <input type="number" class="form-control" id="maxTotalHits" name="maxTotalHits" value="3000" min="1">
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
                        <textarea class="form-control" id="filterableAttributes" name="attributes" rows="6" placeholder='["plpCode", "productName", "price"]'>["badges","base_note","brand","by_feature","category","coverage","finish","formulation","fragrance_family","fragrance_notes","gender","inventory_count","is_deal_of_week","middle_note","occasion","personality","price","product_type","rating_avg","seasons","size","skin_type","top_note","type","website_stock"]</textarea>
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
                        <textarea class="form-control" id="searchableAttributes" name="attributes" rows="4" placeholder='["productName", "description"]'>["name","sku","brand","search_keywords","description","category"]</textarea>
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
                        <textarea class="form-control" id="sortableAttributes" name="attributes" rows="4" placeholder='["price", "created_at"]'>["name","price","rating_avg","same_variation_max_price","same_variation_min_price"]</textarea>
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
                        >["words","typo","proximity","attribute","sort","exactness"]</textarea>
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
        const API_BASE = @json(rtrim(url('/'), '/'));

        function getTargetIndexUid() {
            const el = document.getElementById('targetIndexUid');
            const v = (el && el.value) ? el.value.trim() : @json(config('global.CUSTOM_PRODUCT_INDEX', 'products_maxaroma'));
            return v || @json(config('global.CUSTOM_PRODUCT_INDEX', 'products_maxaroma'));
        }

        function syncTargetIndexToForms() {
            const uid = getTargetIndexUid();
            ['paginationIndexName', 'filterableIndexName', 'searchableIndexName', 'sortableIndexName', 'customRankingIndexName'].forEach(function (id) {
                const h = document.getElementById(id);
                if (h) h.value = uid;
            });
        }

        function populateSettingsFields(result) {
            const s = result.settings || {};
            const info = result.info || {};

            const pag = s.pagination;
            if (pag && typeof pag.maxTotalHits === 'number') {
                document.getElementById('maxTotalHits').value = pag.maxTotalHits;
            }
            const fa = s.filterableAttributes;
            if (Array.isArray(fa)) {
                document.getElementById('filterableAttributes').value = JSON.stringify(fa, null, 2);
            }
            const sa = s.searchableAttributes;
            if (Array.isArray(sa)) {
                document.getElementById('searchableAttributes').value = JSON.stringify(sa, null, 2);
            }
            const so = s.sortableAttributes;
            if (Array.isArray(so)) {
                document.getElementById('sortableAttributes').value = JSON.stringify(so, null, 2);
            }
            const rr = s.rankingRules;
            if (Array.isArray(rr)) {
                document.getElementById('customRankingRules').value = JSON.stringify(rr, null, 2);
            }
            if (info.primaryKey) {
                const pk = document.getElementById('primaryKey');
                if (pk) {
                    pk.value = info.primaryKey;
                }
            }
            document.getElementById('indexInfoContent').textContent = JSON.stringify(result, null, 2);
            document.getElementById('indexInfoDisplay').style.display = 'block';
        }

        async function loadSettingsFromMeilisearch() {
            const indexName = getTargetIndexUid();
            if (!indexName) {
                showAlert('danger', 'Enter an index name first.');
                return false;
            }
            syncTargetIndexToForms();
            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/info`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (!result.success) {
                    showAlert('danger', result.message || 'Failed to load index info');
                    return false;
                }
                populateSettingsFields(result);
                showAlert('success', 'Form fields updated from Meilisearch for index "' + indexName + '".');
                return true;
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
                return false;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            syncTargetIndexToForms();
            const t = document.getElementById('targetIndexUid');
            if (t) {
                t.addEventListener('change', syncTargetIndexToForms);
                t.addEventListener('blur', syncTargetIndexToForms);
            }
            const loadForm = document.getElementById('loadIndexSettingsForm');
            if (loadForm) {
                loadForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    loadSettingsFromMeilisearch();
                });
            }
            const urlIndex = new URLSearchParams(window.location.search).get('index');
            if (urlIndex) {
                loadSettingsFromMeilisearch();
            }
        });
        
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
                    const newUid = (data.uid || '').trim();
                    if (newUid) {
                        document.getElementById('targetIndexUid').value = newUid;
                        syncTargetIndexToForms();
                        await loadSettingsFromMeilisearch();
                    }
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
            const indexName = getTargetIndexUid();
            syncTargetIndexToForms();
            
            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/info`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    populateSettingsFields(result);
                    const docs = result.numberOfDocuments ?? result.info?.numberOfDocuments ?? result.stats?.numberOfDocuments ?? 0;
                    showAlert('success', 'Index info retrieved. Documents in index: ' + docs);
                } else {
                    showAlert('danger', result.message || 'Failed to get index info');
                }
            } catch (error) {
                showAlert('danger', 'Error: ' + error.message);
            }
        }

        // Delete Index
        async function deleteIndex() {
            const indexName = getTargetIndexUid();
            syncTargetIndexToForms();
            
            if (!confirm(`Are you sure you want to delete index "${indexName}"? This action cannot be undone!`)) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/delete`, {
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
            syncTargetIndexToForms();
            const indexName = getTargetIndexUid();
            const data = {
                maxTotalHits: parseInt(formData.get('maxTotalHits'))
            };

            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/settings/pagination`, {
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
            syncTargetIndexToForms();
            const indexName = getTargetIndexUid();
            let attributes;
            
            try {
                attributes = JSON.parse(formData.get('attributes'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for attributes');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/settings/filterable-attributes`, {
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
            syncTargetIndexToForms();
            const indexName = getTargetIndexUid();
            let attributes;
            
            try {
                attributes = JSON.parse(formData.get('attributes'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for attributes');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/settings/searchable-attributes`, {
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
            syncTargetIndexToForms();
            const indexName = getTargetIndexUid();
            let attributes;
            
            try {
                attributes = JSON.parse(formData.get('attributes'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for attributes');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/settings/sortable-attributes`, {
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
            syncTargetIndexToForms();
            const indexName = getTargetIndexUid();
            let rules;

            try {
                rules = JSON.parse(formData.get('rules'));
            } catch (error) {
                showAlert('danger', 'Invalid JSON format for custom ranking rules');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/indexes/${encodeURIComponent(indexName)}/settings/custom-ranking`, {
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

