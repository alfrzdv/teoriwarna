# Script to copy new features to main project
$source = "C:\Users\-alfrzdv\.claude-worktrees\teoriwarna\kind-spence"
$destination = "C:\laragon\www\teoriwarna"

Write-Host "Copying files from worktree to main project..." -ForegroundColor Green

# Controllers
Write-Host "Copying Controllers..." -ForegroundColor Yellow
Copy-Item "$source\app\Http\Controllers\Admin\ProductController.php" "$destination\app\Http\Controllers\Admin\" -Force
Copy-Item "$source\app\Http\Controllers\ProductCatalogController.php" "$destination\app\Http\Controllers\" -Force
Copy-Item "$source\app\Http\Controllers\CartController.php" "$destination\app\Http\Controllers\" -Force
Copy-Item "$source\app\Http\Controllers\CheckoutController.php" "$destination\app\Http\Controllers\" -Force
Copy-Item "$source\app\Http\Controllers\OrderController.php" "$destination\app\Http\Controllers\" -Force

# Views - Products (Admin)
Write-Host "Copying Admin Product Views..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "$destination\resources\views\admin\products" -Force | Out-Null
Copy-Item "$source\resources\views\admin\products\*" "$destination\resources\views\admin\products\" -Force

# Views - Catalog
Write-Host "Copying Catalog Views..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "$destination\resources\views\catalog" -Force | Out-Null
Copy-Item "$source\resources\views\catalog\*" "$destination\resources\views\catalog\" -Force

# Views - Cart
Write-Host "Copying Cart Views..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "$destination\resources\views\cart" -Force | Out-Null
Copy-Item "$source\resources\views\cart\*" "$destination\resources\views\cart\" -Force

# Views - Checkout
Write-Host "Copying Checkout Views..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "$destination\resources\views\checkout" -Force | Out-Null
Copy-Item "$source\resources\views\checkout\*" "$destination\resources\views\checkout\" -Force

# Views - Orders
Write-Host "Copying Order Views..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "$destination\resources\views\orders" -Force | Out-Null
Copy-Item "$source\resources\views\orders\*" "$destination\resources\views\orders\" -Force

# CSS Files
Write-Host "Copying CSS Files..." -ForegroundColor Yellow
New-Item -ItemType Directory -Path "$destination\public\css\admin" -Force | Out-Null
New-Item -ItemType Directory -Path "$destination\public\css\catalog" -Force | Out-Null
New-Item -ItemType Directory -Path "$destination\public\css\cart" -Force | Out-Null

Copy-Item "$source\public\css\admin\products.css" "$destination\public\css\admin\" -Force
Copy-Item "$source\public\css\admin\categories.css" "$destination\public\css\admin\" -Force
Copy-Item "$source\public\css\catalog\products.css" "$destination\public\css\catalog\" -Force
Copy-Item "$source\public\css\cart\cart.css" "$destination\public\css\cart\" -Force

# Routes
Write-Host "Copying Routes..." -ForegroundColor Yellow
Copy-Item "$source\routes\web.php" "$destination\routes\" -Force

Write-Host "`nDONE! All files copied successfully!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Cyan
Write-Host "1. cd C:\laragon\www\teoriwarna"
Write-Host "2. php artisan migrate"
Write-Host "3. php artisan serve"
Write-Host ""
