<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'User Authentication' ?></title>
  <!-- Using a robust font stack and Bootstrap 5 for utilities -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    /* -------------------------------------------------------------------------- */
    /* GLOBAL STYLES & TYPOGRAPHY */
    /* -------------------------------------------------------------------------- */
    :root {
      --color-primary: #4c70ff; /* Corporate Blue */
      --color-primary-dark: #3a5edb;
      --color-bg-light: #f4f7ff; /* Very light blue/gray background */
      --color-text-dark: #334155; /* Slate Gray */
      --color-border: #e2e8f0;
    }

    body {
      margin: 0;
      height: 100vh;
      background: var(--color-bg-light);
      font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      color: var(--color-text-dark);
    }
    
    /* -------------------------------------------------------------------------- */
    /* LAYOUT (Full-Screen Split) */
    /* -------------------------------------------------------------------------- */
    .auth-wrapper {
      display: flex;
      height: 100vh;
      width: 100%;
      overflow: hidden;
    }
    
    /* Left Side: Image/Branding */
    .auth-left {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #ffffff; /* Use white background for contrast against the body */
      padding: 3rem;
      position: relative;
    }
    .auth-left img {
      max-width: 90%;
      max-height: 500px;
      object-fit: contain;
      filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.05)); /* Subtle shadow on image */
      border-radius: 12px;
    }
    
    /* Right Side: Form Container */
    .auth-right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--color-bg-light); 
      padding: 2rem;
    }
    
    /* -------------------------------------------------------------------------- */
    /* CARD & FORM ELEMENTS */
    /* -------------------------------------------------------------------------- */
    .auth-card {
      width: 100%;
      max-width: 450px;
      padding: 2.5rem;
      background: #ffffff;
      border-radius: 12px; /* Smoother rounded corners */
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); /* Professional shadow */
      border: 1px solid var(--color-border);
    }
    .auth-card h3 {
      font-weight: 700;
      margin-bottom: 2rem;
      text-align: center;
      color: var(--color-text-dark);
      font-size: 1.75rem;
    }
    
    .form-control {
      border-radius: 8px;
      border: 1px solid var(--color-border);
      padding: 0.75rem 1rem;
      transition: border-color 0.2s;
    }
    .form-control:focus {
      border-color: var(--color-primary);
      box-shadow: 0 0 0 0.25rem rgba(76, 112, 255, 0.25);
    }
    
    /* Button Styling */
    .btn-primary {
      background-color: var(--color-primary);
      border-color: var(--color-primary);
      font-weight: 600;
      border-radius: 8px;
      padding: 0.75rem 1.5rem;
      transition: background-color 0.2s, border-color 0.2s;
    }
    .btn-primary:hover {
      background-color: var(--color-primary-dark);
      border-color: var(--color-primary-dark);
    }
    
    /* Link Styling */
    .small-link {
      display: block;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: var(--color-primary);
      text-decoration: none;
      text-align: center;
      font-weight: 500;
    }
    .small-link:hover {
      text-decoration: underline;
      color: var(--color-primary-dark);
    }

    /* Back Button Styling */
    .back-link {
      color: var(--color-text-dark) !important;
      transition: color 0.2s;
    }
    .back-link:hover {
        color: var(--color-primary) !important;
    }
    
    /* Responsive adjustment: force right side to take full width on mobile */
    @media (max-width: 767px) {
        .auth-right {
            flex: 1 0 100%;
            padding: 1.5rem;
        }
        .auth-left {
            display: none !important;
        }
        .auth-card {
            padding: 1.5rem;
        }
    }
  </style>
</head>
<body>
<div class="auth-wrapper">
    <!-- Left side (Image/Branding Area) - Hidden on Mobile -->
    <div class="auth-left d-none d-md-flex position-relative">
        
        <!-- Back Arrow Button (placed outside the main layout content, relative to the left pane) -->
        <a href="<?= base_url('/') ?>" 
           class="btn btn-link position-absolute top-0 start-0 m-4 text-decoration-none fw-bold back-link" 
           style="font-size: 1.2rem;">
            ← Home
        </a>
        
        <!-- Placeholder Image. Assumes a clear, professional image at this path -->
        <img src="<?= base_url('images/cert.png') ?>" alt="Branding Illustration">
    </div>

    <!-- Right side (Form Area) -->
    <div class="auth-right">
        <!-- The content section (login form, register form, etc.) will be rendered here -->
        <div class="auth-card">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle (optional, but good for components like alerts) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>