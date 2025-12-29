# PowerShell script to install PHP, Composer, and Laravel dependencies on Windows

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Laravel Dependencies Installer for Windows" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

# Check if Chocolatey is installed
$chocoInstalled = Get-Command choco -ErrorAction SilentlyContinue

if (-not $chocoInstalled) {
    Write-Host "Chocolatey not found. Would you like to install it? (Y/N)" -ForegroundColor Yellow
    $response = Read-Host
    
    if ($response -eq 'Y' -or $response -eq 'y') {
        if (-not $isAdmin) {
            Write-Host "ERROR: Administrator privileges required to install Chocolatey!" -ForegroundColor Red
            Write-Host "Please run this script as Administrator." -ForegroundColor Red
            exit 1
        }
        
        Write-Host "Installing Chocolatey..." -ForegroundColor Green
        Set-ExecutionPolicy Bypass -Scope Process -Force
        [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
        Invoke-Expression ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
        
        # Refresh environment
        $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
    } else {
        Write-Host ""
        Write-Host "Manual installation required:" -ForegroundColor Yellow
        Write-Host "1. Download PHP 8.2+ from: https://windows.php.net/download/" -ForegroundColor White
        Write-Host "2. Download Composer from: https://getcomposer.org/Composer-Setup.exe" -ForegroundColor White
        Write-Host "3. After installation, run: composer install" -ForegroundColor White
        exit 0
    }
}

# Check if PHP is installed
$phpInstalled = Get-Command php -ErrorAction SilentlyContinue

if (-not $phpInstalled) {
    Write-Host "Installing PHP..." -ForegroundColor Green
    if (-not $isAdmin) {
        Write-Host "ERROR: Administrator privileges required!" -ForegroundColor Red
        Write-Host "Please run this script as Administrator." -ForegroundColor Red
        exit 1
    }
    choco install php -y
    refreshenv
}

# Check if Composer is installed
$composerInstalled = Get-Command composer -ErrorAction SilentlyContinue

if (-not $composerInstalled) {
    Write-Host "Installing Composer..." -ForegroundColor Green
    if (-not $isAdmin) {
        Write-Host "ERROR: Administrator privileges required!" -ForegroundColor Red
        Write-Host "Please run this script as Administrator." -ForegroundColor Red
        exit 1
    }
    choco install composer -y
    refreshenv
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Installing Laravel Dependencies" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Install composer dependencies
Write-Host "Running composer install..." -ForegroundColor Green
composer install --no-interaction --prefer-dist --optimize-autoloader

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "=========================================" -ForegroundColor Green
    Write-Host "SUCCESS! Dependencies installed." -ForegroundColor Green
    Write-Host "=========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "1. Run: docker compose restart app" -ForegroundColor White
    Write-Host "2. Visit: http://localhost:8080" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "=========================================" -ForegroundColor Red
    Write-Host "ERROR: Composer install failed!" -ForegroundColor Red
    Write-Host "=========================================" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please check your network connection and try again." -ForegroundColor Yellow
    exit 1
}
