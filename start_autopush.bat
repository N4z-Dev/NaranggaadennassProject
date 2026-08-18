@echo off
title Auto Push GitHub - TUGAS
cd /d "%~dp0"
powershell -ExecutionPolicy Bypass -File "%~dp0autopush.ps1"
pause
