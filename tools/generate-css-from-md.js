const fs = require('fs');
const path = require('path');

const mdPath = path.resolve(__dirname, '..', 'refined-css.md');
const outPath = path.resolve(__dirname, '..', 'profit-benefit', 'assets', 'css', 'main.refined.css');

let txt = fs.readFileSync(mdPath, 'utf8');
// Remove leading and trailing triple backticks that may wrap the file
txt = txt.replace(/^```[a-zA-Z]*\n/, '');
txt = txt.replace(/\n```\s*$/, '');
// Write cleaned CSS
fs.writeFileSync(outPath, txt, 'utf8');
console.log('Wrote', outPath);
