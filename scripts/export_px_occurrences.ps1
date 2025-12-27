$pattern = '\b(1200|5|6|14|22|25|26|35|45|70|75|88|90|11|13|15|28|100|140|150|160|220|240|250|280|300|350|380|450|639)px\b'
$outfile = "profit-benefit/px-occurrences.csv"

Get-ChildItem -Path 'profit-benefit' -Recurse -File |
    Select-String -Pattern $pattern -AllMatches |
    ForEach-Object {
        foreach ($m in $_.Matches) {
            [PSCustomObject]@{
                Value = $m.Value
                File = $_.Path
                Line = $_.LineNumber
                Context = $_.Line.Trim()
            }
        }
    } | Export-Csv -Path $outfile -NoTypeInformation -Encoding UTF8

Write-Output "Exported occurrences to $outfile"