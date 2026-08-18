# ========================================================
# Auto-Commit & Auto-Push Script untuk Project Tugas
# Branch: main -> Remote: origin (N4z-Dev/Project)
# ========================================================

$branch = "main"
$remote = "origin"
$intervalSeconds = 30

Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host "  AUTO PUSH KE GITHUB (Project - Tugas) DIAKTIFKAN" -ForegroundColor Green
Write-Host "  Folder : $PSScriptRoot" -ForegroundColor Yellow
Write-Host "  Branch : $branch" -ForegroundColor Yellow
Write-Host "  Remote : https://github.com/N4z-Dev/Project.git" -ForegroundColor Yellow
Write-Host "  Interval Check: Tiap $intervalSeconds detik" -ForegroundColor Yellow
Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host "Biarkan jendela ini terbuka saat Anda mengerjakan tugas." -ForegroundColor White
Write-Host "Tekan CTRL + C di terminal ini jika ingin mematikan auto-push.`n" -ForegroundColor DarkGray

while ($true) {
    # Cek apakah ada perubahan file (staged, unstaged, untracked)
    $changes = git status --porcelain
    if ($changes) {
        $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
        Write-Host "[$timestamp] Perubahan terdeteksi! Memproses commit..." -ForegroundColor Cyan
        
        git add -A
        $commitMessage = "Update tugas: $timestamp"
        git commit -m "$commitMessage"
        
        Write-Host "[$timestamp] Mengirim ke GitHub ($remote/$branch)..." -ForegroundColor Yellow
        $pushResult = git push -u $remote $branch
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "[$timestamp] [OK] Berhasil disimpan & di-push ke GitHub!" -ForegroundColor Green
        } else {
            Write-Host "[$timestamp] [!] Gagal push. Cek koneksi atau izin GitHub." -ForegroundColor Red
        }
        Write-Host "-----------------------------------------------------" -ForegroundColor DarkGray
    }
    
    Start-Sleep -Seconds $intervalSeconds
}
