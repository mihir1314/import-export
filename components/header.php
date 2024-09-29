<nav class="navbar navbar-expand-lg bg-body-tertiary px-5 py-3 shadow-sm rounded">
    <div class="container-fluid">
        <a class="navbar-brand px-2 d-flex align-items-center" href="#">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M19 16v4H5v-4H3v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4h-2zM16.707 11.707l-1.414-1.414L13 12.586V3h-2v9.586l-2.293-2.293-1.414 1.414L12 15.414l4.707-4.707zM5 8V4h14v4h2V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4h2z"
                    fill="#000" />
            </svg>
            <span class="ms-2 fw-bold text-uppercase">Import-Export</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Doc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    .navbar-brand svg {
        fill: #3b56fc;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        margin-left: 15px;
        position: relative;
    }

    .navbar-nav .nav-link.active {
        color: #3b56fc;
    }

    .navbar-nav .nav-link::before {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #3b56fc;
        transition: width 0.3s ease-in-out;
    }

    .navbar-nav .nav-link:hover::before,
    .navbar-nav .nav-link.active::before {
        width: 100%;
    }

    .navbar-toggler {
        border: none;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30' fill='black'%3E%3Cpath stroke='rgba%280, 0, 0, 1%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    .shadow-sm {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
