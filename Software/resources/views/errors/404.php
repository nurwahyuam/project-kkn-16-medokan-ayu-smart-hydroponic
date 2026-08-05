<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <style>
        body {
            font-family: var(--font-family-base);
            background: var(--color-bg-body);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        .not-found h1 {
            font-size: 64px;
            color: var(--color-primary);
            margin-bottom: 8px;
        }

        .not-found p {
            color: var(--color-text-muted);
        }

        .not-found a {
            color: var(--color-primary);
            font-weight: var(--fw-semibold);
        }
    </style>
</head>

<body>
    <div class="not-found">
        <h1>404</h1>
        <p>The page you're looking for doesn't exist.</p>
        <a href="/">&larr; Back to Dashboard</a>
    </div>
</body>

</html>
