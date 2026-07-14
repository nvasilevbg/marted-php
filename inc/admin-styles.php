<style>
.adminCards{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-top:30px}
.adminCard{background:var(--bg-2);border:1px solid var(--line);border-radius:var(--radius);padding:24px;text-align:center;text-decoration:none;color:var(--ink);transition:transform .2s}
.adminCard:hover{transform:translateY(-2px);border-color:var(--accent)}
.adminCard.small{padding:14px;font-family:var(--font-mono);font-size:12px;color:var(--muted)}
.acNum{font-family:var(--font-serif);font-size:36px;color:var(--accent-2)}
.acLabel{font-family:var(--font-mono);font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-top:6px}
.projTable{display:grid;gap:10px;margin-top:20px}
.projRow{display:grid;grid-template-columns:80px 1fr auto;gap:16px;align-items:center;background:var(--bg-2);border:1px solid var(--line);border-radius:var(--radius);padding:12px 16px}
.projRow img{width:80px;height:60px;object-fit:cover;border-radius:var(--radius)}
.projRowInfo{display:grid;gap:3px}
.projRowInfo strong{font-family:var(--font-serif);font-size:17px}
.projRowInfo span{font-family:var(--font-mono);font-size:11px;text-transform:uppercase;color:var(--muted)}
.projRowInfo code{font-family:var(--font-mono);font-size:11px;color:var(--accent-2)}
.projRowActions{display:flex;gap:8px}
.projRowActions a,.projRowActions button{font-family:var(--font-mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;padding:8px 12px;border:1px solid var(--line);border-radius:var(--radius);background:transparent;color:var(--ink-2);cursor:pointer;text-decoration:none}
.projRowActions a:hover,.projRowActions button:hover{border-color:var(--accent);color:var(--accent-2)}
.projRowActions .del:hover{border-color:#b5503e;color:#e09a8a}
.projForm{display:grid;gap:18px;background:var(--bg-2);border:1px solid var(--line);border-radius:var(--radius);padding:30px;max-width:720px;box-shadow:var(--shadow-soft)}
.projFormHead{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.projForm input,.projForm textarea,.projForm select{background:var(--bg-3);border:1px solid var(--line);border-radius:var(--radius);padding:10px 12px;color:var(--ink);font-size:15px}
.projForm label{font-family:var(--font-mono);font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:4px;display:block}
.formRow{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.uploadHint{font-family:var(--font-mono);font-size:11px;color:var(--muted-2);margin-top:4px}
.bookingsList{display:grid;gap:12px;margin-top:20px}
display:grid;grid-template-columns:1fr auto;gap:10px 18px;background:var(--bg-2);border:1px solid var(--line);border-radius:var(--radius);padding:18px 20px}
@media(max-width:760px){.projRow{grid-template-columns:60px 1fr}.projRowActions{grid-column:1/-1}.formRow{grid-template-columns:1fr}}
</style>.biActions{grid-column:1/-1;display:flex;flex-wrap:wrap;gap:8px;padding-top:11px;border-top:1px solid var(--line-soft);margin-top:10px}
.biActions button{font-family:var(--font-mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;padding:8px 14px;border:1px solid var(--line);border-radius:var(--radius);background:transparent;color:var(--ink-2);cursor:pointer;transition:all .2s}
.biActions button:hover{border-color:var(--accent);color:var(--accent-2)}
.biActions .del:hover{border-color:#b5503e;color:#e09a8a}
.biService{font-family:var(--font-mono);font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--muted)}
.biContact{display:flex;flex-direction:column;gap:4px;align-items:end}
.biContact a{display:inline-flex;align-items:center;gap:6px;color:var(--ink);font-size:14px;text-decoration:none}
.biContact .icon{width:14px;height:14px;color:var(--accent-2)}
.biNotes{grid-column:1/-1;color:var(--muted);font-size:14px;margin:0;padding-top:9px;border-top:1px solid var(--line-soft)}