# Quick Setup Script for Watch Commerce on XAMPP (Windows)
# Usage: .\setup.ps1

$xamppPath = "C:\xampp"
$phpExe = "$xamppPath\php\php.exe"
$appPath = Get-Location

Write-Host "🚀 Watch Commerce Setup Script" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Check PHP
Write-Host "1️⃣  Checking PHP..." -ForegroundColor Yellow
if (-not (Test-Path $phpExe)) {
    Write-Host "❌ PHP not found at $phpExe" -ForegroundColor Red
    Write-Host "   Please install XAMPP or adjust the path" -ForegroundColor Red
    exit 1
}
$phpVersion = & $phpExe --version
Write-Host "✓ $phpVersion" -ForegroundColor Green
Write-Host ""

# Create .env
Write-Host "2️⃣  Setting up .env..." -ForegroundColor Yellow
if (-not (Test-Path .env)) {
    Copy-Item .env.example -Destination .env
    Write-Host "✓ Created .env from .env.example" -ForegroundColor Green
} else {
    Write-Host "✓ .env already exists" -ForegroundColor Green
}
Write-Host ""

# Run migrations
Write-Host "3️⃣  Creating database tables..." -ForegroundColor Yellow
& $phpExe scripts/migrate.php
Write-Host ""

# Run seeds
Write-Host "4️⃣  Loading sample data..." -ForegroundColor Yellow
& $phpExe scripts/seed.php
Write-Host ""

# Verify
Write-Host "5️⃣  Verifying installation..." -ForegroundColor Yellow
& $phpExe scripts/verify_db.php
Write-Host ""

Write-Host "✅ Setup Complete!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Open browser: http://localhost/watch-commerce/public/" -ForegroundColor Gray
Write-Host "2. Login with: admin@watchcommerce.test / admin1234" -ForegroundColor Gray
Write-Host "3. Check the admin dashboard" -ForegroundColor Gray
Write-Host ""
