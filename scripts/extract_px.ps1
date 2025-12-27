$matches = Get-ChildItem -Path "profit-benefit" -Recurse -File | `
    Select-String -Pattern '\b(\d+)px\b' -AllMatches | `
    ForEach-Object { $_.Matches } | ForEach-Object { $_.Groups[1].Value } | Sort-Object -Unique
$matches
Write-Output ""
Write-Output ("COUNT: " + $matches.Count)
