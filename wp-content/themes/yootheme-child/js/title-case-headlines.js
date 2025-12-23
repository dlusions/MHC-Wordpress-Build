(function () {
  const SMALL_WORDS = new Set([
    "a","an","and","as","at","but","by","en","for","if","in","nor","of","on","or","per","the","to","v","vs","via","with","from","into","onto","over","out","up","down","off","n"
  ]);
  const ACRONYMS = new Set([
    "ADA","WCAG","HIPAA","HHS","OCR","CMS","EHR","EHRs","HMO","API","HTML","CSS","URL","PDF","FAQ","SOW","RFP","US","UK"
  ]);
  const PROMOTE_NEXT = /[:;.!?\u2014]\s*$/;
  const SPACE_RE = /^[\s\u00A0\u1680\u180E\u2000-\u200A\u200B\u200C\u200D\u2028\u2029\u202F\u205F\u3000\uFEFF]+$/;

  function isSpaceToken(tk) {
    return SPACE_RE.test(tk);
  }
  function isAcronym(word) {
    const core = word.replace(/[^A-Za-z]/g, "");
    return core.length > 1 && (ACRONYMS.has(core) || /^[A-Z]{2,}$/.test(core));
  }
  function hasMixedCaseOrDigits(word) {
    return /[A-Z].*[a-z]|[a-z].*[A-Z]|\d/.test(word);
  }
  function isDottedAbbrev(coreWithDots) {
    const core = coreWithDots.replace(/^[("'“‘\[]+|[)"'”’\]]+$/g, "");
    return /^(?:[A-Za-z]\.){2,}[A-Za-z]?\.?$/.test(core) ||
           /^(?:[A-Za-z]\.){1,}[A-Za-z]\.(?:[A-Za-z]\.)+$/.test(core) ||
           /^(e\.g\.|i\.e\.|etc\.)$/i.test(core);
  }
  function capitalizeWord(str) {
    if (!str) return str;
    return str.replace(/(^[A-Za-z])|('[A-Za-z])/g, m => m.toUpperCase());
  }

  function titleCaseToken(token, position, tokens, firstNonSpaceIdx, lastNonSpaceIdx, prevNonSpaceToken) {
    const match = token.match(/^(\W*)([\w\u00C0-\u024F\u1E00-\u1EFF\-]+|\w(?:\.\w)+)(\W*)$/u);
    if (!match) return token;
    const lead = match[1], core = match[2], trail = match[3];

    if (isDottedAbbrev(lead + core + trail)) {
      return lead + core + trail;
    }

    const isFirst = position === firstNonSpaceIdx;
    const isLast = position === lastNonSpaceIdx;

    const prev = prevNonSpaceToken || "";
    const promote = PROMOTE_NEXT.test(prev);

    const segments = core.split("-");

    const processed = segments.map((seg, i) => {
      if (!seg) return seg;
      if (isAcronym(seg) || hasMixedCaseOrDigits(seg)) {
        return seg;
      }
      const isCompound = segments.length > 1;
      const isCompoundFirst = isCompound && i === 0;
      const isCompoundLast = isCompound && i === segments.length - 1;
      const small = SMALL_WORDS.has(seg.toLowerCase());
      
      // Logic: Always cap first/last/promoted words, or compound parts, or non-small words
      const shouldCap =
        isFirst || isLast || promote ||
        isCompoundFirst || isCompoundLast ||
        !small;
      return shouldCap ? capitalizeWord(seg.toLowerCase()) : seg.toLowerCase();
    });

    return lead + processed.join("-") + trail;
  }

  function toTitleCaseForSingleHeading(text) {
    const tokens = text.split(/(\s+)/);
    const nonSpaceIndices = [];
    for (let i = 0; i < tokens.length; i++) {
      // FIX APPLIED HERE: 
      // Added "tokens[i] &&" to ensure empty strings (caused by leading whitespace)
      // are ignored when calculating the first word index.
      if (tokens[i] && !isSpaceToken(tokens[i])) {
        nonSpaceIndices.push(i);
      }
    }
    if (nonSpaceIndices.length === 0) {
      return text;
    }
    const firstIdx = nonSpaceIndices[0];
    const lastIdx = nonSpaceIndices[nonSpaceIndices.length - 1];

    const prevNonSpaceAt = new Array(tokens.length);
    let lastSeen = "";
    for (let i = 0; i < tokens.length; i++) {
      prevNonSpaceAt[i] = lastSeen;
      if (tokens[i] && !isSpaceToken(tokens[i])) {
        lastSeen = tokens[i];
      }
    }

    return tokens.map((tk, idx) => {
      if (isSpaceToken(tk)) {
        return tk;
      }
      return titleCaseToken(tk, idx, tokens, firstIdx, lastIdx, prevNonSpaceAt[idx]);
    }).join("");
  }

  function processHeading(el) {
    if (!el.hasAttribute("data-skip-titlecase")) {
      el.textContent = toTitleCaseForSingleHeading(el.textContent);
    }
  }
  function processAllHeadings() {
    document.querySelectorAll("h1, h2, h3, h4, h5, h6").forEach(el => {
      processHeading(el);
    });
  }
  function setupMutationObserver() {
    const observer = new MutationObserver(mutations => {
      mutations.forEach(m => {
        m.addedNodes.forEach(node => {
          if (!(node instanceof Element)) return;
          if (/^H[1-6]$/.test(node.tagName)) {
            processHeading(node);
          }
          node.querySelectorAll("h1, h2, h3, h4, h5, h6").forEach(child => {
            processHeading(child);
          });
        });
      });
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }
  window.addEventListener("load", () => {
    processAllHeadings();
    setupMutationObserver();
  });
})();