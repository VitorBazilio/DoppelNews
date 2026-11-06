@echo off
rem Inicia o Doppel em http://localhost:8000 (SQLite, sem configuracao). Feche a janela para parar.
cd /d "%~dp0"
for /f "delims=" %%i in ('where php 2^>nul') do (set "PHPEXE=%%i" & goto :achou)
echo PHP nao encontrado no PATH. Instale o PHP 8.1+ e tente de novo.
pause & exit /b 1
:achou
for %%i in ("%PHPEXE%") do set "EXT=%%~dpiext"
echo Doppel rodando em http://localhost:8000  (Ctrl+C para parar)
"%PHPEXE%" -d extension_dir="%EXT%" -d extension=pdo_sqlite -d extension=sqlite3 -d extension=pdo_mysql -d extension=fileinfo -S localhost:8000 -t public public/index.php
pause
