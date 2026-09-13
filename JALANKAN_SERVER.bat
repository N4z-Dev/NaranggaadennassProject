@echo off
title Server Praktikum CRUD PHP & MySQL
echo ========================================================
echo  MENJALANKAN SERVER DEMO CRUD PHP & MYSQL
echo  Mahasiswa: Narangga Aden (2507421029)
echo ========================================================
echo.
echo 1. Menjalankan PHP Development Server pada port 8000...
echo 2. Aplikasi dapat diakses di:
echo    - Multi-Page : http://localhost:8000/crud-multi-page/
echo    - Single-Page: http://localhost:8000/crud-single-page/
echo.
echo Tekan CTRL + C untuk menghentikan server.
echo ========================================================
echo.
start http://localhost:8000/crud-multi-page/
php -S 127.0.0.1:8000 -t "%~dp0"
pause
