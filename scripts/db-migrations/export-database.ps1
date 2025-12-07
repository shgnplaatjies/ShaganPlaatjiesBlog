<#
.SYNOPSIS
Export the WordPress database to an SQL file

.DESCRIPTION
Export the WordPress database using WP-CLI

.PARAMETER Filename
The output filename for the SQL export. If not provided, a timestamp-based filename is used.

.EXAMPLE
.\export-database.ps1
.\export-database.ps1 -Filename "my-backup.sql"

.NOTES
Requires WP-CLI to be installed
#>

param(
    [string]$Filename = ""
)

# Set error action preference
$ErrorActionPreference = "Stop"

# Color output function
function Write-Success {
    param([string]$Message)
    Write-Host "$Message" -ForegroundColor Green
}

function Write-Warning {
    param([string]$Message)
    Write-Host "⚠ $Message" -ForegroundColor Yellow
}

function Write-Error {
    param([string]$Message)
    Write-Host "$Message" -ForegroundColor Red
}

# Load .env file if it exists
if (Test-Path ".env") {
    Get-Content ".env" | ForEach-Object {
        if ($_ -match "^([^=]+)=(.*)$") {
            $name = $matches[1]
            $value = $matches[2]
            [Environment]::SetEnvironmentVariable($name, $value)
        }
    }
}

# Set default filename
if ([string]::IsNullOrEmpty($Filename)) {
    $Filename = "database-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss').sql"
}

$DBPath = if (Test-Path "env:DB_PATH") { $env:DB_PATH } else { "." }

Write-Warning "Exporting WordPress database..."
Write-Host "Filename: $Filename"

# Check if WP-CLI is available
$wpCLI = if (Get-Command wp -ErrorAction SilentlyContinue) { "wp" } else { $null }

if ($null -ne $wpCLI) {
    Write-Success "Using WP-CLI to export database..."

    # Change to WordPress root
    Push-Location $DBPath

    try {
        # Export database
        & wp db export $Filename --allow-root

        Write-Success "Database exported successfully!"
        Write-Success "File: $(Get-Item $Filename | Select-Object -ExpandProperty FullName)"
        Write-Host ""
        Write-Host "To import this database on another environment:"
        Write-Host "  wp db import $Filename"
    }
    finally {
        Pop-Location
    }
}
else {
    Write-Error "WP-CLI not found. Please install it first."
    Write-Host "Visit: https://wp-cli.org/docs/installing/"
    exit 1
}

# Show file info
if (Test-Path $Filename) {
    $fileSize = (Get-Item $Filename).Length / 1MB
    Write-Host "File size: $([Math]::Round($fileSize, 2)) MB"
}
