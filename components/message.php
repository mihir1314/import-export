<?php
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = htmlspecialchars($_GET['message']);
    
    $alertClass = $status === 'success' ? 'alert-success' : 'alert-danger';
    
    echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show alert-auto-dismiss" role="alert">' .
         $message .
         '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' .
         '</div>';
}
?>
<style>
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050; /* Ensures the alert is above other content */
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

.alert-auto-dismiss {
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.alert-auto-dismiss.fade {
    opacity: 0;
    transform: translateY(-20px);
}

.alert-auto-dismiss.fade.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select all auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    
    alerts.forEach(alert => {
        // Set a timeout to remove the alert after 5 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            // Ensure the alert is completely removed after the transition
            setTimeout(() => alert.remove(), 500); // Matches the CSS transition duration
        }, 5000);
    });
});
</script>
