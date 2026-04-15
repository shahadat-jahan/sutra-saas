@echo off
REM PostgreSQL Quick Start Helper
REM This script helps verify and start PostgreSQL on Windows

setlocal enabledelayedexpansion

cls
echo.
echo ╔═════════════════════════════════════════════════════════╗
echo ║  PostgreSQL Connection Fix                              ║
echo ╚═════════════════════════════════════════════════════════╝
echo.

echo Step 1: Checking for PostgreSQL services...
echo.

REM Check for common PostgreSQL service names
for %%i in (15, 14, 13, 12) do (
    sc query postgresql-x64-%%i >nul 2>&1
    if !errorlevel! equ 0 (
        echo [✓] Found: postgresql-x64-%%i

        echo.
        echo Step 2: Checking if service is running...
        sc query postgresql-x64-%%i | findstr "RUNNING" >nul
        if !errorlevel! equ 0 (
            echo [✓] PostgreSQL is RUNNING - Connection should work!
            echo.
            echo Try your app now:
            echo   composer run dev
        ) else (
            echo [✗] PostgreSQL is STOPPED
            echo.
            echo Starting PostgreSQL service...
            net start postgresql-x64-%%i

            echo.
            if !errorlevel! equ 0 (
                echo [✓] PostgreSQL started successfully!
                echo.
                echo Try your app now:
                echo   composer run dev
            ) else (
                echo [✗] Failed to start PostgreSQL
                echo.
                echo Try starting manually:
                echo   1. Press Win+R
                echo   2. Type: services.msc
                echo   3. Find postgresql-x64-%%i
                echo   4. Right-click: Start
            )
        )

        pause
        exit /b 0
    )
)

echo [✗] No PostgreSQL service found
echo.
echo PostgreSQL might not be installed as a service.
echo.
echo Options:
echo 1. Check Windows Services manually:
echo    - Press Win+R
echo    - Type: services.msc
echo    - Look for postgresql service
echo    - Right-click: Start
echo.
echo 2. Download and install PostgreSQL:
echo    - postgresql.org/download/windows/
echo.

pause

