const fs = require('fs');
const path = 'profit-benefit/assets/js/main.js';
const s = fs.readFileSync(path, 'utf8');
const stack = [];
let i = 0;
while (i < s.length) {
  const ch = s[i];
  if (ch === '/') {
    const next = s[i+1];
    if (next === '/') { while (i < s.length && s[i] !== '\n') i++; i++; continue; }
    if (next === '*') { i += 2; while (i < s.length && !(s[i] === '*' && s[i+1] === '/')) i++; i += 2; continue; }
  }
  if (ch === '"' || ch === "'" || ch === '`') {
    const quote = ch; i++;
    while (i < s.length) {
      if (s[i] === '\\') { i += 2; continue; }
      if (s[i] === quote) { i++; break; }
      i++;
    }
    continue;
  }
  if ('([{'.includes(ch)) stack.push({ ch, i, line: (s.slice(0, i).match(/\n/g) || []).length + 1 });
  else if (')]}' .includes(ch)) {
    const pairs = { ')': '(', ']': '[', '}': '{' };
    const last = stack[stack.length - 1];
    if (!last || last.ch !== pairs[ch]) { console.log('mismatch at', i, 'line', (s.slice(0, i).match(/\n/g) || []).length + 1, 'char', ch, 'expected', pairs[ch], 'top', last); break; }
    stack.pop();
  }
  i++;
}
if (stack.length) {
  console.log('unmatched openings (top 6):', stack.slice(-6));
  stack.slice(-6).forEach(o => {
    const lines = s.split('\n');
    const start = Math.max(1, o.line - 4);
    console.log('\nContext for opening at line ' + o.line + ':');
    for (let L = start; L <= o.line + 4 && L <= lines.length; L++) {
      console.log(L + ': ' + lines[L - 1]);
    }
  });
} else console.log('No unmatched openings');
