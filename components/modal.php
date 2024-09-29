<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" method="post" action="controller/export.php">
                    <div class="mb-3">
                        <label for="dataAmount" class="form-label">Amount of Data</label>
                        <select id="dataAmount" name="dataAmount" class="form-select">
                            <option value="all">All Data</option>
                            <option value="10">Last 10 Records</option>
                            <option value="50">Last 50 Records</option>
                            <option value="100">Last 100 Records</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fileFormat" class="form-label">File Format</label>
                        <select id="fileFormat" name="fileFormat" class="form-select">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="json">JSON</option>
                            <option value="xml">XML</option>
                            <option value="html">HTML</option>
                            <option value="txt">Text</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" method="post" action="controller/import.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileType" class="form-label">File Type</label>
                        <select id="fileType" name="fileType" class="form-select">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="html">HTML</option>
                            <option value="txt">Text</option>
                            <option value="xml">XML</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label">Select File</label>
                        <input type="file" id="fileUpload" name="fileUpload" class="form-control" />
                        <small class="form-text text-muted">Only the selected file type will be allowed.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileTypeSelect = document.getElementById('fileType');
        const fileInput = document.getElementById('fileUpload');

        // Function to update the accept attribute based on selected file type
        function updateFileInputAccept() {
            const selectedType = fileTypeSelect.value;
            let acceptTypes = '';

            switch (selectedType) {
                case 'csv':
                    acceptTypes = '.csv';
                    break;
                case 'excel':
                    acceptTypes = '.xlsx';
                    break;
                case 'html':
                    acceptTypes = '.html';
                    break;
                case 'txt':
                    acceptTypes = '.txt';
                    break;
                case 'xml':
                    acceptTypes = '.xml';
                    break;
                case 'josn':
                    acceptTypes = '.josn';
                    break;
                default:
                    acceptTypes = ''; // No restriction
                    break;
            }

            fileInput.setAttribute('accept', acceptTypes);
        }

        // Update file input accept attribute when file type changes
        fileTypeSelect.addEventListener('change', updateFileInputAccept);

        // Initialize the file input accept attribute on page load
        updateFileInputAccept();
    });
</script>