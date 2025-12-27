$pattern = '\b(1200|5|6|14|22|25|26|35|45|70|75|88|90|11|13|15|28|100|140|150|160|220|240|250|280|300|350|380|450|639)px\b'
Get-ChildItem -Path "profit-benefit" -Recurse -File | Select-String -Pattern $pattern -AllMatches |
ForEach-Object { foreach ($m in $_.Matches) { "$($_.Path),$($_.LineNumber),$($m.Value)" } } | Out-File -Encoding utf8 profit-benefit/px-occurrences.csv
