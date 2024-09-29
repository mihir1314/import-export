<?php
// Query to fetch data
$sql = "SELECT * FROM random_table";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>
<div class="container p-3 py-5">
    <div class="d-flex justify-content-end mb-3">
    <!-- Export Button -->
    <button class="btn btn-primary me-2" id="exportButton" data-bs-toggle="modal" data-bs-target="#exportModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M12 16c-.55 0-1-.45-1-1V5.83L9.41 7.41c-.39.39-1.02.39-1.41 0s-.39-1.02 0-1.41l4-4c.39-.39 1.02-.39 1.41 0l4 4c.39.39.39 1.02 0 1.41s-1.02.39-1.41 0L13 5.83V15c0 .55-.45 1-1 1zM19 13c-.55 0-1 .45-1 1v5H6v-5c0-.55-.45-1-1-1s-1 .45-1 1v5c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-5c0-.55-.45-1-1-1z" />
        </svg>
        Export
    </button>
        <?php include 'modal.php';?>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
        <path
            d="M12 8c.55 0 1 .45 1 1v9.17l1.59-1.59c.39-.39 1.02-.39 1.41 0s.39 1.02 0 1.41l-4 4c-.39.39-1.02.39-1.41 0l-4-4c-.39-.39-.39-1.02 0-1.41s1.02-.39 1.41 0L11 18.17V9c0-.55.45-1 1-1zM19 3c.55 0 1 .45 1 1v5c0 .55-.45 1-1 1s-1-.45-1-1V6H6v3c0 .55-.45 1-1 1s-1-.45-1-1V4c0-1.1.9-2 2-2h12c1.1 0 2 .9 2 2v4c0 .55-.45 1-1 1s-1-.45-1-1V4c0-.55-.45-1-1-1H6c-.55 0-1 .45-1 1v5c0 .55-.45 1-1 1s-1-.45-1-1V4c0-1.1.9-2 2-2h12z" />
    </svg>
    Import
</button>

    </div>

    <table class="table table-striped table-hover border border-black rounded-4 overflow-hidden shadow-lg">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Column 1</th>
                <th scope="col">Column 2</th>
                <th scope="col">Column 3</th>
                <th scope="col">Column 4</th>
                <th scope="col">Column 5</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $row_number = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $row_number . "</th>";
                    echo "<td>" . htmlspecialchars($row["col1"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["col2"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["col3"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["col4"]) . "</td>";
                    echo "<td>" . ($row["col5"] ? 'True' : 'False') . "</td>";
                    echo "</tr>";
                    $row_number++;
                }
            } else {
                echo "<tr><td colspan='6'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

<style>
    .table {
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    .table thead {
        border-radius: 10px 10px 0 0;
    }

    .table-hover tbody tr:hover {
        background-color: #e9ecef;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .table td:first-child,
    .table th:first-child {
        border-left: none;
    }

    .table td:last-child,
    .table th:last-child {
        border-right: none;
    }

    .btn {
        font-weight: 500;
        display: flex;
        align-items: center;
        border-radius: 8px;
    }

    .btn-primary {
        background-color: #3b56fc;
        border-color: #3b56fc;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn svg {
        margin-right: 8px;
    }
</style>
<script>

</script>