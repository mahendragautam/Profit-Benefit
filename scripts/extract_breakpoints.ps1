$matches = Get-ChildItem -Path "profit-benefit" -Recurse -File | `
    Select-String -Pattern '(?:min-width|min-device-width|max-width)\s*:\s*(\d+)px' -AllMatches | `
    ForEach-Object { foreach ($m in $_.Matches) { $m.Groups[1].Value } } | Sort-Object -Unique
$matches
Write-Output ""
Write-Output ("COUNT: " + $matches.Count)
